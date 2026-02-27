<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::with('member')
            ->whereIn('role', ['superadmin', 'admin', 'pengurus', 'anggota'])
            ->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
            $request->validate([
                'name'     => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email'    => 'required|email:rfc,dns|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'role'     => 'required|in:superadmin,admin,pengurus,anggota',
                'no_hp'    => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
                'alamat'   => 'nullable|string|max:500',
                'divisi'   => 'nullable|string|max:100',
                'nim'      => 'nullable|string|max:20',
                'angkatan' => 'nullable|digits:4',
            ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'is_active' => true,
            ]);

            Member::create([
                'user_id'  => $user->id,
                'nim'      => $request->nim,
                'angkatan' => $request->angkatan,
                'divisi'   => $request->divisi,
                'no_hp'    => $request->no_hp,
                'alamat'   => $request->alamat,
                'status'   => 'aktif',
            ]);
        });
        LogActivity::log('create', 'Menambah anggota baru: ' . $request->name, 'User');

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

public function edit(string $id)
{
    $user = User::with('member')->findOrFail($id);
    return view('members.edit', compact('user'));
}

public function update(Request $request, string $id)
{
    $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email'    => 'required|email:rfc,dns|unique:users,email,' . $user->id,
            'role'     => 'required|in:superadmin,admin,pengurus,anggota',
            'no_hp'    => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'alamat'   => 'nullable|string|max:500',
            'divisi'   => 'nullable|string|max:100',
            'nim'      => 'nullable|string|max:20',
            'angkatan' => 'nullable|digits:4',
        ]);

    DB::transaction(function () use ($request, $user) {
$user->update([
    'name'      => $request->name,
    'email'     => $request->email,
    'role'      => $request->role,
    'is_active' => $request->is_active == '1' ? true : false,
]);

        $user->member()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nim'      => $request->nim,
                'angkatan' => $request->angkatan,
                'divisi'   => $request->divisi,
                'no_hp'    => $request->no_hp,
                'alamat'   => $request->alamat,
            ]
        );
    });
    LogActivity::log('update', 'Mengupdate anggota: ' . $user->name, 'User', $id);

    return redirect()->route('members.index')->with('success', 'Anggota berhasil diupdate!');
}

public function destroy(string $id)
{
    $user = User::findOrFail($id);

    DB::transaction(function () use ($user) {
        $user->member()->delete();
        $user->delete();
    });
    LogActivity::log('delete', 'Menghapus anggota: ' . $user->name, 'User', $id);

    return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus!');
}
}