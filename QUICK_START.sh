#!/bin/bash
# ============================================================================
# QUICK START SCRIPT - Assessment Tool Backend Setup
# ============================================================================
# Run this script to quickly setup the backend environment
# Requirements: PHP 8.1+, MySQL, Composer
# ============================================================================

echo "🚀 Assessment Tool - Backend Quick Setup"
echo "========================================"
echo ""

# Set working directory
PROJECT_ROOT="c:\laragon\www\TUBES_MANPROSI"
BACKEND_DIR="$PROJECT_ROOT\backend"

# Step 1: Navigate to backend
echo "📁 Step 1: Navigating to backend directory..."
cd "$BACKEND_DIR" || exit

# Step 2: Install Composer dependencies
echo "📦 Step 2: Installing composer packages..."
composer install --quiet

# Step 3: Setup environment file
echo "⚙️  Step 3: Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✓ .env file created from example"
else
    echo "✓ .env file already exists"
fi

# Step 4: Generate app key
echo "🔑 Step 4: Generating application key..."
php artisan key:generate --quiet
echo "✓ Application key generated"

# Step 5: Run migrations
echo "🗄️  Step 5: Running database migrations..."
php artisan migrate --quiet
echo "✓ Migrations completed"

# Step 6: Create storage link
echo "📁 Step 6: Creating storage link..."
php artisan storage:link --quiet
echo "✓ Storage link created"

# Step 7: Success message
echo ""
echo "✅ Backend setup completed successfully!"
echo ""
echo "📝 Next steps:"
echo "  1. Update .env with your database credentials if needed"
echo "  2. Start Laravel server: php artisan serve"
echo "  3. Backend will run on: http://localhost:8000"
echo "  4. Copy API_INTEGRATION_SNIPPET.js to frontend"
echo "  5. Update API_BASE_URL in frontend app.js"
echo ""
echo "📚 For detailed documentation, see: IMPLEMENTATION_GUIDE.md"
echo "========================================"
