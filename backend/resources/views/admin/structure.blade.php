@extends('admin.layout')

@section('title', 'Database Structure')

@section('styles')
<style>
    .erd-container {
        background: white;
        padding: 40px;
        border-radius: 12px;
        overflow-x: auto;
    }
    
    .erd-diagram {
        display: flex;
        gap: 24px;
        min-width: 1200px;
        padding: 20px;
        position: relative;
    }
    
    .entity-column {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    
    .entity-box {
        background: #f8fafc;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        position: relative;
    }
    
    .entity-box.primary {
        background: #1e293b;
        color: white;
        border-color: #1e293b;
    }
    
    .entity-box.highlight {
        background: #fef2f2;
        border-color: #ef4444;
    }
    
    .entity-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    .entity-box.primary .entity-title {
        border-bottom-color: rgba(255,255,255,0.2);
    }
    
    .entity-field {
        font-size: 13px;
        padding: 4px 0;
        color: #475569;
    }
    
    .entity-box.primary .entity-field {
        color: #cbd5e1;
    }
    
    .relationship-line {
        position: absolute;
        stroke: #94a3b8;
        stroke-width: 2;
        fill: none;
    }
    
    .cardinality {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }
    
    .info-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }
    
    .info-box h3 {
        color: #1e40af;
        font-size: 16px;
        margin-bottom: 8px;
    }
    
    .info-box ul {
        margin-left: 20px;
        color: #1e40af;
    }
    
    .maturity-levels {
        background: #18181b;
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-top: 24px;
    }
    
    .maturity-levels h3 {
        margin-bottom: 16px;
        color: #f97316;
    }
    
    .maturity-level {
        padding: 8px 0;
        font-size: 13px;
        border-bottom: 1px solid #27272a;
    }
    
    .maturity-level:last-child {
        border-bottom: none;
    }
    
    .level-number {
        display: inline-block;
        background: #f97316;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        text-align: center;
        line-height: 24px;
        font-weight: 700;
        margin-right: 12px;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Database Structure & Relationships</h1>
</div>

<div class="info-box">
    <h3>📊 SPBE Assessment System Architecture</h3>
    <p style="color: #1e40af; margin-top: 8px;">
        Sistem ini mengikuti standar SPBE (Sistem Pemerintahan Berbasis Elektronik) dengan struktur hierarki:
        <strong>Informasi Organisasi → Domain → Indikator → Assessment Response → Score → Maturity Level</strong>
    </p>
</div>

<div class="card">
    <h2 class="card-title">Entity Relationship Diagram</h2>
    
    <div class="erd-container">
        <div class="erd-diagram">
            <!-- Column 1: Organization Info -->
            <div class="entity-column">
                <div class="entity-box highlight">
                    <div class="entity-title">📋 Informasi Organisasi</div>
                    <div class="entity-field">→ Nama Instansi/Organisasi</div>
                    <div class="entity-field">→ Jenis Instansi <span style="font-size: 11px; color: #ef4444;">(6 types)</span></div>
                    <div class="entity-field">→ Nama Assessor</div>
                    <div class="entity-field">→ Jabatan Assessor</div>
                    <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #fca5a5;">
                        <div style="font-size: 11px; color: #991b1b; font-weight: 600;">Jenis Instansi:</div>
                        <div style="font-size: 11px; color: #991b1b;">1. Kementerian</div>
                        <div style="font-size: 11px; color: #991b1b;">2. Lembaga Pemerintah Non-Kementerian</div>
                        <div style="font-size: 11px; color: #991b1b;">3. Pemerintah Provinsi</div>
                        <div style="font-size: 11px; color: #991b1b;">4. Pemerintah Kabupaten/Kota</div>
                        <div style="font-size: 11px; color: #991b1b;">5. BUMN</div>
                        <div style="font-size: 11px; color: #991b1b;">6. BUMD</div>
                    </div>
                </div>
            </div>
            
            <!-- Column 2: Assessment Flow -->
            <div class="entity-column">
                <div class="entity-box primary">
                    <div class="entity-title">📝 Assessment Response</div>
                    <div class="entity-field">→ Organization ID <span class="cardinality">(1:1)</span></div>
                    <div class="entity-field">→ Indicator ID <span class="cardinality">(1:1)</span></div>
                    <div class="entity-field">→ Response Value</div>
                    <div class="entity-field">→ Evidence/Dokumen</div>
                    <div class="entity-field">→ Timestamp</div>
                </div>
                
                <div class="entity-box primary">
                    <div class="entity-title">📊 Score Calculation</div>
                    <div class="entity-field">→ Total Score (0-5)</div>
                    <div class="entity-field">→ Domain Scores</div>
                    <div class="entity-field">→ Weighted Average</div>
                </div>
            </div>
            
            <!-- Column 3: Domain & Indicators -->
            <div class="entity-column">
                <div class="entity-box highlight">
                    <div class="entity-title">🌐 Domain</div>
                    <div style="font-size: 11px; color: #991b1b; font-weight: 600; margin-bottom: 4px;">4 Domain SPBE:</div>
                    <div class="entity-field" style="color: #991b1b;">1. Kebijakan dan Tata Kelola SPBE</div>
                    <div class="entity-field" style="color: #991b1b;">2. Kapabilitas dan Budaya Digital</div>
                    <div class="entity-field" style="color: #991b1b;">3. Pemanfaatan Data Lintas Sektor</div>
                    <div class="entity-field" style="color: #991b1b;">4. Keterpaduan Layanan Digital</div>
                </div>
                
                <div class="entity-box primary">
                    <div class="entity-title">📌 Indikator</div>
                    <div class="entity-field">→ Domain ID <span class="cardinality">(M:N)</span></div>
                    <div class="entity-field">→ Kode Indikator</div>
                    <div class="entity-field">→ Nama Indikator</div>
                    <div class="entity-field">→ Bobot/Weight</div>
                </div>
                
                <div class="entity-box">
                    <div class="entity-title">⚖️ Bobot</div>
                    <div class="entity-field">→ Indicator ID <span class="cardinality">(1:1)</span></div>
                    <div class="entity-field">→ Weight Value</div>
                    <div class="entity-field">→ Calculation Method</div>
                </div>
            </div>
            
            <!-- Column 4: Output -->
            <div class="entity-column">
                <div class="entity-box">
                    <div class="entity-title">📁 Bukti Pendukung</div>
                    <div class="entity-field">→ Assessment ID</div>
                    <div class="entity-field">→ File Upload</div>
                    <div class="entity-field">→ Document Type</div>
                    <div class="entity-field">→ Upload Date</div>
                </div>
                
                <div class="entity-box primary" style="background: #f97316; border-color: #f97316;">
                    <div class="entity-title">🎯 Maturity Level</div>
                    <div class="entity-field">→ Level 1-5</div>
                    <div class="entity-field">→ Based on Score</div>
                    <div class="entity-field">→ Criteria Definition</div>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 24px; padding: 16px; background: #f8fafc; border-radius: 8px; font-size: 13px; color: #475569;">
            <strong>Relationships:</strong><br>
            • <strong>1:1</strong> = One-to-One relationship<br>
            • <strong>M:N</strong> = Many-to-Many relationship<br>
            • <strong>Assessment Response</strong> connects Organization Info with Indicators<br>
            • <strong>Domain</strong> contains multiple Indicators<br>
            • <strong>Bobot</strong> determines weight of each Indicator in final score calculation
        </div>
    </div>
</div>

<div class="maturity-levels">
    <h3>🎯 Maturity Level / Tingkat Kematangan (Level 1-5)</h3>
    <div class="maturity-level">
        <span class="level-number">1</span>
        <strong>Initial</strong> - Proses ad hoc dan tidak terstruktur
    </div>
    <div class="maturity-level">
        <span class="level-number">2</span>
        <strong>Managed</strong> - Proses terstruktur tapi belum konsisten
    </div>
    <div class="maturity-level">
        <span class="level-number">3</span>
        <strong>Defined</strong> - Proses standar yang konsisten
    </div>
    <div class="maturity-level">
        <span class="level-number">4</span>
        <strong>Quantitatively Managed</strong> - Proses terukur dan terkontrol
    </div>
    <div class="maturity-level">
        <span class="level-number">5</span>
        <strong>Optimizing</strong> - Fokus pada peningkatan proses berkelanjutan
    </div>
</div>

<div class="card">
    <h2 class="card-title">📋 Database Tables</h2>
    <table>
        <thead>
            <tr>
                <th>Table Name</th>
                <th>Description</th>
                <th>Key Fields</th>
                <th>Records</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>organizations</strong></td>
                <td>Organization master data</td>
                <td>id, name, type, contact</td>
                <td>-</td>
            </tr>
            <tr>
                <td><strong>assessments</strong></td>
                <td>Assessment submissions</td>
                <td>id, org_id, total_score, maturity_level</td>
                <td>{{ \App\Models\Assessment::count() }}</td>
            </tr>
            <tr>
                <td><strong>domains</strong></td>
                <td>SPBE domains (4 fixed)</td>
                <td>id, code, name, description</td>
                <td>4 domains</td>
            </tr>
            <tr>
                <td><strong>indicators</strong></td>
                <td>SPBE indicators</td>
                <td>id, domain_id, code, name, weight</td>
                <td>~45 indicators</td>
            </tr>
            <tr>
                <td><strong>assessment_responses</strong></td>
                <td>Individual indicator responses</td>
                <td>id, assessment_id, indicator_id, value</td>
                <td>-</td>
            </tr>
            <tr>
                <td><strong>evidences</strong></td>
                <td>Supporting documents</td>
                <td>id, assessment_id, file_path, type</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
