<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Anggota
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-semibold">Total: {{ $members->total() }} Anggota</h3>
                    <a href="{{ route('members.create') }}" class="bg-blue-500 text-blue px-4 py-2 rounded hover:bg-blue-600">
                        + Tambah Anggota
                    </a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border">Nama</th>
                            <th class="p-3 border">Email</th>
                            <th class="p-3 border">Role</th>
                            <th class="p-3 border">Divisi</th>
                            <th class="p-3 border">Status</th>
                            <th class="p-3 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border">{{ $user->name }}</td>
                            <td class="p-3 border">{{ $user->email }}</td>
                            <td class="p-3 border">{{ $user->role }}</td>
                            <td class="p-3 border">{{ $user->member->divisi ?? '-' }}</td>
                            <td class="p-3 border">
                                <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="p-3 border">
                                <a href="{{ route('members.edit', $user->id) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
                                <form action="{{ route('members.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Yakin hapus anggota ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $members->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>