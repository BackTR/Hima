<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    //liat profil
    public function profil(Request $request){
        $user = $request->user()->load('member');

        return response()->json([
            'success'   => true,
            'data'      => [
                'id'        =>$user->id,
                'name'      =>$user->name,
                'email'     =>$user->email,
                'role'      =>$user->role,
                'is_active' =>$user->is_active,
                'member'    =>$user->member ? [
                    'nim'       =>$user->member->nim,
                    'angkatan'  =>$user->member->angkatan,
                    'divisi'    =>$user->member->divisi,
                    'no_hp'     =>$user->member->no_hp,
                    'alamat'    =>$user->member->alamat,
                    'status'    =>$user->member->status,
                ] : null
            ],
        ]);
    }

//edit profil
    public function updateProfil(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'  =>'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'no_hp' =>'required|string|max:15|regex:/^[0-9+\-\s]+$/',
            'alamat'=>'required|string|max:500'
        ]);

        DB::transaction(function() use($request, $user){
            $user->update(['name' =>$request->name]);
            $user->member()->updateOrCreate(
                ['user_id' =>$user->id],
                [
                    'no_hp' =>$request->no_hp,
                    'alamat'=>$request->alamat
                ]
            );
            LogActivity::log('update', 'Mengupdate Via API', 'User', $user->id);
        });

        return response()->json([
            'success'   =>true,
            'message'   =>'profil berhasil di update'
        ]);
    }

    //ganti password
    public function gantiPassword(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
        'password_lama'  => 'required',
        'password'       => 'required|min:8|confirmed',
    ]);

    if (!Hash::check($request->password_lama, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Password lama tidak sesuai!'],422);
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    LogActivity::log('update', 'Mengganti password sendiri', 'User', $user->id);
    return response()->json([
        'success'   =>true,
        'message'   =>'password berhasil di ubah'
        ]);
    }

    //riwayat kehadiran
    public function riwayat(Request $request)
    {
    
    $member = $request->user()->member;
    
    if(!$member){
        return response()->json([
            'success' => true,
            'data'   =>[]
        ]);
    }

    $attendance = Attendance::with('event')
        ->where('member_id', $member->id)
        ->latest()
        ->get()
        ->map(function ($attendance){
            return [
                'event'         =>$Attendance->event->nama_event ?? '-',
                'tanggal'       =>$attendance->event->tanngal ?? '-',
                'waktu_scan'    =>$attendance->waktu_scan,
                'status'        =>$attendance->status
            ];
        });
        return response()->json([
            'success'   =>true,
            'data'      =>$attendance
        ]);
    }

    //list Event hari ini untuk scan
    public function events(Request $request)
    {
        $events = Event::whereDate('tanggal', today())
            ->get()
            ->map(function ($event){
                return [
                    'id'         => $event->id,
                    'nama_event' => $event->nama_event,
                    'lokasi'     => $event->lokasi,
                    'tanggal'    => $event->tanggal,
                    'has_active_session' => $event->activeSession ? true : false,
                ];
            });
        return response()->json([
        'success' => true,
        'data'    => $events,
        ]);
    }

    // Scan absensi
    public function scan(Request $request, string $token)
    {
        $session = EventSession::where('token', $token)->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid!',
            ], 404);
        }

        if (!$session->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi absensi sudah ditutup!',
            ], 400);
        }

        if ($session->expired_at->isPast()) {
            $session->update(['is_active' => false]);
            return response()->json([
                'success' => false,
                'message' => 'QR Code sudah expired!',
            ], 400);
        }

        $member = $request->user()->member;

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Data anggota tidak ditemukan!',
            ], 404);
        }

        $existing = Attendance::where('event_id', $session->event_id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah tercatat hadir di event ini!',
            ], 400);
        }

        Attendance::create([
            'event_id'   => $session->event_id,
            'member_id'  => $member->id,
            'waktu_scan' => now(),
            'status'     => 'hadir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Kehadiran kamu sudah tercatat.',
            'event'   => $session->event->nama_event,
        ]);
    }
}
