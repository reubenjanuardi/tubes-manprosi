@extends('admin.layout')

@section('title', 'Assessments')

@section('content')
<div class="page-header">
    <h1 class="page-title">Assessments</h1>
</div>

<div class="card">
    <h2 class="card-title">All Assessment Submissions</h2>
    
    @if($assessments->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Organization</th>
                <th>Type</th>
                <th>Assessor</th>
                <th>Score</th>
                <th>Maturity Level</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assessments as $assessment)
            <tr>
                <td><strong>{{ $assessment->org_name }}</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $assessment->org_type)) }}</td>
                <td>{{ $assessment->assessor_name }}</td>
                <td>{{ $assessment->total_score ? number_format($assessment->total_score, 2) : '-' }}</td>
                <td>
                    @if($assessment->maturity_level)
                        <span class="badge badge-info">Level {{ $assessment->maturity_level }}</span>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $assessment->created_at->format('d M Y H:i') }}</td>
                <td>
                    @if($assessment->total_score)
                        <span class="badge badge-success">Completed</span>
                    @else
                        <span class="badge badge-info">In Progress</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination">
        {{ $assessments->links('pagination::simple-default') }}
    </div>
    @else
    <p style="text-align: center; color: #71717a; padding: 40px;">No assessments found.</p>
    @endif
</div>
@endsection
