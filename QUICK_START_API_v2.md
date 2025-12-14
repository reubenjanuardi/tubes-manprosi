# 🚀 QUICK START GUIDE - PEMDI.ID API v2.0

## Prerequisites
- PHP 8.1+
- Composer
- SQLite or MySQL
- Postman (untuk testing)

---

## 📦 INSTALLATION

### 1. Install Dependencies
```bash
cd backend
composer install
```

### 2. Setup Environment
```bash
# Copy .env jika belum ada
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Run Migrations
```bash
# Jalankan semua migrations
php artisan migrate

# Atau fresh migrate (HATI-HATI: hapus semua data)
php artisan migrate:fresh
```

### 4. Start Development Server
```bash
php artisan serve
```
Server akan berjalan di: `http://localhost:8000`

---

## 🧪 TESTING API

### Method 1: Using Postman

**Import Postman Collection:**
1. Open Postman
2. Click **Import**
3. Select file: `PEMDI_API_Collection.postman_collection.json`
4. Set environment variables:
   - `base_url` = `http://localhost:8000`
   - `token` = (akan terisi setelah login)

**Test Flow:**
1. Register → Copy token dari response
2. Paste token ke environment variable
3. Test authenticated endpoints

### Method 2: Using cURL

**1. Register User:**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {...},
    "access_token": "1|xxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

**2. Login:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**3. Save Progress (with auth):**
```bash
curl -X POST http://localhost:8000/api/assessment/progress \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "progress_data": {
      "currentStep": 1,
      "responses": []
    }
  }'
```

---

## 🧪 RUN TESTS

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter AuthTest

# Run with coverage
php artisan test --coverage
```

---

## 📋 API ENDPOINTS OVERVIEW

### Public Endpoints (No Auth)
```
POST   /api/register       - Register new user
POST   /api/login          - Login user
POST   /api/contact        - Submit contact form
```

### Protected Endpoints (Require Bearer Token)
```
POST   /api/logout                           - Logout
GET    /api/profile                          - Get user profile
POST   /api/assessment                       - Submit assessment
GET    /api/assessment/{id}                  - Get assessment
GET    /api/assessment/{id}/export/pdf       - Export PDF
GET    /api/assessment/{id}/export/excel     - Export Excel
GET    /api/assessment/progress              - Get all progress
POST   /api/assessment/progress              - Save progress
GET    /api/assessment/progress/{id}         - Get specific progress
DELETE /api/assessment/progress/{id}         - Delete progress
```

---

## 🔑 AUTHENTICATION FLOW

### 1. Register/Login → Get Token
```json
{
  "access_token": "1|xxxxxxxxxxxxxx",
  "token_type": "Bearer"
}
```

### 2. Use Token in Headers
```
Authorization: Bearer 1|xxxxxxxxxxxxxx
```

### 3. Logout → Token Revoked
Token tidak bisa digunakan lagi, harus login ulang.

---

## 📊 DATABASE STRUCTURE

### Tables Created (8 total)
```
users                      - User accounts
organizations              - Organization data
assessments                - Assessment headers
assessment_responses       - Assessment details
assessment_progress        - Saved progress
contacts                   - Contact form submissions
personal_access_tokens     - Sanctum tokens
cache, jobs                - Laravel system tables
```

---

## 🐛 TROUBLESHOOTING

### Error: "SQLSTATE[HY000] [14] unable to open database file"
**Solution:**
```bash
# Create database file
touch database/database.sqlite

# Set permissions (Linux/Mac)
chmod 664 database/database.sqlite
```

### Error: "Unauthenticated"
**Solution:** Make sure you include Authorization header:
```
Authorization: Bearer {your_token}
```

### Error: "Class 'Laravel\Sanctum\HasApiTokens' not found"
**Solution:**
```bash
composer require laravel/sanctum
php artisan migrate
```

### Routes not found
**Solution:**
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

---

## 📝 EXAMPLE: Complete Assessment Flow

### Step 1: Register User
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Step 2: Save Token
```bash
TOKEN="1|xxxxxxxxxxxxxx"  # From register response
```

### Step 3: Save Progress
```bash
curl -X POST http://localhost:8000/api/assessment/progress \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "progress_data": {
      "currentStep": 5,
      "responses": [
        {"indicator_id": 1, "score": 4, "evidence_text": "Evidence 1"},
        {"indicator_id": 2, "score": 3, "evidence_text": "Evidence 2"}
      ]
    }
  }'
```

### Step 4: Submit Complete Assessment
```bash
curl -X POST http://localhost:8000/api/assessment \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "org_name": "Test Organization",
    "org_type": "Pemerintah",
    "assessor_name": "John Doe",
    "assessor_position": "Assessor",
    "assessment_date": "2025-12-14",
    "responses": [
      {"indicator_id": 1, "score": 4, "evidence_text": "Evidence 1"},
      {"indicator_id": 2, "score": 3, "evidence_text": "Evidence 2"}
    ]
  }'
```

### Step 5: Get Assessment
```bash
ASSESSMENT_ID="xxx-xxx-xxx"  # From submit response

curl -X GET http://localhost:8000/api/assessment/$ASSESSMENT_ID \
  -H "Authorization: Bearer $TOKEN"
```

### Step 6: Export PDF
```bash
curl -X GET http://localhost:8000/api/assessment/$ASSESSMENT_ID/export/pdf \
  -H "Authorization: Bearer $TOKEN" \
  --output assessment.pdf
```

---

## 🎯 FRONTEND INTEGRATION

### JavaScript Example
```javascript
const API_URL = 'http://localhost:8000/api';
let token = '';

// Register
async function register(name, email, password) {
  const response = await fetch(`${API_URL}/register`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name,
      email,
      password,
      password_confirmation: password
    })
  });
  const data = await response.json();
  token = data.data.access_token;
  return data;
}

// Login
async function login(email, password) {
  const response = await fetch(`${API_URL}/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });
  const data = await response.json();
  token = data.data.access_token;
  localStorage.setItem('token', token);
  return data;
}

// Save Progress
async function saveProgress(progressData) {
  const response = await fetch(`${API_URL}/assessment/progress`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({ progress_data: progressData })
  });
  return await response.json();
}

// Submit Assessment
async function submitAssessment(assessmentData) {
  const response = await fetch(`${API_URL}/assessment`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify(assessmentData)
  });
  return await response.json();
}
```

---

## 📞 SUPPORT

**Issues?** Check:
1. ✅ Migration status: `php artisan migrate:status`
2. ✅ Routes list: `php artisan route:list --path=api`
3. ✅ Laravel logs: `storage/logs/laravel.log`
4. ✅ Database connection: Check `.env` file

**Documentation:**
- 📄 Implementation Report: `IMPLEMENTATION_COMPLETE_REPORT.md`
- 📄 Postman Collection: `PEMDI_API_Collection.postman_collection.json`

---

**Version:** 2.0.0  
**Last Updated:** December 14, 2025  
**Status:** ✅ Production Ready
