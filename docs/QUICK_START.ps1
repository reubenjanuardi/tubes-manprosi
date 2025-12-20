# ============================================================================
# QUICK START SCRIPT - Assessment Tool Backend Setup (PowerShell)
# ============================================================================
# Run this script to quickly setup the backend environment
# Requirements: PHP 8.1+, MySQL, Composer
# 
# Usage: 
#   powershell -ExecutionPolicy Bypass -File QUICK_START.ps1
# ============================================================================

Write-Host "🚀 Assessment Tool - Backend Quick Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Configuration
$ProjectRoot = "c:\laragon\www\TUBES_MANPROSI"
$BackendDir = "$ProjectRoot\backend"

# Step 1: Navigate to backend
Write-Host "📁 Step 1: Navigating to backend directory..." -ForegroundColor Yellow
Set-Location "$BackendDir" -ErrorAction Stop
Write-Host "✓ Location: $(Get-Location)" -ForegroundColor Green
Write-Host ""

# Step 2: Install Composer dependencies
Write-Host "📦 Step 2: Installing composer packages..." -ForegroundColor Yellow
composer install --quiet
Write-Host "✓ Composer packages installed" -ForegroundColor Green
Write-Host ""

# Step 3: Setup environment file
Write-Host "⚙️  Step 3: Setting up environment..." -ForegroundColor Yellow
if (!(Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✓ .env file created from example" -ForegroundColor Green
} else {
    Write-Host "✓ .env file already exists" -ForegroundColor Green
}
Write-Host ""

# Step 4: Generate app key
Write-Host "🔑 Step 4: Generating application key..." -ForegroundColor Yellow
php artisan key:generate --quiet
Write-Host "✓ Application key generated" -ForegroundColor Green
Write-Host ""

# Step 5: Run migrations
Write-Host "🗄️  Step 5: Running database migrations..." -ForegroundColor Yellow
Write-Host "  Note: Database will be created automatically if it doesn't exist" -ForegroundColor Gray
php artisan migrate
Write-Host "✓ Migrations completed" -ForegroundColor Green
Write-Host ""

# Step 6: Create storage link
Write-Host "📁 Step 6: Creating storage link..." -ForegroundColor Yellow
php artisan storage:link --quiet
Write-Host "✓ Storage link created" -ForegroundColor Green
Write-Host ""

# Step 7: Success message
Write-Host "✅ Backend setup completed successfully!" -ForegroundColor Green
Write-Host ""
Write-Host "📝 Next steps:" -ForegroundColor Cyan
Write-Host "  1. Update .env with your database credentials if needed" -ForegroundColor Gray
Write-Host "  2. Start Laravel server: php artisan serve" -ForegroundColor Gray
Write-Host "  3. Backend will run on: http://localhost:8000" -ForegroundColor Gray
Write-Host "  4. Copy API_INTEGRATION_SNIPPET.js to frontend" -ForegroundColor Gray
Write-Host "  5. Update API_BASE_URL in frontend app.js" -ForegroundColor Gray
Write-Host ""
Write-Host "📚 For detailed documentation, see: IMPLEMENTATION_GUIDE.md" -ForegroundColor Cyan
Write-Host "========================================"
