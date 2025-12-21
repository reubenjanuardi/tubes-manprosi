# ============================================================================
# Phase 1 Quick Setup Script - Dynamic Indicator System
# ============================================================================

Write-Host "`n" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Phase 1 Setup - Dynamic Indicators" -ForegroundColor Cyan  
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "`n"

$ErrorActionPreference = "Stop"

# Check if we're in the right directory
if (-not (Test-Path "backend/artisan")) {
    Write-Host "❌ Error: Please run this script from the project root directory" -ForegroundColor Red
    Write-Host "   Current directory: $(Get-Location)" -ForegroundColor Yellow
    exit 1
}

Write-Host "✓ Project directory verified" -ForegroundColor Green

# Step 1: Run migrations
Write-Host "`n📦 Step 1: Running database migrations..." -ForegroundColor Yellow
Set-Location backend
try {
    php artisan migrate --force
    Write-Host "✓ Migrations completed successfully" -ForegroundColor Green
} catch {
    Write-Host "❌ Migration failed: $_" -ForegroundColor Red
    exit 1
}

# Step 2: Seed indicators
Write-Host "`n🌱 Step 2: Seeding indicator data..." -ForegroundColor Yellow
try {
    php artisan db:seed --class=IndicatorSeeder
    Write-Host "✓ Seeding completed successfully" -ForegroundColor Green
} catch {
    Write-Host "❌ Seeding failed: $_" -ForegroundColor Red
    exit 1
}

# Step 3: Verify data
Write-Host "`n🔍 Step 3: Verifying database..." -ForegroundColor Yellow
$verifyScript = @"
\$count = \App\Models\Indicator::count();
\$activeCount = \App\Models\Indicator::active()->count();
\$version = \App\Models\Indicator::getCurrentVersion();
echo "Total indicators: \$count\n";
echo "Active indicators: \$activeCount\n";
echo "Current version: \$version\n";
exit;
"@

$verifyScript | php artisan tinker

# Step 4: Test API endpoints
Write-Host "`n🧪 Step 4: Testing API endpoints..." -ForegroundColor Yellow

# Start Laravel server in background
Write-Host "   Starting Laravel server..." -ForegroundColor Gray
$serverJob = Start-Job -ScriptBlock {
    Set-Location $using:PWD
    php artisan serve
}

Start-Sleep -Seconds 3

try {
    # Test indicators endpoint
    Write-Host "   Testing GET /api/indicators..." -ForegroundColor Gray
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/indicators" -Method GET -UseBasicParsing
    
    if ($response.StatusCode -eq 200) {
        Write-Host "   ✓ Indicators endpoint working" -ForegroundColor Green
        
        $data = $response.Content | ConvertFrom-Json
        $indicatorCount = 0
        foreach ($group in $data.data.indicators.PSObject.Properties) {
            $indicatorCount += $group.Value.Count
        }
        Write-Host "   ✓ Loaded $indicatorCount indicators" -ForegroundColor Green
        Write-Host "   ✓ Version: $($data.data.version)" -ForegroundColor Green
    }
    
    # Test version endpoint
    Write-Host "   Testing GET /api/indicators/version..." -ForegroundColor Gray
    $versionResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/indicators/version" -Method GET -UseBasicParsing
    
    if ($versionResponse.StatusCode -eq 200) {
        Write-Host "   ✓ Version endpoint working" -ForegroundColor Green
    }
} catch {
    Write-Host "   ⚠️  API test failed (server might need more time)" -ForegroundColor Yellow
    Write-Host "   Error: $_" -ForegroundColor Gray
} finally {
    # Stop the background server
    Stop-Job -Job $serverJob
    Remove-Job -Job $serverJob
}

Set-Location ..

# Summary
Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete! 🎉" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "`n"

Write-Host "📋 Next Steps:" -ForegroundColor Yellow
Write-Host "   1. Start backend server:" -ForegroundColor White
Write-Host "      cd backend" -ForegroundColor Gray
Write-Host "      php artisan serve" -ForegroundColor Gray
Write-Host "`n"
Write-Host "   2. Start frontend server:" -ForegroundColor White
Write-Host "      cd client" -ForegroundColor Gray
Write-Host "      python -m http.server 5500" -ForegroundColor Gray
Write-Host "`n"
Write-Host "   3. Open browser:" -ForegroundColor White
Write-Host "      Frontend: http://localhost:5500/index.html" -ForegroundColor Gray
Write-Host "      Admin:    http://localhost:5500/admin/indicators.html" -ForegroundColor Gray
Write-Host "`n"

Write-Host "📖 Documentation:" -ForegroundColor Yellow
Write-Host "   See PHASE1_SETUP_GUIDE.md for detailed instructions" -ForegroundColor White
Write-Host "`n"

Write-Host "✨ Features Enabled:" -ForegroundColor Green
Write-Host "   ✓ Dynamic indicator loading from database" -ForegroundColor White
Write-Host "   ✓ Real-time sync (30 second polling)" -ForegroundColor White
Write-Host "   ✓ Admin dashboard for CRUD operations" -ForegroundColor White
Write-Host "   ✓ Cache mechanism with fallback" -ForegroundColor White
Write-Host "   ✓ Version tracking for updates" -ForegroundColor White
Write-Host "`n"
