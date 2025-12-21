<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Indicator;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing indicators
        Indicator::truncate();

        $maturityLevels = [
            ["level" => 1, "name" => "Initial", "description" => "Proses ad hoc dan tidak terstruktur", "color" => "#ef4444"],
            ["level" => 2, "name" => "Managed", "description" => "Proses terstruktur tapi belum konsisten", "color" => "#f97316"],
            ["level" => 3, "name" => "Defined", "description" => "Proses standar yang konsisten", "color" => "#eab308"],
            ["level" => 4, "name" => "Quantitatively Managed", "description" => "Proses terukur dan terkontrol", "color" => "#22c55e"],
            ["level" => 5, "name" => "Optimizing", "description" => "Fokus pada peningkatan proses berkelanjutan", "color" => "#10b981"]
        ];

        $indicators = [
            // Domain 1: Kebijakan dan Tata Kelola SPBE (Subdomain 11 - indicators 1-10)
            ["id" => 1, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Kebijakan Internal Pemerintah Digital Instansi Pusat/Pemerintah Daerah"],
            ["id" => 2, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Penerapan Manajemen Risiko dalam penerapan pemerintah digital sebagal bagian dari manajemen risiko pembangunan nasional"],
            ["id" => 3, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Penerapan Manajemen Keamanan Informasi"],
            ["id" => 4, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Penerapan Manajemen Aset Digital"],
            ["id" => 5, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Penerapan Manajemen Pengetahuan"],
            ["id" => 6, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Penerapan Manajemen Perubahan"],
            ["id" => 7, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Penerapan Manajemen Layanan Digital"],
            ["id" => 8, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Penerapan Manajemen Kelangsungan Layanan Digital Pemerintah (BCP, DRP, BIA, Disaster Response Team)"],
            ["id" => 9, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Skalabilitas Pelaksanaan Transformasi Digital Pemerintah melalui Tim Koordinasi lintas unit di Instansi Pusat/Pemerintah Daerah"],
            ["id" => 10, "group" => "Kebijakan Tata Kelola dan Manajemen Pemerintah Digital", "text" => "Tingkat Kematangan Kolaborasi Penerapan Pemerintah Digital"],

            // Domain 1: Subdomain 12 - Perencanaan dan Strategi (indicators 11-14)
            ["id" => 11, "group" => "Perencanaan dan Strategi", "text" => "Tingkat Kematangan Arsitektur Pemerintah Digital Instansi Pusat/Pemerintah Daerah"],
            ["id" => 12, "group" => "Perencanaan dan Strategi", "text" => "Tingkat Kematangan Peta Rencana Pemerintah Digital Instansi Pusat/Pemerintah Daerah untuk mendukung Perencanaan Pembangunan Nasional"],
            ["id" => 13, "group" => "Perencanaan dan Strategi", "text" => "Tingkat Kematangan Keterpaduan Rencana dan Anggaran Pemerintah Digital untuk mendukung efisiensi Pembangunan Nasional"],
            ["id" => 14, "group" => "Perencanaan dan Strategi", "text" => "Tingkat Kematangan Inovasi Proses Bisnis Tematik untuk mendukung keterpaduan dan kemudahan layanan digital pemerintah"],

            // Domain 1: Subdomain 13 - Teknologi Digital (indicators 15-18)
            ["id" => 15, "group" => "Teknologi Digital", "text" => "Tingkat Kematangan Pembangunan Aplikasi"],
            ["id" => 16, "group" => "Teknologi Digital", "text" => "Tingkat Kematangan Pernanfaatan Ekosistem Pusat Data Nasional"],
            ["id" => 17, "group" => "Teknologi Digital", "text" => "Tingkat Kematangan Layanan Jaringan Intra Instansi Pusat/Pemerintah Daerah"],
            ["id" => 18, "group" => "Teknologi Digital", "text" => "Tingkat kematangan skalabilltas penguatan keamanan informasi pada layanan digital"],

            // Domain 1: Subdomain 14 - Pelaksanaan Audit (indicator 19)
            ["id" => 19, "group" => "Pelaksanaan Audit Pemerintah Digital", "text" => "Tingkat Kematangan Pelaksanaan Audit Pemerintah Digital"],

            // Domain 2: Kapabilitas dan Budaya Digital (indicators 20-21)
            ["id" => 20, "group" => "Kapabilitas dan Budaya Digital", "text" => "Tingkat Kematangan Penerapan Kapabilitas Sumber Daya Manusia Digital"],
            ["id" => 21, "group" => "Kapabilitas dan Budaya Digital", "text" => "Tingkat Kematangan Penerapan Budaya Digital"],

            // Domain 3: Pemanfaatan Data Lintas Sektor (indicators 22-25)
            ["id" => 22, "group" => "Penerapan Tata Kelola Data", "text" => "Tingkat Kematangan Penerapan Manajemen Data"],
            ["id" => 23, "group" => "Penerapan Tata Kelola Data", "text" => "Tingkat Kematangan Pemanfaatan Data dan Informasi Lintas Sektor"],
            ["id" => 24, "group" => "Penerapan Tata Kelola Data", "text" => "Tingkat Kematangan Skalabilitas pemanfaatan system penghubung layanan Instansi Pusat/Pemerintah Daerah"],
            ["id" => 25, "group" => "Penerapan Tata Kelola Data", "text" => "Tingkat Kematangan Pemanfaatan Big Data, Data Analytic, dan Business intelligence"],

            // Domain 4: Keterpaduan Layanan Digital (indicators 26-32)
            ["id" => 26, "group" => "Keterpaduan Layanan Digital Pemerintah", "text" => "Tingkat Kematangan Keterpaduan Layanan Administrasi Pemerintahan"],
            ["id" => 27, "group" => "Keterpaduan Layanan Digital Pemerintah", "text" => "Tingkat Kematangan Skalabilitas Pemanfaatan Portal Nasional Administrasi Pemerintahan"],
            ["id" => 28, "group" => "Keterpaduan Layanan Digital Pemerintah", "text" => "Tingkat Kematangan Keterpaduan Pelayanan Publik"],
            ["id" => 29, "group" => "Keterpaduan Layanan Digital Pemerintah", "text" => "Tingkat Kematangan Skalabilitas Pemanfaatan Portal Nasional Pelayanan Publik"],
            ["id" => 30, "group" => "Keterpaduan Layanan Digital Pemerintah", "text" => "Tingkat Kematangan Skalabilitas Pemanfaatan identitas Digital Nasional"],
            ["id" => 31, "group" => "Keterpaduan Layanan Digital Pemerintah", "text" => "Tingkat Kematangan Skalabilitas Pemanfaatan Kecerdasan Artifisial pada layanan digital pemerintah"],
            ["id" => 32, "group" => "Kepuasan Pengguna Layanan Pemerintah Digital", "text" => "Tingkat Kepuasan Pengguna Layanan Pemerintah, melalui survey kepuasan pengguna"],
        ];

        $scaleValues = [1, 2, 3, 4, 5];
        $scaleLabels = array_column($maturityLevels, 'name');

        foreach ($indicators as $index => $indicator) {
            Indicator::create([
                'group_name' => $indicator['group'],
                'indicator_text' => $indicator['text'],
                'type' => 'scale',
                'scale_values' => $scaleValues,
                'scale_labels' => $scaleLabels,
                'display_order' => $indicator['id'],
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Successfully seeded 32 indicators!');
    }
}
