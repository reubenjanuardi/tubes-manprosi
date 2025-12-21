<?php

namespace App\Services;

class SpbeNarrativeGenerator
{
    protected $institution;
    protected $year;
    protected $indexScore;
    protected $predicate;
    protected $aspects;

    public function __construct(array $data)
    {
        $this->institution = $data['institution'] ?? 'Instansi';
        $this->year = $data['year'] ?? date('Y');
        $this->indexScore = $this->calculateIndexScore($data['indeksSpbe'] ?? []);
        $this->predicate = $this->determinePredicate($this->indexScore);
        $this->aspects = $this->prepareAspects($data['indeksSpbe'] ?? []);
    }

    protected function calculateIndexScore(array $indeksSpbe): float
    {
        if (empty($indeksSpbe)) {
            return 0;
        }
        $total = 0;
        foreach ($indeksSpbe as $item) {
            $total += $item['nilai'] ?? 0;
        }
        return $total / count($indeksSpbe);
    }

    protected function determinePredicate(float $score): string
    {
        if ($score >= 4.2) return 'Memuaskan';
        if ($score >= 3.5) return 'Sangat Baik';
        if ($score >= 2.6) return 'Baik';
        if ($score >= 1.8) return 'Cukup';
        return 'Kurang';
    }

    protected function determineLevel(float $score): string
    {
        if ($score >= 4.5) return 'Optimum';
        if ($score >= 3.5) return 'Terintegrasi';
        if ($score >= 2.5) return 'Terstandardisasi';
        if ($score >= 1.5) return 'Terkelola';
        return 'Rintisan';
    }

    protected function prepareAspects(array $indeksSpbe): array
    {
        $aspects = [];
        foreach ($indeksSpbe as $item) {
            $nilai = $item['nilai'] ?? 0;
            $aspects[] = [
                'name' => $item['domain'] ?? 'Aspek',
                'score' => $nilai,
                'level' => $this->determineLevel($nilai),
            ];
        }
        return $aspects;
    }

    /**
     * SECTION 1: KATA PENGANTAR
     * 2-3 paragraphs with formal opening, purpose, methodology, and appreciation
     */
    public function generateKataPengantar(): string
    {
        $paragraph1 = "Puji syukur kami panjatkan ke hadirat Tuhan Yang Maha Esa, karena atas " .
            "rahmat dan karunia-Nya, Laporan Hasil Evaluasi Sistem Pemerintahan Berbasis Elektronik " .
            "(SPBE) pada {$this->institution} Tahun {$this->year} dapat diselesaikan dengan baik. " .
            "Laporan ini disusun sebagai bentuk pertanggungjawaban atas pelaksanaan evaluasi SPBE " .
            "yang telah dilakukan secara komprehensif dan objektif berdasarkan ketentuan yang berlaku.";

        $paragraph2 = "Evaluasi SPBE merupakan proses penilaian terhadap pelaksanaan SPBE di " .
            "Instansi Pusat dan Pemerintah Daerah yang menghasilkan nilai Indeks SPBE sebagai " .
            "tolok ukur tingkat kematangan penerapan SPBE. Pelaksanaan evaluasi mengacu pada " .
            "Peraturan Presiden Nomor 95 Tahun 2018 tentang Sistem Pemerintahan Berbasis Elektronik " .
            "dan Peraturan Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi tentang " .
            "Pedoman Evaluasi SPBE. Metodologi evaluasi mencakup pengumpulan data dan bukti dukung, " .
            "verifikasi dokumen, penilaian tingkat kematangan indikator, serta analisis kekuatan, " .
            "kelemahan, dan rekomendasi perbaikan.";

        $paragraph3 = "Kami menyampaikan apresiasi dan ucapan terima kasih yang sebesar-besarnya " .
            "kepada seluruh pihak yang telah berkontribusi dalam pelaksanaan evaluasi ini, khususnya " .
            "kepada pimpinan dan seluruh jajaran {$this->institution} atas kerja sama dan dukungan " .
            "yang diberikan selama proses evaluasi berlangsung. Semoga laporan ini dapat memberikan " .
            "manfaat dan menjadi acuan dalam upaya peningkatan kualitas penyelenggaraan SPBE di " .
            "{$this->institution} pada masa mendatang.";

        return $paragraph1 . "\n\n" . $paragraph2 . "\n\n" . $paragraph3;
    }

    /**
     * SECTION 2: RINGKASAN EKSEKUTIF
     * 2-3 paragraphs explaining overall condition, achievements, and challenges
     */
    public function generateRingkasanEksekutif(): string
    {
        $scoreDescription = $this->getOverallScoreDescription();
        $achievementSummary = $this->getAchievementSummary();
        $challengeSummary = $this->getChallengeSummary();

        $paragraph1 = "Evaluasi Sistem Pemerintahan Berbasis Elektronik (SPBE) pada {$this->institution} " .
            "Tahun {$this->year} telah dilaksanakan secara menyeluruh terhadap seluruh domain dan aspek " .
            "penilaian yang ditetapkan. Hasil evaluasi menunjukkan bahwa {$this->institution} memperoleh " .
            "nilai Indeks SPBE sebesar " . number_format($this->indexScore, 2) . " dengan predikat " .
            "\"{$this->predicate}\". {$scoreDescription}";

        $paragraph2 = "Dari aspek pencapaian, {$achievementSummary} Hal ini menunjukkan komitmen " .
            "{$this->institution} dalam mengimplementasikan tata kelola pemerintahan berbasis elektronik " .
            "yang efisien, efektif, dan akuntabel. Penerapan SPBE telah memberikan kontribusi positif " .
            "terhadap peningkatan kualitas layanan publik dan tata kelola internal instansi.";

        $paragraph3 = "Di sisi lain, {$challengeSummary} Upaya peningkatan berkelanjutan perlu " .
            "dilakukan untuk mengatasi tantangan tersebut dan mencapai tingkat kematangan SPBE " .
            "yang lebih optimal. Dukungan dari seluruh pemangku kepentingan sangat diperlukan " .
            "untuk mewujudkan tata kelola pemerintahan berbasis elektronik yang terintegrasi " .
            "dan berkelanjutan.";

        return $paragraph1 . "\n\n" . $paragraph2 . "\n\n" . $paragraph3;
    }

    protected function getOverallScoreDescription(): string
    {
        if ($this->indexScore >= 4.2) {
            return "Capaian ini mencerminkan tingkat kematangan penerapan SPBE yang sangat memuaskan, " .
                "dimana seluruh aspek telah terimplementasi secara optimal, terintegrasi, dan berkelanjutan.";
        } elseif ($this->indexScore >= 3.5) {
            return "Capaian ini menunjukkan bahwa penerapan SPBE di {$this->institution} telah berjalan " .
                "dengan sangat baik dengan tingkat kematangan yang terintegrasi antarunit organisasi.";
        } elseif ($this->indexScore >= 2.6) {
            return "Capaian ini menunjukkan bahwa penerapan SPBE di {$this->institution} telah " .
                "terstandardisasi dan berjalan dengan baik, meskipun masih terdapat ruang untuk peningkatan.";
        } elseif ($this->indexScore >= 1.8) {
            return "Capaian ini menunjukkan bahwa penerapan SPBE di {$this->institution} telah " .
                "terkelola namun masih memerlukan upaya peningkatan yang signifikan pada berbagai aspek.";
        } else {
            return "Capaian ini menunjukkan bahwa penerapan SPBE di {$this->institution} masih dalam " .
                "tahap rintisan dan memerlukan perhatian serius untuk perbaikan pada seluruh aspek.";
        }
    }

    protected function getAchievementSummary(): string
    {
        $strongAspects = [];
        foreach ($this->aspects as $aspect) {
            if ($aspect['score'] >= 4.0) {
                $strongAspects[] = $aspect['name'];
            }
        }

        if (count($strongAspects) >= 3) {
            return "beberapa aspek telah menunjukkan tingkat kematangan yang optimal, antara lain " .
                implode(', ', array_slice($strongAspects, 0, 3)) . ".";
        } elseif (count($strongAspects) > 0) {
            return "aspek " . implode(' dan ', $strongAspects) . " telah menunjukkan pencapaian yang baik.";
        } else {
            return "{$this->institution} telah menunjukkan upaya dalam mengimplementasikan SPBE " .
                "pada berbagai aspek meskipun masih memerlukan penguatan lebih lanjut.";
        }
    }

    protected function getChallengeSummary(): string
    {
        $weakAspects = [];
        foreach ($this->aspects as $aspect) {
            if ($aspect['score'] < 3.0) {
                $weakAspects[] = $aspect['name'];
            }
        }

        if (count($weakAspects) >= 2) {
            return "masih terdapat beberapa aspek yang memerlukan perhatian khusus dan peningkatan, " .
                "terutama pada " . implode(' dan ', array_slice($weakAspects, 0, 2)) . ".";
        } elseif (count($weakAspects) > 0) {
            return "aspek " . $weakAspects[0] . " masih memerlukan upaya peningkatan yang lebih intensif.";
        } else {
            return "tantangan utama adalah mempertahankan dan meningkatkan capaian yang telah diraih " .
                "serta melakukan inovasi berkelanjutan untuk mengantisipasi perkembangan teknologi " .
                "dan kebutuhan layanan.";
        }
    }

    /**
     * SECTION 3: TINGKAT KEMATANGAN PENERAPAN SPBE
     * 1-2 paragraphs explaining the meaning of maturity level
     */
    public function generateTingkatKematangan(): string
    {
        $level = $this->determineLevel($this->indexScore);
        $levelDescription = $this->getLevelDescription($level);
        $governanceImplication = $this->getGovernanceImplication();

        $paragraph1 = "Berdasarkan hasil evaluasi, {$this->institution} berada pada tingkat kematangan " .
            "\"{$level}\" dengan nilai Indeks SPBE sebesar " . number_format($this->indexScore, 2) . " " .
            "dan predikat \"{$this->predicate}\". {$levelDescription}";

        $paragraph2 = "{$governanceImplication} Tingkat kematangan ini perlu terus ditingkatkan " .
            "melalui upaya perbaikan berkelanjutan pada aspek-aspek yang masih memerlukan penguatan, " .
            "dengan tetap mempertahankan capaian yang telah diraih pada aspek-aspek yang sudah baik.";

        return $paragraph1 . "\n\n" . $paragraph2;
    }

    protected function getLevelDescription(string $level): string
    {
        $descriptions = [
            'Optimum' => "Pada tingkat ini, penerapan SPBE telah berjalan secara optimal dengan " .
                "dukungan kebijakan yang komprehensif, tata kelola yang terintegrasi, manajemen yang " .
                "efektif, serta layanan yang berkualitas tinggi. Instansi telah melakukan evaluasi " .
                "dan perbaikan berkelanjutan untuk memastikan optimalisasi penerapan SPBE.",
            
            'Terintegrasi' => "Pada tingkat ini, penerapan SPBE telah terintegrasi antarunit organisasi " .
                "dengan dukungan kebijakan, tata kelola, dan manajemen yang terkoordinasi. Layanan " .
                "SPBE telah saling terhubung dan memberikan kemudahan bagi pengguna dalam mengakses " .
                "layanan pemerintahan berbasis elektronik.",
            
            'Terstandardisasi' => "Pada tingkat ini, penerapan SPBE telah mengacu pada standar dan " .
                "pedoman yang ditetapkan secara organisasi. Kebijakan, tata kelola, manajemen, dan " .
                "layanan SPBE telah didokumentasikan dan disosialisasikan kepada seluruh unit kerja " .
                "di lingkungan instansi.",
            
            'Terkelola' => "Pada tingkat ini, penerapan SPBE sudah dilaksanakan namun belum sepenuhnya " .
                "terstandardisasi secara organisasi. Beberapa aspek telah berjalan dengan baik, " .
                "namun masih terdapat variasi dalam pelaksanaan antarunit kerja.",
            
            'Rintisan' => "Pada tingkat ini, penerapan SPBE baru dimulai atau masih dalam tahap " .
                "pengembangan awal. Kebijakan, tata kelola, manajemen, dan layanan SPBE belum " .
                "terstandarisasi dan masih bersifat ad-hoc atau berdasarkan inisiatif individual.",
        ];

        return $descriptions[$level] ?? $descriptions['Rintisan'];
    }

    protected function getGovernanceImplication(): string
    {
        if ($this->indexScore >= 4.2) {
            return "Dari sisi tata kelola, tingkat kematangan ini menunjukkan bahwa {$this->institution} " .
                "telah memiliki tata kelola SPBE yang matang, terintegrasi, dan berkelanjutan. " .
                "Konsistensi penerapan telah terjaga dengan baik di seluruh unit organisasi.";
        } elseif ($this->indexScore >= 3.5) {
            return "Dari sisi tata kelola, tingkat kematangan ini menunjukkan bahwa koordinasi dan " .
                "integrasi antarunit organisasi telah berjalan dengan baik. Konsistensi penerapan " .
                "perlu terus dijaga dan ditingkatkan untuk mencapai tingkat optimum.";
        } elseif ($this->indexScore >= 2.6) {
            return "Dari sisi tata kelola, tingkat kematangan ini menunjukkan bahwa standardisasi " .
                "telah diterapkan pada tingkat organisasi. Tantangan selanjutnya adalah meningkatkan " .
                "integrasi antarunit dan memastikan konsistensi penerapan di seluruh organisasi.";
        } else {
            return "Dari sisi tata kelola, tingkat kematangan ini menunjukkan bahwa masih diperlukan " .
                "upaya yang signifikan untuk membangun fondasi tata kelola SPBE yang solid, " .
                "terstandarisasi, dan terintegrasi di seluruh unit organisasi.";
        }
    }

    /**
     * SECTION 4: KEKUATAN DAN KELEMAHAN PER ASPEK
     * For each aspect with structured format
     */
    public function generateKekuatanKelemahan(): array
    {
        $result = [];

        foreach ($this->aspects as $aspect) {
            $key = $this->getAspectKey($aspect['name']);
            $result[$key] = [
                'kekuatan' => $this->generateDetailedKekuatan($aspect),
                'kelemahan' => $this->generateDetailedKelemahan($aspect),
            ];
        }

        // Ensure all required keys exist with default content
        $requiredKeys = [
            'kebijakan_internal' => 'Kebijakan Internal Tata Kelola SPBE',
            'perencanaan_strategis' => 'Perencanaan Strategis SPBE',
            'tik' => 'Teknologi Informasi dan Komunikasi',
            'penyelenggara' => 'Penyelenggara SPBE',
            'manajemen' => 'Penerapan Manajemen SPBE',
            'layanan_administrasi' => 'Layanan Administrasi Pemerintahan Berbasis Elektronik',
            'layanan_publik' => 'Layanan Publik Berbasis Elektronik',
        ];

        foreach ($requiredKeys as $key => $name) {
            if (!isset($result[$key])) {
                $defaultAspect = [
                    'name' => $name,
                    'score' => $this->indexScore,
                    'level' => $this->determineLevel($this->indexScore),
                ];
                $result[$key] = [
                    'kekuatan' => $this->generateDetailedKekuatan($defaultAspect),
                    'kelemahan' => $this->generateDetailedKelemahan($defaultAspect),
                ];
            }
        }

        return $result;
    }

    protected function getAspectKey(string $name): string
    {
        $name = strtolower($name);
        
        if (str_contains($name, 'kebijakan')) return 'kebijakan_internal';
        if (str_contains($name, 'perencanaan') || str_contains($name, 'strategis')) return 'perencanaan_strategis';
        if (str_contains($name, 'teknologi') || str_contains($name, 'tik') || str_contains($name, 'informasi')) return 'tik';
        if (str_contains($name, 'penyelenggara')) return 'penyelenggara';
        if (str_contains($name, 'manajemen')) return 'manajemen';
        if (str_contains($name, 'administrasi')) return 'layanan_administrasi';
        if (str_contains($name, 'publik') || str_contains($name, 'layanan')) return 'layanan_publik';
        
        return 'layanan_publik';
    }

    protected function generateDetailedKekuatan(array $aspect): string
    {
        $name = $aspect['name'];
        $score = $aspect['score'];
        $level = $aspect['level'];

        if ($score >= 4.0) {
            $paragraph1 = "{$this->institution} telah menunjukkan tingkat kematangan yang optimal " .
                "dalam penerapan {$name} dengan pencapaian level \"{$level}\". Penerapan pada aspek " .
                "ini telah terintegrasi secara menyeluruh antarunit organisasi dengan dukungan " .
                "kebijakan yang komprehensif dan mekanisme koordinasi yang efektif.";
            
            $paragraph2 = "Konsistensi penerapan telah terjaga dengan baik melalui dokumentasi yang " .
                "lengkap, sosialisasi yang berkelanjutan, serta monitoring dan evaluasi berkala. " .
                "Instansi juga telah melakukan inovasi dan perbaikan berkelanjutan untuk memastikan " .
                "relevansi dan efektivitas penerapan sesuai dengan perkembangan kebutuhan organisasi.";
            
            return $paragraph1 . "\n\n" . $paragraph2;
            
        } elseif ($score >= 3.0) {
            $paragraph = "{$this->institution} telah membangun fondasi yang baik dalam penerapan " .
                "{$name} dengan pencapaian level \"{$level}\". Aspek ini telah terstandardisasi pada " .
                "tingkat organisasi dengan dukungan dokumentasi dan prosedur yang memadai. Penerapan " .
                "telah berjalan secara konsisten pada sebagian besar unit kerja dan memberikan " .
                "kontribusi positif terhadap pelaksanaan SPBE di lingkungan instansi. Meskipun demikian, " .
                "masih terdapat peluang untuk optimalisasi terutama dalam hal integrasi antarunit " .
                "organisasi dan peningkatan efektivitas pelaksanaan.";
            
            return $paragraph;
            
        } else {
            $paragraph = "{$this->institution} telah menunjukkan komitmen awal dalam penerapan " .
                "{$name} dengan pencapaian level \"{$level}\". Beberapa inisiatif dan upaya telah " .
                "dilakukan untuk membangun fondasi pada aspek ini, meskipun masih bersifat parsial " .
                "dan belum sepenuhnya terstandardisasi. Komitmen pimpinan dan dukungan dari seluruh " .
                "unit kerja perlu terus ditingkatkan untuk mencapai tingkat kematangan yang " .
                "diharapkan pada aspek ini.";
            
            return $paragraph;
        }
    }

    protected function generateDetailedKelemahan(array $aspect): string
    {
        $name = $aspect['name'];
        $score = $aspect['score'];

        if ($score >= 4.0) {
            return "Meskipun capaian pada aspek {$name} sudah sangat baik, tantangan ke depan adalah " .
                "mempertahankan tingkat kematangan yang telah dicapai serta melakukan inovasi " .
                "berkelanjutan untuk mengantisipasi perkembangan teknologi dan dinamika kebutuhan " .
                "organisasi. Perlu dilakukan evaluasi berkala untuk memastikan relevansi dan " .
                "efektivitas penerapan serta mengidentifikasi peluang perbaikan yang dapat " .
                "dilakukan untuk mencapai keunggulan yang lebih optimal.";
            
        } elseif ($score >= 3.0) {
            return "Pada aspek {$name}, masih terdapat ruang untuk peningkatan terutama dalam hal " .
                "integrasi antarunit organisasi dan konsistensi penerapan di seluruh unit kerja. " .
                "Perlu dilakukan penguatan koordinasi, peningkatan kapasitas sumber daya, serta " .
                "penyempurnaan dokumentasi dan prosedur untuk mencapai tingkat kematangan yang " .
                "lebih optimal. Monitoring dan evaluasi perlu ditingkatkan untuk mengidentifikasi " .
                "hambatan dan menyusun langkah-langkah perbaikan yang diperlukan.";
            
        } else {
            return "Aspek {$name} memerlukan perhatian serius karena masih terdapat kesenjangan yang " .
                "signifikan dengan tingkat kematangan yang diharapkan. Perlu dilakukan penyusunan " .
                "kebijakan dan prosedur yang lebih komprehensif, peningkatan kapasitas dan kompetensi " .
                "sumber daya manusia, serta alokasi anggaran yang memadai untuk mendukung pengembangan " .
                "aspek ini. Koordinasi antarunit organisasi perlu diperkuat dan mekanisme monitoring " .
                "evaluasi perlu dibangun untuk memastikan perbaikan berkelanjutan.";
        }
    }

    /**
     * SECTION 5: REKOMENDASI
     * 1-2 paragraphs with strategic recommendations
     */
    public function generateRekomendasi(): string
    {
        $weakAspects = [];
        $mediumAspects = [];

        foreach ($this->aspects as $aspect) {
            if ($aspect['score'] < 3.0) {
                $weakAspects[] = $aspect;
            } elseif ($aspect['score'] < 4.0) {
                $mediumAspects[] = $aspect;
            }
        }

        // Sort by score ascending
        usort($weakAspects, fn($a, $b) => $a['score'] <=> $b['score']);
        usort($mediumAspects, fn($a, $b) => $a['score'] <=> $b['score']);

        $priorityAspects = array_merge($weakAspects, $mediumAspects);
        $focusAreas = $this->identifyFocusAreas($priorityAspects);

        $paragraph1 = "Berdasarkan hasil evaluasi SPBE Tahun {$this->year}, {$this->institution} perlu " .
            "menyusun strategi peningkatan yang komprehensif dan berkelanjutan. {$focusAreas} " .
            "Penguatan tata kelola SPBE perlu dilakukan melalui peningkatan koordinasi antarunit " .
            "organisasi, penyempurnaan kebijakan dan prosedur, serta pengembangan kapasitas sumber " .
            "daya manusia dalam pengelolaan teknologi informasi dan komunikasi.";

        $paragraph2 = "Untuk mewujudkan peningkatan tersebut, diperlukan komitmen yang kuat dari " .
            "seluruh level pimpinan dan dukungan dari seluruh pemangku kepentingan. Alokasi " .
            "anggaran yang memadai perlu dialokasikan untuk mendukung pengembangan infrastruktur, " .
            "sistem, dan layanan SPBE. Selain itu, perlu dibangun mekanisme monitoring dan evaluasi " .
            "yang efektif untuk mengukur kemajuan penerapan dan mengidentifikasi area-area yang " .
            "memerlukan perbaikan. Dengan upaya yang terencana dan berkelanjutan, {$this->institution} " .
            "diharapkan dapat mencapai tingkat kematangan SPBE yang lebih optimal pada periode " .
            "evaluasi berikutnya.";

        return $paragraph1 . "\n\n" . $paragraph2;
    }

    protected function identifyFocusAreas(array $priorityAspects): string
    {
        if (empty($priorityAspects)) {
            return "Meskipun capaian secara keseluruhan sudah baik, upaya peningkatan berkelanjutan " .
                "tetap perlu dilakukan untuk mempertahankan dan meningkatkan tingkat kematangan " .
                "yang telah dicapai.";
        }

        $aspectNames = array_map(fn($a) => $a['name'], array_slice($priorityAspects, 0, 3));
        
        if (count($aspectNames) >= 2) {
            $lastAspect = array_pop($aspectNames);
            return "Fokus peningkatan perlu diarahkan pada aspek " . implode(', ', $aspectNames) . 
                ", serta {$lastAspect} yang masih memerlukan penguatan untuk mencapai tingkat " .
                "kematangan yang optimal.";
        } else {
            return "Fokus peningkatan perlu diarahkan pada aspek " . $aspectNames[0] . 
                " yang masih memerlukan penguatan untuk mencapai tingkat kematangan yang optimal.";
        }
    }

    /**
     * METODOLOGI EVALUASI (Static content)
     */
    public function generateMetodologi(): string
    {
        return "Evaluasi SPBE dilaksanakan dengan metode penilaian mandiri (self-assessment) " .
            "yang diverifikasi oleh tim evaluator berdasarkan ketentuan yang ditetapkan dalam " .
            "Peraturan Menteri Pendayagunaan Aparatur Negara dan Reformasi Birokrasi tentang " .
            "Pedoman Evaluasi SPBE.\n\n" .
            "Proses evaluasi dilaksanakan melalui tahapan sebagai berikut:\n" .
            "1. Pengumpulan data dan dokumen pendukung dari seluruh unit kerja terkait\n" .
            "2. Verifikasi kelengkapan dan kesesuaian dokumen dengan kriteria penilaian yang ditetapkan\n" .
            "3. Penilaian tingkat kematangan setiap indikator berdasarkan bukti yang tersedia\n" .
            "4. Penghitungan nilai Indeks SPBE berdasarkan bobot masing-masing domain dan aspek\n" .
            "5. Analisis kekuatan, kelemahan, dan penyusunan rekomendasi perbaikan\n\n" .
            "Penilaian tingkat kematangan menggunakan skala 1 sampai 5 dengan kategori sebagai berikut:\n" .
            "- Level 1 (Rintisan): Penerapan belum atau baru dimulai secara ad-hoc\n" .
            "- Level 2 (Terkelola): Penerapan sudah dilaksanakan namun belum terstandardisasi\n" .
            "- Level 3 (Terstandardisasi): Penerapan sudah mengacu pada standar organisasi\n" .
            "- Level 4 (Terintegrasi): Penerapan sudah terintegrasi antarunit organisasi\n" .
            "- Level 5 (Optimum): Penerapan sudah optimal dengan evaluasi dan perbaikan berkelanjutan";
    }

    /**
     * Generate all content sections
     */
    public function generateAllContent(): array
    {
        return [
            'kataPengantar' => $this->generateKataPengantar(),
            'ringkasan' => $this->generateRingkasanEksekutif(),
            'metodologi' => $this->generateMetodologi(),
            'tingkatKematangan' => $this->generateTingkatKematangan(),
            'evaluasi' => $this->generateKekuatanKelemahan(),
            'rekomendasi' => $this->generateRekomendasi(),
        ];
    }

    // =========================================================================
    // ACADEMIC PROJECT EVALUATION METHODS
    // =========================================================================

    /**
     * SECTION: KEKUATAN (STRENGTHS) - Academic evaluation of project strengths
     * 3-5 well-developed paragraphs
     */
    public function generateProjectStrengths(): string
    {
        $paragraph1 = "Dari aspek metodologi dan desain evaluasi, sistem evaluasi SPBE yang dikembangkan " .
            "telah mengadopsi kerangka penilaian yang komprehensif dan sesuai dengan standar nasional " .
            "yang ditetapkan oleh Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi. " .
            "Penggunaan indikator-indikator yang terstruktur dan terukur memungkinkan penilaian yang " .
            "objektif terhadap berbagai aspek penerapan SPBE, mulai dari kebijakan, tata kelola, " .
            "manajemen, hingga layanan. Desain evaluasi yang sistematis ini memberikan dasar yang " .
            "kuat untuk menghasilkan temuan yang valid dan dapat dipertanggungjawabkan secara akademis.";

        $paragraph2 = "Dalam hal implementasi sistem dan konsistensi proses, sistem evaluasi berbasis web " .
            "yang dikembangkan menunjukkan keunggulan dalam hal kemudahan penggunaan dan aksesibilitas. " .
            "Proses pengumpulan data, verifikasi, dan perhitungan nilai indeks telah terotomatisasi " .
            "dengan baik sehingga meminimalkan potensi kesalahan manusia dalam proses penilaian. " .
            "Konsistensi dalam penerapan kriteria penilaian di seluruh domain dan aspek menjamin " .
            "keseragaman hasil evaluasi yang dapat diperbandingkan antarperiode waktu maupun " .
            "antarinstansi yang dievaluasi.";

        $paragraph3 = "Keunikan dan kebaruan pendekatan yang diterapkan tercermin dari integrasi " .
            "visualisasi data dalam bentuk grafik radar dan presentasi nilai per domain yang " .
            "memudahkan pemangku kepentingan dalam memahami kondisi penerapan SPBE secara holistik. " .
            "Pendekatan visual ini merupakan nilai tambah yang signifikan dibandingkan dengan " .
            "metode evaluasi konvensional yang hanya menyajikan data dalam bentuk tabel numerik. " .
            "Kemampuan sistem untuk menghasilkan laporan PDF yang terstruktur dan sesuai dengan " .
            "format laporan resmi pemerintah juga menunjukkan perhatian terhadap aspek praktis " .
            "penggunaan hasil evaluasi.";

        $paragraph4 = "Dari perspektif kualitas data dan temuan, sistem telah dirancang untuk " .
            "mengumpulkan bukti dukung yang memadai sebagai dasar penilaian. Mekanisme verifikasi " .
            "dokumen dan validasi data yang terintegrasi dalam sistem membantu memastikan bahwa " .
            "temuan evaluasi didasarkan pada bukti yang dapat dipertanggungjawabkan. Analisis " .
            "yang dihasilkan mencakup identifikasi kekuatan dan kelemahan per aspek, yang " .
            "memberikan informasi yang actionable bagi instansi yang dievaluasi untuk melakukan " .
            "perbaikan yang terarah.";

        $paragraph5 = "Dukungan sumber daya dan integrasi komponen dalam sistem evaluasi menunjukkan " .
            "tingkat kematangan yang memadai. Integrasi antara modul pengumpulan data, modul " .
            "perhitungan, modul visualisasi, dan modul pelaporan berjalan dengan baik dan " .
            "memberikan pengalaman pengguna yang seamless. Arsitektur sistem yang modular " .
            "juga memungkinkan pengembangan dan penyesuaian di masa depan sesuai dengan " .
            "perubahan kebutuhan atau regulasi terkait evaluasi SPBE.";

        return $paragraph1 . "\n\n" . $paragraph2 . "\n\n" . $paragraph3 . "\n\n" . 
               $paragraph4 . "\n\n" . $paragraph5;
    }

    /**
     * SECTION: KELEMAHAN / KETERBATASAN (WEAKNESSES / LIMITATIONS)
     * 3-5 well-developed paragraphs
     */
    public function generateProjectWeaknesses(): string
    {
        $paragraph1 = "Dari aspek keterbatasan metodologis, terdapat beberapa hal yang perlu " .
            "diperhatikan dalam menginterpretasikan hasil evaluasi. Metode penilaian mandiri " .
            "(self-assessment) yang diterapkan berpotensi mengandung bias subjektivitas dari " .
            "pihak yang melakukan penilaian. Meskipun telah tersedia mekanisme verifikasi, " .
            "validasi sepenuhnya terhadap seluruh bukti dukung memerlukan sumber daya yang " .
            "signifikan dan tidak selalu dapat dilakukan secara komprehensif. Keterbatasan ini " .
            "dapat mempengaruhi akurasi penilaian terutama pada indikator-indikator yang " .
            "bersifat kualitatif dan memerlukan judgment profesional.";

        $paragraph2 = "Berkaitan dengan keterbatasan cakupan dan generalisasi, hasil evaluasi yang " .
            "diperoleh bersifat spesifik untuk konteks instansi dan periode waktu tertentu. " .
            "Temuan dari satu instansi tidak dapat secara langsung digeneralisasikan ke instansi " .
            "lain mengingat perbedaan karakteristik, sumber daya, dan tingkat kematangan organisasi. " .
            "Selain itu, evaluasi yang dilakukan pada satu titik waktu memberikan gambaran snapshot " .
            "yang mungkin tidak sepenuhnya mencerminkan dinamika perubahan yang terjadi dalam " .
            "penerapan SPBE sepanjang periode evaluasi.";

        $paragraph3 = "Dari sisi kendala teknis dan praktis, sistem evaluasi memiliki ketergantungan " .
            "pada ketersediaan infrastruktur teknologi informasi yang memadai, baik di sisi " .
            "penyelenggara evaluasi maupun di sisi instansi yang dievaluasi. Keterbatasan " .
            "konektivitas internet atau kompatibilitas perangkat dapat menghambat proses " .
            "pengumpulan data dan penggunaan sistem secara optimal. Selain itu, kebutuhan " .
            "akan literasi digital yang memadai dari pengguna sistem menjadi prasyarat yang " .
            "tidak selalu dapat dipenuhi oleh seluruh pemangku kepentingan yang terlibat.";

        $paragraph4 = "Ketergantungan pada kualitas data input merupakan faktor kritis yang " .
            "mempengaruhi validitas hasil evaluasi. Kelengkapan, keakuratan, dan kekinian " .
            "data yang diinputkan ke dalam sistem sangat bergantung pada kesiapan dan " .
            "kapabilitas instansi yang dievaluasi dalam menyediakan dokumen dan bukti dukung. " .
            "Data yang tidak lengkap atau tidak akurat akan menghasilkan penilaian yang " .
            "tidak mencerminkan kondisi sebenarnya, sehingga rekomendasi yang dihasilkan " .
            "pun mungkin tidak tepat sasaran.";

        $paragraph5 = "Faktor eksternal yang tidak sepenuhnya terkendali juga perlu menjadi " .
            "pertimbangan dalam menginterpretasikan hasil evaluasi. Perubahan regulasi, " .
            "kebijakan anggaran, mutasi pejabat, atau kondisi force majeure dapat mempengaruhi " .
            "kemampuan instansi dalam menerapkan SPBE dan memenuhi indikator yang ditetapkan. " .
            "Faktor-faktor kontekstual ini tidak selalu dapat ditangkap sepenuhnya dalam " .
            "framework evaluasi yang bersifat standardized, sehingga perlu kehati-hatian " .
            "dalam membuat kesimpulan atau perbandingan antarinstansi.";

        return $paragraph1 . "\n\n" . $paragraph2 . "\n\n" . $paragraph3 . "\n\n" . 
               $paragraph4 . "\n\n" . $paragraph5;
    }

    /**
     * SECTION: REKOMENDASI AKADEMIS - Detailed structured recommendations
     */
    public function generateProjectRecommendations(): array
    {
        return [
            'tindakan_perbaikan' => $this->generateRekomendasiTindakanPerbaikan(),
            'pengembangan_kekuatan' => $this->generateRekomendasiPengembanganKekuatan(),
            'implikasi_kebijakan' => $this->generateRekomendasiImplkasiKebijakan(),
            'pengembangan_lanjutan' => $this->generateRekomendasiPengembanganLanjutan(),
        ];
    }

    /**
     * A. Rekomendasi Tindakan Perbaikan
     */
    protected function generateRekomendasiTindakanPerbaikan(): string
    {
        $paragraph1 = "Untuk mengatasi keterbatasan metodologis yang telah diidentifikasi, diperlukan " .
            "pengembangan mekanisme triangulasi data yang lebih robust dalam proses evaluasi. " .
            "Tim evaluator perlu dilengkapi dengan panduan verifikasi yang lebih detail dan " .
            "checklist validasi yang terstandarisasi untuk memastikan konsistensi dalam " .
            "proses penilaian antarinstansi. Penguatan kapasitas evaluator melalui pelatihan " .
            "berkala tentang metodologi penilaian dan interpretasi bukti dukung juga perlu " .
            "diagendakan secara sistematis oleh penyelenggara evaluasi.";

        $paragraph2 = "Dalam rangka meningkatkan kualitas data input, perlu dikembangkan mekanisme " .
            "asistensi teknis bagi instansi yang dievaluasi dalam proses pengumpulan dan " .
            "penyiapan dokumen bukti dukung. Helpdesk atau pusat bantuan yang responsif " .
            "perlu disediakan untuk memfasilitasi instansi yang mengalami kendala teknis " .
            "atau memerlukan klarifikasi terkait persyaratan dokumentasi. Selain itu, " .
            "penyediaan template dokumen standar dan contoh-contoh bukti dukung yang baik " .
            "dapat membantu meningkatkan kualitas dan kelengkapan data yang dikumpulkan.";

        return $paragraph1 . "\n\n" . $paragraph2;
    }

    /**
     * B. Pengembangan dan Pemanfaatan Kekuatan
     */
    protected function generateRekomendasiPengembanganKekuatan(): string
    {
        $paragraph1 = "Keunggulan visualisasi data yang telah dikembangkan dalam sistem evaluasi perlu " .
            "dioptimalkan lebih lanjut untuk mendukung pengambilan keputusan berbasis data. " .
            "Pengembangan dashboard eksekutif yang dapat menampilkan tren perkembangan SPBE " .
            "antarperiode evaluasi dan perbandingan capaian antarinstansi akan meningkatkan " .
            "nilai tambah sistem bagi para pengambil kebijakan di tingkat pusat maupun daerah. " .
            "Fitur analitik lanjutan seperti identifikasi pola kelemahan yang berulang dan " .
            "prediksi area yang memerlukan perhatian khusus dapat dikembangkan untuk " .
            "memperkaya insight yang dihasilkan dari data evaluasi.";

        $paragraph2 = "Arsitektur modular sistem yang telah terbangun dengan baik dapat dimanfaatkan " .
            "untuk melakukan scaling dan replikasi ke level evaluasi yang lebih luas. " .
            "Pengembangan modul integrasi dengan sistem informasi pemerintahan lainnya, " .
            "seperti sistem perencanaan dan penganggaran, akan meningkatkan efisiensi " .
            "pengumpulan data dan memungkinkan monitoring berkelanjutan terhadap progress " .
            "perbaikan yang dilakukan oleh instansi pasca evaluasi. Strategi knowledge " .
            "sharing dan best practice repository juga dapat dikembangkan untuk memfasilitasi " .
            "pembelajaran antarinstansi berdasarkan temuan-temuan positif dari proses evaluasi.";

        return $paragraph1 . "\n\n" . $paragraph2;
    }

    /**
     * C. Implikasi Kebijakan
     */
    protected function generateRekomendasiImplkasiKebijakan(): string
    {
        $paragraph1 = "Bagi Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi sebagai " .
            "pembina SPBE nasional, hasil evaluasi ini mengimplikasikan perlunya penguatan " .
            "mekanisme pembinaan dan pendampingan bagi instansi yang masih berada pada tingkat " .
            "kematangan rendah. Pengalokasian sumber daya untuk program capacity building yang " .
            "terstruktur dan berkelanjutan perlu menjadi prioritas dalam perencanaan program " .
            "kerja tahunan. Selain itu, peninjauan berkala terhadap indikator evaluasi untuk " .
            "memastikan relevansinya dengan perkembangan teknologi dan kebutuhan tata kelola " .
            "pemerintahan perlu dilakukan secara sistematis.";

        $paragraph2 = "Bagi pimpinan instansi yang dievaluasi, hasil evaluasi SPBE perlu dijadikan " .
            "sebagai bahan pertimbangan strategis dalam penyusunan rencana kerja dan alokasi " .
            "anggaran terkait pengembangan infrastruktur dan layanan berbasis elektronik. " .
            "Pembentukan tim khusus pengelola SPBE dengan kewenangan dan sumber daya yang " .
            "memadai perlu dipertimbangkan untuk memastikan keberlanjutan upaya peningkatan. " .
            "Integrasi target capaian SPBE ke dalam dokumen perencanaan strategis instansi " .
            "dan sistem manajemen kinerja pejabat terkait akan memperkuat akuntabilitas " .
            "dan komitmen dalam pencapaian target yang telah ditetapkan.";

        return $paragraph1 . "\n\n" . $paragraph2;
    }

    /**
     * D. Rekomendasi untuk Penelitian atau Pengembangan Lanjutan
     */
    protected function generateRekomendasiPengembanganLanjutan(): string
    {
        $paragraph1 = "Untuk pengembangan penelitian lanjutan, diperlukan kajian mendalam mengenai " .
            "faktor-faktor determinan yang mempengaruhi keberhasilan penerapan SPBE di berbagai " .
            "konteks instansi. Studi komparatif yang melibatkan instansi dengan karakteristik " .
            "dan tingkat kematangan yang beragam dapat memberikan insight berharga mengenai " .
            "best practices dan lesson learned yang dapat direplikasi. Pengembangan model " .
            "prediktif untuk mengidentifikasi instansi yang berpotensi mengalami kendala " .
            "dalam pencapaian target SPBE juga merupakan area penelitian yang menjanjikan " .
            "untuk mendukung intervensi kebijakan yang lebih proaktif.";

        $paragraph2 = "Dari sisi pengembangan sistem, eksplorasi terhadap teknologi-teknologi baru " .
            "seperti analitik big data, machine learning untuk deteksi anomali, dan natural " .
            "language processing untuk analisis dokumen dapat dipertimbangkan untuk meningkatkan " .
            "efisiensi dan akurasi proses evaluasi. Pengembangan platform kolaboratif yang " .
            "memungkinkan pertukaran pengetahuan dan pengalaman antarinstansi dalam konteks " .
            "penerapan SPBE juga dapat menjadi nilai tambah yang signifikan. Studi longitudinal " .
            "untuk mengukur dampak penerapan SPBE terhadap kualitas layanan publik dan " .
            "efisiensi pemerintahan secara lebih komprehensif merupakan agenda penelitian " .
            "jangka panjang yang perlu diinisiasi.";

        return $paragraph1 . "\n\n" . $paragraph2;
    }

    /**
     * Generate complete academic evaluation content
     */
    public function generateAcademicEvaluation(): array
    {
        return [
            'kekuatan' => $this->generateProjectStrengths(),
            'kelemahan' => $this->generateProjectWeaknesses(),
            'rekomendasi' => $this->generateProjectRecommendations(),
        ];
    }

    /**
     * Generate all content including academic evaluation
     */
    public function generateFullReport(): array
    {
        $baseContent = $this->generateAllContent();
        $academicContent = $this->generateAcademicEvaluation();
        
        return array_merge($baseContent, [
            'evaluasiProyek' => $academicContent,
        ]);
    }
}

