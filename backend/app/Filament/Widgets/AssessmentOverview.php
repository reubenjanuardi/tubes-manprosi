<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\ChartWidget;
use BackedEnum;

class AssessmentOverview extends ChartWidget
{
    protected ?string $heading = 'Perbandingan Skor Asesmen Organisasi';
    // Menentukan seberapa sering grafik di-refresh secara otomatis
    protected ?string $pollingInterval = '10s';

    /**
     * Mengambil data dari database untuk ditampilkan di grafik.
     */
    protected function getData(): array
    {
        // Mengambil 10 data asesmen terbaru
        $data = Assessment::query()
            ->latest()
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Skor Kematangan (0.0 - 5.0)',
                    'data' => $data->pluck('total_score')->toArray(), // Ambil kolom total_score
                    'backgroundColor' => [
                        '#1e3a8a',
                        '#3b82f6',
                        '#60a5fa',
                        '#93c5fd',
                        '#bfdbfe',
                        '#1e3a8a',
                        '#3b82f6',
                        '#60a5fa',
                        '#93c5fd',
                        '#bfdbfe'
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('org_name')->toArray(), // Label menggunakan nama organisasi
        ];
    }

    /**
     * Konfigurasi tambahan untuk Chart.js.
     */
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 5, // Batas maksimal skor kematangan
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    /**
     * Menentukan jenis chart. 
     * Anda bisa menggunakan 'bar', 'line', 'radar', atau 'pie'.
     */
    protected function getType(): string
    {
        return 'bar';
    }
}
