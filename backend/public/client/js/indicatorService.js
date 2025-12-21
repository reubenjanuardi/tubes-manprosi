/**
 * ============================================================================
 * INDICATOR SERVICE - Dynamic Indicator Loading with Real-time Sync
 * ============================================================================
 * 
 * This module handles:
 * - Loading indicators from API
 * - Caching to localStorage
 * - Polling for updates every 30 seconds
 * - Automatic re-render when indicators change
 */

class IndicatorService {
    constructor() {
        this.apiBaseUrl = 'http://localhost:8000/api';
        this.cacheKey = 'cached_indicators';
        this.versionKey = 'indicator_version';
        this.timestampKey = 'indicator_timestamp';
        this.cacheExpiry = 5 * 60 * 1000; // 5 minutes
        this.pollInterval = 30 * 1000; // 30 seconds
        this.pollTimer = null;
        this.onUpdateCallback = null;
    }

    /**
     * Initialize indicator service
     * - Load indicators from API or cache
     * - Start polling for updates
     */
    async initialize(onUpdateCallback) {
        this.onUpdateCallback = onUpdateCallback;

        // Try to load from cache first
        const cachedData = this.getFromCache();
        
        if (cachedData) {
            console.log('✓ Loaded indicators from cache');
            return cachedData;
        }

        // If no cache, fetch from API
        console.log('⬇️ Fetching indicators from API...');
        const data = await this.fetchIndicators();
        
        if (data) {
            this.saveToCache(data);
            return data;
        }

        // If API fails and no cache, return null
        throw new Error('Failed to load indicators from both API and cache');
    }

    /**
     * Start polling for updates
     */
    startPolling() {
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
        }

        this.pollTimer = setInterval(async () => {
            await this.checkForUpdates();
        }, this.pollInterval);

        console.log(`🔄 Polling started (checking every ${this.pollInterval / 1000}s)`);
    }

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
            console.log('⏸️ Polling stopped');
        }
    }

    /**
     * Check if indicators have been updated
     */
    async checkForUpdates() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/indicators/version`);
            
            if (!response.ok) {
                console.warn('Failed to check version:', response.status);
                return;
            }

            const result = await response.json();
            
            if (!result.success) {
                console.warn('Version check failed:', result.message);
                return;
            }

            const currentVersion = localStorage.getItem(this.versionKey);
            const newVersion = result.version.toString();

            if (currentVersion && newVersion !== currentVersion) {
                console.log('🔔 Indicator update detected!');
                console.log(`Current version: ${currentVersion}, New version: ${newVersion}`);
                
                // Fetch new data
                const newData = await this.fetchIndicators();
                
                if (newData) {
                    this.saveToCache(newData);
                    
                    // Trigger update callback
                    if (this.onUpdateCallback) {
                        this.onUpdateCallback(newData);
                    }
                }
            }
        } catch (error) {
            console.error('Error checking for updates:', error);
        }
    }

    /**
     * Fetch indicators from API
     */
    async fetchIndicators() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/indicators`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Failed to fetch indicators');
            }

            return result.data;
        } catch (error) {
            console.error('Error fetching indicators:', error);
            return null;
        }
    }

    /**
     * Get indicators from localStorage cache
     */
    getFromCache() {
        try {
            const cached = localStorage.getItem(this.cacheKey);
            const timestamp = localStorage.getItem(this.timestampKey);

            if (!cached || !timestamp) {
                return null;
            }

            const age = Date.now() - parseInt(timestamp);

            // Check if cache is expired
            if (age > this.cacheExpiry) {
                console.log('⏱️ Cache expired');
                this.clearCache();
                return null;
            }

            return JSON.parse(cached);
        } catch (error) {
            console.error('Error reading from cache:', error);
            return null;
        }
    }

    /**
     * Save indicators to localStorage cache
     */
    saveToCache(data) {
        try {
            localStorage.setItem(this.cacheKey, JSON.stringify(data));
            localStorage.setItem(this.versionKey, data.version.toString());
            localStorage.setItem(this.timestampKey, Date.now().toString());
            console.log('💾 Indicators saved to cache (version: ' + data.version + ')');
        } catch (error) {
            console.error('Error saving to cache:', error);
        }
    }

    /**
     * Clear cache
     */
    clearCache() {
        localStorage.removeItem(this.cacheKey);
        localStorage.removeItem(this.versionKey);
        localStorage.removeItem(this.timestampKey);
        console.log('🗑️ Cache cleared');
    }

    /**
     * Force refresh indicators
     */
    async refresh() {
        console.log('🔄 Force refreshing indicators...');
        this.clearCache();
        const data = await this.fetchIndicators();
        
        if (data) {
            this.saveToCache(data);
            
            if (this.onUpdateCallback) {
                this.onUpdateCallback(data);
            }
        }
        
        return data;
    }

    /**
     * Transform API data to app.js compatible format
     */
    transformToLegacyFormat(apiData) {
        // Convert grouped indicators back to flat array
        const indicators = [];
        
        Object.entries(apiData.indicators).forEach(([groupName, groupIndicators]) => {
            groupIndicators.forEach(indicator => {
                indicators.push({
                    id: indicator.id,
                    name: indicator.name,
                    group: indicator.group,
                    type: indicator.type,
                    scaleValues: indicator.scaleValues,
                    scaleLabels: indicator.scaleLabels
                });
            });
        });

        return {
            indicators: indicators.sort((a, b) => a.id - b.id),
            version: apiData.version,
            lastUpdated: apiData.last_updated
        };
    }
}

// Create global instance
const indicatorService = new IndicatorService();
