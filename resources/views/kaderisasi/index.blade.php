<x-app-sidebar>
    <x-slot name="header">Kaderisasi</x-slot>
    <x-slot name="breadcrumb">Home / Kaderisasi</x-slot>

    @if(session('success'))
        <div class="bg-green-900 text-green-300 p-3 rounded mb-4 border border-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Total: {{ $kaderisasi->total() }} Data</h3>
            <a href="{{ route('kaderisasi.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Tambah Kaderisasi
            </a>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-700">
                    <th class="p-3 border border-gray-600 text-gray-300">Nama Anggota</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Level</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Status</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Catatan</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kaderisasi as $item)
                <tr class="hover:bg-gray-700 border-b border-gray-700">
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $item->member->user->name ?? '-' }}</td>
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $item->level }}</td>
                    <td class="p-3 border border-gray-700">
                        <span class="px-2 py-1 rounded text-sm
                            @if($item->status == 'lulus') bg-green-900 text-green-300
                            @elseif($item->status == 'proses') bg-yellow-900 text-yellow-300
                            @else bg-red-900 text-red-300
                            @endif">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $item->catatan ?? '-' }}</td>
                    <td class="p-3 border border-gray-700">
                        <a href="{{ route('kaderisasi.edit', $item->id) }}" class="text-indigo-400 hover:underline mr-2">Edit</a>
                        <form action="{{ route('kaderisasi.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:underline" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $kaderisasi->links() }}
        </div>

    </div>

</x-app-sidebar>