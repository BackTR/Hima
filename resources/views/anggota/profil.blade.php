<x-app-sidebar>
    <x-slot name="header">Profil Saya</x-slot>
    <x-slot name="breadcrumb">Home / Profil</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Info Akun -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Info Akun</h3>

            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Nama</p>
                    <p class="text-gray-200">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Email</p>
                    <p class="text-gray-200">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Role</p>
                    <p class="text-gray-200 capitalize">{{ $user->role }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('anggota.editprofil') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                ✏️ Edit Profil
                </a>
                <a href="{{ route('anggota.gantipassword') }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                    🔑 Ganti Password
                </a>
            </div>
        </div>


        <!-- Info Member -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Info Anggota</h3>

            @if($member)
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">NIM</p>
                        <p class="text-gray-200">{{ $member->nim ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Angkatan</p>
                        <p class="text-gray-200">{{ $member->angkatan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Divisi</p>
                        <p class="text-gray-200">{{ $member->divisi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">No. HP</p>
                        <p class="text-gray-200">{{ $member->no_hp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Alamat</p>
                        <p class="text-gray-200">{{ $member->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status Keanggotaan</p>
                        <span class="px-2 py-1 rounded text-sm
                            @if($member->status == 'aktif') bg-green-900 text-green-300
                            @elseif($member->status == 'alumni') bg-yellow-900 text-yellow-300
                            @else bg-red-900 text-red-300
                            @endif">
                            {{ $member->status }}
                        </span>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Data anggota belum tersedia.</p>
            @endif
        </div>

    </div>

</x-app-sidebar>