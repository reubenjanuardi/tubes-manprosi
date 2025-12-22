<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assessment;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        // Get statistics
        $total_assessments = Assessment::count();
        $completed = Assessment::whereNotNull('total_score')->count();
        $avg_score = Assessment::whereNotNull('total_score')->avg('total_score');
        $this_month = Assessment::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $stats = [
            'total_assessments' => $total_assessments,
            'completed' => $completed,
            'avg_score' => $avg_score ?? 0,
            'this_month' => $this_month,
        ];

        // Get recent assessments
        $recent_assessments = Assessment::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get chart data - top 10 organizations by score
        $chart_assessments = Assessment::whereNotNull('total_score')
            ->orderBy('total_score', 'desc')
            ->limit(10)
            ->get(['org_name', 'total_score', 'org_type']);

        $chart_data = [
            'labels' => $chart_assessments->pluck('org_name')->toArray(),
            'scores' => $chart_assessments->pluck('total_score')->toArray(),
            'types' => $chart_assessments->pluck('org_type')->toArray(),
        ];

        return view('admin.dashboard', compact('stats', 'recent_assessments', 'chart_data'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function assessments()
    {
        $assessments = Assessment::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.assessments', compact('assessments'));
    }

    public function domains()
    {
        // Placeholder - implement domain management
        return view('admin.domains');
    }

    public function indicators()
    {
        // Placeholder - implement indicator management
        return view('admin.indicators');
    }

    public function subdomains()
    {
        // Placeholder - implement subdomain management
        return view('admin.subdomains');
    }

    public function structure()
    {
        // Database structure visualization
        return view('admin.structure');
    }
}
