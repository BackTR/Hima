<x-app-sidebar>
    <x-slot name="header">Detail Anggota</x-slot>
    <x-slot name="breadcrumb">Home / Anggota / Detail</x-slot>

    <!-- Tombol Aksi -->
    @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
    <div class="flex gap-2 mb-6">
        <a href="{{ route('members.edit', $user->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            ✏️ Edit
        </a>
        <button onclick="openResetModal({{ $user->id }}, '{{ $user->name }}')"
            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
            🔑 Reset Password
        </button>
        <a href="{{ route('members.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            Kembali
        </a>
    </div>
    @else
    <div class="flex gap-2 mb-6">
        <a href="{{ route('members.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            Kembali
        </a>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

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
                    <p class="text-xs text-gray-500">Status Akun</p>
                    <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Member -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Info Anggota</h3>
            @if($user->member)
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">NIM</p>
                    <p class="text-gray-200">{{ $user->member->nim ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Angkatan</p>
                    <p class="text-gray-200">{{ $user->member->angkatan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Divisi</p>
                    <p class="text-gray-200">{{ $user->member->divisi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">No. HP</p>
                    <p class="text-gray-200">{{ $user->member->no_hp ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Alamat</p>
                    <p class="text-gray-200">{{ $user->member->alamat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Status Keanggotaan</p>
                    <span class="px-2 py-1 rounded text-sm
                        @if($user->member->status == 'aktif') bg-green-900 text-green-300
                        @elseif($user->member->status == 'alumni') bg-yellow-900 text-yellow-300
                        @else bg-red-900 text-red-300
                        @endif">
                        {{ $user->member->status }}
                    </span>
                </div>
            </div>
            @else
                <p class="text-gray-500">Data anggota belum tersedia.</p>
            @endif
        </div>

    </div>

    <!-- Kaderisasi -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">🎓 Kaderisasi</h3>
        @if($user->member && $user->member->kaderisasi)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Level</p>
                    <p class="text-gray-200">{{ $user->member->kaderisasi->level }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <span class="px-2 py-1 rounded text-sm
                        @if($user->member->kaderisasi->status == 'lulus') bg-green-900 text-green-300
                        @elseif($user->member->kaderisasi->status == 'proses') bg-yellow-900 text-yellow-300
                        @else bg-red-900 text-red-300
                        @endif">
                        {{ $user->member->kaderisasi->status }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Catatan</p>
                    <p class="text-gray-200">{{ $user->member->kaderisasi->catatan ?? '-' }}</p>
                </div>
            </div>
        @else
            <p class="text-gray-500">Belum ada data kaderisasi.</p>
        @endif
    </div>

    <!-- Riwayat Kehadiran -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">📝 Riwayat Kehadiran</h3>
        @if($user->member && $user->member->attendances->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="p-3 border border-gray-600 text-gray-300">Event</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Tanggal</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Waktu Scan</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->member->attendances as $attendance)
                    <tr class="hover:bg-gray-700 border-b border-gray-700">
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $attendance->event->nama_event ?? '-' }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ \Carbon\Carbon::parse($attendance->event->tanggal)->format('d/m/Y') }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ \Carbon\Carbon::parse($attendance->waktu_scan)->format('d/m/Y H:i') }}</td>
                        <td class="p-3 border border-gray-700">
                            <span class="px-2 py-1 rounded text-sm {{ $attendance->status == 'hadir' ? 'bg-green-900 text-green-300' : 'bg-yellow-900 text-yellow-300' }}">
                                {{ $attendance->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Belum ada riwayat kehadiran.</p>
        @endif
    </div>

    <!-- Modal Reset Password -->
    @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
    <div id="resetModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-2xl w-full max-w-sm mx-4">
            <div class="flex justify-between items-center p-5 border-b border-gray-700">
                <div>
                    <h3 class="text-base font-semibold text-white">Reset Password</h3>
                    <p class="text-xs text-gray-400 mt-1">User: <span id="resetName" class="text-indigo-400"></span></p>
                </div>
                <button onclick="closeResetModal()" class="text-gray-400 hover:text-white text-xl">✕</button>
            </div>
            <form id="resetForm" method="POST" class="p-5">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-400 mb-1">Password Baru</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter"
                        class="block w-full bg-gray-700 border border-gray-600 rounded-lg p-2.5 text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-400 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                        class="block w-full bg-gray-700 border border-gray-600 rounded-lg p-2.5 text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-yellow-600 text-white py-2 rounded-lg hover:bg-yellow-700 text-sm font-medium">
                        Reset Password
                    </button>
                    <button type="button" onclick="closeResetModal()" class="flex-1 bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openResetModal(id, name) {
            document.getElementById('resetName').textContent = name;
            document.getElementById('resetForm').action = '/members/' + id + '/reset-password';
            document.getElementById('resetModal').classList.remove('hidden');
        }
        function closeResetModal() {
            document.getElementById('resetModal').classList.add('hidden');
        }
    </script>
    @endif

</x-app-sidebar>