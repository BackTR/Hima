<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //list kehadiran per event
    public function index(string $event_id)
    {
        $event = Event::findOrFail($event_id);
        $members = Member::with('user')->where('status', 'aktif')->get();
        $attendances = Attendance::where('event_id', $event_id)->with('member.user')->get();

        return view('attendances.index', compact('event', 'members', 'attendances'));
    }

    public function store(Request $request, string $event_id)
    {
        $request->validate
        ([
            'member_id' =>  'required|exists:members,id',
            'status'    =>  'required|in:hadir,izin'
        ]);
        $event = Event::findOrFail($event_id);

        //cek sudah absen blum

        $existing = Attendance::where('event_id', $event_id)
        ->where('member_id', $request->member_id)
        ->first();

        if($existing){
            return back()->with('error', 'lu udah absen');
        }
    
        Attendance::create
        ([
            'event_id'  => $event_id,
            'member_id'  => $request->member_id,
            'waktu_scan'  => now(),
            'status'  => $request->status,
        ]);

        LogActivity::log('create','input kehadiran' . $event->nama_event, 'Attendance');
        return back()->with('success', 'kehadiran berhasil di catat');
    }

    //kehadiran di hapus
    public function destroy(string $event_id, string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return back()->with('success', 'kehadiran berhasil di hapus');
    }
}
