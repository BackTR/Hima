<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        public function scanQr()
    {
        return view('anggota.scan_qr');
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

public function editProfil()
{
    $user = Auth::user();
    $member = $user->member;
    return view('anggota.edit_profil', compact('user', 'member'));
}

public function updateProfil(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name'   => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
        'no_hp'  => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
        'alamat' => 'nullable|string|max:500',
    ]);

    DB::transaction(function () use ($request, $user) {
        $user->update([
            'name' => $request->name,
        ]);

        $user->member()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'no_hp'  => $request->no_hp,
                'alamat' => $request->alamat,
            ]
        );

        LogActivity::log('update', 'Mengupdate profil sendiri', 'User', $user->id);
    });

    return redirect()->route('anggota.profil')->with('success', 'Profil berhasil diupdate!');
}

public function gantiPassword()
{
    return view('anggota.ganti_password');
}

public function updatePassword(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'password_lama'  => 'required',
        'password'       => 'required|min:8|confirmed',
    ]);

    // Cek password lama
    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->withErrors(['password_lama' => 'Password lama tidak sesuai!']);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    LogActivity::log('update', 'Mengganti password sendiri', 'User', $user->id);

    return redirect()->route('anggota.profil')->with('success', 'Password berhasil diubah!');
}
}