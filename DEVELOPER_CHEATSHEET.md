# 🎯 Assessment Tool - Developer Quick Reference (Cheat Sheet)

## ⚡ Quick Commands

### Start Backend
```powershell
cd c:\laragon\www\TUBES_MANPROSI\backend
php artisan serve
```
**URL:** http://localhost:8000

### Database Operations
```powershell
# Run migrations
php artisan migrate

# Reset database
php artisan migrate:fresh

# Seed database (if seeders exist)
php artisan migrate:seed

# Create storage link
php artisan storage:link

# Check migration status
php artisan migrate:status
```

### Laravel Tinker (Database REPL)
```powershell
php artisan tinker

# Then in Tinker:
>>> Assessment::all();
>>> Assessment::with('responses')->first();
>>> ContactMessage::find(1);
>>> Assessment::find('550e8400-e29b-41d4-a716-446655440000');
```

### Useful Routes
```powershell
# List all routes
php artisan route:list

# List only API routes
php artisan route:list --path=api
```

---

## 📡 API Endpoints Quick Reference

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/contact` | Submit contact form |
| POST | `/api/assessment` | Submit assessment |
| GET | `/api/assessment/{id}` | Get assessment |
| GET | `/api/assessment/{id}/export/pdf` | Download PDF |
| GET | `/api/assessment/{id}/export/excel` | Get Excel data |

---

## 🗄️ Database Quick Reference

### Tables
```sql
contact_messages      -- Contact form submissions
assessments          -- Assessment headers
assessment_responses -- Individual responses (32 per assessment)
```

### Key Fields
```sql
-- Assessments
id                  VARCHAR(36) UUID      -- Assessment unique ID
total_score         DECIMAL(8,2)          -- Average score (1-5)
maturity_level      VARCHAR(255)          -- Calculated level
status              VARCHAR(255)          -- "completed"

-- Assessment Responses
assessment_id       VARCHAR(36)           -- FK to assessments
indicator_id        INT                   -- 1-32
score               INT                   -- 1-5
evidence_text       TEXT                  -- Optional description
document_path       VARCHAR(255)          -- File path
```

---

## 💻 Frontend Integration Quick Start

### 1. Copy Integration Code
```javascript
// From: API_INTEGRATION_SNIPPET.js
// To: client/app.js

// Copy these functions:
- submitContactForm()
- submitAssessment()
- displayExportButtons()
- initializeApiIntegration()
- testApiConnection()
```

### 2. Set API URL
```javascript
// Top of app.js
const API_BASE_URL = 'http://localhost:8000/api';
```

### 3. Initialize
```javascript
// In DOMContentLoaded event
document.addEventListener('DOMContentLoaded', function() {
  initializeApp();
  initializeApiIntegration(); // ADD THIS
});
```

### 4. Test
```javascript
// In browser console (F12)
testApiConnection();
```

---

## 🧪 Testing Quick Commands

### Test API with cURL
```bash
# Contact Form
curl -X POST http://localhost:8000/api/contact \
  -H "Content-Type: application/json" \
  -d '{"institution":"Test","fullname":"User","email":"test@test.com","service_type":"Test","message":"Message"}'

# Get Assessment
curl http://localhost:8000/api/assessment/550e8400-e29b-41d4-a716-446655440000 \
  -H "Accept: application/json"
```

### Test in Browser Console
```javascript
// Test API connection
testApiConnection();

// Test contact form
fetch('http://localhost:8000/api/contact', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    institution: 'Test',
    fullname: 'User',
    email: 'test@test.com',
    service_type: 'Test',
    message: 'Message'
  })
}).then(r => r.json()).then(d => console.log(d));
```

---

## 📊 Indicator IDs Reference

```
1-10   : Governance & Strategy
11-20  : Infrastructure & Operations
21-30  : Services & Capability
31-32  : Quality & Documentation

Use app/Helpers/IndicatorMapper.php to get full names:
IndicatorMapper::getIndicatorName(1)  // "Strategi dan Perencanaan TI"
IndicatorMapper::getIndicators()      // Get all 32
IndicatorMapper::getMaturityLevel(3.5) // "Managed"
```

---

## 🎨 Maturity Levels

```
Score 1.0-1.4 → Initial         (Proses ad hoc)
Score 1.5-2.4 → Repeatable      (Terstruktur tapi inkonsisten)
Score 2.5-3.4 → Defined         (Standar konsisten)
Score 3.5-4.4 → Managed         (Terukur & terkontrol)
Score 4.5-5.0 → Optimized       (Peningkatan berkelanjutan)
```

---

## 📁 File Structure Quick Reference

```
backend/
├── app/
│   ├── Models/
│   │   ├── Assessment.php         (UUID, HasMany responses)
│   │   ├── AssessmentResponse.php (BelongsTo assessment)
│   │   └── ContactMessage.php
│   ├── Http/Controllers/
│   │   ├── AssessmentController.php (Main logic, export)
│   │   └── ContactController.php
│   ├── Helpers/
│   │   └── IndicatorMapper.php (32 indicators mapping)
│   └── Exports/
│       └── AssessmentExport.php (Excel format)
├── database/migrations/
│   ├── create_contact_messages_table.php
│   ├── create_assessments_table.php
│   └── create_assessment_responses_table.php
├── resources/views/assessment/
│   └── pdf-report.blade.php
└── routes/
    └── api.php (5 endpoints)
```

---

## 🔑 Key Classes & Methods

### Assessment Model
```php
$assessment = Assessment::create([
  'org_name' => '...',
  'org_type' => '...',
  'assessor_name' => '...',
  'assessor_position' => '...',
  'assessment_date' => '...',
  'total_score' => 3.5,
  'maturity_level' => 'Managed',
  'status' => 'completed'
]);

// Get responses
$assessment->responses;
$assessment->responses()->get();

// Related query
Assessment::with('responses')->find($id);
```

### AssessmentController Methods
```php
// Submit assessment
store(Request $request)          // POST /api/assessment

// Get details
show(string $id)                 // GET /api/assessment/{id}

// Export PDF
exportPdf(string $id)            // GET /api/assessment/{id}/export/pdf

// Export Excel
exportExcel(string $id)          // GET /api/assessment/{id}/export/excel
```

### IndicatorMapper
```php
// Get indicator name by ID
IndicatorMapper::getIndicatorName(1)

// Get all indicators
IndicatorMapper::getIndicators()

// Get maturity level by score
IndicatorMapper::getMaturityLevel(3.5)  // "Managed"
```

---

## 🔍 Debugging Tips

### Check Logs
```powershell
# Real-time log watching
Get-Content storage/logs/laravel.log -Tail 50 -Wait

# Or just last 50 lines
Get-Content storage/logs/laravel.log -Tail 50
```

### Database Debugging
```powershell
php artisan tinker

# Check tables
>>> DB::select('SHOW TABLES;');

# Check specific data
>>> Assessment::count();
>>> Assessment::latest()->first();
>>> ContactMessage::all();
```

### API Debugging
```javascript
// In browser console
// Check network requests (F12 → Network)
// Check request/response in Console tab
testApiConnection();

// Manual fetch test
fetch('/api/assessment')
  .then(r => r.json())
  .then(d => console.log(d));
```

---

## 📝 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Database not found | Run `php artisan migrate` |
| File not uploading | Check storage:link created |
| CORS error | Update `config/cors.php` |
| UUID error | Check `Assessment` uses `HasUuids` trait |
| PDF blank | Check view file exists |
| API 404 | Check routes in `routes/api.php` |
| Validation error | Check request payload format |

---

## 🚀 Performance Tips

1. **Use eager loading:**
   ```php
   Assessment::with('responses')->get()  // Good
   Assessment::all()                      // Bad (N+1)
   ```

2. **Index frequently queried columns:**
   ```php
   // Already done: assessment_id (FK)
   // Already done: created_at (timestamp)
   ```

3. **Cache indicator mappings:**
   ```php
   // IndicatorMapper uses static array (no DB query)
   IndicatorMapper::getIndicators()  // Fast
   ```

4. **Optimize PDF generation:**
   ```php
   // Load only needed data
   Assessment::with('responses')->find($id)
   ```

---

## 📚 File Locations Quick Lookup

| What | Where |
|-----|-------|
| API routes | `routes/api.php` |
| Assessment logic | `app/Http/Controllers/AssessmentController.php` |
| Contact logic | `app/Http/Controllers/ContactController.php` |
| Assessment model | `app/Models/Assessment.php` |
| Indicator mapping | `app/Helpers/IndicatorMapper.php` |
| PDF template | `resources/views/assessment/pdf-report.blade.php` |
| Database config | `.env` |
| Frontend integration | `API_INTEGRATION_SNIPPET.js` |

---

## 🎯 Development Workflow

1. **Start backend**
   ```powershell
   php artisan serve
   ```

2. **Keep logs open**
   ```powershell
   # In separate terminal
   Get-Content storage/logs/laravel.log -Tail 50 -Wait
   ```

3. **Test endpoints**
   - Use Postman or browser console
   - Check network tab (F12)

4. **Debug with Tinker**
   ```powershell
   php artisan tinker
   # Query database directly
   ```

5. **Deploy changes**
   - Clear cache: `php artisan cache:clear`
   - Optimize: `php artisan optimize`

---

## 💡 Pro Tips

✨ **Tip 1:** Use Postman for API testing
- Create collection with all endpoints
- Save requests & responses
- Test different payloads quickly

✨ **Tip 2:** Use browser DevTools
- Network tab shows actual requests
- Console tab for JavaScript debugging
- Application tab for storage/cache

✨ **Tip 3:** Use Laravel Tinker
- Direct database queries
- Test models/methods
- Perfect for exploring data

✨ **Tip 4:** Check logs regularly
- Catch errors early
- Understand request flow
- Debug issues quickly

✨ **Tip 5:** Use version control
- Commit working code
- Easy to revert changes
- Track feature history

---

## 🔄 Common Workflows

### Workflow 1: Add New Indicator
1. Update `IndicatorMapper.php` (add to array)
2. Update frontend form (add score input)
3. Test submission
4. Verify in database

### Workflow 2: Fix API Bug
1. Check logs: `storage/logs/laravel.log`
2. Use Tinker to test: `php artisan tinker`
3. Check request payload in DevTools
4. Update controller logic
5. Test with Postman

### Workflow 3: Change Database
1. Create migration: `php artisan make:migration`
2. Edit migration file
3. Run: `php artisan migrate`
4. Update model if needed
5. Test with Tinker

### Workflow 4: Deploy to Production
1. Update `.env` (DB credentials, APP_DEBUG=false)
2. Run: `php artisan migrate`
3. Run: `php artisan optimize`
4. Run: `php artisan cache:clear`
5. Test all endpoints

---

## 📞 Quick Reference URLs

| Purpose | URL |
|---------|-----|
| Backend | `http://localhost:8000` |
| Contact API | `http://localhost:8000/api/contact` |
| Assessment API | `http://localhost:8000/api/assessment` |
| API routes list | `php artisan route:list --path=api` |
| Laravel docs | `https://laravel.com/docs` |
| PHP docs | `https://www.php.net/docs.php` |

---

## ⚙️ Environment Variables

```env
# .env file
APP_NAME=AssessmentTool
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=assessment_tool
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🎓 Learning Path

1. **Start:** Read `README.md`
2. **Understand:** Review `IMPLEMENTATION_GUIDE.md`
3. **Explore:** Check out `API_EXAMPLES.md`
4. **Practice:** Use Postman to test endpoints
5. **Code:** Study `AssessmentController.php`
6. **Integrate:** Copy `API_INTEGRATION_SNIPPET.js`
7. **Deploy:** Follow deployment guide

---

**Created:** December 8, 2025  
**Status:** Production Ready  
**Last Updated:** December 8, 2025

---

*Use this cheat sheet as quick reference while developing. For detailed info, refer to main documentation files.*
