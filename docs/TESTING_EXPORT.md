# Testing Export PDF & Excel Functionality

## ✅ Perbaikan yang Telah Dilakukan

### 1. File `client/app.js`
- ✅ **Menghapus fungsi `exportResults()` lama** yang menampilkan alert
- ✅ **Menghapus fungsi `submitAssessment()` duplikat** yang tidak terintegrasi dengan API
- ✅ **Update fungsi `displayExportButtons()`** untuk menaruh tombol di container yang tepat
- ✅ **Verifikasi `API_BASE_URL`** sudah terdefinisi: `http://localhost:8000/api`

### 2. File `client/index.html`
- ✅ **Menghapus tombol export lama** dengan onclick `exportResults()`
- ✅ **Menambahkan container baru**: `<div id="export-buttons-container"></div>`
- ✅ Tombol export sekarang dibuat secara dinamis setelah assessment berhasil di-submit

---

## 🧪 Cara Testing

### Langkah 1: Jalankan Backend Laravel
```bash
cd c:\laragon\www\TUBES_MANPROSI
php artisan serve
```
Backend harus running di `http://localhost:8000`

### Langkah 2: Buka Frontend
Buka file: `c:\laragon\www\TUBES_MANPROSI\client\index.html` di browser

### Langkah 3: Lakukan Assessment
1. Klik "Mulai Assessment" 
2. Isi informasi organisasi
3. Isi semua 32 indikator dengan:
   - Pilih score (0-5)
   - Isi evidence text
   - Upload file bukti (opsional)
4. Klik **"Kirim Assessment"**

### Langkah 4: Test Export Buttons
Setelah assessment berhasil di-submit, Anda akan melihat:

**✅ DUA TOMBOL BARU:**
- 📥 **Export PDF** - Download laporan lengkap format PDF
- 📊 **Export Excel** - Download ringkasan data format XLSX

**Klik tombol tersebut untuk download file!**

---

## 🔍 Verifikasi Backend API

### Test Manual via Browser
Setelah assessment selesai, catat Assessment ID (UUID format), lalu buka:

```
http://localhost:8000/api/assessment/{assessment_id}/export/pdf
http://localhost:8000/api/assessment/{assessment_id}/export/excel
```

Ganti `{assessment_id}` dengan ID assessment yang baru dibuat.

### Expected Behavior
- **PDF:** Browser akan download file `Assessment_Report_{id}.pdf`
- **Excel:** Browser akan download file `Assessment_Summary_{id}.xlsx`

---

## 🐛 Troubleshooting

### Tombol Export Tidak Muncul
**Penyebab:** Frontend gagal submit assessment ke backend

**Solusi:**
1. Buka **Browser Console** (F12 → Console tab)
2. Cek error message
3. Pastikan backend Laravel running di `http://localhost:8000`
4. Pastikan database sudah di-migrate: `php artisan migrate`

### Error 404 Not Found
**Penyebab:** Route backend tidak ditemukan

**Solusi:**
```bash
php artisan route:list --path=api/assessment
```
Verifikasi route export ada:
- `GET api/assessment/{assessment}/export/pdf`
- `GET api/assessment/{assessment}/export/excel`

### PDF/Excel Download Gagal
**Penyebab:** Data assessment tidak ditemukan atau error di backend

**Solusi:**
1. Cek Laravel logs: `storage/logs/laravel.log`
2. Test API langsung:
   ```bash
   # Get latest assessment ID
   php artisan tinker
   >>> App\Models\Assessment::latest()->first()->id
   ```
3. Buka URL manual di browser dengan ID tersebut

### File Kosong atau Corrupt
**Penyebab:** 
- PDF: Template blade error atau library DOMPDF tidak terinstall
- Excel: Library maatwebsite/excel tidak terinstall

**Solusi:**
```bash
# Install dependencies
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel

# Publish config
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"

# Clear cache
php artisan config:clear
php artisan view:clear
```

---

## 📝 Technical Details

### Frontend Flow
```
User clicks "Kirim Assessment"
   ↓
submitAssessment() function (line ~266)
   ↓
FormData dengan 32 responses + files
   ↓
POST /api/assessment
   ↓
Backend returns: { success: true, assessment_id: "uuid..." }
   ↓
displayExportButtons(assessment_id) dipanggil
   ↓
Tombol Export PDF & Excel dibuat dengan href:
   - /api/assessment/{id}/export/pdf
   - /api/assessment/{id}/export/excel
```

### Backend Flow
```
GET /api/assessment/{id}/export/pdf
   ↓
AssessmentController@exportPdf()
   ↓
Load Assessment + Responses dari database
   ↓
Render Blade template: resources/views/assessment/pdf-report.blade.php
   ↓
DOMPDF generate PDF
   ↓
Return PDF file dengan headers:
   - Content-Type: application/pdf
   - Content-Disposition: attachment; filename="..."
```

---

## ✨ Fitur yang Telah Diimplementasikan

### PDF Report Features:
- ✅ Header dengan logo & informasi organisasi
- ✅ Executive Summary (total score + maturity level)
- ✅ Domain-by-domain breakdown
- ✅ Semua 32 indikator responses dengan evidence
- ✅ Professional styling dengan warna domain

### Excel Report Features:
- ✅ Sheet 1: Summary (metadata + overall score)
- ✅ Sheet 2: Responses (32 rows dengan indicator name, score, evidence)
- ✅ Professional formatting dengan header colors
- ✅ Auto column width

---

## 📞 Support

Jika masih ada issue setelah testing:
1. Cek Browser Console untuk error JavaScript
2. Cek Laravel logs: `storage/logs/laravel.log`
3. Test API endpoint manual via browser atau Postman
4. Pastikan semua dependencies terinstall: `composer install`

**Happy Testing! 🚀**
