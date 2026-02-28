<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventSession;
use App\Models\Event;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventSessionController extends Controller
{
    // Admin: Mulai absensi - generate QR
    public function start(string $event_id)
    {
        $event = Event::findOrFail($event_id);

        // Nonaktifkan session sebelumnya jika ada
        EventSession::where('event_id', $event_id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Buat session baru
        $session = EventSession::create([
            'event_id'   => $event_id,
            'token'      => Str::uuid(),
            'is_active'  => true,
            'expired_at' => now()->addMinutes(5),
            'created_by' => Auth::id(),
        ]);

        LogActivity::log('create', 'Memulai sesi absensi event: ' . $event->nama_event, 'EventSession', $session->id);

        return redirect()->route('event-sessions.qr', $session->id)
            ->with('success', 'Sesi absensi dimulai! QR aktif selama 5 menit.');
    }

    // Tampilkan QR
    public function showQr(string $id)
    {
        $session = EventSession::with('event')->findOrFail($id);
        return view('event_sessions.qr', compact('session'));
    }

    // Admin: Tutup absensi
    public function stop(string $event_id)
    {
        EventSession::where('event_id', $event_id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return redirect()->route('events.show', $event_id)
            ->with('success', 'Sesi absensi ditutup!');
    }

    // Anggota: Scan QR
    public function scan(string $token)
    {
        $session = EventSession::where('token', $token)->first();

        // Cek token valid
        if (!$session) {
            return view('event_sessions.scan_result', [
                'status'  => 'error',
                'message' => 'Token tidak valid!'
            ]);
        }

        // Cek is_active
        if (!$session->is_active) {
            return view('event_sessions.scan_result', [
                'status'  => 'error',
                'message' => 'Sesi absensi sudah ditutup!'
            ]);
        }

        // Cek expired
        if ($session->expired_at->isPast()) {
            $session->update(['is_active' => false]);
            return view('event_sessions.scan_result', [
                'status'  => 'error',
                'message' => 'QR Code sudah expired!'
            ]);
        }

        $member = Auth::user()->member;

        // Cek member ada
        if (!$member) {
            return view('event_sessions.scan_result', [
                'status'  => 'error',
                'message' => 'Data anggota tidak ditemukan!'
            ]);
        }

        // Cek sudah absen belum
        $existing = Attendance::where('event_id', $session->event_id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return view('event_sessions.scan_result', [
                'status'  => 'warning',
                'message' => 'Kamu sudah tercatat hadir di event ini!'
            ]);
        }

        // Simpan attendance
        Attendance::create([
            'event_id'   => $session->event_id,
            'member_id'  => $member->id,
            'waktu_scan' => now(),
            'status'     => 'hadir',
        ]);

        return view('event_sessions.scan_result', [
            'status'  => 'success',
            'message' => 'Berhasil! Kehadiran kamu sudah tercatat.',
            'event'   => $session->event,
        ]);
    }
}