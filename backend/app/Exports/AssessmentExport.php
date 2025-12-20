<?php

namespace App\Exports;

use App\Models\Assessment;
use App\Helpers\IndicatorMapper;

class AssessmentExport
{
    protected $assessment;

    public function __construct(Assessment $assessment)
    {
        // Pastikan kita me-load relasi indikator pada setiap respon
        $this->assessment = $assessment->load('responses.indicator');
    }

    /**
     * Get data for Excel export as array
     */
    public function toArray()
    {
        $data = [];

        // Add header information
        $data[] = ['Organization Name', $this->assessment->org_name];
        $data[] = ['Organization Type', $this->assessment->org_type];
        $data[] = ['Assessor Name', $this->assessment->assessor_name];
        $data[] = ['Assessor Position', $this->assessment->assessor_position];
        $data[] = ['Assessment Date', $this->assessment->assessment_date];
        $data[] = ['Total Score', (float)$this->assessment->total_score];
        $data[] = ['Maturity Level', $this->assessment->maturity_level];
        $data[] = ['Status', $this->assessment->status];

        // Empty row
        $data[] = [''];

        // Detail responses header
        $data[] = ['Indicator ID', 'Indicator Name', 'Score', 'Evidence'];

        // Add responses
        foreach ($this->assessment->responses as $response) {
            $data[] = [
                $response->indicator_id,
                $response->indicator ? $response->indicator->name : 'Unknown Indicator',
                $response->score,
                $response->evidence_text ?? $response->document_path ?? '-',
            ];
        }

        return $data;
    }
}
