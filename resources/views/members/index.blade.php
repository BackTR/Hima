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

</x-app-sidebar>