/**
 * ============================================================================
 * REFACTORED FUNCTIONS FOR BACKEND API INTEGRATION
 * Assessment Tool - Frontend API Integration
 * 
 * Replace the following functions in your existing app.js with these implementations:
 * 1. submitContactForm()
 * 2. submitAssessment()
 * 
 * Backend API Configuration
 * ============================================================================
 */

// API Configuration
const API_BASE_URL = 'http://localhost:8000/api'; // Sesuaikan dengan URL backend Anda

/**
 * ============================================================================
 * FUNCTION 1: Submit Contact Form dengan API
 * ============================================================================
 * Mengganti fungsi submitContactForm() yang lama
 * Mengirim data contact ke endpoint: POST /api/contact
 */
function submitContactForm() {
  const contactForm = document.getElementById('contact-form');
  
  if (!contactForm) return;
  
  contactForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(contactForm);
    const data = {
      institution: formData.get('institution'),
      fullname: formData.get('fullname'),
      email: formData.get('email'),
      service_type: formData.get('service'),
      message: formData.get('message')
    };
    
    // Validasi input
    if (!data.institution || !data.fullname || !data.email || !data.service_type || !data.message) {
      alert('Semua field harus diisi!');
      return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
      alert('Format email tidak valid!');
      return;
    }
    
    const submitButton = contactForm.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.textContent = 'Mengirim...';
    submitButton.disabled = true;
    
    try {
      // Send POST request to backend
      const response = await fetch(`${API_BASE_URL}/contact`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });
      
      const result = await response.json();
      
      if (response.ok && result.success) {
        // Success - show confirmation
        alert('✓ Pesan Anda telah terkirim! Tim PEMDI.ID akan segera menghubungi Anda.');
        contactForm.reset();
        
        // Optional: Redirect to WhatsApp for follow-up
        if (confirm('Apakah Anda ingin melanjutkan percakapan melalui WhatsApp?')) {
          const whatsappNumber = '+6281234567890';
          const whatsappMessage = `Halo PEMDI.ID, saya ${data.fullname} dari ${data.institution}. 
            Saya tertarik dengan layanan ${data.service_type}. 
            ${data.message}`;
          const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMessage)}`;
          window.open(whatsappUrl, '_blank');
        }
      } else {
        // Error response from server
        alert(`Gagal mengirim pesan: ${result.message || 'Unknown error'}`);
      }
    } catch (error) {
      console.error('Error:', error);
      alert(`Terjadi kesalahan: ${error.message}`);
    } finally {
      submitButton.textContent = originalText;
      submitButton.disabled = false;
    }
  });
}


/**
 * ============================================================================
 * FUNCTION 2: Submit Assessment dengan API
 * ============================================================================
 * Mengganti fungsi submitAssessment() yang lama
 * 
 * PENTING:
 * - Mengumpulkan semua data dari appState
 * - Membungkus JSON + File uploads menggunakan FormData
 * - Menangani multipart/form-data untuk file uploads
 * - Struktur data: responses[indicatorId][score], responses[indicatorId][file], dll
 * 
 * Mengirim POST ke: /api/assessment
 */
async function submitAssessment() {
  // Validasi semua indikator sudah dinilai
  const incompleteIndicators = Object.entries(appState.assessmentResponses)
    .filter(([id, response]) => !response.completed)
    .map(([id]) => parseInt(id));
  
  if (incompleteIndicators.length > 0) {
    alert(`Masih ada ${incompleteIndicators.length} indikator yang belum dinilai. Silakan lengkapi terlebih dahulu.`);
    return;
  }
  
  // Create FormData object untuk multipart/form-data
  const formData = new FormData();
  
  // 1. Add header information (organization & assessor data)
  formData.append('org_name', appState.organizationInfo.name);
  formData.append('org_type', appState.organizationInfo.type);
  formData.append('assessor_name', appState.organizationInfo.assessorName);
  formData.append('assessor_position', appState.organizationInfo.assessorPosition);
  formData.append('assessment_date', appState.organizationInfo.date);
  
  // 2. Build responses array dengan score, evidence text, dan files
  // Structure: responses[0][indicator_id], responses[0][score], responses[0][evidence_text], responses[0][file]
  //            responses[1][indicator_id], responses[1][score], responses[1][evidence_text], responses[1][file]
  
  let responseIndex = 0;
  
  Object.entries(appState.assessmentResponses).forEach(([indicatorId, responseData]) => {
    const idx = responseIndex;
    
    // Add basic response data
    formData.append(`responses[${idx}][indicator_id]`, parseInt(indicatorId));
    formData.append(`responses[${idx}][score]`, responseData.score);
    
    // Add evidence text jika ada
    if (responseData.evidence && responseData.evidence.trim() !== '') {
      formData.append(`responses[${idx}][evidence_text]`, responseData.evidence);
    }
    
    // Add file jika ada
    // Ambil file dari input element dengan ID: file-{indicatorId}
    const fileInput = document.getElementById(`file-${indicatorId}`);
    if (fileInput && fileInput.files && fileInput.files.length > 0) {
      // Ambil file pertama (atau modifikasi untuk multiple files)
      const file = fileInput.files[0];
      formData.append(`responses[${idx}][file]`, file);
    }
    
    responseIndex++;
  });
  
  // Show loading state
  const submitButton = document.getElementById('submit-btn');
  if (submitButton) {
    submitButton.textContent = 'Mengirim Assessment...';
    submitButton.disabled = true;
  }
  
  try {
    // Send POST request dengan FormData
    const response = await fetch(`${API_BASE_URL}/assessment`, {
      method: 'POST',
      body: formData
      // NOTE: Jangan set 'Content-Type' header - browser akan set otomatis dengan boundary
      // headers: { 'Accept': 'application/json' } - Accept saja, Content-Type auto
    });
    
    const result = await response.json();
    
    if (response.ok && result.success) {
      // Success - simpan assessment ID untuk export
      const assessmentId = result.assessment_id;
      const totalScore = result.total_score;
      const maturityLevel = result.maturity_level;
      
      // Store assessment ID untuk digunakan di hasil
      appState.completedAssessmentId = assessmentId;
      appState.submittedTotalScore = totalScore;
      appState.submittedMaturityLevel = maturityLevel;
      
      // Tampilkan hasil assessment
      calculateResults();
      showSection('results-section');
      
      // Tampilkan tombol download PDF dan Excel
      displayExportButtons(assessmentId);
      
      console.log('Assessment submitted successfully! ID:', assessmentId);
    } else {
      // Error response
      alert(`Gagal mengirim assessment: ${result.message || 'Unknown error'}`);
      console.error('Server error:', result);
    }
  } catch (error) {
    console.error('Error submitting assessment:', error);
    alert(`Terjadi kesalahan saat mengirim assessment: ${error.message}`);
  } finally {
    if (submitButton) {
      submitButton.textContent = 'Kirim Assessment';
      submitButton.disabled = false;
    }
  }
}


/**
 * ============================================================================
 * HELPER FUNCTION: Display Export Buttons
 * ============================================================================
 * Menampilkan tombol download PDF dan Excel setelah assessment berhasil
 */
function displayExportButtons(assessmentId) {
  // Create container untuk export buttons
  const exportContainer = document.createElement('div');
  exportContainer.className = 'export-buttons-container';
  exportContainer.style.cssText = `
    display: flex;
    gap: 15px;
    margin-top: 20px;
    justify-content: center;
    flex-wrap: wrap;
  `;
  
  // Button PDF Export
  const pdfButton = document.createElement('a');
  pdfButton.href = `${API_BASE_URL}/assessment/${assessmentId}/export/pdf`;
  pdfButton.className = 'btn btn-primary';
  pdfButton.textContent = '📥 Download PDF Report';
  pdfButton.style.cssText = `
    padding: 12px 24px;
    background-color: #0066cc;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
  `;
  pdfButton.onmouseover = function() { this.style.backgroundColor = '#0052a3'; };
  pdfButton.onmouseout = function() { this.style.backgroundColor = '#0066cc'; };
  pdfButton.download = `Assessment_Report_${assessmentId}.pdf`;
  
  // Button Excel Export
  const excelButton = document.createElement('button');
  excelButton.className = 'btn btn-secondary';
  excelButton.textContent = '📊 Download Excel Summary';
  excelButton.style.cssText = `
    padding: 12px 24px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
  `;
  excelButton.onmouseover = function() { this.style.backgroundColor = '#218838'; };
  excelButton.onmouseout = function() { this.style.backgroundColor = '#28a745'; };
  
  excelButton.onclick = async function() {
    try {
      const response = await fetch(`${API_BASE_URL}/assessment/${assessmentId}/export/excel`);
      const result = await response.json();
      
      if (result.success) {
        // Untuk saat ini, tampilkan data dalam format JSON
        // Di kemudian hari, implementasikan full Excel generation di backend
        console.log('Excel data:', result.data);
        alert('Excel data telah disiapkan. Format lengkap akan segera diimplementasikan.');
        
        // Alternative: Bisa gunakan library seperti SheetJS untuk generate Excel di frontend
        // atau tunggu backend mengimplementasikan full Excel export
      }
    } catch (error) {
      console.error('Error exporting Excel:', error);
      alert(`Gagal mengunduh Excel: ${error.message}`);
    }
  };
  
  // Append buttons ke container
  exportContainer.appendChild(pdfButton);
  exportContainer.appendChild(excelButton);
  
  // Insert ke dalam results section setelah summary
  const resultsSummary = document.querySelector('.results-summary');
  if (resultsSummary && resultsSummary.parentNode) {
    resultsSummary.parentNode.insertBefore(exportContainer, resultsSummary.nextSibling);
  }
}


/**
 * ============================================================================
 * HELPER FUNCTION: Initialize API Integration
 * ============================================================================
 * Call fungsi ini di dalam initializeApp() atau DOMContentLoaded listener
 */
function initializeApiIntegration() {
  // Initialize contact form submission
  submitContactForm();
  
  // Bind submit assessment button ke fungsi baru
  const submitBtn = document.getElementById('submit-btn');
  if (submitBtn) {
    submitBtn.onclick = submitAssessment;
  }
  
  console.log('✓ API Integration initialized');
  console.log(`API Base URL: ${API_BASE_URL}`);
}


/**
 * ============================================================================
 * DEBUGGING: Test API Connection
 * ============================================================================
 * Jalankan fungsi ini di console browser untuk test koneksi ke backend
 * testApiConnection();
 */
async function testApiConnection() {
  console.log('Testing API connection to:', API_BASE_URL);
  
  try {
    const response = await fetch(`${API_BASE_URL}/assessment`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        org_name: 'Test Organization',
        org_type: 'Pemerintah',
        assessor_name: 'Test Assessor',
        assessor_position: 'Test Position',
        assessment_date: new Date().toISOString().split('T')[0],
        responses: []
      })
    });
    
    console.log('Response status:', response.status);
    console.log('Response headers:', response.headers);
    
    const data = await response.json();
    console.log('Response data:', data);
    
    if (response.ok) {
      console.log('✓ API connection test PASSED');
    } else {
      console.log('✗ API returned error:', data.message);
    }
  } catch (error) {
    console.error('✗ API connection test FAILED:', error);
  }
}


/**
 * ============================================================================
 * IMPLEMENTATION INSTRUCTIONS
 * ============================================================================
 * 
 * 1. COPY fungsi-fungsi di atas ke dalam file app.js Anda
 * 
 * 2. UPDATE API_BASE_URL sesuai dengan URL backend Laravel Anda:
 *    - Development: http://localhost:8000/api
 *    - Production: https://yourdomain.com/api
 * 
 * 3. CALL initializeApiIntegration() di dalam event DOMContentLoaded atau 
 *    di akhir fungsi initializeApp() yang existing:
 *    
 *    document.addEventListener('DOMContentLoaded', function() {
 *      initializeApp();
 *      initializeApiIntegration(); // Add this line
 *    });
 * 
 * 4. REMOVE atau COMMENT fungsi-fungsi lama:
 *    - submitContactForm() (yang lama)
 *    - submitAssessment() (yang lama)
 * 
 * 5. TEST koneksi API dengan membuka console browser dan jalankan:
 *    testApiConnection();
 * 
 * 6. SETUP CORS di Laravel (jika frontend dan backend berbeda domain):
 *    - Pastikan middleware CORS sudah aktif
 *    - Update config/cors.php jika perlu
 * 
 * ============================================================================
 */
