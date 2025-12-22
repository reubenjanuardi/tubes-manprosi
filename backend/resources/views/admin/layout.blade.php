<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head-scripts')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f9fafb;
        }
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background: #18181b;
            color: white;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #27272a;
        }
        
        .sidebar-logo {
            font-size: 18px;
            font-weight: 600;
            color: white;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 16px;
            flex: 1;
        }
        
        .sidebar-menu li {
            margin-bottom: 4px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: #a1a1aa;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.15s;
        }
        
        .sidebar-menu a:hover {
            background: #27272a;
            color: white;
        }
        
        .sidebar-menu a.active {
            background: #f97316;
            color: white;
        }
        
        .sidebar-menu .icon {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Top Bar */
        .topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-bar {
            flex: 1;
            max-width: 400px;
        }
        
        .search-bar input {
            width: 100%;
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            background: #18181b;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Content Area */
        .content-area {
            padding: 32px;
            flex: 1;
            overflow-y: auto;
        }
        
        .page-header {
            margin-bottom: 32px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #18181b;
        }
        
        /* Cards */
        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #18181b;
            margin-bottom: 16px;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            text-align: left;
            padding: 12px;
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            color: #1e293b;
        }
        
        tr:hover {
            background: #f8fafc;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 24px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            color: #475569;
            text-decoration: none;
            font-size: 14px;
        }
        
        .pagination a:hover {
            background: #f8fafc;
        }
        
        .pagination .active {
            background: #f97316;
            color: white;
            border-color: #f97316;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">Laravel</div>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="icon">🏠</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.assessments') }}" class="{{ request()->routeIs('admin.assessments') ? 'active' : '' }}">
                        <span class="icon">📝</span>
                        <span>Assessments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.domains') }}" class="{{ request()->routeIs('admin.domains') ? 'active' : '' }}">
                        <span class="icon">🌐</span>
                        <span>Domains</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.indicators') }}" class="{{ request()->routeIs('admin.indicators') ? 'active' : '' }}">
                        <span class="icon">📊</span>
                        <span>Indicators</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.subdomains') }}" class="{{ request()->routeIs('admin.subdomains') ? 'active' : '' }}">
                        <span class="icon">📂</span>
                        <span>Subdomains</span>
                    </a>
                </li>
                <li style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #27272a;">
                    <a href="{{ route('admin.structure') }}" class="{{ request()->routeIs('admin.structure') ? 'active' : '' }}">
                        <span class="icon">🗂️</span>
                        <span>DB Structure</span>
                    </a>
                </li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="topbar">
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                </div>
                <div class="topbar-right">
                    <div class="user-avatar">A</div>
                </div>
            </div>
            
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>
    
    @yield('scripts')
</body>
</html>
