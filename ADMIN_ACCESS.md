# Admin Panel Access Guide

## 🔐 Login Credentials

**URL**: `http://localhost:8000/admin`

**Default Admin Account**:
- **Email**: `admin@pemdilid.com`
- **Password**: `admin123`

---

## 📋 Available Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/admin` | Admin login page |
| POST | `/admin/login` | Process authentication |
| GET | `/admin/dashboard` | Admin dashboard (protected) |
| POST | `/admin/logout` | Logout admin |

---

## 🚀 Quick Start

### 1. Access Admin Panel
```
http://localhost:8000/admin
```

### 2. Login with Default Credentials
- Enter email: `admin@pemdilid.com`
- Enter password: `admin123`
- Click "Login to Dashboard"

### 3. Dashboard Features
- **Statistics Overview**: Total assessments, completed count, average score
- **Recent Assessments**: Last 10 submissions with organization details
- **Export Options**: CSV/PDF reports (coming soon)

---

## 🔧 Admin User Management

### Create New Admin User
```bash
cd backend
php artisan tinker
```

```php
User::create([
    'name' => 'Your Name',
    'email' => 'your.email@example.com',
    'password' => Hash::make('your-password'),
    'role' => 'admin',
    'phone' => '+62 812-xxxx-xxxx',
    'organization' => 'Your Organization',
    'position' => 'Your Position',
    'is_active' => true,
]);
```

### Reset Admin Password
```bash
cd backend
php artisan tinker
```

```php
$user = User::where('email', 'admin@pemdilid.com')->first();
$user->password = Hash::make('new-password');
$user->save();
```

### Re-run Seeder (Reset to Default)
```bash
cd backend
php artisan db:seed --class=AdminUserSeeder
```

---

## 🛡️ Security Notes

1. **Change Default Password**: In production, immediately change `admin123` to a strong password
2. **Session-Based Auth**: Uses Laravel's built-in authentication (cookies + sessions)
3. **CSRF Protection**: All forms include `@csrf` tokens
4. **Middleware Protection**: Dashboard routes require `auth` middleware

---

## 📊 Dashboard Statistics

The admin dashboard displays:

- **Total Assessments**: Count of all assessment submissions
- **Completed**: Assessments with calculated scores
- **Average Score**: Mean score across all completed assessments
- **This Month**: Assessments submitted in current month

---

## 🎨 Custom Admin Solution

**Why not Filament?**
- Filament v4 requires PHP 8.3+ (current system: PHP 8.2.12)
- Laravel 12 compatibility issues with Filament v3.2
- Custom solution provides:
  - Zero external dependencies
  - Full control over UI/UX
  - Faster page loads
  - Tailored to PEMDI.ID requirements

---

## 🐛 Troubleshooting

### Issue: "419 Page Expired" on Login
**Solution**: Clear browser cookies and cache, then refresh

### Issue: "SQLSTATE[42S02] Table 'users' doesn't exist"
**Solution**: Run migrations
```bash
cd backend
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
```

### Issue: "Class 'Assessment' not found"
**Solution**: Verify Assessment model exists at `backend/app/Models/Assessment.php`

### Issue: Cannot access /admin/dashboard directly
**Solution**: You must login first at `/admin` - dashboard is protected by auth middleware

---

## 📝 Next Steps (Optional Enhancements)

- [ ] Add role-based permissions (admin, super-admin, viewer)
- [ ] Implement assessment detail view
- [ ] Add CSV/PDF export for all assessments
- [ ] Create user management interface
- [ ] Add activity logs
- [ ] Implement search and filters
- [ ] Add pagination for assessment list

---

## 📧 Support

For issues or questions:
1. Check Laravel logs: `backend/storage/logs/laravel.log`
2. Review error messages in browser console
3. Verify database connection in `.env` file
