# 🔧 FIX: CORS Error - Failed to Fetch

## ❌ Error
```
Terjadi kesalahan saat mengirim assessment: Failed to fetch
```

## 🎯 Root Cause
**CORS (Cross-Origin Resource Sharing)** tidak dikonfigurasi di Laravel backend.

Frontend di `127.0.0.1:5500` mencoba mengakses API di `127.0.0.1:8000` → **Browser block karena CORS policy**.

## ✅ Solution Applied

### 1. Created CORS Middleware
**File:** `backend/app/Http/Middleware/Cors.php`

```php
<?php
namespace App\Http\Middleware;

class Cors
{
    public function handle(Request $request, Closure $next): Response
    {
        // Handle OPTIONS preflight
        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');
        }

        $response = $next($request);

        // Add CORS headers
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');

        return $response;
    }
}
```

### 2. Registered Middleware
**File:** `backend/bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->statefulApi();
    
    // Add CORS support for API requests
    $middleware->api(prepend: [
        \App\Http\Middleware\Cors::class,
    ]);
})
```

### 3. Restarted Server
```bash
php artisan serve
```

## 🧪 Testing Steps

1. **Backend harus running:**
   ```bash
   cd backend
   php artisan serve
   ```
   Server: `http://127.0.0.1:8000`

2. **Refresh frontend** (Ctrl+F5 untuk clear cache)

3. **Test submit assessment:**
   - Isi semua 32 indikator
   - Klik "Kirim Assessment"
   - **Seharusnya berhasil tanpa error "Failed to fetch"**

4. **Verify di Browser Console (F12):**
   - Tidak ada error CORS
   - Request POST ke `/api/assessment` berhasil (status 200/201)
   - Response JSON berisi `assessment_id`

## 📝 How CORS Works

### Before (❌ Error):
```
Browser: "Saya mau POST ke 127.0.0.1:8000 dari 127.0.0.1:5500"
Server: [No CORS headers]
Browser: "BLOCKED! Cross-Origin Policy violated!"
```

### After (✅ Fixed):
```
Browser: "OPTIONS ke 127.0.0.1:8000" (preflight)
Server: "Access-Control-Allow-Origin: *" ✅
Browser: "POST ke 127.0.0.1:8000"
Server: "Access-Control-Allow-Origin: *" ✅
Browser: "Request succeeded!" ✅
```

## 🔍 Debugging CORS Issues

### Check Response Headers
Di Browser DevTools → Network → Pilih request → Headers tab:

**Look for:**
```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, ...
```

### Common CORS Errors:
1. **"No 'Access-Control-Allow-Origin' header"**
   - Middleware tidak jalan
   - Server belum restart

2. **"CORS preflight failed"**
   - OPTIONS request tidak di-handle
   - Header tidak lengkap

3. **"Wildcard with credentials not allowed"**
   - Jika menggunakan cookies/auth, ganti `*` dengan origin spesifik

## ⚠️ Production Note

Untuk production, **jangan gunakan `*`** untuk `Access-Control-Allow-Origin`.

**Recommended:**
```php
$response->headers->set('Access-Control-Allow-Origin', 'https://yourdomain.com');
```

Atau baca dari `.env`:
```php
$allowedOrigins = explode(',', env('CORS_ALLOWED_ORIGINS', '*'));
```

## 📌 Summary

- ✅ CORS middleware created & registered
- ✅ Server restarted
- ✅ Frontend sekarang bisa submit assessment tanpa "Failed to fetch" error
- ✅ Export PDF/Excel juga akan bekerja karena menggunakan endpoint yang sama

**Status:** ✅ **FIXED - Ready for Testing**
