<?php

namespace App\Services;

use TCPDF;

class SpbePdfGenerator extends TCPDF
{
    protected $institution;
    protected $year;

    public function __construct($institution = '', $year = '')
    {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->institution = $institution;
        $this->year = $year;
    }

    public function Header()
    {
        if ($this->getPage() > 1) {
            $this->SetFont('dejavusans', '', 8);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Laporan Hasil Evaluasi SPBE - ' . $this->institution, 0, 1, 'C');
            $this->SetDrawColor(26, 58, 107);
            $this->Line(15, 15, 195, 15);
            $this->Ln(5);
        }
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, 0, 'C');
    }

    public function generateReport($data)
    {
        $this->institution = $data['institution'] ?? '';
        $this->year = $data['year'] ?? date('Y');

        $this->SetCreator('SPBE Evaluation System');
        $this->SetAuthor($this->institution);
        $this->SetTitle('Laporan Hasil Evaluasi SPBE - ' . $this->institution);
        $this->SetMargins(20, 20, 20);
        $this->SetAutoPageBreak(true, 25);

        // 1. COVER PAGE
        $this->addCoverPage($data);

        // 2. KATA PENGANTAR
        $this->AddPage();
        $this->addKataPengantar($data);

        // 3. EVALUASI SPBE TAHUN 2024 - Ringkasan, Dasar Hukum, Metodologi
        $this->AddPage();
        $this->addEvaluasiSection($data);
        
        $this->addMetodologiSection($data);

        // 4. EVALUASI SPBE TAHUN 2024 - Hasil Assessment & Indeks SPBE
        $this->AddPage();
        $this->addDashboardPage($data);
        
        $this->AddPage();
        $this->addIndeksSpbeSection($data);

        // 5. KEKUATAN DAN KELEMAHAN
        $this->addKekuatanKelemahan($data);

        // 6. REKOMENDASI
        $this->AddPage();
        $this->addRekomendasi($data);

        // 7. TINGKAT KEMATANGAN INDIKATOR
        $this->addNilaiIndikator($data);
        
        // 8. PENUTUP
        $this->AddPage();
        $this->addPenutup($data);

        // 10. ADD PAGE - EVALUASI PROYEK (Academic Evaluation)
        if (isset($data['evaluasiProyek'])) {
            $this->AddPage();
            $this->addProjectEvaluation($data['evaluasiProyek']);
        } else {
            // Add document footer
            $this->Ln(10);
            $this->SetFont('dejavusans', 'I', 9);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, '--- Akhir Dokumen ---', 0, 1, 'C');
            $this->Cell(0, 5, 'Dokumen ini dibuat secara otomatis oleh Sistem Evaluasi SPBE', 0, 1, 'C');
            $this->Cell(0, 5, 'Dicetak pada: ' . date('d F Y H:i:s', time() + (7 * 3600)) . ' WIB', 0, 1, 'C');
        }

        return $this->Output('', 'S');
    }

    protected function addCoverPage($data)
    {
        $this->AddPage();
        $this->SetPrintHeader(false);

        // Border
        $this->SetDrawColor(26, 58, 107);
        $this->SetLineWidth(1);
        $this->Rect(15, 15, 180, 267);

        // Inner border
        $this->SetLineWidth(0.3);
        $this->Rect(18, 18, 174, 261);

        // Logo PEMDI.ID - Centered
        $pageWidth = 210; // A4 width
        $logoY = 25;
        
        // Calculate dimensions
        $rectWidth = 35;   // Blue rectangle width
        $textWidth = 15;   // ".ID" text width
        $totalWidth = $rectWidth + $textWidth; // Total = 50mm
        
        // Center the entire logo group
        $logoStartX = ($pageWidth - $totalWidth) / 2; // Start X for perfect center
        
        // Blue rounded rectangle background
        $this->SetFillColor(26, 58, 107); // Brand blue
        $this->RoundedRect($logoStartX, $logoY, $rectWidth, 15, 2, '1111', 'F');
        
        // "PEMDI" text in white (centered in rectangle)
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY($logoStartX, $logoY + 3.5);
        $this->Cell($rectWidth, 8, 'PEMDI', 0, 0, 'C');
        
        // ".ID" text in blue (next to rectangle)
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(26, 58, 107);
        $this->SetXY($logoStartX + $rectWidth, $logoY + 3.5);
        $this->Cell($textWidth, 8, '.ID', 0, 0, 'L');
        
        // Logo SPBE - Centered below PEMDI
        $spbeLogoPath = public_path('client/assets/spbe_2023_sinergi-untuk-indonesia-maju-1024x844-A3QrEwkkwrSnE3Mg.avif');
        $spbeLogoPngPath = public_path('client/assets/spbe-logo.png');
        
        // Try to use the original AVIF or fallback to PNG
        $logoToUse = null;
        $logoExt = null;
        
        if (file_exists($spbeLogoPngPath)) {
            $logoToUse = $spbeLogoPngPath;
            $logoExt = 'PNG';
        } elseif (file_exists($spbeLogoPath)) {
            // Try AVIF (may not work in TCPDF)
            $logoToUse = $spbeLogoPath;
            $logoExt = 'PNG'; // Try treating as PNG
        }
        
        if ($logoToUse) {
            try {
                // Logo SPBE positioned centered below PEMDI logo
                $spbeLogoWidth = 50;  // Reduced from 60 to 50
                $spbeLogoHeight = 40; // Approximate height based on aspect ratio
                $spbeX = ($pageWidth - $spbeLogoWidth) / 2;
                $spbeY = $logoY + 25; // Position below PEMDI
                
                $this->Image($logoToUse, $spbeX, $spbeY, $spbeLogoWidth, 0, $logoExt);
                
                // Set Y position after logo (logo position + logo height + spacing)
                $this->SetY($spbeY + $spbeLogoHeight + 10);
            } catch (Exception $e) {
                // Fallback if image fails
                $this->SetY($logoY + 25);
                $this->SetFont('helvetica', 'B', 12);
                $this->SetTextColor(26, 58, 107);
                $this->Cell(0, 8, 'SPBE', 0, 1, 'C');
                $this->SetFont('helvetica', '', 8);
                $this->Cell(0, 5, 'Sistem Pemerintahan Berbasis Elektronik', 0, 1, 'C');
                $this->SetFont('helvetica', 'I', 8);
                $this->SetTextColor(59, 130, 246);
                $this->Cell(0, 5, 'Sinergi Untuk Indonesia Maju', 0, 1, 'C');
                $this->SetY($logoY + 50);
            }
        } else {
            // Fallback: Draw SPBE text if logo not found
            $this->SetY($logoY + 25);
            $this->SetFont('helvetica', 'B', 12);
            $this->SetTextColor(26, 58, 107);
            $this->Cell(0, 8, 'SPBE', 0, 1, 'C');
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 5, 'Sistem Pemerintahan Berbasis Elektronik', 0, 1, 'C');
            $this->SetFont('helvetica', 'I', 8);
            $this->SetTextColor(59, 130, 246);
            $this->Cell(0, 5, 'Sinergi Untuk Indonesia Maju', 0, 1, 'C');
            $this->SetY($logoY + 50);
        }

        // Header - Partnership Info (mulai dari posisi Y saat ini)
        $this->Ln(10); // Extra spacing sebelum partnership text
        
        $this->SetFont('dejavusans', 'B', 10);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 6, "PARTNERSHIP 4NESIA × TELKOM UNIVERSITY", 0, 1, 'C');
        $this->SetFont('dejavusans', 'B', 9);
        $this->Cell(0, 6, "COE SMART CITY", 0, 1, 'C');
        
        $this->Ln(3);
        $this->SetFont('dejavusans', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, "Platform Konsultasi & Pendampingan", 0, 1, 'C');
        $this->Cell(0, 5, "SPBE dan Pemerintahan Digital", 0, 1, 'C');
        
        $this->Ln(15);

        // Main Title - Lebih menonjol
        $this->SetFont('dejavusans', 'B', 20);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 10, 'LAPORAN HASIL EVALUASI', 0, 1, 'C');
        
        $this->Ln(5);
        $this->SetFont('dejavusans', 'B', 14);
        $this->Cell(0, 7, 'SISTEM PEMERINTAHAN BERBASIS ELEKTRONIK (SPBE)', 0, 1, 'C');

        $this->Ln(15);

        // Institution - Perbaiki penulisan
        $institution = $data['institution'] ?? '';
        // Remove "kabkota" or "provinsi" prefix if exists
        $institution = preg_replace('/^(kabkota|provinsi)\s+/i', '', $institution);
        
        $this->SetFont('dejavusans', 'B', 16);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 10, strtoupper($institution), 0, 1, 'C');

        $this->Ln(15);

        // Year - Format lebih elegan
        $this->SetFont('dejavusans', 'B', 18);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 10, 'TAHUN ' . ($data['year'] ?? date('Y')), 0, 1, 'C');

        // Footer - Disederhanakan
        $this->SetY(260);
        
        $this->SetFont('dejavusans', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Powered by PEMDI.ID – Transformasi Digital Indonesia', 0, 1, 'C');

        $this->SetPrintHeader(true);
    }

    protected function addDashboardPage($data)
    {
        $this->SetPrintHeader(false);
        
        // Page Title
        $this->SetFont('dejavusans', 'B', 16);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 10, 'Hasil Assessment Digital Government', 0, 1, 'C');
        $this->Ln(5);

        // Calculate overall score
        $indeksSpbe = $data['indeksSpbe'] ?? [];
        $totalNilai = 0;
        $count = count($indeksSpbe);
        foreach ($indeksSpbe as $item) {
            $totalNilai += $item['nilai'] ?? 0;
        }
        $overallScore = $count > 0 ? $totalNilai / $count : 0;
        
        // Determine maturity level
        if ($overallScore >= 4.2) {
            $maturityLevel = 'Optimized';
        } elseif ($overallScore >= 3.5) {
            $maturityLevel = 'Managed';
        } elseif ($overallScore >= 2.6) {
            $maturityLevel = 'Defined';
        } elseif ($overallScore >= 1.8) {
            $maturityLevel = 'Initial';
        } else {
            $maturityLevel = 'Ad-hoc';
        }

        // Info Cards Row
        $cardWidth = 42;
        $cardHeight = 25;
        $startX = 20;
        $cardY = $this->GetY();

        // Card 1: Overall Score
        $this->Rect($startX, $cardY, $cardWidth, $cardHeight, 'DF', array(), array(240, 245, 250));
        $this->SetXY($startX + 2, $cardY + 3);
        $this->SetFont('dejavusans', 'B', 24);
        $this->SetTextColor(26, 58, 107);
        $this->Cell($cardWidth - 4, 10, number_format($overallScore, 1), 0, 2, 'C');
        $this->SetXY($startX + 2, $cardY + 15);
        $this->SetFont('dejavusans', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell($cardWidth - 4, 5, 'Skor Overall', 0, 0, 'C');

        // Card 2: Maturity Level
        $this->Rect($startX + $cardWidth + 3, $cardY, $cardWidth, $cardHeight, 'DF', array(), array(240, 245, 250));
        $this->SetXY($startX + $cardWidth + 5, $cardY + 3);
        $this->SetFont('dejavusans', 'B', 14);
        $this->SetTextColor(26, 58, 107);
        $this->Cell($cardWidth - 4, 10, $maturityLevel, 0, 2, 'C');
        $this->SetXY($startX + $cardWidth + 5, $cardY + 15);
        $this->SetFont('dejavusans', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell($cardWidth - 4, 5, 'Level Kematangan', 0, 0, 'C');

        // Card 3: Total Indicators
        $totalIndicators = 32; // Default
        $this->Rect($startX + ($cardWidth + 3) * 2, $cardY, $cardWidth, $cardHeight, 'DF', array(), array(240, 245, 250));
        $this->SetXY($startX + ($cardWidth + 3) * 2 + 2, $cardY + 3);
        $this->SetFont('dejavusans', 'B', 24);
        $this->SetTextColor(26, 58, 107);
        $this->Cell($cardWidth - 4, 10, $totalIndicators, 0, 2, 'C');
        $this->SetXY($startX + ($cardWidth + 3) * 2 + 2, $cardY + 15);
        $this->SetFont('dejavusans', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell($cardWidth - 4, 5, 'Indikator Selesai', 0, 0, 'C');

        // Card 4: Assessment Date
        $this->Rect($startX + ($cardWidth + 3) * 3, $cardY, $cardWidth, $cardHeight, 'DF', array(), array(240, 245, 250));
        $this->SetXY($startX + ($cardWidth + 3) * 3 + 2, $cardY + 3);
        $this->SetFont('dejavusans', 'B', 12);
        $this->SetTextColor(26, 58, 107);
        $this->Cell($cardWidth - 4, 10, date('d/m/Y'), 0, 2, 'C');
        $this->SetXY($startX + ($cardWidth + 3) * 3 + 2, $cardY + 15);
        $this->SetFont('dejavusans', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell($cardWidth - 4, 5, 'Tanggal Assessment', 0, 0, 'C');

        $this->SetY($cardY + $cardHeight + 10);

        // Radar Chart Section
        $this->SetFont('dejavusans', 'B', 12);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 8, 'Maturity Radar Chart', 0, 1, 'L');
        $this->Ln(3);

        // Draw Radar Chart
        $this->drawRadarChart($indeksSpbe);

        $this->Ln(10);

        // Domain Score Bar Chart
        $this->SetFont('dejavusans', 'B', 12);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 8, 'Domain Score', 0, 1, 'L');
        $this->Ln(3);

        // Draw Domain Score Bars
        $this->drawDomainScoreBars($indeksSpbe);

        $this->SetPrintHeader(true);
    }

    protected function drawRadarChart($indeksSpbe)
    {
        // Radar chart configuration
        $centerX = 105; // Center of page
        $centerY = $this->GetY() + 45;
        $radius = 40;
        
        // Prepare domain data (max 6 domains)
        $domains = array_slice($indeksSpbe, 0, 6);
        $numDomains = count($domains);
        
        if ($numDomains == 0) return;

        // Draw background circles (levels 1-5)
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.2);
        for ($i = 1; $i <= 5; $i++) {
            $r = ($radius / 5) * $i;
            $this->Circle($centerX, $centerY, $r, 0, 360, 'D');
        }

        // Draw axis lines and labels
        $this->SetDrawColor(150, 150, 150);
        $this->SetLineWidth(0.3);
        $angleStep = 360 / $numDomains;
        
        for ($i = 0; $i < $numDomains; $i++) {
            $angle = ($angleStep * $i - 90) * pi() / 180; // Start from top
            $x = $centerX + ($radius * cos($angle));
            $y = $centerY + ($radius * sin($angle));
            
            // Draw axis line
            $this->Line($centerX, $centerY, $x, $y);
            
            // Add domain label
            $labelDistance = $radius + 8;
            $labelX = $centerX + ($labelDistance * cos($angle));
            $labelY = $centerY + ($labelDistance * sin($angle));
            
            $this->SetFont('dejavusans', '', 7);
            $this->SetTextColor(60, 60, 60);
            $this->SetXY($labelX - 15, $labelY - 2);
            $domainName = $domains[$i]['nama'] ?? 'Domain ' . ($i + 1);
            // Truncate long names
            if (strlen($domainName) > 15) {
                $domainName = substr($domainName, 0, 12) . '...';
            }
            $this->Cell(30, 4, $domainName, 0, 0, 'C');
        }

        // Draw data polygon
        $this->SetDrawColor(26, 58, 107);
        $this->SetLineWidth(0.5);
        $points = array();
        
        for ($i = 0; $i < $numDomains; $i++) {
            $angle = ($angleStep * $i - 90) * pi() / 180;
            $nilai = $domains[$i]['nilai'] ?? 0;
            $distance = ($radius / 5) * $nilai; // Scale to 1-5
            
            $x = $centerX + ($distance * cos($angle));
            $y = $centerY + ($distance * sin($angle));
            $points[] = array($x, $y);
        }
        
        // Fill polygon
        $this->SetFillColor(59, 130, 246, 20); // Blue with transparency
        $coords = array();
        foreach ($points as $point) {
            $coords[] = $point[0];
            $coords[] = $point[1];
        }
        
        // Draw polygon outline
        for ($i = 0; $i < count($points); $i++) {
            $nextI = ($i + 1) % count($points);
            $this->Line($points[$i][0], $points[$i][1], $points[$nextI][0], $points[$nextI][1]);
        }
        
        // Draw points
        $this->SetFillColor(26, 58, 107);
        foreach ($points as $point) {
            $this->Circle($point[0], $point[1], 1.5, 0, 360, 'F');
        }
        
        $this->SetY($centerY + $radius + 15);
    }

    protected function drawDomainScoreBars($indeksSpbe)
    {
        $startY = $this->GetY();
        $barWidth = 150;
        $barHeight = 8;
        $spacing = 3;
        
        $this->SetFont('dejavusans', '', 9);
        
        foreach ($indeksSpbe as $domain) {
            $nama = $domain['nama'] ?? 'Domain';
            $nilai = $domain['nilai'] ?? 0;
            
            // Truncate long names
            if (strlen($nama) > 35) {
                $nama = substr($nama, 0, 32) . '...';
            }
            
            // Domain name
            $this->SetTextColor(60, 60, 60);
            $this->Cell(170, 5, $nama, 0, 1, 'L');
            
            // Background bar
            $currentY = $this->GetY();
            $this->Rect(20, $currentY, $barWidth, $barHeight, 'F', array(), array(240, 240, 240));
            
            // Score bar
            $scoreWidth = ($barWidth / 5) * $nilai;
            
            // Color based on score
            if ($nilai >= 4.2) {
                $color = array(34, 197, 94); // Green
            } elseif ($nilai >= 3.5) {
                $color = array(59, 130, 246); // Blue
            } elseif ($nilai >= 2.6) {
                $color = array(234, 179, 8); // Yellow
            } else {
                $color = array(239, 68, 68); // Red
            }
            
            $this->Rect(20, $currentY, $scoreWidth, $barHeight, 'F', array(), $color);
            
            // Score text
            $this->SetXY(20 + $barWidth + 3, $currentY);
            $this->SetFont('dejavusans', 'B', 9);
            $this->SetTextColor(26, 58, 107);
            $this->Cell(15, $barHeight, number_format($nilai, 1), 0, 0, 'L');
            
            $this->SetY($currentY + $barHeight + $spacing);
            
            // Page break check
            if ($this->GetY() > 250) {
                break; // Stop if too many domains
            }
        }
    }
    
    protected function drawMaturityModelDiagram()
    {
        // Diagram Configuration
        $startY = $this->GetY();
        $centerX = 105; // Center of page
        $baseWidth = 100; // Width of bottom level (dikurangi dari 140)
        $levelHeight = 12; // Height of each level (dikurangi dari 18)
        $widthDecrement = 15; // Width reduction per level (dikurangi dari 20)
        
        // Define levels (from bottom to top)
        $levels = [
            1 => ['name' => 'Level 1 - Rintisan', 'desc' => 'Belum atau baru memulai', 'color' => [239, 68, 68]],
            2 => ['name' => 'Level 2 - Berkembang', 'desc' => 'Penerapan masih terbatas', 'color' => [249, 115, 22]],
            3 => ['name' => 'Level 3 - Terdefinisi', 'desc' => 'Sudah terstandar', 'color' => [234, 179, 8]],
            4 => ['name' => 'Level 4 - Terkelola', 'desc' => 'Terukur dan terkontrol', 'color' => [59, 130, 246]],
            5 => ['name' => 'Level 5 - Optimum', 'desc' => 'Optimal dan berkelanjutan', 'color' => [34, 197, 94]]
        ];
        
        // Draw title
        $this->SetFont('dejavusans', 'B', 10);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 6, 'Diagram Tingkat Kematangan SPBE', 0, 1, 'C');
        $this->Ln(2);
        
        $currentY = $this->GetY();
        
        // Draw pyramid from bottom to top
        for ($level = 1; $level <= 5; $level++) {
            $width = $baseWidth - (($level - 1) * $widthDecrement);
            $x = $centerX - ($width / 2);
            $y = $currentY + (($levelHeight + 1) * (5 - $level)); // Reverse order (bottom to top)
            
            // Draw rectangle
            $color = $levels[$level]['color'];
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $this->SetDrawColor(255, 255, 255);
            $this->SetLineWidth(0.5);
            $this->Rect($x, $y, $width, $levelHeight, 'DF');
            
            // Add text
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('dejavusans', 'B', 8);
            $this->SetXY($x, $y + 2);
            $this->Cell($width, 4, $levels[$level]['name'], 0, 0, 'C');
            
            $this->SetFont('dejavusans', '', 7);
            $this->SetXY($x, $y + 7);
            $this->Cell($width, 4, $levels[$level]['desc'], 0, 0, 'C');
        }
        
        // Set Y position after diagram
        $this->SetY($currentY + (($levelHeight + 1) * 5) + 3);
    }

    protected function addSectionTitle($title)
    {
        $this->Ln(5);
        $this->SetFont('dejavusans', 'B', 12);
        $this->SetFillColor(26, 58, 107);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 8, strtoupper($title), 0, 1, 'L', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(3);
    }

    protected function addSubSectionTitle($title)
    {
        $this->Ln(3);
        $this->SetFont('dejavusans', 'B', 11);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 7, $title, 0, 1, 'L');
        $this->SetTextColor(0, 0, 0);
        $this->Ln(2);
    }

    protected function addParagraph($text)
    {
        $this->SetFont('dejavusans', '', 10);
        $this->SetTextColor(30, 30, 30);
        $this->MultiCell(0, 6, $text, 0, 'J');
        $this->Ln(3);
    }

    protected function addKataPengantar($data)
    {
        $this->addSectionTitle('KATA PENGANTAR');
        $this->Ln(2);
        
        $kataPengantar = $data['kataPengantar'] ?? 'Puji syukur kami panjatkan kepada Tuhan Yang Maha Esa atas tersusunnya Laporan Hasil Evaluasi Sistem Pemerintahan Berbasis Elektronik (SPBE) ini.\n\nLaporan ini merupakan hasil evaluasi komprehensif terhadap penerapan SPBE yang bertujuan untuk mengukur tingkat kematangan dan efektivitas implementasi sistem pemerintahan berbasis elektronik.\n\nKami berharap laporan ini dapat memberikan gambaran yang jelas mengenai kondisi existing penerapan SPBE serta memberikan rekomendasi untuk perbaikan dan pengembangan ke depan.\n\nAtas perhatian dan dukungan semua pihak, kami ucapkan terima kasih.';
        
        $this->addParagraph($kataPengantar);
        $this->Ln(5);
    }

    protected function addEvaluasiSection($data)
    {
        // Header Bab
        $this->addSectionTitle('EVALUASI SISTEM PEMERINTAHAN BERBASIS ELEKTRONIK TAHUN ' . $this->year);
        $this->Ln(3);
        
        // Ringkasan Eksekutif
        $this->addSubSectionTitle('Ringkasan Eksekutif');
        $this->Ln(2);
        $this->addParagraph($data['ringkasan'] ?? 'Evaluasi SPBE dilaksanakan untuk mengukur tingkat kematangan penerapan Sistem Pemerintahan Berbasis Elektronik di lingkungan instansi pemerintah.');
        $this->Ln(5);

        // Dasar Hukum
        $this->addSubSectionTitle('Dasar Hukum');
        $this->Ln(2);
        $this->SetFont('dejavusans', '', 10);
        $dasarHukum = [
            'Peraturan Presiden Nomor 95 Tahun 2018 tentang Sistem Pemerintahan Berbasis Elektronik.',
            'Peraturan Menteri Pendayagunaan Aparatur Negara dan Birokrasi Reformasi Nomor 59 Tahun 2020 tentang Pemantauan dan Evaluasi Sistem Pemerintahan Berbasis Elektronik.',
            'Pedoman Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi Nomor 3 Tahun 2024 tentang Tata Cara Pemantauan dan Evaluasi Sistem Pemerintahan Berbasis Elektronik.'
        ];
        $no = 1;
        foreach ($dasarHukum as $hukum) {
            $this->MultiCell(0, 6, $no . '. ' . $hukum, 0, 'J');
            $this->Ln(1);
            $no++;
        }
        $this->Ln(5);
    }
    
    protected function addMetodologiSection($data)
    {
        $this->addSubSectionTitle('Metodologi Evaluasi SPBE');
        $this->Ln(2);
        
        $this->SetFont('dejavusans', 'B', 10);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 6, 'Model Tingkat Kematangan SPBE', 0, 1, 'L');
        $this->Ln(1);
        
        $modelKematangan = "Penerapan SPBE diukur dengan model tingkat kematangan SPBE, di mana setiap tingkat kematangan akan dideskripsikan dengan suatu kriteria yang menggambarkan karakteristik kapabilitas proses dan kapabilitas fungsi teknis SPBE yang terdiri atas 5 (lima) tingkatan, di mana semakin tinggi tingkat kematangan yang dimiliki oleh Instansi Pusat/Pemerintah Daerah menunjukkan semakin tinggi kapabilitas Instansi Pusat/Pemerintah Daerah tersebut.";
        $this->addParagraph($modelKematangan);
        
        // Check if enough space for diagram (need ~70mm)
        if ($this->GetY() > 200) {
            $this->AddPage();
        }
        
        $this->Ln(3);
        
        // Draw Maturity Model Diagram
        $this->drawMaturityModelDiagram();
        $this->Ln(3);
        
        $this->SetFont('dejavusans', 'B', 10);
        $this->SetTextColor(26, 58, 107);
        $this->Cell(0, 6, 'Metode Penilaian', 0, 1, 'L');
        $this->Ln(1);
        
        $metodePenilaian = "Penilaian evaluasi SPBE didasarkan pada data dan informasi yang diberikan oleh Instansi Pusat dan Pemerintah Daerah melalui beberapa tahapan kegiatan, yaitu Penilaian Mandiri, Penilaian Dokumen, Penilaian Interviu, serta Penilaian Visitasi (pada lokus tertentu).";
        $this->addParagraph($metodePenilaian);
    }

    protected function addIndeksSpbeSection($data)
    {
        $this->addSectionTitle('INDEKS SPBE');

        // Calculate total
        $indeksSpbe = $data['indeksSpbe'] ?? [];
        $totalNilai = 0;
        $count = count($indeksSpbe);
        foreach ($indeksSpbe as $item) {
            $totalNilai += $item['nilai'] ?? 0;
        }
        $rataRata = $count > 0 ? $totalNilai / $count : 0;

        // Predikat
        if ($rataRata >= 4.2) {
            $predikat = 'MEMUASKAN';
        } elseif ($rataRata >= 3.5) {
            $predikat = 'SANGAT BAIK';
        } elseif ($rataRata >= 2.6) {
            $predikat = 'BAIK';
        } elseif ($rataRata >= 1.8) {
            $predikat = 'CUKUP';
        } else {
            $predikat = 'KURANG';
        }

        // Summary Box
        $this->SetFont('dejavusans', 'B', 10);
        $this->SetFillColor(240, 245, 250);
        $this->SetDrawColor(26, 58, 107);
        $boxWidth = 60;
        $startX = ($this->getPageWidth() - $boxWidth) / 2;
        $this->SetX($startX);
        $this->Cell($boxWidth, 8, 'NILAI INDEKS SPBE', 1, 1, 'C', true);
        $this->SetX($startX);
        $this->SetFont('dejavusans', 'B', 24);
        $this->SetTextColor(26, 58, 107);
        $this->Cell($boxWidth, 15, number_format($rataRata, 2), 1, 1, 'C');
        $this->SetX($startX);
        $this->SetFont('dejavusans', 'B', 11);
        $this->SetFillColor(26, 58, 107);
        $this->SetTextColor(255, 255, 255);
        $this->Cell($boxWidth, 8, $predikat, 1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(8);

        // Table Header
        $this->SetFont('dejavusans', 'B', 10);
        $this->SetFillColor(26, 58, 107);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(15, 8, 'NO', 1, 0, 'C', true);
        $this->Cell(120, 8, 'DOMAIN / ASPEK', 1, 0, 'C', true);
        $this->Cell(35, 8, 'NILAI', 1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);

        // Table Body
        $this->SetFont('dejavusans', '', 10);
        $no = 1;
        $fill = false;
        foreach ($indeksSpbe as $item) {
            $this->SetFillColor(245, 248, 252);
            $this->Cell(15, 7, $no, 1, 0, 'C', $fill);
            $this->Cell(120, 7, $item['domain'] ?? '', 1, 0, 'L', $fill);
            $this->Cell(35, 7, number_format($item['nilai'] ?? 0, 2), 1, 1, 'C', $fill);
            $no++;
            $fill = !$fill;
        }

        // Total Row
        $this->SetFont('dejavusans', 'B', 10);
        $this->SetFillColor(26, 58, 107);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(135, 8, 'RATA-RATA INDEKS SPBE', 1, 0, 'R', true);
        $this->Cell(35, 8, number_format($rataRata, 2), 1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(5);
    }

    protected function addKekuatanKelemahan($data)
    {
        $this->addSectionTitle('KEKUATAN DAN KELEMAHAN PER ASPEK');

        $evaluasi = $data['evaluasi'] ?? [];

        $aspekList = [
            'kebijakan_internal' => 'a. Kebijakan Internal Tata Kelola SPBE',
            'perencanaan_strategis' => 'b. Perencanaan Strategis SPBE',
            'tik' => 'c. Teknologi Informasi dan Komunikasi',
            'penyelenggara' => 'd. Penyelenggara SPBE',
            'manajemen' => 'e. Penerapan Manajemen SPBE',
            'layanan_administrasi' => 'f. Layanan Administrasi Pemerintahan Berbasis Elektronik',
            'layanan_publik' => 'g. Layanan Publik Berbasis Elektronik',
        ];

        foreach ($aspekList as $key => $title) {
            // Check page space
            if ($this->GetY() > 230) {
                $this->AddPage();
            }

            $this->SetFont('dejavusans', 'B', 10);
            $this->SetTextColor(44, 90, 160);
            $this->Cell(0, 7, $title, 0, 1, 'L');
            $this->SetTextColor(0, 0, 0);

            $this->SetFont('dejavusans', 'B', 9);
            $this->Cell(25, 6, 'Kekuatan:', 0, 0, 'L');
            $this->SetFont('dejavusans', '', 9);
            $kekuatan = $evaluasi[$key]['kekuatan'] ?? '-';
            $this->MultiCell(0, 6, $kekuatan, 0, 'J');

            $this->SetFont('dejavusans', 'B', 9);
            $this->Cell(25, 6, 'Kelemahan:', 0, 0, 'L');
            $this->SetFont('dejavusans', '', 9);
            $kelemahan = $evaluasi[$key]['kelemahan'] ?? '-';
            $this->MultiCell(0, 6, $kelemahan, 0, 'J');

            $this->Ln(3);
        }
    }

    protected function addRekomendasi($data)
    {
        $this->addSectionTitle('REKOMENDASI');
        $this->addParagraph($data['rekomendasi'] ?? 'Berdasarkan hasil evaluasi SPBE, direkomendasikan untuk melakukan penyempurnaan Peta Rencana SPBE dan mengalokasikan anggaran yang memadai untuk peningkatan kematangan SPBE secara berkelanjutan.');
    }

    protected function addNilaiIndikator($data)
    {
        $this->addSectionTitle('PEROLEHAN NILAI TINGKAT KEMATANGAN INDIKATOR');

        $indikatorNilai = $data['indikatorNilai'] ?? [];

        // Table Header
        $this->SetFont('dejavusans', 'B', 9);
        $this->SetFillColor(26, 58, 107);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(12, 7, 'NO', 1, 0, 'C', true);
        $this->Cell(123, 7, 'NAMA INDIKATOR', 1, 0, 'C', true);
        $this->Cell(35, 7, 'NILAI', 1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);

        // Table Body
        $this->SetFont('dejavusans', '', 9);
        $no = 1;
        $fill = false;
        foreach ($indikatorNilai as $item) {
            // Check page space
            if ($this->GetY() > 260) {
                $this->AddPage();
                // Repeat header
                $this->SetFont('dejavusans', 'B', 9);
                $this->SetFillColor(26, 58, 107);
                $this->SetTextColor(255, 255, 255);
                $this->Cell(12, 7, 'NO', 1, 0, 'C', true);
                $this->Cell(123, 7, 'NAMA INDIKATOR', 1, 0, 'C', true);
                $this->Cell(35, 7, 'NILAI', 1, 1, 'C', true);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('dejavusans', '', 9);
            }

            $this->SetFillColor(245, 248, 252);
            $this->Cell(12, 6, $no, 1, 0, 'C', $fill);
            
            // Get indicator name (handle long text)
            $nama = $item['nama'] ?? '';
            $startY = $this->GetY();
            $startX = $this->GetX();
            
            $this->MultiCell(123, 6, $nama, 1, 'L', $fill);
            $endY = $this->GetY();
            $rowHeight = $endY - $startY;
            
            $this->SetXY($startX + 123, $startY);
            $this->Cell(35, $rowHeight, $item['nilai'] ?? 0, 1, 1, 'C', $fill);
            
            $no++;
            $fill = !$fill;
        }

        $this->Ln(10);
    }

    /**
     * Add Project Evaluation Section (Academic Evaluation)
     */
    protected function addProjectEvaluation($evaluasiProyek)
    {
        // KEKUATAN (Strengths)
        $this->addSectionTitle('EVALUASI PROYEK: KEKUATAN (STRENGTHS)');
        $this->addParagraph($evaluasiProyek['kekuatan'] ?? '');

        // Check page space
        if ($this->GetY() > 180) {
            $this->AddPage();
        }

        // KELEMAHAN (Weaknesses)
        $this->addSectionTitle('EVALUASI PROYEK: KELEMAHAN / KETERBATASAN');
        $this->addParagraph($evaluasiProyek['kelemahan'] ?? '');

        // REKOMENDASI STRATEGIS
        $this->AddPage();
        $this->addSectionTitle('REKOMENDASI STRATEGIS');

        $rekomendasi = $evaluasiProyek['rekomendasi'] ?? [];

        // A. Tindakan Perbaikan
        $this->addSubSectionTitle('A. Rekomendasi Tindakan Perbaikan');
        $this->addParagraph($rekomendasi['tindakan_perbaikan'] ?? '');

        // B. Pengembangan Kekuatan
        $this->addSubSectionTitle('B. Pengembangan dan Pemanfaatan Kekuatan');
        $this->addParagraph($rekomendasi['pengembangan_kekuatan'] ?? '');

        // Check page space
        if ($this->GetY() > 200) {
            $this->AddPage();
        }

        // C. Implikasi Kebijakan
        $this->addSubSectionTitle('C. Implikasi Kebijakan');
        $this->addParagraph($rekomendasi['implikasi_kebijakan'] ?? '');

        // D. Pengembangan Lanjutan
        $this->addSubSectionTitle('D. Rekomendasi Penelitian/Pengembangan Lanjutan');
        $this->addParagraph($rekomendasi['pengembangan_lanjutan'] ?? '');

        $this->Ln(10);

        // Footer
        $this->SetFont('dejavusans', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, '--- Akhir Dokumen ---', 0, 1, 'C');
        $this->Cell(0, 5, 'Dokumen ini dibuat secara otomatis oleh Sistem Evaluasi SPBE', 0, 1, 'C');
        $this->Cell(0, 5, 'Dicetak pada: ' . date('d F Y H:i:s', time() + (7 * 3600)) . ' WIB', 0, 1, 'C');
    }
    
    protected function addPenutup($data)
    {
        $this->addSectionTitle('PENUTUP');
        $this->Ln(2);
        
        // Kesimpulan
        $this->addSubSectionTitle('Kesimpulan');
        $this->Ln(1);
        
        $kesimpulan = $data['kesimpulan'] ?? "Berdasarkan hasil evaluasi SPBE yang telah dilaksanakan, dapat disimpulkan bahwa:\n\n1. Tingkat kematangan SPBE saat ini menunjukkan kondisi yang perlu terus ditingkatkan melalui perbaikan berkelanjutan.\n\n2. Terdapat beberapa aspek yang telah berjalan dengan baik dan perlu dipertahankan serta dikembangkan lebih lanjut.\n\n3. Diperlukan komitmen dan dukungan dari seluruh stakeholder untuk meningkatkan kualitas penerapan SPBE.\n\n4. Implementasi rekomendasi yang telah disusun diharapkan dapat meningkatkan efektivitas dan efisiensi penyelenggaraan pemerintahan berbasis elektronik.";
        
        $this->addParagraph($kesimpulan);
        $this->Ln(3);
        
        // Saran
        $this->addSubSectionTitle('Saran');
        $this->Ln(1);
        
        $saran = $data['saran'] ?? "Berdasarkan hasil evaluasi, disarankan:\n\n1. Melakukan perbaikan dan peningkatan pada aspek-aspek yang masih memerlukan pengembangan.\n\n2. Menyusun rencana aksi (action plan) untuk implementasi rekomendasi yang telah disusun.\n\n3. Melakukan monitoring dan evaluasi secara berkala untuk memastikan perbaikan yang berkelanjutan.\n\n4. Meningkatkan kompetensi SDM dalam pengelolaan SPBE melalui pelatihan dan pendampingan.\n\n5. Mengalokasikan anggaran yang memadai untuk pengembangan infrastruktur dan aplikasi SPBE.";
        
        $this->addParagraph($saran);
        $this->Ln(5);
        
        // Footer dokumen
        $this->SetFont('dejavusans', 'I', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, '--- Akhir Dokumen ---', 0, 1, 'C');
        $this->Cell(0, 5, 'Dokumen ini dibuat secara otomatis oleh Sistem Evaluasi SPBE', 0, 1, 'C');
        $this->Cell(0, 5, 'Dicetak pada: ' . date('d F Y H:i:s', time() + (7 * 3600)) . ' WIB', 0, 1, 'C');
    }
}
