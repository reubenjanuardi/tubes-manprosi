/**
 * ============================================================================
 * DYNAMIC INDICATOR INTEGRATION - Add to app.js
 * ============================================================================
 * 
 * This code should be added at the top of app.js, replacing the static
 * assessmentData.indicators array
 */

// ============================================================================
// STEP 1: Add this flag to control dynamic vs static mode
// ============================================================================
const USE_DYNAMIC_INDICATORS = true; // Set to false to use static data

// ============================================================================
// STEP 2: Modify the assessmentData object
// ============================================================================
// Keep domains and maturityLevels as is, but make indicators dynamic

// Original static data (keep as fallback)
const staticIndicators = [
  {"id": 1, "name": "Tingkat Kematangan Kebijakan Internal Pemerintah Digital Instansi Pusat/Pemerintah Daerah", "domain": 1, "subdomain": 11},
  {"id": 2, "name": "Tingkat Kematangan Penerapan Manajemen Risiko dalam penerapan pemerintah digital sebagal bagian dari manajemen risiko pembangunan nasional", "domain": 1, "subdomain": 11},
  // ... rest of static indicators (keep for fallback)
];

// ============================================================================
// STEP 3: Initialize indicator service on app load
// ============================================================================
async function initializeDynamicIndicators() {
  if (!USE_DYNAMIC_INDICATORS) {
    console.log('📌 Using STATIC indicators');
    assessmentData.indicators = staticIndicators;
    return true;
  }

  try {
    console.log('🔄 Initializing dynamic indicators...');
    
    // Load indicators from API or cache
    const data = await indicatorService.initialize(handleIndicatorUpdate);
    
    // Transform to app.js format
    const transformed = indicatorService.transformToLegacyFormat(data);
    
    // Update assessmentData
    assessmentData.indicators = transformed.indicators;
    assessmentData.indicatorVersion = transformed.version;
    assessmentData.indicatorLastUpdated = transformed.lastUpdated;
    
    console.log(`✅ Loaded ${assessmentData.indicators.length} indicators (version ${transformed.version})`);
    
    // Start polling for updates
    indicatorService.startPolling();
    
    return true;
  } catch (error) {
    console.error('❌ Failed to load dynamic indicators:', error);
    console.log('📌 Falling back to STATIC indicators');
    assessmentData.indicators = staticIndicators;
    return false;
  }
}

// ============================================================================
// STEP 4: Handle indicator updates
// ============================================================================
function handleIndicatorUpdate(newData) {
  console.log('🔔 Indicators updated! Refreshing...');
  
  // Transform to app.js format
  const transformed = indicatorService.transformToLegacyFormat(newData);
  
  // Update assessmentData
  assessmentData.indicators = transformed.indicators;
  assessmentData.indicatorVersion = transformed.version;
  assessmentData.indicatorLastUpdated = transformed.lastUpdated;
  
  // Show notification to user (optional)
  showUpdateNotification();
  
  // If user is currently in assessment, re-render current view
  if (appState.currentSection === 'assessment-section') {
    renderCurrentAssessment();
  }
}

// ============================================================================
// STEP 5: Show update notification (optional UI enhancement)
// ============================================================================
function showUpdateNotification() {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = 'indicator-update-notification';
  notification.innerHTML = `
    <div class="notification-content">
      <span>📊 Indikator assessment telah diperbarui!</span>
      <button onclick="this.parentElement.parentElement.remove()">×</button>
    </div>
  `;
  
  // Add styles
  notification.style.cssText = `
    position: fixed;
    top: 80px;
    right: 20px;
    background: #0066cc;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    animation: slideInRight 0.3s ease;
  `;
  
  document.body.appendChild(notification);
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    notification.remove();
  }, 5000);
}

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
  @keyframes slideInRight {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  .notification-content {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .notification-content button {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
  }
`;
document.head.appendChild(style);

// ============================================================================
// STEP 6: Modify initializeApp() function
// ============================================================================
/*
Replace your existing initializeApp() function with this:

async function initializeApp() {
  // Load indicators first
  await initializeDynamicIndicators();
  
  // Then proceed with normal initialization
  setupEventListeners();
  showSection('welcome-section');
  
  // Pre-select the default assessment type
  setTimeout(() => {
    const pemdiCard = document.querySelector('.assessment-card');
    if (pemdiCard) {
      pemdiCard.style.borderColor = '#3b82f6';
      pemdiCard.style.boxShadow = '0 4px 12px rgba(59, 130, 246, 0.15)';
    }
  }, 100);
}
*/

// ============================================================================
// STEP 7: Add manual refresh button (optional)
// ============================================================================
function addRefreshButton() {
  const refreshButton = document.createElement('button');
  refreshButton.id = 'refresh-indicators-btn';
  refreshButton.innerHTML = '🔄 Refresh Indicators';
  refreshButton.style.cssText = `
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 24px;
    background: #0066cc;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    font-size: 14px;
    font-weight: 500;
  `;
  
  refreshButton.addEventListener('click', async () => {
    refreshButton.disabled = true;
    refreshButton.innerHTML = '⏳ Refreshing...';
    
    await indicatorService.refresh();
    
    refreshButton.disabled = false;
    refreshButton.innerHTML = '✓ Refreshed!';
    
    setTimeout(() => {
      refreshButton.innerHTML = '🔄 Refresh Indicators';
    }, 2000);
  });
  
  document.body.appendChild(refreshButton);
}

// ============================================================================
// STEP 8: Update DOMContentLoaded listener
// ============================================================================
/*
Replace your existing DOMContentLoaded with this:

document.addEventListener('DOMContentLoaded', async function() {
  // Initialize app with dynamic indicators
  await initializeApp();
  
  // Initialize other components
  initializeNavigation();
  initializeScrollEffects();
  initializeCounters();
  initializeContactForm();
  
  // Add refresh button (optional)
  if (USE_DYNAMIC_INDICATORS) {
    addRefreshButton();
  }
});
*/

// ============================================================================
// STEP 9: Cleanup on page unload
// ============================================================================
window.addEventListener('beforeunload', () => {
  if (USE_DYNAMIC_INDICATORS) {
    indicatorService.stopPolling();
  }
});

console.log('✅ Dynamic indicator integration code loaded!');
