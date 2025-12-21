/**
 * ============================================================================
 * PEMDI.ID - API Client
 * ============================================================================
 * Centralized API client with automatic token injection and error handling
 * 
 * Features:
 * - Auto-inject authentication token
 * - Automatic 401 handling (auto logout)
 * - Centralized error handling
 * - Request/response interceptors
 * - Support for multipart/form-data
 * 
 * @version 2.0
 * @author PEMDI.ID Development Team
 */

class ApiClient {
    static API_BASE_URL = 'http://127.0.0.1:8000/api';
    
    /**
     * Generic request handler
     * @param {string} endpoint - API endpoint
     * @param {Object} options - Fetch options
     * @returns {Promise<Response>} Fetch response
     */
    static async request(endpoint, options = {}) {
        try {
            // Build full URL
            const url = endpoint.startsWith('http') 
                ? endpoint 
                : `${this.API_BASE_URL}${endpoint}`;
            
            // Merge default headers with custom headers
            const headers = {
                'Accept': 'application/json',
                ...options.headers
            };
            
            // Add Content-Type if not multipart (FormData)
            if (!(options.body instanceof FormData)) {
                headers['Content-Type'] = 'application/json';
            }
            
            // Add authorization token if available (OPTIONAL - not required)
            if (typeof AuthManager !== 'undefined') {
                const token = AuthManager.getToken();
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }
            }
            
            // Build fetch options
            const fetchOptions = {
                ...options,
                headers
            };
            
            // Log request (for debugging)
            console.log(`🌐 API Request: ${options.method || 'GET'} ${url}`);
            
            // Make the request
            const response = await fetch(url, fetchOptions);
            
            // Handle 401 Unauthorized - auto logout (DISABLED for public API)
            if (response.status === 401) {
                console.warn('⚠️ Unauthorized access - but API is public, continuing...');
                // Don't auto-logout for public API
                // Just log the warning and continue
            }
            
            // Handle other error status codes
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                const errorMessage = errorData.message || `HTTP Error ${response.status}`;
                
                console.error(`❌ API Error: ${errorMessage}`, errorData);
                throw new Error(errorMessage);
            }
            
            console.log(`✅ API Response: ${response.status} ${response.statusText}`);
            
            return response;
            
        } catch (error) {
            console.error('❌ API Request failed:', error);
            throw error;
        }
    }
    
    /**
     * GET request
     * @param {string} endpoint - API endpoint
     * @param {Object} options - Additional fetch options
     * @returns {Promise<Object>} Parsed JSON response
     */
    static async get(endpoint, options = {}) {
        const response = await this.request(endpoint, {
            ...options,
            method: 'GET'
        });
        return await response.json();
    }
    
    /**
     * POST request
     * @param {string} endpoint - API endpoint
     * @param {Object|FormData} data - Request payload
     * @param {Object} options - Additional fetch options
     * @returns {Promise<Object>} Parsed JSON response
     */
    static async post(endpoint, data = null, options = {}) {
        const body = data instanceof FormData 
            ? data 
            : JSON.stringify(data);
        
        const response = await this.request(endpoint, {
            ...options,
            method: 'POST',
            body
        });
        return await response.json();
    }
    
    /**
     * PUT request
     * @param {string} endpoint - API endpoint
     * @param {Object} data - Request payload
     * @param {Object} options - Additional fetch options
     * @returns {Promise<Object>} Parsed JSON response
     */
    static async put(endpoint, data = null, options = {}) {
        const response = await this.request(endpoint, {
            ...options,
            method: 'PUT',
            body: JSON.stringify(data)
        });
        return await response.json();
    }
    
    /**
     * DELETE request
     * @param {string} endpoint - API endpoint
     * @param {Object} options - Additional fetch options
     * @returns {Promise<Object>} Parsed JSON response
     */
    static async delete(endpoint, options = {}) {
        const response = await this.request(endpoint, {
            ...options,
            method: 'DELETE'
        });
        return await response.json();
    }
    
    /**
     * Upload file with FormData
     * @param {string} endpoint - API endpoint
     * @param {FormData} formData - Form data with files
     * @param {Function} onProgress - Progress callback (optional)
     * @returns {Promise<Object>} Parsed JSON response
     */
    static async upload(endpoint, formData, onProgress = null) {
        if (!(formData instanceof FormData)) {
            throw new Error('Data must be FormData instance');
        }
        
        // Note: Progress tracking with fetch API is limited
        // For better progress tracking, consider using XMLHttpRequest
        
        // this.post() already returns parsed JSON
        return await this.post(endpoint, formData);
    }
    
    /**
     * Download file
     * @param {string} endpoint - API endpoint
     * @param {string} filename - Downloaded filename
     * @returns {Promise<void>}
     */
    static async download(endpoint, filename) {
        try {
            // Use request() directly to get raw response (not parsed JSON)
            const response = await this.request(endpoint, { method: 'GET' });
            const blob = await response.blob();
            
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename || 'download';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
            
            console.log(`✅ File downloaded: ${filename}`);
            
        } catch (error) {
            console.error('❌ Download failed:', error);
            throw error;
        }
    }
    
    /**
     * Parse JSON response safely
     * @param {Response} response - Fetch response
     * @returns {Promise<Object>}
     */
    static async parseJSON(response) {
        try {
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('❌ Failed to parse JSON:', error);
            throw new Error('Invalid JSON response');
        }
    }
    
    /**
     * Handle API errors consistently
     * @param {Error} error - Error object
     * @param {string} context - Error context
     * @returns {Object} Formatted error
     */
    static handleError(error, context = 'API Request') {
        console.error(`❌ ${context} failed:`, error);
        
        // Check for network errors
        if (error.message === 'Failed to fetch') {
            return {
                success: false,
                message: 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.',
                error: 'NETWORK_ERROR'
            };
        }
        
        // Check for timeout
        if (error.name === 'AbortError') {
            return {
                success: false,
                message: 'Request timeout. Silakan coba lagi.',
                error: 'TIMEOUT'
            };
        }
        
        // Generic error
        return {
            success: false,
            message: error.message || 'Terjadi kesalahan. Silakan coba lagi.',
            error: error.name || 'UNKNOWN_ERROR'
        };
    }
}

// API Shortcuts for common endpoints
class ApiEndpoints {
    // Authentication
    static async login(email, password) {
        return AuthManager.login(email, password);
    }
    
    static async register(userData) {
        return AuthManager.register(userData);
    }
    
    static async logout() {
        return AuthManager.logout();
    }
    
    static async getProfile() {
        return AuthManager.getUserProfile();
    }
    
    // Contact
    static async submitContact(contactData) {
        const response = await ApiClient.post('/contact', contactData);
        return ApiClient.parseJSON(response);
    }
    
    // Assessment
    static async submitAssessment(assessmentData) {
        const response = await ApiClient.post('/assessment', assessmentData);
        return ApiClient.parseJSON(response);
    }
    
    static async getAssessment(assessmentId) {
        const response = await ApiClient.get(`/assessment/${assessmentId}`);
        return ApiClient.parseJSON(response);
    }
    
    static async exportAssessmentPDF(assessmentId) {
        return ApiClient.download(
            `/assessment/${assessmentId}/export/pdf`,
            `Assessment_${assessmentId}.pdf`
        );
    }
    
    // Progress
    static async saveProgress(progressData) {
        const response = await ApiClient.post('/assessment/progress', progressData);
        return ApiClient.parseJSON(response);
    }
    
    static async getProgress() {
        const response = await ApiClient.get('/assessment/progress');
        return ApiClient.parseJSON(response);
    }
    
    static async getProgressById(progressId) {
        const response = await ApiClient.get(`/assessment/progress/${progressId}`);
        return ApiClient.parseJSON(response);
    }
    
    static async deleteProgress(progressId) {
        const response = await ApiClient.delete(`/assessment/progress/${progressId}`);
        return ApiClient.parseJSON(response);
    }
}

// Export for ES6 modules (if used)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ApiClient, ApiEndpoints };
}
