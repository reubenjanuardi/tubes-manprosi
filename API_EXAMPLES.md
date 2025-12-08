# 📮 Assessment Tool API - Request/Response Examples

## 📋 Table of Contents
1. [Contact Form API](#1-contact-form-api)
2. [Assessment Submission](#2-assessment-submission)
3. [Get Assessment](#3-get-assessment)
4. [Export PDF](#4-export-pdf)
5. [Export Excel](#5-export-excel)
6. [Error Responses](#6-error-responses)

---

## 1. Contact Form API

### Endpoint
```http
POST /api/contact
```

### Request Headers
```http
Content-Type: application/json
Accept: application/json
```

### Request Body
```json
{
  "institution": "Pemerintah Daerah Jawa Barat",
  "fullname": "Budi Santoso",
  "email": "budi.santoso@pemda.go.id",
  "service_type": "Assessment Digital Transformation",
  "message": "Kami tertarik untuk melakukan assessment terhadap tingkat kematangan digital pemerintah daerah kami. Mohon informasi lebih lanjut mengenai proses dan timeline."
}
```

### Success Response (201 Created)
```json
{
  "success": true,
  "message": "Contact message saved successfully",
  "data": {
    "id": 1,
    "institution": "Pemerintah Daerah Jawa Barat",
    "fullname": "Budi Santoso",
    "email": "budi.santoso@pemda.go.id",
    "service_type": "Assessment Digital Transformation",
    "message": "Kami tertarik untuk melakukan assessment...",
    "created_at": "2025-12-08T03:52:24.000000Z",
    "updated_at": "2025-12-08T03:52:24.000000Z"
  }
}
```

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": [
      "The email field must be a valid email address."
    ],
    "message": [
      "The message field is required."
    ]
  }
}
```

---

## 2. Assessment Submission

### Endpoint
```http
POST /api/assessment
```

### Request Headers
```http
Content-Type: multipart/form-data
Accept: application/json
```

### Request Body (Form Data)
```
Field Name                          Type        Example Value
─────────────────────────────────────────────────────────────
org_name                           String       "Pemda Jawa Barat"
org_type                           String       "Pemerintah Daerah"
assessor_name                      String       "Budi Santoso"
assessor_position                  String       "Kepala Bagian TI"
assessment_date                    Date         "2025-12-08"

responses[0][indicator_id]         Integer      1
responses[0][score]                Integer      4
responses[0][evidence_text]        Text         "Sudah memiliki..."
responses[0][file]                 File         <binary>

responses[1][indicator_id]         Integer      2
responses[1][score]                Integer      3
responses[1][evidence_text]        Text         "Sedang dalam..."
responses[1][file]                 File         <binary>

... (total 32 indicators)
```

### JavaScript FormData Example
```javascript
const formData = new FormData();

// Header info
formData.append('org_name', 'Pemda Jawa Barat');
formData.append('org_type', 'Pemerintah Daerah');
formData.append('assessor_name', 'Budi Santoso');
formData.append('assessor_position', 'Kepala Bagian TI');
formData.append('assessment_date', '2025-12-08');

// Add responses for each indicator
for (let i = 0; i < 32; i++) {
  const indicatorId = i + 1;
  
  formData.append(`responses[${i}][indicator_id]`, indicatorId);
  formData.append(`responses[${i}][score]`, 4); // 1-5
  formData.append(`responses[${i}][evidence_text]`, 'Evidence text...');
  
  // Add file if exists
  const fileInput = document.getElementById(`file-${indicatorId}`);
  if (fileInput && fileInput.files[0]) {
    formData.append(`responses[${i}][file]`, fileInput.files[0]);
  }
}

// Send with fetch
fetch('/api/assessment', {
  method: 'POST',
  body: formData
  // Don't set Content-Type header - browser will handle it
})
```

### cURL Example
```bash
curl -X POST http://localhost:8000/api/assessment \
  -F "org_name=Pemda Jawa Barat" \
  -F "org_type=Pemerintah Daerah" \
  -F "assessor_name=Budi Santoso" \
  -F "assessor_position=Kepala Bagian TI" \
  -F "assessment_date=2025-12-08" \
  -F "responses[0][indicator_id]=1" \
  -F "responses[0][score]=4" \
  -F "responses[0][evidence_text]=Evidence text" \
  -F "responses[0][file]=@document.pdf" \
  -F "responses[1][indicator_id]=2" \
  -F "responses[1][score]=3" \
  -F "responses[1][evidence_text]=More evidence" \
  -F "responses[1][file]=@report.docx"
```

### Success Response (201 Created)
```json
{
  "success": true,
  "message": "Assessment saved successfully",
  "assessment_id": "550e8400-e29b-41d4-a716-446655440000",
  "total_score": 3.50,
  "maturity_level": "Managed"
}
```

### File Validation Error (422)
```json
{
  "success": false,
  "message": "Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG"
}
```

### File Size Error (422)
```json
{
  "success": false,
  "message": "File size exceeds 5MB limit"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Failed to save assessment",
  "error": "Exception message details..."
}
```

---

## 3. Get Assessment

### Endpoint
```http
GET /api/assessment/{id}
```

### URL Example
```
http://localhost:8000/api/assessment/550e8400-e29b-41d4-a716-446655440000
```

### Success Response (200 OK)
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
    "created_at": "2025-12-08T03:52:24.000000Z",
    "updated_at": "2025-12-08T03:52:24.000000Z",
    "responses": [
      {
        "id": 1,
        "assessment_id": "550e8400-e29b-41d4-a716-446655440000",
        "indicator_id": 1,
        "score": 4,
        "evidence_text": "Sudah memiliki policy terkait governance TI",
        "document_path": "/storage/evidence/550e8400-e29b-41d4-a716-446655440000/governance-policy.pdf",
        "created_at": "2025-12-08T03:52:24.000000Z",
        "updated_at": "2025-12-08T03:52:24.000000Z"
      },
      {
        "id": 2,
        "assessment_id": "550e8400-e29b-41d4-a716-446655440000",
        "indicator_id": 2,
        "score": 3,
        "evidence_text": "Sedang dalam tahap pengembangan",
        "document_path": "/storage/evidence/550e8400-e29b-41d4-a716-446655440000/risk-management.docx",
        "created_at": "2025-12-08T03:52:24.000000Z",
        "updated_at": "2025-12-08T03:52:24.000000Z"
      }
      // ... more responses for other indicators
    ]
  }
}
```

### Not Found Error (404)
```json
{
  "success": false,
  "message": "Assessment not found",
  "error": "No query results found for model [App\\Models\\Assessment]..."
}
```

---

## 4. Export PDF

### Endpoint
```http
GET /api/assessment/{id}/export/pdf
```

### URL Example
```
http://localhost:8000/api/assessment/550e8400-e29b-41d4-a716-446655440000/export/pdf
```

### Response
- **Type:** Binary (PDF file)
- **Headers:**
  ```http
  Content-Type: application/pdf
  Content-Disposition: attachment; filename="Assessment_Pemda Jawa Barat_550e8400.pdf"
  Content-Length: 245678
  ```

### Usage in Frontend
```javascript
// Direct browser download
const assessmentId = "550e8400-e29b-41d4-a716-446655440000";
window.location.href = `/api/assessment/${assessmentId}/export/pdf`;

// Or open in new tab
window.open(`/api/assessment/${assessmentId}/export/pdf`, '_blank');

// Or download programmatically
fetch(`/api/assessment/${assessmentId}/export/pdf`)
  .then(response => response.blob())
  .then(blob => {
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `Assessment_${assessmentId}.pdf`;
    a.click();
  });
```

### PDF Content Includes
- Organization information
- Assessor information
- Assessment date
- Total score (X.XX/5.00)
- Maturity level
- Status
- Detailed responses for each of 32 indicators
  - Indicator name
  - Score
  - Evidence text
  - Document path reference

---

## 5. Export Excel

### Endpoint
```http
GET /api/assessment/{id}/export/excel
```

### URL Example
```
http://localhost:8000/api/assessment/550e8400-e29b-41d4-a716-446655440000/export/excel
```

### Response (200 OK)
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
    [
      1,
      "Strategi dan Perencanaan TI",
      4,
      "Sudah memiliki policy terkait governance TI"
    ],
    [
      2,
      "Governance dan Manajemen Risiko",
      3,
      "Sedang dalam tahap pengembangan"
    ],
    [
      3,
      "Manajemen Aset TI",
      4,
      "Inventori aset TI sudah terdokumentasi"
    ],
    // ... more indicators
    [
      32,
      "Manajemen Dokumentasi",
      3,
      "Dokumentasi masih tersebar di berbagai sistem"
    ]
  ],
  "filename": "Assessment_Pemda Jawa Barat_550e8400.xlsx"
}
```

### Usage in Frontend
```javascript
// Get Excel data and create Excel file
async function downloadExcel(assessmentId) {
  try {
    const response = await fetch(`/api/assessment/${assessmentId}/export/excel`);
    const result = await response.json();
    
    if (result.success) {
      // Option 1: Use a library like SheetJS to create Excel
      const ws = XLSX.utils.aoa_to_sheet(result.data);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Assessment");
      XLSX.writeFile(wb, result.filename);
      
      // Option 2: Or send to server for generation
      // Download via separate endpoint that handles generation
    }
  } catch (error) {
    console.error('Error exporting Excel:', error);
  }
}
```

---

## 6. Error Responses

### Common Error Patterns

#### 1. Validation Error (422 Unprocessable Entity)
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "field_name": [
      "Error message for this field"
    ]
  }
}
```

#### 2. Not Found (404 Not Found)
```json
{
  "success": false,
  "message": "Assessment not found",
  "error": "No query results found..."
}
```

#### 3. Server Error (500 Internal Server Error)
```json
{
  "success": false,
  "message": "Failed to save assessment",
  "error": "SQLSTATE[42S22]: Column not found: 1054 Unknown column..."
}
```

#### 4. CORS Error (Browser - not from API)
```
Access to XMLHttpRequest at 'http://localhost:8000/api/contact' 
from origin 'http://localhost:3000' has been blocked by CORS policy: 
No 'Access-Control-Allow-Origin' header is present on the requested resource.
```

---

## 🧪 Testing with Postman

### 1. Create Collection
```
Assessment Tool API
├── Contact Form
├── Submit Assessment
├── Get Assessment
├── Export PDF
└── Export Excel
```

### 2. Contact Form Request
```
Method:  POST
URL:     http://localhost:8000/api/contact
Headers: Content-Type: application/json
Body:    {
           "institution": "Test Org",
           "fullname": "Test User",
           "email": "test@example.com",
           "service_type": "Assessment",
           "message": "Test message"
         }
```

### 3. Assessment Submission
```
Method:  POST
URL:     http://localhost:8000/api/assessment
Headers: (Postman sets multipart/form-data automatically)
Body:    (form-data)
         org_name: "Test Org"
         org_type: "Government"
         assessor_name: "Test Assessor"
         assessor_position: "Manager"
         assessment_date: "2025-12-08"
         responses[0][indicator_id]: 1
         responses[0][score]: 4
         responses[0][evidence_text]: "Evidence text"
         responses[0][file]: <select file>
         ... (repeat for all indicators)
```

### 4. Get Assessment
```
Method:  GET
URL:     http://localhost:8000/api/assessment/550e8400-e29b-41d4-a716-446655440000
Headers: Accept: application/json
```

### 5. Export Endpoints
```
Method:  GET
URL:     http://localhost:8000/api/assessment/{assessment_id}/export/pdf
         OR
         http://localhost:8000/api/assessment/{assessment_id}/export/excel
Headers: Accept: application/json
```

---

## 💡 Tips

1. **FormData Index Matching:**
   - Pastikan array index konsisten: `responses[0]`, `responses[1]`, dst
   - Jangan gunakan ID indicator langsung sebagai index

2. **File Upload:**
   - Max 5MB per file
   - Allowed: PDF, DOC, DOCX, JPG, PNG
   - Optional per indicator (dapat kosong)

3. **Score Validation:**
   - Score harus integer 1-5
   - Tidak ada score 0
   - Server tidak menerima float

4. **Date Format:**
   - Format: YYYY-MM-DD
   - Contoh: "2025-12-08"
   - String, tidak integer

5. **Assessment ID:**
   - UUID format (generated by server)
   - Simpan untuk reference/export
   - Tidak bisa di-modify setelah created

---

## 📞 Debugging

### Enable Debug Mode (Laravel)
```php
// .env
APP_DEBUG=true
APP_LOG_LEVEL=debug
```

### View Request Payload
```javascript
// Browser DevTools → Network tab
// Click request → Payload tab
```

### Check Server Logs
```powershell
Get-Content storage/logs/laravel.log -Tail 50
```

### Test Database Query
```powershell
php artisan tinker
>>> Assessment::with('responses')->find('550e8400-e29b-41d4-a716-446655440000');
```

---

**Last Updated:** December 8, 2025
**API Version:** v1
**Status:** Complete

