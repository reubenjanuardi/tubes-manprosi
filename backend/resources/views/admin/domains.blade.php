@extends('admin.layout')

@section('title', 'Domains')

@section('content')
<div class="page-header">
    <h1 class="page-title">Domains</h1>
</div>

<div class="card" style="background: #eff6ff; border-color: #3b82f6;">
    <h3 style="color: #1e40af; margin-bottom: 12px;">📘 4 Domain SPBE (Sistem Pemerintahan Berbasis Elektronik)</h3>
    <p style="color: #1e40af; font-size: 14px;">
        Sesuai dengan standar SPBE Indonesia, terdapat 4 domain utama untuk evaluasi kematangan digital organisasi pemerintah.
    </p>
</div>

<div class="card">
    <h2 class="card-title">SPBE Assessment Domains</h2>
    
    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Code</th>
                <th>Domain Name</th>
                <th>Description</th>
                <th style="width: 120px;">Indicators</th>
                <th style="width: 100px;">Weight</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong style="color: #3b82f6;">D1</strong></td>
                <td><strong>Kebijakan dan Tata Kelola SPBE</strong></td>
                <td>Evaluasi kebijakan internal terkait SPBE, arsitektur SPBE, peta rencana SPBE, dan tata kelola organisasi digital</td>
                <td style="text-align: center;">12</td>
                <td style="text-align: center;">25%</td>
            </tr>
            <tr>
                <td><strong style="color: #f97316;">D2</strong></td>
                <td><strong>Kapabilitas dan Budaya Digital</strong></td>
                <td>Penilaian SDM TIK, kompetensi digital pegawai, budaya inovasi, dan transformasi digital organisasi</td>
                <td style="text-align: center;">10</td>
                <td style="text-align: center;">25%</td>
            </tr>
            <tr>
                <td><strong style="color: #10b981;">D3</strong></td>
                <td><strong>Pemanfaatan Data Lintas Sektor</strong></td>
                <td>Evaluasi integrasi data, berbagi pakai data, standarisasi data, dan kolaborasi antar instansi</td>
                <td style="text-align: center;">8</td>
                <td style="text-align: center;">25%</td>
            </tr>
            <tr>
                <td><strong style="color: #8b5cf6;">D4</strong></td>
                <td><strong>Keterpaduan Layanan Digital</strong></td>
                <td>Penilaian layanan publik berbasis elektronik, integrasi layanan, dan kepuasan pengguna</td>
                <td style="text-align: center;">15</td>
                <td style="text-align: center;">25%</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="card">
    <h2 class="card-title">📊 Domain Statistics</h2>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
        <div style="background: #dbeafe; padding: 16px; border-radius: 8px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; color: #3b82f6;">12</div>
            <div style="font-size: 14px; color: #1e40af; margin-top: 4px;">D1 Indicators</div>
        </div>
        <div style="background: #fed7aa; padding: 16px; border-radius: 8px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; color: #f97316;">10</div>
            <div style="font-size: 14px; color: #9a3412; margin-top: 4px;">D2 Indicators</div>
        </div>
        <div style="background: #d1fae5; padding: 16px; border-radius: 8px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; color: #10b981;">8</div>
            <div style="font-size: 14px; color: #065f46; margin-top: 4px;">D3 Indicators</div>
        </div>
        <div style="background: #e9d5ff; padding: 16px; border-radius: 8px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; color: #8b5cf6;">15</div>
            <div style="font-size: 14px; color: #5b21b6; margin-top: 4px;">D4 Indicators</div>
        </div>
    </div>
</div>

<div class="card" style="background: #f8fafc;">
    <h3 style="color: #475569; margin-bottom: 12px;">💡 Assessment Flow</h3>
    <div style="font-size: 14px; color: #64748b; line-height: 1.8;">
        <strong>1. Organization Info</strong> → Pilih jenis instansi & isi data assessor<br>
        <strong>2. Domain Selection</strong> → Sistem otomatis menampilkan 4 domain<br>
        <strong>3. Indicator Assessment</strong> → Jawab setiap indikator per domain<br>
        <strong>4. Evidence Upload</strong> → Upload dokumen pendukung<br>
        <strong>5. Score Calculation</strong> → Sistem menghitung weighted score<br>
        <strong>6. Maturity Level</strong> → Hasil akhir berupa level 1-5
    </div>
</div>
@endsection
