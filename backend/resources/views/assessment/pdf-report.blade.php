<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Assessment Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #0066cc;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }

        .info-section {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f5f5f5;
            border-left: 4px solid #0066cc;
        }

        .info-section h2 {
            margin-top: 0;
            font-size: 14px;
            color: #0066cc;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .info-label {
            font-weight: bold;
            width: 200px;
            color: #333;
        }

        .info-value {
            flex: 1;
            color: #666;
        }

        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-box {
            flex: 1;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            text-align: center;
            border-radius: 4px;
        }

        .summary-box .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .summary-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #0066cc;
        }

        .maturity-box .value {
            background-color: #0066cc;
            color: white;
            padding: 8px;
            border-radius: 4px;
        }

        .responses-section {
            margin-top: 30px;
        }

        .responses-section h2 {
            font-size: 16px;
            color: #0066cc;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 10px;
            text-transform: uppercase;
        }

        .response-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .response-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .response-indicator {
            font-weight: bold;
            color: #0066cc;
            font-size: 13px;
        }

        .response-score {
            background-color: #0066cc;
            color: white;
            padding: 4px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
        }

        .response-content {
            margin-top: 10px;
            font-size: 12px;
            color: #333;
        }

        .response-label {
            font-weight: bold;
            color: #666;
            margin-top: 8px;
            margin-bottom: 4px;
        }

        .response-text {
            color: #666;
            padding: 8px;
            background-color: #f9f9f9;
            border-radius: 3px;
            font-style: italic;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>ASSESSMENT REPORT</h1>
        <p>Digital Maturity Assessment</p>
        <p>Report ID: {{ $assessment->id }}</p>
    </div>

    <!-- Organization Information -->
    <div class="info-section">
        <h2>Organization Information</h2>
        <div class="info-row">
            <div class="info-label">Organization Name:</div>
            <div class="info-value">{{ $assessment->org_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Organization Type:</div>
            <div class="info-value">{{ $assessment->org_type }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Assessment Date:</div>
            <div class="info-value">{{ $assessment->assessment_date->format('d F Y') }}</div>
        </div>
    </div>

    <!-- Assessor Information -->
    <div class="info-section">
        <h2>Assessor Information</h2>
        <div class="info-row">
            <div class="info-label">Assessor Name:</div>
            <div class="info-value">{{ $assessment->assessor_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Position:</div>
            <div class="info-value">{{ $assessment->assessor_position }}</div>
        </div>
    </div>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-box">
            <div class="label">Total Score</div>
            <div class="value">{{ number_format($assessment->total_score, 2) }}/5.00</div>
        </div>
        <div class="summary-box maturity-box">
            <div class="label">Maturity Level</div>
            <div class="value">{{ $assessment->maturity_level }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Status</div>
            <div class="value" style="color: #28a745;">{{ $assessment->status }}</div>
        </div>
    </div>

    <!-- Assessment Responses -->
    <div class="responses-section">
        <h2>Assessment Responses</h2>

        @foreach ($assessment->responses as $response)
        <div class="response-item">
            <div class="response-header">
                <div class="response-indicator">
                    Indicator #{{ $response->indicator_id }}: {{ $indicators[$response->indicator_id] ?? 'Unknown' }}
                </div>
                <div class="response-score">Score: {{ $response->score }}/5</div>
            </div>

            <div class="response-content">
                @if ($response->evidence_text)
                <div>
                    <div class="response-label">Evidence:</div>
                    <div class="response-text">{{ $response->evidence_text }}</div>
                </div>
                @endif

                @if ($response->document_path)
                <div>
                    <div class="response-label">Supporting Document:</div>
                    <div class="response-text">{{ $response->document_path }}</div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This report was generated on {{ now()->format('d F Y H:i:s') }}</p>
        <p>Confidential - For authorized personnel only</p>
    </div>
</body>

</html>