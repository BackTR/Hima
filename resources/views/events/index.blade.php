<x-app-sidebar>
    <x-slot name="header">Daftar Event</x-slot>
    <x-slot name="breadcrumb">Home / Event</x-slot>

    @if(session('success'))
        <div class="bg-green-900 text-green-300 p-3 rounded mb-4 border border-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Total: {{ $events->total() }} Event</h3>
            <a href="{{ route('events.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Buat Event
            </a>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-700">
                    <th class="p-3 border border-gray-600 text-gray-300">Nama Event</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Tanggal</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Lokasi</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Dibuat Oleh</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr class="hover:bg-gray-700 border-b border-gray-700">
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $event->nama_event }}</td>
                    <td class="p-3 border border-gray-700 text-gray-200">{{ \Carbon\Carbon::parse($event->tanggal)->format('d/m/Y') }}</td>
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $event->lokasi ?? '-' }}</td>
                    <td class="p-3 border border-gray-700 text-gray-200">{{ $event->creator->name ?? '-' }}</td>
                    <td class="p-3 border border-gray-700">
                        <a href="{{ route('events.show', $event->id) }}" class="text-green-400 hover:underline mr-2">Detail</a>
                        <a href="{{ route('events.edit', $event->id) }}" class="text-indigo-400 hover:underline mr-2">Edit</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:underline" onclick="return confirm('Yakin hapus event ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $events->links() }}
        </div>

    </div>

</x-app-sidebar>