# 🚀 QUICK START SCRIPT - Phase 1 & 2 Implementation
# Run this script to setup the complete system

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "PEMDI Assessment Platform - Setup" -ForegroundColor Cyan
Write-Host "Phase 1 & 2 Complete Implementation" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$backendPath = "$PSScriptRoot\backend"
Set-Location $backendPath

Write-Host "[1/6] Checking prerequisites..." -ForegroundColor Yellow
# Check PHP
$phpVersion = php -v 2>&1 | Select-String -Pattern "PHP (\d+\.\d+)"
if ($phpVersion) {
    Write-Host "✓ PHP installed: $($phpVersion.Matches.Groups[1].Value)" -ForegroundColor Green
} else {
    Write-Host "✗ PHP not found. Please install PHP 8.1 or higher." -ForegroundColor Red
    exit 1
}

# Check Composer
$composerVersion = composer -V 2>&1 | Select-String -Pattern "Composer version (\S+)"
if ($composerVersion) {
    Write-Host "✓ Composer installed: $($composerVersion.Matches.Groups[1].Value)" -ForegroundColor Green
} else {
    Write-Host "✗ Composer not found. Please install Composer." -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "[2/6] Installing dependencies..." -ForegroundColor Yellow
composer install --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Dependencies installed" -ForegroundColor Green
} else {
    Write-Host "✗ Failed to install dependencies" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "[3/6] Setting up environment..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✓ .env file created" -ForegroundColor Green
    
    # Generate app key
    php artisan key:generate --no-interaction
    Write-Host "✓ Application key generated" -ForegroundColor Green
    
    # Generate JWT secret
    php artisan jwt:secret --no-interaction 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ JWT secret generated" -ForegroundColor Green
    }
} else {
    Write-Host "✓ .env file exists" -ForegroundColor Green
}

Write-Host ""
Write-Host "[4/6] Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Migrations completed" -ForegroundColor Green
} else {
    Write-Host "✗ Migration failed" -ForegroundColor Red
    Write-Host "  Tip: Check database connection in .env file" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "[5/6] Seeding initial data..." -ForegroundColor Yellow
$seedResponse = Read-Host "Do you want to seed indicators? (y/n)"
if ($seedResponse -eq "y") {
    php artisan db:seed --class=IndicatorSeeder
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ Indicators seeded" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "[6/6] Creating super admin user..." -ForegroundColor Yellow
$createAdmin = Read-Host "Create super admin user? (y/n)"
if ($createAdmin -eq "y") {
    Write-Host "Enter admin details:" -ForegroundColor Cyan
    $adminName = Read-Host "Name"
    $adminEmail = Read-Host "Email"
    $adminPassword = Read-Host "Password" -AsSecureString
    $adminPasswordText = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
        [Runtime.InteropServices.Marshal]::SecureStringToBSTR($adminPassword)
    )
    
    $createUserScript = @"
use App\Models\User;
\$user = User::create([
    'name' => '$adminName',
    'email' => '$adminEmail',
    'password' => bcrypt('$adminPasswordText'),
    'role' => 'super_admin',
    'is_active' => true
]);
echo 'Super Admin created: ' . \$user->email;
"@
    
    $createUserScript | php artisan tinker
    Write-Host "✓ Super admin created" -ForegroundColor Green
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "✅ SETUP COMPLETE!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "📋 Next Steps:" -ForegroundColor Yellow
Write-Host "1. Start the backend server:" -ForegroundColor White
Write-Host "   php artisan serve" -ForegroundColor Cyan
Write-Host ""
Write-Host "2. Open frontend in browser:" -ForegroundColor White
Write-Host "   file:///$(Resolve-Path "$PSScriptRoot\client\index.html")" -ForegroundColor Cyan
Write-Host ""
Write-Host "3. Open admin panel:" -ForegroundColor White
Write-Host "   file:///$(Resolve-Path "$PSScriptRoot\client\admin\login.html")" -ForegroundColor Cyan
Write-Host ""
Write-Host "📚 Documentation:" -ForegroundColor Yellow
Write-Host "- Implementation Report: COMPLETE_IMPLEMENTATION_PRD_PHASE1_PHASE2.md" -ForegroundColor White
Write-Host "- API Testing Guide: API_TESTING_GUIDE.md" -ForegroundColor White
Write-Host "- Phase 1 Summary: PHASE1_IMPLEMENTATION_SUMMARY.md" -ForegroundColor White
Write-Host ""
Write-Host "🧪 Run Tests:" -ForegroundColor Yellow
Write-Host "   php artisan test" -ForegroundColor Cyan
Write-Host ""
Write-Host "Happy coding! 🚀" -ForegroundColor Green
