<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    // Profil anggota
    public function profil()
    {
        $user = Auth::user();
        $member = $user->member;
        return view('anggota.profil', compact('user', 'member'));
    }

    // Riwayat kehadiran
    public function riwayat()
    {
        $member = Auth::user()->member;

        if (!$member) {
            return view('anggota.riwayat', ['attendances' => collect()]);
        }

        $attendances = Attendance::with('event')
            ->where('member_id', $member->id)
            ->latest()
            ->paginate(10);

        return view('anggota.riwayat', compact('attendances'));
    }
}