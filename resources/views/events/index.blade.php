<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Event
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
                    <h3 class="text-lg font-semibold">Total: {{ $events->total() }} Event</h3>
                    <a href="{{ route('events.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        + Buat Event
                    </a>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border">Nama Event</th>
                            <th class="p-3 border">Tanggal</th>
                            <th class="p-3 border">Lokasi</th>
                            <th class="p-3 border">Dibuat Oleh</th>
                            <th class="p-3 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border">{{ $event->nama_event }}</td>
                            <td class="p-3 border">{{ \Carbon\Carbon::parse($event->tanggal)->format('d/m/Y') }}</td>
                            <td class="p-3 border">{{ $event->lokasi ?? '-' }}</td>
                            <td class="p-3 border">{{ $event->creator->name ?? '-' }}</td>
                            <td class="p-3 border">
                                <a href="{{ route('events.show', $event->id) }}" class="text-green-500 hover:underline mr-2">Detail</a>
                                <a href="{{ route('events.edit', $event->id) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Yakin hapus event ini?')">Hapus</button>
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
        </div>
    </div>
</x-app-layout>