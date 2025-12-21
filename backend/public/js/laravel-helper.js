/**
 * Laravel Form Helper - No API, Direct Form Submission
 */

// Get CSRF token from meta tag
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Submit form data directly to Laravel routes
async function submitFormData(url, data, files = {}) {
    const formData = new FormData();
    
    // Add CSRF token
    formData.append('_token', getCsrfToken());
    
    // Add regular form data
    Object.keys(data).forEach(key => {
        if (data[key] !== null && data[key] !== undefined) {
            formData.append(key, data[key]);
        }
    });
    
    // Add files if any
    Object.keys(files).forEach(key => {
        if (files[key]) {
            formData.append(key, files[key]);
        }
    });
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Form submission error:', error);
        throw error;
    }
}

// Submit JSON data to Laravel
async function submitJsonData(url, data, method = 'POST') {
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('JSON submission error:', error);
        throw error;
    }
}
