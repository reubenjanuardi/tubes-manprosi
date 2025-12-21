# 🎨 Panduan Customize Template PDF SPBE

## File Location
`backend/app/Services/SpbePdfGenerator.php`

---

## 1️⃣ WARNA BRAND

### Warna Utama (Header, Border, Title)
**Lokasi:** Line 97, 110, 148, 157, 193, dll.
```php
// Default: Biru Gelap (26, 58, 107)
$this->SetFillColor(26, 58, 107);
$this->SetDrawColor(26, 58, 107);
$this->SetTextColor(26, 58, 107);

// Ubah ke warna lain, misal:
// Hijau: (34, 139, 34)
// Merah: (220, 53, 69)
// Ungu: (111, 66, 193)
```

### Warna Background Box
**Lokasi:** Line 223
```php
// Default: Biru Muda (240, 245, 250)
$this->SetFillColor(240, 245, 250);
```

---

## 2️⃣ LOGO & HEADER

### Menambahkan Logo di Cover Page
**Lokasi:** Setelah line 105 (dalam addCoverPage)
```php
// Tambahkan logo di bagian atas
$logoPath = public_path('client/assets/pemdi-logo.svg');
if (file_exists($logoPath)) {
    $this->Image($logoPath, 80, 30, 50, 0, 'SVG');
}
```

### Mengubah Text Header Cover
**Lokasi:** Line 110-112
```php
// Ganti text ini sesuai kebutuhan
$this->MultiCell(0, 6, "PEMDI.ID - TRANSFORMASI DIGITAL\nPLATFORM ASSESSMENT SPBE", 0, 'C');
```

---

## 3️⃣ JUDUL & SUBTITLE

### Judul Utama
**Lokasi:** Line 116-121
```php
// Ubah ukuran font (default: 18)
$this->SetFont('dejavusans', 'B', 18);
$this->Cell(0, 10, 'LAPORAN HASIL EVALUASI', 0, 1, 'C');

// Ubah text
$this->MultiCell(0, 8, "PENILAIAN KEMATANGAN\nDIGITAL PEMERINTAHAN", 0, 'C');
```

### Footer Cover
**Lokasi:** Line 133-136
```php
// Ubah text footer
$this->Cell(0, 5, 'Powered by PEMDI.ID', 0, 1, 'C');
```

---

## 4️⃣ FONT & UKURAN TEXT

### Font Family
Tersedia: `dejavusans`, `helvetica`, `times`, `courier`

### Ukuran Font Default:
```php
// Section Title (line 148)
$this->SetFont('dejavusans', 'B', 12);  // Bold, 12pt

// Sub-section (line 157)
$this->SetFont('dejavusans', 'B', 11);  // Bold, 11pt

// Paragraph (line 164)
$this->SetFont('dejavusans', '', 10);   // Regular, 10pt

// Footer (line 35)
$this->SetFont('dejavusans', '', 8);    // Regular, 8pt
```

---

## 5️⃣ MARGIN & SPACING

### Page Margins
**Lokasi:** Line 45
```php
// Default: 20mm semua sisi
$this->SetMargins(20, 20, 20);

// Ubah misal: kiri=25, atas=30, kanan=25
$this->SetMargins(25, 30, 25);
```

### Line Spacing
**Lokasi:** Line 165, 168, 169
```php
// Spacing antar paragraf
$this->Ln(3);   // 3mm
$this->Ln(5);   // 5mm
$this->Ln(10);  // 10mm
```

---

## 6️⃣ TABEL STYLING

### Header Tabel
**Lokasi:** Sekitar line 230-240
```php
// Background header tabel
$this->SetFillColor(26, 58, 107);  // Warna background
$this->SetTextColor(255, 255, 255); // Warna text putih
$this->SetFont('dejavusans', 'B', 10); // Font bold

// Cell tabel
$this->Cell(100, 8, 'DOMAIN', 1, 0, 'C', true);
```

### Border Tabel
```php
// 1 = dengan border
// 0 = tanpa border
$this->Cell(100, 8, 'Content', 1, 0, 'C', true);
                              // ^
                              // border
```

---

## 7️⃣ WATERMARK (Optional)

### Menambahkan Watermark
**Lokasi:** Dalam method Header() atau Footer()
```php
public function Header()
{
    if ($this->getPage() > 1) {
        // Existing code...
        
        // Tambahkan watermark
        $this->SetAlpha(0.1);
        $this->SetFont('dejavusans', 'B', 60);
        $this->SetTextColor(200, 200, 200);
        $this->RotatedText(50, 150, 'DRAFT', 45);
        $this->SetAlpha(1);
    }
}
```

---

## 8️⃣ CUSTOM SECTIONS

### Menambahkan Section Baru
**Lokasi:** Dalam method generateReport() setelah line 75
```php
// Tambahkan section custom
$this->AddPage();
$this->addCustomSection($data);

// Buat method baru
protected function addCustomSection($data)
{
    $this->addSectionTitle('ANALISIS TAMBAHAN');
    $this->addParagraph('Konten analisis tambahan...');
}
```

---

## 9️⃣ BORDER & DECORATIONS

### Border Cover
**Lokasi:** Line 97-102
```php
// Outer border (tebal)
$this->SetLineWidth(1);
$this->Rect(15, 15, 180, 267);

// Inner border (tipis)
$this->SetLineWidth(0.3);
$this->Rect(18, 18, 174, 261);

// Ubah ketebalan atau posisi sesuai keinginan
```

---

## 🔟 QUICK TIPS

### Mengubah Orientasi Halaman
```php
// Portrait (default)
parent::__construct('P', 'mm', 'A4', ...);

// Landscape
parent::__construct('L', 'mm', 'A4', ...);
```

### Menambahkan QR Code
```php
$this->write2DBarcode('https://pemdi.id/assessment/123', 'QRCODE,L', 170, 270, 20, 20);
```

### Export Options
```php
// Download (default)
return $this->Output('filename.pdf', 'D');

// Inline browser
return $this->Output('filename.pdf', 'I');

// Save to file
return $this->Output('/path/filename.pdf', 'F');

// Return as string (current)
return $this->Output('', 'S');
```

---

## 📚 RESOURCES

- TCPDF Documentation: https://tcpdf.org/docs/
- RGB Color Picker: https://www.google.com/search?q=rgb+color+picker
- Font List: DejaVu Sans, Helvetica, Times, Courier

---

## ⚠️ IMPORTANT NOTES

1. Setelah edit, test generate PDF untuk memastikan layout tidak rusak
2. Backup file sebelum melakukan perubahan besar
3. Ukuran A4: 210mm x 297mm (width x height)
4. Margins mempengaruhi area cetak (default 20mm semua sisi)
5. RGB values range: 0-255

---

## 🚀 TESTING

Untuk test hasil customization:
1. Jalankan backend: `php artisan serve`
2. Buka frontend: http://127.0.0.1:8000/client/index.html
3. Selesaikan assessment
4. Klik "Export PDF"
5. Lihat hasilnya!

Atau via API langsung:
```bash
curl http://127.0.0.1:8000/api/assessment/{id}/export/pdf -o test.pdf
```
