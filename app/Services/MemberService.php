<?php

namespace App\Services;

use App\Helpers\LogActivity;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberService
{
    public function create(array $data): User
    {
        return DB::transaction(function() use ($data){
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'role'      => $data['role'],
                'is_active' => true,
            ]);

            Member::create([
                'user_id'  => $user->id,
                'nim'      => $data['nim'] ?? null,
                'angkatan' => $data['angkatan'] ?? null,
                'divisi'   => $data['divisi'] ?? null,
                'no_hp'    => $data['no_hp'] ?? null,
                'alamat'   => $data['alamat'] ?? null,
                'status'   => 'aktif',
            ]);
            LogActivity::log('create', 'menambah anggota baru:' . $data['name'], 'User');
            return $user;

        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function() use($user, $data){
            $user->update([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'role'      => $data['role'],
                'is_active' => $data['is_active'] == '1' ? true : false,
            ]);
            $memberStatus = $data['is_active'] == '1' ? 'aktif' : 'nonaktif';

        $user->member()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nim'      => $data['nim'] ?? null,
                    'angkatan' => $data['angkatan'] ?? null,
                    'divisi'   => $data['divisi'] ?? null,
                    'no_hp'    => $data['no_hp']  ?? null,
                    'alamat'   => $data['alamat'] ?? null,
                    'status'   => $memberStatus
                ]
            );
            LogActivity::log('update', 'Mengupdate anggota: ' . $user->name, 'User', $user->id);
            return $user;
        });
    }

    public function delete(User $user): void
    {
            DB::transaction(function () use ($user) {
            LogActivity::log('delete', 'Menghapus anggota: ' . $user->name, 'User', $user->id);
            $user->member()->delete();
            $user->delete();
        });
    }

    public function resetPassword(User $user, string $password): void
    {
        $user->update(['password' => Hash::make($password)]);
        LogActivity::log('update', 'Mereset password anggota: ' . $user->name, 'User', $user->id);
    }
}