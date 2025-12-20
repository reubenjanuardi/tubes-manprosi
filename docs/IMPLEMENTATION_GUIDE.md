# Assessment Tool - Backend API Implementation Guide

## 📋 Daftar Isi
1. [Struktur Proyek](#struktur-proyek)
2. [Setup Backend](#setup-backend)
3. [API Endpoints](#api-endpoints)
4. [Database Schema](#database-schema)
5. [Frontend Integration](#frontend-integration)
6. [Troubleshooting](#troubleshooting)

---

## 🏗️ Struktur Proyek

```
TUBES_MANPROSI/
├── client/                          # Frontend (HTML/JS/CSS)
│   ├── index.html
│   ├── app.js                      # Main frontend app
│   └── style.css
│
└── backend/                         # Laravel 11 API Backend
    ├── app/
    │   ├── Models/
    │   │   ├── ContactMessage.php
    │   │   ├── Assessment.php
    │   │   └── AssessmentResponse.php
    │   ├── Http/
    │   │   └── Controllers/
    │   │       ├── ContactController.php
    │   │       └── AssessmentController.php
    │   ├── Exports/
    │   │   └── AssessmentExport.php
    │   └── Helpers/
    │       └── IndicatorMapper.php
    ├── database/
    │   └── migrations/
    │       ├── create_contact_messages_table.php
    │       ├── create_assessments_table.php
    │       └── create_assessment_responses_table.php
    ├── resources/
    │   └── views/
    │       └── assessment/
    │           └── pdf-report.blade.php
    ├── routes/
    │   └── api.php
    ├── .env
    └── ...
```

---

## 🚀 Setup Backend

### 1. Prerequisites
- PHP 8.1+
- MySQL 5.7+
- Composer
- Laravel 11

### 2. Installation Steps

#### Step 1: Navigasi ke backend directory
```powershell
cd c:\laragon\www\TUBES_MANPROSI\backend
```

#### Step 2: Install dependencies
```powershell
composer install
```

#### Step 3: Setup environment file
```powershell
copy .env.example .env
php artisan key:generate
```

#### Step 4: Configure database (.env file)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=assessment_tool
DB_USERNAME=root
DB_PASSWORD=
```

#### Step 5: Run migrations
```powershell
php artisan migrate
```

#### Step 6: Create storage link (untuk file uploads)
```powershell
php artisan storage:link
```

#### Step 7: Install required packages (jika belum)
```powershell
composer require barryvdh/laravel-dompdf maatwebsite/excel -W
```

#### Step 8: Start Laravel development server
```powershell
php artisan serve
```

Backend akan berjalan di: `http://localhost:8000`

---

## 📡 API Endpoints

### 1. Contact Form Endpoint

**POST** `/api/contact`

**Request Body:**
```json
{
  "institution": "Pemda Jawa Barat",
  "fullname": "John Doe",
  "email": "john@example.com",
  "service_type": "Assessment Konsultasi",
  "message": "Saya tertarik dengan layanan assessment..."
}
```

**Response (Success - 201):**
```json
{
  "success": true,
  "message": "Contact message saved successfully",
  "data": {
    "id": 1,
    "institution": "Pemda Jawa Barat",
    "fullname": "John Doe",
    "email": "john@example.com",
    "service_type": "Assessment Konsultasi",
    "message": "...",
    "created_at": "2025-12-08T03:52:24.000000Z"
  }
}
```

---

### 2. Submit Assessment Endpoint

**POST** `/api/assessment`

**Content-Type:** `multipart/form-data`

**Request Body:**
```
org_name: "Pemda Jawa Barat"
org_type: "Pemerintah Daerah"
assessor_name: "Budi Santoso"
assessor_position: "Kepala Bagian TI"
assessment_date: "2025-12-08"
responses[0][indicator_id]: 1
responses[0][score]: 4
responses[0][evidence_text]: "Sudah memiliki policy..."
responses[0][file]: <FILE_OBJECT>
responses[1][indicator_id]: 2
responses[1][score]: 3
responses[1][evidence_text]: "Sedang dalam tahap..."
...
```

**Response (Success - 201):**
```json
{
  "success": true,
  "message": "Assessment saved successfully",
  "assessment_id": "550e8400-e29b-41d4-a716-446655440000",
  "total_score": 3.5,
  "maturity_level": "Managed"
}
```

**File Upload Requirements:**
- Max size: 5MB per file
- Allowed types: PDF, DOC, DOCX, JPG, PNG
- Storage path: `/storage/app/public/evidence/{assessment_id}/`

---

### 3. Get Assessment Details

**GET** `/api/assessment/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "org_name": "Pemda Jawa Barat",
    "org_type": "Pemerintah Daerah",
    "assessor_name": "Budi Santoso",
    "assessor_position": "Kepala Bagian TI",
    "assessment_date": "2025-12-08",
    "total_score": "3.50",
    "maturity_level": "Managed",
    "status": "completed",
    "responses": [
      {
        "id": 1,
        "assessment_id": "550e8400-e29b-41d4-a716-446655440000",
        "indicator_id": 1,
        "score": 4,
        "evidence_text": "Sudah memiliki policy...",
        "document_path": "/storage/evidence/550e8400.../file.pdf",
        "created_at": "2025-12-08T03:52:24.000000Z"
      }
    ]
  }
}
```

---

### 4. Export Assessment as PDF

**GET** `/api/assessment/{id}/export/pdf`

**Response:** File PDF

**Usage in Frontend:**
```javascript
const assessmentId = "550e8400-e29b-41d4-a716-446655440000";
window.location.href = `/api/assessment/${assessmentId}/export/pdf`;
// atau
fetch(`/api/assessment/${assessmentId}/export/pdf`).then(res => res.blob());
```

---

### 5. Export Assessment as Excel

**GET** `/api/assessment/{id}/export/excel`

**Response:** JSON dengan data untuk Excel

**Sample Response:**
```json
{
  "success": true,
  "data": [
    ["Organization Name", "Pemda Jawa Barat"],
    ["Organization Type", "Pemerintah Daerah"],
    ["Assessor Name", "Budi Santoso"],
    ["Assessor Position", "Kepala Bagian TI"],
    ["Assessment Date", "2025-12-08"],
    ["Total Score", 3.5],
    ["Maturity Level", "Managed"],
    ["Status", "completed"],
    [""],
    ["Indicator ID", "Indicator Name", "Score", "Evidence"],
    [1, "Strategi dan Perencanaan TI", 4, "Sudah memiliki policy..."]
  ],
  "filename": "Assessment_Pemda Jawa Barat_550e8400.xlsx"
}
```

---

## 🗄️ Database Schema

### Table: contact_messages
```sql
CREATE TABLE contact_messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  institution VARCHAR(255) NOT NULL,
  fullname VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  service_type VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Table: assessments
```sql
CREATE TABLE assessments (
  id CHAR(36) PRIMARY KEY,  -- UUID
  org_name VARCHAR(255) NOT NULL,
  org_type VARCHAR(255) NOT NULL,
  assessor_name VARCHAR(255) NOT NULL,
  assessor_position VARCHAR(255) NOT NULL,
  assessment_date DATE NOT NULL,
  total_score DECIMAL(8, 2) NOT NULL,
  maturity_level VARCHAR(255) NOT NULL,
  status VARCHAR(255) DEFAULT 'completed',
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Table: assessment_responses
```sql
CREATE TABLE assessment_responses (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  assessment_id CHAR(36) NOT NULL,
  indicator_id INT NOT NULL,
  score INT NOT NULL,
  evidence_text TEXT NULL,
  document_path VARCHAR(255) NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  
  FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE
);
```

### Indicator Mapping
Lihat file: `app/Helpers/IndicatorMapper.php`

**32 Indikator:**
1. Strategi dan Perencanaan TI
2. Governance dan Manajemen Risiko
3. Manajemen Aset TI
... dst (total 32 indikator)

---

## 🔗 Frontend Integration

### Quick Start

#### 1. Copy API Integration Snippet
Copy file `API_INTEGRATION_SNIPPET.js` ke frontend Anda atau copy-paste kodenya ke `app.js`

#### 2. Update API_BASE_URL
```javascript
// Ubah sesuai dengan URL backend Anda
const API_BASE_URL = 'http://localhost:8000/api';
```

#### 3. Initialize API Integration
Tambahkan di `DOMContentLoaded` event:
```javascript
document.addEventListener('DOMContentLoaded', function() {
  initializeApp();
  initializeApiIntegration(); // Add this
});
```

#### 4. Test Koneksi
Buka browser console dan jalankan:
```javascript
testApiConnection();
```

### Key Functions

#### submitContactForm()
Mengirim contact form ke `/api/contact`
```javascript
// Automatic when form is submitted
// No manual call needed
```

#### submitAssessment()
Mengirim assessment dengan multipart form data
```javascript
// Automatic when submit button clicked
// No manual call needed
```

#### displayExportButtons(assessmentId)
Menampilkan tombol download PDF dan Excel
```javascript
// Automatic when assessment submitted
// No manual call needed
```

### FormData Structure (Important!)

**Struktur data yang dikirim:**
```
org_name: "..."
org_type: "..."
assessor_name: "..."
assessor_position: "..."
assessment_date: "YYYY-MM-DD"

responses[0][indicator_id]: 1
responses[0][score]: 4
responses[0][evidence_text]: "..."
responses[0][file]: <File Object>

responses[1][indicator_id]: 2
responses[1][score]: 3
responses[1][evidence_text]: "..."
responses[1][file]: <File Object>

... dst untuk 32 indikator
```

---

## ✅ Testing

### Test Contact Form
```bash
curl -X POST http://localhost:8000/api/contact \
  -H "Content-Type: application/json" \
  -d '{
    "institution": "Test Org",
    "fullname": "Test User",
    "email": "test@example.com",
    "service_type": "Assessment",
    "message": "Test message"
  }'
```

### Test Assessment Submission
Gunakan Postman atau test via frontend interface

### Check Database
```bash
# Via Laravel Tinker
php artisan tinker
> Assessment::all();
> ContactMessage::all();
```

---

## 🐛 Troubleshooting

### 1. Database Connection Error
**Error:** `SQLSTATE[HY000] [1049] Unknown database`

**Solution:**
```powershell
# Check MySQL is running
# Verify .env DB_DATABASE=assessment_tool
php artisan migrate
```

### 2. CORS Error
**Error:** `Access to XMLHttpRequest has been blocked by CORS policy`

**Solution:**
- Ensure `.env` has correct `APP_URL`
- Update `config/cors.php` to allow frontend domain
- Or use proxy during development

### 3. File Upload Not Working
**Problem:** Files tidak tersimpan

**Solution:**
- Check file permissions: `storage/app/public` harus writable
- Run: `php artisan storage:link`
- Check file size (max 5MB)
- Check file type (PDF, DOC, DOCX, JPG, PNG only)

### 4. PDF Export Tidak Bekerja
**Error:** `DOMPDF` error

**Solution:**
```powershell
composer update barryvdh/laravel-dompdf
# Check `resources/views/assessment/pdf-report.blade.php` exists
```

### 5. 419 Token Mismatch (CSRF)
**Error:** `CSRF token mismatch`

**Solution:**
- API endpoints tidak memerlukan CSRF jika di dalam `/api` route
- Pastikan routes di `routes/api.php`
- Jangan gunakan session-based CSRF untuk API

### 6. Assessment ID tidak valid
**Problem:** UUID validation error

**Solution:**
- Pastikan `Assessment` model menggunakan `HasUuids` trait
- Check migration file uses `uuid()` column type
- Frontend tidak perlu generate UUID, server yang buat

---

## 📚 Additional Resources

### Files Created
1. **Models**: `app/Models/Assessment.php`, `AssessmentResponse.php`, `ContactMessage.php`
2. **Controllers**: `app/Http/Controllers/AssessmentController.php`, `ContactController.php`
3. **Helpers**: `app/Helpers/IndicatorMapper.php`
4. **Exports**: `app/Exports/AssessmentExport.php`
5. **Views**: `resources/views/assessment/pdf-report.blade.php`
6. **Routes**: `routes/api.php`
7. **Frontend**: `API_INTEGRATION_SNIPPET.js`

### Configuration Files Modified
1. `.env` - Database configuration
2. `bootstrap/app.php` - API routing
3. Migration files - Database schema

---

## 🎯 Next Steps

1. **Setup MySQL Database**
   ```powershell
   # Via Laragon: Start MySQL from Laragon menu
   ```

2. **Run Migrations**
   ```powershell
   php artisan migrate
   ```

3. **Start Backend Server**
   ```powershell
   php artisan serve
   ```

4. **Start Frontend**
   ```powershell
   # Open index.html in browser or use local server
   ```

5. **Test API Integration**
   - Fill contact form
   - Start assessment
   - Submit assessment
   - Download PDF/Excel

---

## 📞 Support

Untuk pertanyaan atau issues, silakan:
1. Check console browser (F12 → Console)
2. Check Laravel logs: `storage/logs/laravel.log`
3. Run `testApiConnection()` di browser console
4. Verify database: `php artisan tinker`

---

**Last Updated:** December 8, 2025
**Laravel Version:** 11
**API Version:** v1

