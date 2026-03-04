<x-app-sidebar>
    <x-slot name="header">Daftar Anggota</x-slot>
    <x-slot name="breadcrumb">Home / Anggota</x-slot>

    @if(session('success'))
        <div class="bg-green-900 text-green-300 p-3 rounded mb-4 border border-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Total: {{ $members->total() }} Anggota</h3>
            @if (in_array(Auth::user()->role, ['superadmin', 'admin']))  
            <a href="{{ route('members.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Tambah Anggota
            </a>
            @endif
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-700">
                    <th class="p-3 border border-gray-600 text-gray-300">Nama</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Email</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Role</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Divisi</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Status</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $user)
                <tr class="hover:bg-gray-700 border-b border-gray-700">
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $user->name }}</td>
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $user->email }}</td>
                    <td class="p-3 border border-gray-700 text-gray-200 capitalize">{{ $user->role }}</td>
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $user->member->divisi ?? '-' }}</td>
                    <td class="p-3 border border-gray-700">
                        <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="p-3 border border-gray-700">
                        @if (in_array(Auth::user()->role, ['superadmin', 'admin']))
                        <a href="{{ route('members.edit', $user->id) }}" class="text-indigo-400 hover:underline mr-2">Edit</a>
                        <button onclick="openResetModal({{ $user->id }}, '{{ $user->name }}')" 
                            class="text-yellow-400 hover:underline mr-2">
                            Reset PW
                        </button>
                        <form action="{{ route('members.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:underline" onclick="return confirm('Yakin hapus anggota ini?')">Hapus</button>
                        </form>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $members->links() }}
        </div>

    </div>

<!-- Modal Reset Password -->
<div id="resetModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-2xl w-full max-w-sm mx-4">
        
        <!-- Header Modal -->
        <div class="flex justify-between items-center p-5 border-b border-gray-700">
            <div>
                <h3 class="text-base font-semibold text-white">Reset Password</h3>
                <p class="text-xs text-gray-400 mt-1">User: <span id="resetName" class="text-indigo-400"></span></p>
            </div>
            <button onclick="closeResetModal()" class="text-gray-400 hover:text-white text-xl">✕</button>
        </div>

        <!-- Body Modal -->
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

            <!-- Footer Modal -->
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
</x-app-sidebar>