@extends('admin.layout')

@section('title', 'Indicators')

@section('content')
<div class="page-header">
    <h1 class="page-title">Indicators</h1>
</div>

<div class="card">
    <h2 class="card-title">SPBE Assessment Indicators</h2>
    
    <table>
        <thead>
            <tr>
                <th>Indicator Code</th>
                <th>Indicator Name</th>
                <th>Domain</th>
                <th>Weight</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>I.1.1</strong></td>
                <td>Kebijakan Tata Kelola TIK</td>
                <td>Kebijakan SPBE</td>
                <td>5%</td>
                <td><span class="badge badge-success">Active</span></td>
            </tr>
            <tr>
                <td><strong>I.1.2</strong></td>
                <td>Kebijakan Keamanan Informasi</td>
                <td>Kebijakan SPBE</td>
                <td>5%</td>
                <td><span class="badge badge-success">Active</span></td>
            </tr>
            <tr>
                <td><strong>I.2.1</strong></td>
                <td>Struktur Organisasi SPBE</td>
                <td>Tata Kelola SPBE</td>
                <td>4%</td>
                <td><span class="badge badge-success">Active</span></td>
            </tr>
            <tr>
                <td><strong>I.2.2</strong></td>
                <td>Pejabat SPBE</td>
                <td>Tata Kelola SPBE</td>
                <td>4%</td>
                <td><span class="badge badge-success">Active</span></td>
            </tr>
            <tr>
                <td><strong>I.3.1</strong></td>
                <td>Manajemen Risiko</td>
                <td>Manajemen SPBE</td>
                <td>6%</td>
                <td><span class="badge badge-success">Active</span></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="card">
    <p style="color: #71717a; font-size: 14px;">
        <strong>Note:</strong> Indicator management features coming soon. This page will allow you to add, edit, and configure SPBE indicators.
    </p>
</div>
@endsection
