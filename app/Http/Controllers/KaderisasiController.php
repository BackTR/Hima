<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Kaderisasi;
use App\Models\Member;
use Illuminate\Http\Request;

class KaderisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kaderisasi = Kaderisasi::with('member.user')
        ->latest()
        ->paginate(10);

        return view('kaderisasi.index', compact('kaderisasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::with('user')->where('status', 'aktif')->get();
        return view('kaderisasi.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'level'     => 'required|string|max:100',
            'status'    => 'required|in:proses,lulus,gagal',
            'catatan'   => 'nullable|string|max:500',
        ]);

        Kaderisasi::create([
            'member_id' => $request->member_id,
            'level'     => $request->level,
            'status'    => $request->status,
            'catatan'   => $request->catatan,
        ]);

        LogActivity::log('create', 'Menambah kaderisasi member', 'Kaderisasi');

        return redirect()->route('kaderisasi.index')->with('success', 'Kaderisasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kaderisasi = Kaderisasi::findOrFail($id);
        $members = Member::with('user')->where('status', 'aktif')->get();
        return view('kaderisasi.edit', compact('kaderisasi', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kaderisasi = Kaderisasi::findOrFail($id);
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'level'     => 'required|string|max:100',
            'status'    => 'required|in:proses,lulus,gagal',
            'catatan'   => 'nullable|string|max:500',
        ]);

        $kaderisasi->update([
            'member_id' => $request->member_id,
            'level'     => $request->level,
            'status'    => $request->status,
            'catatan'   => $request->catatan,
        ]);

        LogActivity::log('update', 'Mengupdate kaderisasi member', 'Kaderisasi', $id);

        return redirect()->route('kaderisasi.index')->with('success', 'Kaderisasi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kaderisasi = Kaderisasi::findOrFail($id);

        LogActivity::log('delete', 'Menghapus kaderisasi member', 'Kaderisasi', $id);

        $kaderisasi->delete();

        return redirect()->route('kaderisasi.index')->with('success', 'Kaderisasi berhasil dihapus!');
    }
}
