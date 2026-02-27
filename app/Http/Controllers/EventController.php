<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('creator')
        ->latest()
        ->paginate(10);
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal'    => 'required|date|after_or_equal:today',
            'lokasi'     => 'nullable|string|max:255',
            'deskripsi'  => 'nullable|string|max:1000',
        ]);

        $event = Event::create([
            'nama_event'    => $request->nama_event,
            'tanggal'       => $request->tanggal,
            'lokasi'        => $request->lokasi,
            'deskripsi'     =>$request->deskripsi,
            'created_by'    => Auth::id()
        ]);

        LogActivity::log('create', 'Membuat event:' .$event->nama_event, 'Event', $event->id );
    
        return redirect()->route('events.index')->with('success', 'event berhasil di buat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with(['creator', 'attendances.member.user'])->findOrFail($id);
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal'    => 'required|date|after_or_equal:today',
            'lokasi'     => 'nullable|string|max:255',
            'deskripsi'  => 'nullable|string|max:1000',
        ]);

        $event->update([
            'nama_event' => $request->nama_event,
            'tanggal'    => $request->tanggal,
            'lokasi'     => $request->lokasi,
            'deskripsi'  => $request->deskripsi,
        ]);

        LogActivity::log('update', 'Mengupdate event: ' . $event->nama_event, 'Event', $id);

        return redirect()->route('events.index')->with('success', 'Event berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);

        LogActivity::log('delete', 'Menghapus event: ' . $event->nama_event, 'Event', $id);

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }
    
}
