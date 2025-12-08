<?php

namespace App\Helpers;

/**
 * Indicator Mapper - Maps indicator ID to indicator name
 * Data dikopy dari soal assessment frontend
 */
class IndicatorMapper
{
    /**
     * Get all indicators mapping
     * 
     * @return array
     */
    public static function getIndicators(): array
    {
        return [
            1 => 'Strategi dan Perencanaan TI',
            2 => 'Governance dan Manajemen Risiko',
            3 => 'Manajemen Aset TI',
            4 => 'Keamanan Informasi',
            5 => 'Manajemen Infrastruktur',
            6 => 'Manajemen Layanan Aplikasi',
            7 => 'Manajemen Incident',
            8 => 'Manajemen Problem',
            9 => 'Manajemen Perubahan',
            10 => 'Manajemen Kapasitas',
            11 => 'Manajemen Ketersediaan',
            12 => 'Manajemen Kontinuitas Bisnis',
            13 => 'Manajemen Kinerja',
            14 => 'Manajemen Pengetahuan',
            15 => 'Manajemen Hubungan Pelanggan',
            16 => 'Manajemen Reputasi',
            17 => 'Manajemen Budaya Organisasi',
            18 => 'Manajemen Sumber Daya Manusia',
            19 => 'Manajemen Vendor dan Pihak Ketiga',
            20 => 'Manajemen Keuangan',
            21 => 'Manajemen Proses',
            22 => 'Manajemen Kualitas',
            23 => 'Manajemen Inovasi',
            24 => 'Manajemen Komunikasi',
            25 => 'Manajemen Proyek',
            26 => 'Manajemen Portfolio',
            27 => 'Manajemen Program',
            28 => 'Manajemen Pengembangan Produk',
            29 => 'Manajemen Compliance',
            30 => 'Manajemen Audit',
            31 => 'Manajemen Kebijakan',
            32 => 'Manajemen Dokumentasi',
        ];
    }

    /**
     * Get indicator name by ID
     * 
     * @param int $id
     * @return string|null
     */
    public static function getIndicatorName(int $id): ?string
    {
        $indicators = self::getIndicators();
        return $indicators[$id] ?? null;
    }

    /**
     * Get maturity level based on score
     * Score range: 1-5
     * 
     * @param float $averageScore
     * @return string
     */
    public static function getMaturityLevel(float $averageScore): string
    {
        if ($averageScore >= 4.5) {
            return 'Optimized';
        } elseif ($averageScore >= 3.5) {
            return 'Managed';
        } elseif ($averageScore >= 2.5) {
            return 'Defined';
        } elseif ($averageScore >= 1.5) {
            return 'Repeatable';
        } else {
            return 'Initial';
        }
    }
}
