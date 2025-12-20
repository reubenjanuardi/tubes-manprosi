<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Subdomain;
use App\Models\Indicator;
use Illuminate\Database\Seeder;

class AssessmentHierarchySeeder extends Seeder
{
    /**
     * Jalankan database seeder.
     * Data diambil dari konstanta assessmentData di app.js.
     */
    public function run(): void
    {
        // 1. Definisikan Data Indikator (32 Pertanyaan)
        $indicatorsMap = [
            1 => "Tingkat Kematangan Kebijakan Internal Pemerintah Digital Instansi Pusat/Pemerintah Daerah",
            2 => "Tingkat Kematangan Penerapan Manajemen Risiko dalam penerapan pemerintah digital sebagal bagian dari manajemen risiko pembangunan nasional",
            3 => "Tingkat Kematangan Penerapan Manajemen Keamanan Informasi",
            4 => "Tingkat Kematangan Penerapan Manajemen Aset Digital",
            5 => "Tingkat Kematangan Penerapan Manajemen Pengetahuan",
            6 => "Tingkat Kematangan Penerapan Manajemen Perubahan",
            7 => "Tingkat Kematangan Penerapan Manajemen Layanan Digital",
            8 => "Tingkat Kematangan Penerapan Manajemen Kelangsungan Layanan Digital Pemerintah (BCP, DRP, BIA, Disaster Response Team)",
            9 => "Tingkat Kematangan Skalabilitas Pelaksanaan Transformasi Digital Pemerintah melalui Tim Koordinasi lintas unit di Instansi Pusat/Pemerintah Daerah",
            10 => "Tingkat Kematangan Kolaborasi Penerapan Pemerintah Digital",
            11 => "Tingkat Kematangan Arsitektur Pemerintah Digital Instansi Pusat/Pemerintah Daerah",
            12 => "Tingkat Kematangan Peta Rencana Pemerintah Digital Instansi Pusat/Pemerintah Daerah untuk mendukung Perencanaan Pembangunan Nasional",
            13 => "Tingkat Kematangan Keterpaduan Rencana dan Anggaran Pemerintah Digital untuk mendukung efisiensi Pembangunan Nasional",
            14 => "Tingkat Kematangan Inovasi Proses Bisnis Tematik untuk mendukung keterpaduan dan kemudahan layanan digital pemerintah",
            15 => "Tingkat Kematangan Pembangunan Aplikasi",
            16 => "Tingkat Kematangan Pernanfaatan Ekosistem Pusat Data Nasional",
            17 => "Tingkat Kematangan Layanan Jaringan Intra Instansi Pusat/Pemerintah Daerah",
            18 => "Tingkat kematangan skalabilltas penguatan keamanan informasi pada layanan digital",
            19 => "Tingkat Kematangan Pelaksanaan Audit Pemerintah Digital",
            20 => "Tingkat Kematangan Penerapan Kapabilitas Sumber Daya Manusia Digital",
            21 => "Tingkat Kematangan Penerapan Budaya Digital",
            22 => "Tingkat Kematangan Penerapan Manajemen Data",
            23 => "Tingkat Kematangan Pemanfaatan Data dan Informasi Lintas Sektor",
            24 => "Tingkat Kematangan Skalabilitas pemanfaatan system penghubung layanan Instansi Pusat/Pemerintah Daerah",
            25 => "Tingkat Kematangan Pemanfaatan Big Data, Data Analytic, dan Business intelligence",
            26 => "Tingkat Kematangan Keterpaduan Layanan Administrasi Pemerintahan",
            27 => "Tingkat Kematangan Skalabilitas Pemanfaatan Portal Nasional Administrasi Pemerintahan",
            28 => "Tingkat Kematangan Keterpaduan Pelayanan Publik",
            29 => "Tingkat Kematangan Skalabilitas Pemanfaatan Portal Nasional Pelayanan Publik",
            30 => "Tingkat Kematangan Skalabilitas Pemanfaatan identitas Digital Nasional",
            31 => "Tingkat Kematangan Skalabilitas Pemanfaatan Kecerdasan Artifisial pada layanan digital pemerintah",
            32 => "Tingkat Kepuasan Pengguna Layanan Pemerintah, melalui survey kepuasan pengguna",
        ];

        // 2. Definisikan Hierarki Domain & Subdomain
        $domains = [
            [
                'name' => "Kebijakan dan Tata Kelola SPBE",
                'weight' => 20,
                'color' => "#1e3a8a",
                'subdomains' => [
                    ['name' => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", 'weight' => 5, 'indicator_ids' => range(1, 10)],
                    ['name' => "Perencanaan dan Strategi", 'weight' => 5, 'indicator_ids' => range(11, 14)],
                    ['name' => "Teknologi Digital", 'weight' => 5, 'indicator_ids' => range(15, 18)],
                    ['name' => "Pelaksanaan Audit Pemerintah Digital", 'weight' => 5, 'indicator_ids' => [19]],
                ]
            ],
            [
                'name' => "Kapabilitas dan Budaya Digital",
                'weight' => 20,
                'color' => "#3b82f6",
                'subdomains' => [
                    ['name' => "Kapabilitas dan Budaya Digital", 'weight' => 20, 'indicator_ids' => [20, 21]],
                ]
            ],
            [
                'name' => "Pemanfaatan Data Lintas Sektor",
                'weight' => 30,
                'color' => "#60a5fa",
                'subdomains' => [
                    ['name' => "Penerapan Tata Kelola Data", 'weight' => 30, 'indicator_ids' => range(22, 25)],
                ]
            ],
            [
                'name' => "Keterpaduan Layanan Digital",
                'weight' => 30,
                'color' => "#93c5fd",
                'subdomains' => [
                    ['name' => "Keterpaduan Layanan Digital Pemerintah", 'weight' => 10, 'indicator_ids' => range(26, 31)],
                    ['name' => "Kepuasan Pengguna Layanan Pemerintah Digital", 'weight' => 20, 'indicator_ids' => [32]],
                ]
            ],
        ];

        // 3. Proses Loop untuk Insert ke Database
        foreach ($domains as $domainData) {
            $domain = Domain::create([
                'name' => $domainData['name'],
                'weight' => $domainData['weight'],
                'color' => $domainData['color'],
            ]);

            foreach ($domainData['subdomains'] as $subdomainData) {
                $subdomain = Subdomain::create([
                    'domain_id' => $domain->id,
                    'name' => $subdomainData['name'],
                    'weight' => $subdomainData['weight'],
                ]);

                foreach ($subdomainData['indicator_ids'] as $id) {
                    Indicator::create([
                        'subdomain_id' => $subdomain->id,
                        'code' => 'IND_' . str_pad($id, 2, '0', STR_PAD_LEFT), // Contoh: IND_01
                        'name' => $indicatorsMap[$id],
                    ]);
                }
            }
        }
    }
}
