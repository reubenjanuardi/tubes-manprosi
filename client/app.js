// PEMDI.ID Digital Government Assessment Tool
// 4nesia x Telkom University COE Smart City

// Application Data
const assessmentData = {
  domains: [
    {
      id: 1,
      name: "Kebijakan dan Tata Kelola SPBE",
      weight: 20,
      color: "#1e3a8a",
      subdomains: [
        {
          id: 11,
          name: "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital",
          weight: 5,
          indicators: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        },
        {
          id: 12,
          name: "Perencanaan dan Strategi",
          weight: 5,
          indicators: [11, 12, 13, 14]
        },
        {
          id: 13,
          name: "Teknologi Digital",
          weight: 5,
          indicators: [15, 16, 17, 18]
        },
        {
          id: 14,
          name: "Pelaksanaan Audit Pemerintah Digital",
          weight: 5,
          indicators: [19]
        }
      ]
    },
    {
      id: 2,
      name: "Kapabilitas dan Budaya Digital",
      weight: 20,
      color: "#3b82f6",
      subdomains: [
        {
          id: 21,
          name: "Kapabilitas dan Budaya Digital",
          weight: 20,
          indicators: [20, 21]
        }
      ]
    },
    {
      id: 3,
      name: "Pemanfaatan Data Lintas Sektor",
      weight: 30,
      color: "#60a5fa",
      subdomains: [
        {
          id: 31,
          name: "Penerapan Tata Kelola Data",
          weight: 30,
          indicators: [22, 23, 24, 25]
        }
      ]
    },
    {
      id: 4,
      name: "Keterpaduan Layanan Digital",
      weight: 30,
      color: "#93c5fd",
      subdomains: [
        {
          id: 41,
          name: "Keterpaduan Layanan Digital Pemerintah",
          weight: 10,
          indicators: [26, 27, 28, 29, 30, 31]
        },
        {
          id: 42,
          name: "Kepuasan Pengguna Layanan Pemerintah Digital",
          weight: 20,
          indicators: [32]
        }
      ]
    }
  ],
  indicators: [
    {"id": 1, "name": "Tingkat Kematangan Kebijakan Internal Pemerintah Digital Instansi Pusat/Pemerintah Daerah", "domain": 1, "subdomain": 11},
    {"id": 2, "name": "Tingkat Kematangan Penerapan Manajemen Risiko dalam penerapan pemerintah digital sebagal bagian dari manajemen risiko pembangunan nasional", "domain": 1, "subdomain": 11},
    {"id": 3, "name": "Tingkat Kematangan Penerapan Manajemen Keamanan Informasi", "domain": 1, "subdomain": 11},
    {"id": 4, "name": "Tingkat Kematangan Penerapan Manajemen Aset Digital", "domain": 1, "subdomain": 11},
    {"id": 5, "name": "Tingkat Kematangan Penerapan Manajemen Pengetahuan", "domain": 1, "subdomain": 11},
    {"id": 6, "name": "Tingkat Kematangan Penerapan Manajemen Perubahan", "domain": 1, "subdomain": 11},
    {"id": 7, "name": "Tingkat Kematangan Penerapan Manajemen Layanan Digital", "domain": 1, "subdomain": 11},
    {"id": 8, "name": "Tingkat Kematangan Penerapan Manajemen Kelangsungan Layanan Digital Pemerintah (BCP, DRP, BIA, Disaster Response Team)", "domain": 1, "subdomain": 11},
    {"id": 9, "name": "Tingkat Kematangan Skalabilitas Pelaksanaan Transformasi Digital Pemerintah melalui Tim Koordinasi lintas unit di Instansi Pusat/Pemerintah Daerah", "domain": 1, "subdomain": 11},
    {"id": 10, "name": "Tingkat Kematangan Kolaborasi Penerapan Pemerintah Digital", "domain": 1, "subdomain": 11},
    {"id": 11, "name": "Tingkat Kematangan Arsitektur Pemerintah Digital Instansi Pusat/Pemerintah Daerah", "domain": 1, "subdomain": 12},
    {"id": 12, "name": "Tingkat Kematangan Peta Rencana Pemerintah Digital Instansi Pusat/Pemerintah Daerah untuk mendukung Perencanaan Pembangunan Nasional", "domain": 1, "subdomain": 12},
    {"id": 13, "name": "Tingkat Kematangan Keterpaduan Rencana dan Anggaran Pemerintah Digital untuk mendukung efisiensi Pembangunan Nasional", "domain": 1, "subdomain": 12},
    {"id": 14, "name": "Tingkat Kematangan Inovasi Proses Bisnis Tematik untuk mendukung keterpaduan dan kemudahan layanan digital pemerintah", "domain": 1, "subdomain": 12},
    {"id": 15, "name": "Tingkat Kematangan Pembangunan Aplikasi", "domain": 1, "subdomain": 13},
    {"id": 16, "name": "Tingkat Kematangan Pernanfaatan Ekosistem Pusat Data Nasional", "domain": 1, "subdomain": 13},
    {"id": 17, "name": "Tingkat Kematangan Layanan Jaringan Intra Instansi Pusat/Pemerintah Daerah", "domain": 1, "subdomain": 13},
    {"id": 18, "name": "Tingkat kematangan skalabilltas penguatan keamanan informasi pada layanan digital", "domain": 1, "subdomain": 13},
    {"id": 19, "name": "Tingkat Kematangan Pelaksanaan Audit Pemerintah Digital", "domain": 1, "subdomain": 14},
    {"id": 20, "name": "Tingkat Kematangan Penerapan Kapabilitas Sumber Daya Manusia Digital", "domain": 2, "subdomain": 21},
    {"id": 21, "name": "Tingkat Kematangan Penerapan Budaya Digital", "domain": 2, "subdomain": 21},
    {"id": 22, "name": "Tingkat Kematangan Penerapan Manajemen Data", "domain": 3, "subdomain": 31},
    {"id": 23, "name": "Tingkat Kematangan Pemanfaatan Data dan Informasi Lintas Sektor", "domain": 3, "subdomain": 31},
    {"id": 24, "name": "Tingkat Kematangan Skalabilitas pemanfaatan system penghubung layanan Instansi Pusat/Pemerintah Daerah", "domain": 3, "subdomain": 31},
    {"id": 25, "name": "Tingkat Kematangan Pemanfaatan Big Data, Data Analytic, dan Business intelligence", "domain": 3, "subdomain": 31},
    {"id": 26, "name": "Tingkat Kematangan Keterpaduan Layanan Administrasi Pemerintahan", "domain": 4, "subdomain": 41},
    {"id": 27, "name": "Tingkat Kematangan Skalabilitas Pemanfaatan Portal Nasional Administrasi Pemerintahan", "domain": 4, "subdomain": 41},
    {"id": 28, "name": "Tingkat Kematangan Keterpaduan Pelayanan Publik", "domain": 4, "subdomain": 41},
    {"id": 29, "name": "Tingkat Kematangan Skalabilitas Pemanfaatan Portal Nasional Pelayanan Publik", "domain": 4, "subdomain": 41},
    {"id": 30, "name": "Tingkat Kematangan Skalabilitas Pemanfaatan identitas Digital Nasional", "domain": 4, "subdomain": 41},
    {"id": 31, "name": "Tingkat Kematangan Skalabilitas Pemanfaatan Kecerdasan Artifisial pada layanan digital pemerintah", "domain": 4, "subdomain": 41},
    {"id": 32, "name": "Tingkat Kepuasan Pengguna Layanan Pemerintah, melalui survey kepuasan pengguna", "domain": 4, "subdomain": 42}
  ],
  maturityLevels: [
    {"level": 1, "name": "Initial", "description": "Proses ad hoc dan tidak terstruktur", "color": "#ef4444"},
    {"level": 2, "name": "Managed", "description": "Proses terstruktur tapi belum konsisten", "color": "#f97316"},
    {"level": 3, "name": "Defined", "description": "Proses standar yang konsisten", "color": "#eab308"},
    {"level": 4, "name": "Quantitatively Managed", "description": "Proses terukur dan terkontrol", "color": "#22c55e"},
    {"level": 5, "name": "Optimizing", "description": "Fokus pada peningkatan proses berkelanjutan", "color": "#10b981"}
  ]
};

// Application State
let appState = {
  selectedAssessmentType: 'pemdi', // Default to pemdi
  currentSection: 'welcome',
  currentDomain: 1,
  currentIndicatorIndex: 0,
  organizationInfo: {},
  assessmentResponses: {},
  results: null
};

// Initialize Application
document.addEventListener('DOMContentLoaded', function() {
  initializeApp();
  initializeNavigation();
  initializeScrollEffects();
  initializeCounters();
  initializeContactForm();
});

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
      headers: {
        'Accept': 'application/json',
      },
      body: formData
      // NOTE: Jangan set 'Content-Type' header - browser akan set otomatis dengan boundary
    });
    
    // Check if response is JSON
    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      // Response is not JSON (probably HTML error page)
      const text = await response.text();
      console.error('Non-JSON response:', text);
      throw new Error('Server returned non-JSON response. Check backend logs.');
    }
    
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
      // Error response from API
      const errorMsg = result.message || 'Unknown error';
      const errorDetails = result.errors ? JSON.stringify(result.errors) : '';
      alert(`Gagal mengirim assessment: ${errorMsg}\n${errorDetails}`);
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
  // Cari container yang sudah ada di HTML
  const container = document.getElementById('export-buttons-container');
  if (!container) {
    console.error('Export buttons container not found');
    return;
  }
  
  // Clear existing content
  container.innerHTML = '';
  
  // Button PDF Export
  const pdfButton = document.createElement('a');
  pdfButton.href = `${API_BASE_URL}/assessment/${assessmentId}/export/pdf`;
  pdfButton.className = 'btn btn--secondary';
  pdfButton.textContent = '📥 Export PDF';
  pdfButton.download = `Assessment_Report_${assessmentId}.pdf`;
  pdfButton.target = '_blank';
  
  // Button Excel Export
  const excelButton = document.createElement('a');
  excelButton.href = `${API_BASE_URL}/assessment/${assessmentId}/export/excel`;
  excelButton.className = 'btn btn--secondary';
  excelButton.textContent = '📊 Export Excel';
  excelButton.download = `Assessment_Summary_${assessmentId}.xlsx`;
  excelButton.target = '_blank';
  
  // Append buttons ke container
  container.appendChild(pdfButton);
  container.appendChild(excelButton);
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

function initializeApp() {
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

// Navigation functionality
function initializeNavigation() {
  const navToggle = document.getElementById('nav-toggle');
  const navMenu = document.getElementById('nav-menu');
  const navLinks = document.querySelectorAll('.nav__link');

  // Mobile menu toggle
  if (navToggle) {
    navToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });
  }

  // Close mobile menu when clicking on a link
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      navMenu.classList.remove('active');
    });
  });

  // Smooth scrolling for navigation links
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = link.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);
      
      if (targetElement) {
        const headerHeight = document.querySelector('.header').offsetHeight;
        const targetPosition = targetElement.offsetTop - headerHeight;
        
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
}

// Scroll effects
function initializeScrollEffects() {
  const header = document.getElementById('header');
  
  window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
}

// Animated counters
function initializeCounters() {
  const counterElements = document.querySelectorAll('.stat-number');
  const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  counterElements.forEach(counter => {
    observer.observe(counter);
  });
}

function animateCounter(element) {
  const target = parseInt(element.getAttribute('data-target'));
  const duration = 2000;
  const step = target / (duration / 16);
  let current = 0;

  const timer = setInterval(() => {
    current += step;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    element.textContent = Math.floor(current);
  }, 16);
}

// Contact form handling
function initializeContactForm() {
  const contactForm = document.getElementById('contact-form');
  
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(contactForm);
      const data = Object.fromEntries(formData);
      
      // Simulate form submission
      const submitButton = contactForm.querySelector('button[type="submit"]');
      const originalText = submitButton.textContent;
      
      submitButton.textContent = 'Mengirim...';
      submitButton.disabled = true;
      
      setTimeout(() => {
        alert('Pesan Anda telah terkirim! Tim PEMDI.ID akan segera menghubungi Anda.');
        contactForm.reset();
        submitButton.textContent = originalText;
        submitButton.disabled = false;
        
        // Optional: Redirect to WhatsApp
        const whatsappNumber = '+6281234567890';
        const message = `Halo PEMDI.ID, saya ${data.fullname} dari ${data.institution}. Saya tertarik dengan layanan ${data.service}. ${data.message}`;
        const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
        
        if (confirm('Apakah Anda ingin melanjutkan percakapan melalui WhatsApp?')) {
          window.open(whatsappUrl, '_blank');
        }
      }, 2000);
    });
  }
}

function setupEventListeners() {
  // Organization form
  const orgForm = document.getElementById('org-form');
  if (orgForm) {
    orgForm.addEventListener('submit', handleOrgFormSubmit);
  }
}

// Navigation Functions
function showSection(sectionId) {
  // Hide all sections
  document.querySelectorAll('.section').forEach(section => {
    section.classList.add('section--hidden');
  });
  
  // Also hide welcome section
  const welcomeSection = document.getElementById('welcome-section');
  if (welcomeSection) {
    welcomeSection.style.display = 'none';
  }
  
  // Show target section
  const targetSection = document.getElementById(sectionId);
  if (targetSection) {
    targetSection.classList.remove('section--hidden');
    targetSection.style.display = 'block';
  }
  
  // Special handling for welcome section
  if (sectionId === 'welcome-section' && welcomeSection) {
    welcomeSection.style.display = 'block';
  }
  
  appState.currentSection = sectionId;
}

function selectAssessmentType(type) {
  appState.selectedAssessmentType = type;
  
  // Update visual selection
  document.querySelectorAll('.assessment-card').forEach(card => {
    card.style.borderColor = '';
    card.style.boxShadow = '';
  });
  
  // Find and highlight the selected card
  const cards = document.querySelectorAll('.assessment-card');
  const selectedIndex = type === 'pemdi' ? 0 : 1;
  if (cards[selectedIndex]) {
    cards[selectedIndex].style.borderColor = '#3b82f6';
    cards[selectedIndex].style.boxShadow = '0 4px 12px rgba(59, 130, 246, 0.15)';
  }
}

// Show assessment type choices or go directly to org-info
function startAssessment() {
  // Hide welcome CTA/button to avoid duplicate actions
  const welcomeEl = document.getElementById('welcome-section');
  if (welcomeEl) welcomeEl.style.display = 'none';

  // Ensure org-info-section is shown
  showSection('org-info-section');

  // If you want to reveal assessment types inside welcome instead of navigating,
  // comment out the two lines above and use the older logic that toggles .assessment-types
}

function goToWelcome() {
  // Show welcome section again
  const welcomeEl = document.getElementById('welcome-section');
  if (welcomeEl) {
    welcomeEl.style.display = 'block';
    // scroll into view optional
    welcomeEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // Hide the other flow sections
  ['org-info-section', 'assessment-section', 'results-section'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.classList.add('section--hidden');
      el.style.display = 'none';
    }
  });

  // Update app state
  appState.currentSection = 'welcome-section';
}

function handleOrgFormSubmit(e) {
  e.preventDefault();
  
  // Collect organization information
  appState.organizationInfo = {
    name: document.getElementById('org-name').value,
    type: document.getElementById('org-type').value,
    assessorName: document.getElementById('assessor-name').value,
    assessorPosition: document.getElementById('assessor-position').value,
    date: new Date().toISOString().split('T')[0]
  };
  
  // Initialize assessment
  initializeAssessment();
  showSection('assessment-section');
}

function initializeAssessment() {
  // Setup domain navigation
  setupDomainNavigation();
  
  // Initialize assessment responses
  assessmentData.indicators.forEach(indicator => {
    if (!appState.assessmentResponses[indicator.id]) {
      appState.assessmentResponses[indicator.id] = {
        score: 0,
        evidence: '',
        completed: false
      };
    }
  });
  
  // Start with first domain
  appState.currentDomain = 1;
  appState.currentIndicatorIndex = 0;
  
  renderCurrentAssessment();
  updateProgress();
}

function setupDomainNavigation() {
  const domainTabs = document.getElementById('domain-tabs');
  if (!domainTabs) return;
  
  domainTabs.innerHTML = '';
  
  assessmentData.domains.forEach(domain => {
    const tab = document.createElement('button');
    tab.className = 'domain-tab';
    tab.textContent = `${domain.id}. ${domain.name}`;
    tab.onclick = () => switchToDomain(domain.id);
    
    if (domain.id === appState.currentDomain) {
      tab.classList.add('active');
    }
    
    domainTabs.appendChild(tab);
  });
}

function switchToDomain(domainId) {
  appState.currentDomain = domainId;
  appState.currentIndicatorIndex = 0;
  
  // Update active tab
  document.querySelectorAll('.domain-tab').forEach((tab, index) => {
    tab.classList.toggle('active', index === domainId - 1);
  });
  
  renderCurrentAssessment();
  updateProgress();
}

function renderCurrentAssessment() {
  const currentDomain = assessmentData.domains.find(d => d.id === appState.currentDomain);
  const domainIndicators = getDomainIndicators(appState.currentDomain);
  
  // Update domain title
  const titleElement = document.getElementById('current-domain-title');
  if (titleElement) {
    titleElement.textContent = `${currentDomain.name} - Assessment Digital Government`;
  }
  
  // Render assessment form
  const assessmentForm = document.getElementById('assessment-form');
  if (!assessmentForm) return;
  
  assessmentForm.innerHTML = '';
  
  domainIndicators.forEach((indicator, index) => {
    const indicatorElement = createIndicatorElement(indicator, index);
    assessmentForm.appendChild(indicatorElement);
  });
  
  updateNavigationButtons();
}

function getDomainIndicators(domainId) {
  return assessmentData.indicators.filter(indicator => indicator.domain === domainId);
}

function createIndicatorElement(indicator, index) {
  const indicatorDiv = document.createElement('div');
  indicatorDiv.className = 'indicator-item';
  indicatorDiv.innerHTML = `
    <div class="indicator-header">
      <div class="indicator-number">Indikator ${indicator.id}</div>
      <h3 class="indicator-title">${indicator.name}</h3>
    </div>
    <div class="indicator-content">
      <div class="maturity-selection">
        <h4>Pilih Tingkat Kematangan</h4>
        <div class="maturity-options">
          ${assessmentData.maturityLevels.map(level => `
            <label class="maturity-option ${appState.assessmentResponses[indicator.id]?.score === level.level ? 'selected' : ''}" 
                   onclick="selectMaturityLevel(${indicator.id}, ${level.level})">
              <input type="radio" name="maturity-${indicator.id}" value="${level.level}" 
                     ${appState.assessmentResponses[indicator.id]?.score === level.level ? 'checked' : ''}>
              <span class="maturity-level">Level ${level.level} - ${level.name}</span>
              <span class="maturity-description">${level.description}</span>
            </label>
          `).join('')}
        </div>
      </div>
      <div class="evidence-upload">
        <h4>Bukti Pendukung</h4>
        <textarea class="form-control" placeholder="Jelaskan bukti atau dokumentasi yang mendukung penilaian ini..."
                  onchange="updateEvidence(${indicator.id}, this.value)">${appState.assessmentResponses[indicator.id]?.evidence || ''}</textarea>
        <label class="form-label" for="file-${indicator.id}">Upload Dokumen (Opsional)</label>
        <input type="file" id="file-${indicator.id}" class="form-control" multiple 
               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.png">
      </div>
    </div>
  `;
  
  return indicatorDiv;
}

function selectMaturityLevel(indicatorId, level) {
  appState.assessmentResponses[indicatorId].score = level;
  appState.assessmentResponses[indicatorId].completed = true;
  
  // Update visual selection
  document.querySelectorAll(`input[name="maturity-${indicatorId}"]`).forEach(radio => {
    const option = radio.closest('.maturity-option');
    if (option) {
      option.classList.toggle('selected', radio.value == level);
    }
  });
  
  updateProgress();
  autoSave();
}

function updateEvidence(indicatorId, evidence) {
  appState.assessmentResponses[indicatorId].evidence = evidence;
  autoSave();
}

function updateProgress() {
  const completedCount = Object.values(appState.assessmentResponses)
    .filter(response => response.completed).length;
  const totalCount = assessmentData.indicators.length;
  
  const progressPercentage = (completedCount / totalCount) * 100;
  
  const progressFill = document.getElementById('progress-fill');
  const progressCurrent = document.getElementById('progress-current');
  const progressTotal = document.getElementById('progress-total');
  
  if (progressFill) progressFill.style.width = `${progressPercentage}%`;
  if (progressCurrent) progressCurrent.textContent = completedCount;
  if (progressTotal) progressTotal.textContent = totalCount;
}

function updateNavigationButtons() {
  const prevBtn = document.getElementById('prev-btn');
  const nextBtn = document.getElementById('next-btn');
  const submitBtn = document.getElementById('submit-btn');
  
  if (prevBtn) prevBtn.disabled = appState.currentDomain === 1;
  
  if (appState.currentDomain === assessmentData.domains.length) {
    if (nextBtn) nextBtn.classList.add('section--hidden');
    if (submitBtn) submitBtn.classList.remove('section--hidden');
  } else {
    if (nextBtn) nextBtn.classList.remove('section--hidden');
    if (submitBtn) submitBtn.classList.add('section--hidden');
  }
}

function navigateAssessment(direction) {
  if (direction === 'prev' && appState.currentDomain > 1) {
    switchToDomain(appState.currentDomain - 1);
  } else if (direction === 'next' && appState.currentDomain < assessmentData.domains.length) {
    switchToDomain(appState.currentDomain + 1);
  }
}

function saveProgress() {
  // In a real application, this would save to a server
  console.log('Progress saved:', appState);
  alert('Progress berhasil disimpan!');
}

function autoSave() {
  // Auto-save functionality (would normally save to server)
  console.log('Auto-saving...');
}

function showDashboard() {
  // Quick preview of current results
  calculateResults();
  showSection('results-section');
}

// submitAssessment() function moved to API integration section (line ~266)
// Now handles API submission with FormData and calls displayExportButtons() after success

function calculateResults() {
  // Calculate domain scores
  const domainScores = {};
  let overallScore = 0;
  
  assessmentData.domains.forEach(domain => {
    const domainIndicators = getDomainIndicators(domain.id);
    const domainTotal = domainIndicators.reduce((sum, indicator) => {
      const response = appState.assessmentResponses[indicator.id];
      return sum + (response?.score || 0);
    }, 0);
    
    const domainAverage = domainTotal / domainIndicators.length;
    domainScores[domain.id] = {
      score: domainAverage,
      weightedScore: (domainAverage * domain.weight) / 100
    };
    
    overallScore += domainScores[domain.id].weightedScore;
  });
  
  // Store results
  appState.results = {
    overallScore: overallScore,
    domainScores: domainScores,
    completedIndicators: Object.values(appState.assessmentResponses).filter(r => r.completed).length,
    assessmentDate: new Date().toLocaleDateString('id-ID')
  };
  
  renderResults();
}

function renderResults() {
  const results = appState.results;
  
  // Update summary
  const overallScoreEl = document.getElementById('overall-score');
  const completedIndicatorsEl = document.getElementById('completed-indicators');
  const assessmentDateEl = document.getElementById('assessment-date');
  
  if (overallScoreEl) overallScoreEl.textContent = results.overallScore.toFixed(1);
  if (completedIndicatorsEl) completedIndicatorsEl.textContent = results.completedIndicators;
  if (assessmentDateEl) assessmentDateEl.textContent = results.assessmentDate;
  
  // Update maturity level
  const overallLevel = getMaturityLevel(results.overallScore);
  const levelElement = document.getElementById('overall-level');
  if (levelElement) {
    levelElement.textContent = overallLevel.name;
    levelElement.className = `summary-level status--level-${overallLevel.level}`;
  }
  
  // Create charts
  createRadarChart();
  createDomainChart();
  renderDomainDetails();
}

function getMaturityLevel(score) {
  if (score < 2) return assessmentData.maturityLevels[0];
  if (score < 3) return assessmentData.maturityLevels[1];
  if (score < 4) return assessmentData.maturityLevels[2];
  if (score < 5) return assessmentData.maturityLevels[3];
  return assessmentData.maturityLevels[4];
}

function createRadarChart() {
  const canvas = document.getElementById('radar-chart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  const domainLabels = assessmentData.domains.map(d => d.name);
  const domainData = assessmentData.domains.map(d => appState.results.domainScores[d.id].score);
  
  new Chart(ctx, {
    type: 'radar',
    data: {
      labels: domainLabels,
      datasets: [{
        label: 'Tingkat Kematangan',
        data: domainData,
        backgroundColor: 'rgba(59, 130, 246, 0.2)',
        borderColor: '#3b82f6',
        borderWidth: 2,
        pointBackgroundColor: '#3b82f6',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: '#3b82f6'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        r: {
          beginAtZero: true,
          max: 5,
          ticks: {
            stepSize: 1
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
}

function createDomainChart() {
  const canvas = document.getElementById('domain-chart');
  if (!canvas) return;
  
  const ctx = canvas.getContext('2d');
  
  const domainLabels = assessmentData.domains.map(d => d.name);
  const domainScores = assessmentData.domains.map(d => appState.results.domainScores[d.id].score);
  const colors = ['#1FB8CD', '#FFC185', '#B4413C', '#ECEBD5'];
  
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: domainLabels,
      datasets: [{
        label: 'Skor Domain',
        data: domainScores,
        backgroundColor: colors,
        borderColor: colors.map(c => c + 'CC'),
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          max: 5,
          ticks: {
            stepSize: 1
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
}

function renderDomainDetails() {
  const domainDetails = document.getElementById('domain-details');
  if (!domainDetails) return;
  
  domainDetails.innerHTML = '';
  
  assessmentData.domains.forEach(domain => {
    const domainScore = appState.results.domainScores[domain.id];
    const domainIndicators = getDomainIndicators(domain.id);
    
    const domainDiv = document.createElement('div');
    domainDiv.className = 'domain-detail';
    domainDiv.innerHTML = `
      <div class="domain-detail__header">
        <h3>${domain.name}</h3>
        <div class="domain-score">
          <span class="score-value">${domainScore.score.toFixed(1)}</span>
          <span class="score-level">${getMaturityLevel(domainScore.score).name}</span>
        </div>
      </div>
      <div class="domain-detail__content">
        <div class="indicator-results">
          ${domainIndicators.map(indicator => {
            const response = appState.assessmentResponses[indicator.id];
            return `
              <div class="indicator-result">
                <span class="indicator-result__name">${indicator.name}</span>
                <span class="indicator-result__score">${response?.score || 0}/5</span>
              </div>
            `;
          }).join('')}
        </div>
      </div>
    `;
    
    domainDetails.appendChild(domainDiv);
  });
}

// Export buttons now handled by displayExportButtons() function called after assessment submission

function startNewAssessment() {
  // Reset application state
  appState = {
    selectedAssessmentType: 'pemdi',
    currentSection: 'welcome',
    currentDomain: 1,
    currentIndicatorIndex: 0,
    organizationInfo: {},
    assessmentResponses: {},
    results: null
  };
  
  // Clear forms
  const orgForm = document.getElementById('org-form');
  if (orgForm) orgForm.reset();
  
  showSection('welcome-section');
}

// Utility Functions
function formatDate(date) {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Auto-save with debounce
const debouncedAutoSave = debounce(autoSave, 2000);

// Export for debugging
window.appState = appState;
window.assessmentData = assessmentData;