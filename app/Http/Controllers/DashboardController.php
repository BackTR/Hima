<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        $stats = [];
        $recentLogs = collect();
        $recentMembers = collect();

        if (in_array($role, ['superadmin', 'admin'])) {
            $stats = [
                'total_user'       => User::count(),
                'total_anggota'    => User::where('role', 'anggota')->count(),
                'total_pengurus'   => User::where('role', 'pengurus')->count(),
                'total_admin'      => User::where('role', 'admin')->count(),
                'anggota_aktif'    => Member::where('status', 'aktif')->count(),
                'anggota_alumni'   => Member::where('status', 'alumni')->count(),
                'anggota_nonaktif' => Member::where('status', 'nonaktif')->count(),
            ];

            $recentMembers = User::with('member')
                ->where('role', 'anggota')
                ->latest()
                ->take(5)
                ->get();
        }

        if ($role === 'superadmin') {
            $recentLogs = ActivityLog::with('user')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'recentLogs', 'recentMembers'));
    }
}