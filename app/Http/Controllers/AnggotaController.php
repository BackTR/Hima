<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    public function scan()
    {
        $events = Event::whereDate('tanggal', today())->get();
        $member = Auth::user()->member;
        return view('anggota.scan', compact('events', 'member'));
    }

    public function submitScan(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Data anggota tidak ditemukan.');
        }

        // Cek sudah absen belum
        $existing = Attendance::where('event_id', $request->event_id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah tercatat hadir di event ini!');
    }

    Attendance::create([
        'event_id'   => $request->event_id,
        'member_id'  => $member->id,
        'waktu_scan' => now(),
        'status'     => 'hadir',
    ]);

    return back()->with('success', 'Berhasil scan! Kehadiran kamu sudah tercatat.');
    }

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