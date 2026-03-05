<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    protected MemberService $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

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
            'angkatan' => 'nullable|digits:2',
        ]);

        $this->memberService->create($request->all());

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $user = User::with(['member', 'member.kaderisasi', 'member.attendances.event'])
            ->findOrFail($id);

        return view('members.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::with('member')->findOrFail($id);
        return view('members.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!in_array($currentUser->role, ['superadmin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'superadmin' && $currentUser->role !== 'superadmin') {
            return redirect()->route('members.index')->with('error', 'Kamu tidak bisa mengedit superadmin!');
        }

        if ($currentUser->id === $user->id && $request->role !== $currentUser->role) {
            return redirect()->route('members.edit', $id)->with('error', 'Kamu tidak bisa mengubah role dirinya sendiri!');
        }

        if ($currentUser->role === 'admin' && $request->role === 'superadmin') {
            return redirect()->route('members.edit', $id)->with('error', 'Kamu tidak bisa menetapkan role superadmin!');
        }

        $request->validate([
            'name'     => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email'    => 'required|email:rfc,dns|unique:users,email,' . $user->id,
            'role'     => 'required|in:superadmin,admin,pengurus,anggota',
            'no_hp'    => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'alamat'   => 'nullable|string|max:500',
            'divisi'   => 'nullable|string|max:100',
            'nim'      => 'nullable|string|max:20',
            'angkatan' => 'nullable|digits:2',
        ]);

        $this->memberService->update($user, $request->all());

        return redirect()->route('members.index')->with('success', 'Anggota berhasil diupdate!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!in_array($currentUser->role, ['superadmin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'superadmin') {
            return redirect()->route('members.index')->with('error', 'Superadmin tidak dapat dihapus!');
        }

        if ($user->id === $currentUser->id) {
            return redirect()->route('members.index')->with('error', 'Kamu tidak dapat menghapus akun sendiri!');
        }

        if ($currentUser->role === 'admin' && $user->role === 'admin') {
            return redirect()->route('members.index')->with('error', 'Admin tidak bisa menghapus admin lain!');
        }

        $this->memberService->delete($user);

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus!');
    }

    public function resetPassword(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!in_array($currentUser->role, ['superadmin', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'superadmin' && $currentUser->role !== 'superadmin') {
            return redirect()->route('members.index')->with('error', 'Kamu tidak bisa reset password superadmin!');
        }

        if ($currentUser->role === 'admin' && $user->role === 'admin' && $currentUser->id !== $user->id) {
            return redirect()->route('members.index')->with('error', 'Admin tidak bisa reset password admin lain!');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $this->memberService->resetPassword($user, $request->password);

        return redirect()->route('members.index')->with('success', 'Password berhasil direset!');
    }
}