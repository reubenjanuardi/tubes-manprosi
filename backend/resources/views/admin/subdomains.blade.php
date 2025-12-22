@extends('admin.layout')

@section('title', 'Subdomains')

@section('content')
<div class="page-header">
    <h1 class="page-title">Subdomains</h1>
</div>

<div class="card">
    <h2 class="card-title">SPBE Assessment Subdomains</h2>
    
    <table>
        <thead>
            <tr>
                <th>Subdomain Code</th>
                <th>Subdomain Name</th>
                <th>Parent Domain</th>
                <th>Indicators</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>S.1.1</strong></td>
                <td>Kebijakan Internal</td>
                <td>Kebijakan SPBE</td>
                <td>4 indicators</td>
            </tr>
            <tr>
                <td><strong>S.1.2</strong></td>
                <td>Kebijakan Eksternal</td>
                <td>Kebijakan SPBE</td>
                <td>4 indicators</td>
            </tr>
            <tr>
                <td><strong>S.2.1</strong></td>
                <td>Kelembagaan</td>
                <td>Tata Kelola SPBE</td>
                <td>6 indicators</td>
            </tr>
            <tr>
                <td><strong>S.2.2</strong></td>
                <td>Strategi dan Perencanaan</td>
                <td>Tata Kelola SPBE</td>
                <td>6 indicators</td>
            </tr>
            <tr>
                <td><strong>S.3.1</strong></td>
                <td>Teknologi Informasi</td>
                <td>Manajemen SPBE</td>
                <td>5 indicators</td>
            </tr>
            <tr>
                <td><strong>S.3.2</strong></td>
                <td>Penyelenggaraan</td>
                <td>Manajemen SPBE</td>
                <td>5 indicators</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="card">
    <p style="color: #71717a; font-size: 14px;">
        <strong>Note:</strong> Subdomain management features coming soon. This page will allow you to organize indicators into subdomains.
    </p>
</div>
@endsection
