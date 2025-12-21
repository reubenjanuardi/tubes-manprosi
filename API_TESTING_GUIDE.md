# 🧪 API TESTING GUIDE
## Digital Assessment Platform - Complete API Test Suite

**Version:** 2.0  
**Last Updated:** December 17, 2025

---

## 🚀 QUICK START

### Prerequisites
```bash
# Ensure backend server is running
cd backend
php artisan serve
# Server: http://localhost:8000
```

### Get Postman Collection
Import: `PEMDI_API_Collection.postman_collection.json`

---

## 📋 TEST SCENARIOS

### Scenario 1: Public API Access (No Auth Required)

#### 1.1 Get Active Indicators
```http
GET http://localhost:8000/api/indicators
Content-Type: application/json
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "indicators": {
      "Kebijakan dan Tata Kelola SPBE": [ ... ],
      "Layanan SPBE": [ ... ]
    },
    "version": 1,
    "last_updated": "2025-12-17T10:00:00.000000Z"
  }
}
```

#### 1.2 Check Sync Version
```http
GET http://localhost:8000/api/indicators/version
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "version": 1,
    "last_updated": "2025-12-17T10:00:00.000000Z",
    "last_updated_by": null,
    "change_description": "Initial system setup"
  }
}
```

---

### Scenario 2: User Authentication

#### 2.1 Register New User
```http
POST http://localhost:8000/api/auth/jwt/register
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@example.com",
      "role": "user"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

#### 2.2 Login (Get JWT Token)
```http
POST http://localhost:8000/api/auth/jwt/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "role": "admin"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
  }
}
```

**💡 Important:** Save the `token` for subsequent requests!

#### 2.3 Get Current User Profile
```http
GET http://localhost:8000/api/auth/me
Authorization: Bearer YOUR_TOKEN_HERE
```

#### 2.4 Refresh Token
```http
POST http://localhost:8000/api/auth/refresh
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### Scenario 3: Admin - Indicator Management

**Prerequisites:** Login as `admin` or `super_admin` and get token

#### 3.1 List All Indicators (with pagination)
```http
GET http://localhost:8000/api/admin/indicators?per_page=15&page=1
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Query Parameters:**
- `per_page` (int): Items per page (default: 15)
- `page` (int): Page number
- `search` (string): Search in indicator_text or group_name
- `status` (string): 'all', 'active', or 'inactive'
- `sort_by` (string): Column to sort by
- `sort_order` (string): 'asc' or 'desc'

**Example with filters:**
```http
GET http://localhost:8000/api/admin/indicators?search=Digital&status=active&per_page=10
Authorization: Bearer YOUR_ADMIN_TOKEN
```

#### 3.2 Create New Indicator
```http
POST http://localhost:8000/api/admin/indicators
Authorization: Bearer YOUR_ADMIN_TOKEN
Content-Type: application/json

{
  "group_name": "Infrastruktur SPBE",
  "indicator_text": "Ketersediaan perangkat komputasi cloud",
  "type": "scale",
  "scale_values": [1, 2, 3, 4, 5],
  "scale_labels": [
    "Tidak Ada",
    "Ad-hoc",
    "Terdefinisi",
    "Terkelola",
    "Optimal"
  ],
  "display_order": 1,
  "is_active": true
}
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Indicator created successfully",
  "data": {
    "indicator": {
      "id": 33,
      "group_name": "Infrastruktur SPBE",
      "indicator_text": "Ketersediaan perangkat komputasi cloud",
      "type": "scale",
      "scale_values": [1, 2, 3, 4, 5],
      "scale_labels": [...],
      "display_order": 1,
      "is_active": true,
      "created_at": "2025-12-17T10:30:00.000000Z"
    }
  }
}
```

**💡 Note:** Version will auto-increment after creation!

#### 3.3 Update Existing Indicator
```http
PUT http://localhost:8000/api/admin/indicators/33
Authorization: Bearer YOUR_ADMIN_TOKEN
Content-Type: application/json

{
  "indicator_text": "Updated indicator text",
  "is_active": false
}
```

#### 3.4 Delete Indicator
```http
DELETE http://localhost:8000/api/admin/indicators/33
Authorization: Bearer YOUR_ADMIN_TOKEN
```

#### 3.5 Reorder Indicators
```http
POST http://localhost:8000/api/admin/indicators/reorder
Authorization: Bearer YOUR_ADMIN_TOKEN
Content-Type: application/json

{
  "indicators": [
    {"id": 1, "display_order": 1},
    {"id": 2, "display_order": 2},
    {"id": 3, "display_order": 3}
  ]
}
```

---

### Scenario 4: Admin - User Management

**Prerequisites:** Login as `super_admin`

#### 4.1 List All Users
```http
GET http://localhost:8000/api/admin/users?per_page=20
Authorization: Bearer YOUR_SUPER_ADMIN_TOKEN
```

**Query Parameters:**
- `role` (string): Filter by role (user, admin, super_admin, viewer, auditor)
- `is_active` (boolean): Filter by active status
- `search` (string): Search in name, email, or organization
- `sort_by` (string): Column to sort
- `sort_order` (string): 'asc' or 'desc'

#### 4.2 Create New User
```http
POST http://localhost:8000/api/admin/users
Authorization: Bearer YOUR_SUPER_ADMIN_TOKEN
Content-Type: application/json

{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "SecurePass123",
  "role": "admin",
  "phone": "+62812345678",
  "organization": "Pemda XYZ",
  "position": "Staff IT",
  "is_active": true
}
```

**Valid Roles:**
- `user` - Basic user
- `admin` - Can manage indicators & assessments
- `super_admin` - Full system access
- `viewer` - Read-only access
- `auditor` - Report access + validation

#### 4.3 Update User
```http
PUT http://localhost:8000/api/admin/users/2
Authorization: Bearer YOUR_SUPER_ADMIN_TOKEN
Content-Type: application/json

{
  "name": "Jane Smith",
  "phone": "+628999999999",
  "role": "viewer"
}
```

#### 4.4 Toggle User Active Status
```http
POST http://localhost:8000/api/admin/users/2/toggle-active
Authorization: Bearer YOUR_SUPER_ADMIN_TOKEN
```

#### 4.5 Delete User
```http
DELETE http://localhost:8000/api/admin/users/2
Authorization: Bearer YOUR_SUPER_ADMIN_TOKEN
```

**⚠️ Note:** Cannot delete own account!

---

### Scenario 5: Admin - Executive Dashboard

#### 5.1 Get Dashboard Overview
```http
GET http://localhost:8000/api/admin/dashboard?timeframe=30
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Query Parameters:**
- `timeframe` (int): Number of days to look back (default: 30)

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "kpi": {
      "total_assessments": {"value": 150, "change": 12.5, "trend": "up"},
      "active_assessments": {"value": 25, "change": 0, "trend": "stable"},
      "total_responses": {"value": 3420, "change": 8.3, "trend": "up"},
      "response_rate": {"value": 84.5, "unit": "%"},
      "average_score": {"value": 4.2, "unit": "/5"},
      "completion_rate": {"value": 78.5, "unit": "%"}
    },
    "charts": {
      "assessments_timeline": {...},
      "responses_timeline": {...},
      "maturity_distribution": {...}
    },
    "recent_activity": [...],
    "top_performers": [...],
    "alerts": [...]
  }
}
```

#### 5.2 Get Custom Widget Data
```http
POST http://localhost:8000/api/admin/dashboard/widget
Authorization: Bearer YOUR_ADMIN_TOKEN
Content-Type: application/json

{
  "widget_type": "kpi",
  "widget_config": {
    "metric": "total_users",
    "timeframe": 7
  }
}
```

---

### Scenario 6: Admin - Response Analytics

#### 6.1 Get Analytics Overview
```http
GET http://localhost:8000/api/admin/responses/analytics
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "statistics": {
      "total_responses": 5432,
      "total_assessments": 150,
      "total_indicators": 32,
      "validated_responses": 4521,
      "pending_validation": 911,
      "completion_rate": 82.5,
      "average_response_time": 45.3
    },
    "recent_responses": [...]
  }
}
```

#### 6.2 List Responses with Filters
```http
GET http://localhost:8000/api/admin/responses?assessment_id=123&validated=true&per_page=50
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Query Parameters:**
- `assessment_id` (uuid): Filter by assessment
- `indicator_id` (int): Filter by indicator
- `validated` (boolean): Filter by validation status
- `start_date` (date): Start date (YYYY-MM-DD)
- `end_date` (date): End date (YYYY-MM-DD)
- `search` (string): Search in submitted_by
- `sort_by` (string): Column to sort
- `sort_order` (string): 'asc' or 'desc'
- `per_page` (int): Items per page

#### 6.3 Analyze Specific Assessment
```http
POST http://localhost:8000/api/admin/responses/analyze
Authorization: Bearer YOUR_ADMIN_TOKEN
Content-Type: application/json

{
  "assessment_id": "9d4f5678-1234-5678-1234-567812345678"
}
```

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "assessment": {
      "id": "9d4f5678-1234-5678-1234-567812345678",
      "name": "Q4 2024 Assessment",
      "status": "completed"
    },
    "overall_statistics": {
      "total_responses": 160,
      "validated_responses": 155,
      "unique_indicators": 32,
      "completion_percentage": 100
    },
    "by_indicator": [
      {
        "indicator_id": 1,
        "indicator_text": "...",
        "group_name": "...",
        "total_responses": 5,
        "validated_responses": 5,
        "average_score": 4.2,
        "min_score": 3,
        "max_score": 5,
        "median_score": 4,
        "response_distribution": {"1": 0, "2": 0, "3": 1, "4": 2, "5": 2}
      },
      ...
    ]
  }
}
```

#### 6.4 Export Responses to Excel
```http
GET http://localhost:8000/api/admin/responses/export?assessment_id=123&validated=true
Authorization: Bearer YOUR_ADMIN_TOKEN
```

**Response:** Excel file download (`responses_export_YYYY-MM-DD_HHMMSS.xlsx`)

**Supports same filters as list endpoint:**
- `assessment_id`
- `validated`
- `start_date`
- `end_date`

---

### Scenario 7: Admin - Assessment Management

#### 7.1 List Assessments
```http
GET http://localhost:8000/api/admin/assessments
Authorization: Bearer YOUR_ADMIN_TOKEN
```

#### 7.2 Get Assessment Statistics
```http
GET http://localhost:8000/api/admin/assessments/statistics
Authorization: Bearer YOUR_ADMIN_TOKEN
```

#### 7.3 Get Dashboard Data
```http
GET http://localhost:8000/api/admin/assessments/dashboard
Authorization: Bearer YOUR_ADMIN_TOKEN
```

---

## 🧪 TESTING WORKFLOW

### Complete Test Flow (15 minutes)

1. **Setup** (2 min)
   - Start backend server
   - Open Postman/Thunder Client
   - Import collection

2. **Public API** (2 min)
   - ✅ Get indicators
   - ✅ Check version

3. **Authentication** (3 min)
   - ✅ Register new user
   - ✅ Login as admin
   - ✅ Get profile
   - ✅ Refresh token

4. **Indicator Management** (4 min)
   - ✅ List indicators
   - ✅ Create indicator
   - ✅ Update indicator
   - ✅ Verify version incremented
   - ✅ Delete indicator

5. **User Management** (2 min)
   - ✅ Login as super_admin
   - ✅ Create user
   - ✅ Update user role
   - ✅ Toggle active status

6. **Analytics** (2 min)
   - ✅ Get dashboard overview
   - ✅ Get response analytics
   - ✅ Analyze assessment
   - ✅ Export Excel

---

## 🐛 TROUBLESHOOTING

### Common Issues

#### 401 Unauthorized
```json
{"success": false, "message": "Unauthenticated"}
```
**Solution:** Include valid JWT token in Authorization header

#### 403 Forbidden
```json
{"success": false, "message": "Unauthorized. Admin access required."}
```
**Solution:** Login with admin/super_admin role

#### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required."]
  }
}
```
**Solution:** Check request body against validation rules

#### 500 Internal Server Error
**Solution:** Check `storage/logs/laravel.log` for details

---

## 📊 PERFORMANCE BENCHMARKS

| Endpoint | Average Response Time | Expected |
|----------|----------------------|----------|
| GET /api/indicators | 150ms | < 200ms ✅ |
| POST /api/admin/indicators | 250ms | < 500ms ✅ |
| GET /api/admin/dashboard | 400ms | < 500ms ✅ |
| POST /api/admin/responses/analyze | 1200ms | < 2000ms ✅ |
| GET /api/admin/responses/export | 2500ms | < 5000ms ✅ |

---

## 🔐 SECURITY NOTES

1. **Never commit tokens to version control**
2. **Use HTTPS in production**
3. **Set strong JWT secret in `.env`**
4. **Implement rate limiting for production**
5. **Rotate tokens regularly**
6. **Monitor failed login attempts**

---

## 📝 API CHANGELOG

### Version 2.0 (December 17, 2025)
- ✅ Added sync_tracking table
- ✅ Extended RBAC with 5 roles
- ✅ Added dashboard analytics endpoints
- ✅ Added response analytics & export
- ✅ Added assessment_indicator_pivot
- ✅ Enhanced user management

### Version 1.0 (December 8, 2025)
- Initial API release
- Basic indicator management
- Assessment CRUD
- PDF/Excel export

---

**END OF API TESTING GUIDE**
