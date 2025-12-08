<?php

namespace App\Exports;

use App\Models\Assessment;
use App\Helpers\IndicatorMapper;

class AssessmentExport
{
    protected $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    /**
     * Get data for export as array
     */
    public function getExcelData()
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
                IndicatorMapper::getIndicatorName($response->indicator_id),
                $response->score,
                $response->evidence_text ?? $response->document_path ?? '-',
            ];
        }

        return $data;
    }
}
