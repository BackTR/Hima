<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Event
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Info Event -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold mb-4">{{ $event->nama_event }}</h3>
                <p class="text-gray-600 mb-2">📅 {{ \Carbon\Carbon::parse($event->tanggal)->format('d/m/Y') }}</p>
                <p class="text-gray-600 mb-2">📍 {{ $event->lokasi ?? '-' }}</p>
                <p class="text-gray-600 mb-2">👤 Dibuat oleh: {{ $event->creator->name ?? '-' }}</p>
                <p class="text-gray-600 mt-4">{{ $event->deskripsi ?? '-' }}</p>

                <div class="flex gap-2 mt-6">
                    <a href="{{ route('attendances.index', $event->id) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Kelola Absensi
                    </a>
                    <a href="{{ route('events.edit', $event->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Edit Event
                    </a>
                    <a href="{{ route('events.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Daftar Kehadiran -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Daftar Kehadiran ({{ $event->attendances->count() }})</h3>

                @if($event->attendances->count() > 0)
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 border">Nama</th>
                                <th class="p-3 border">Waktu Scan</th>
                                <th class="p-3 border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($event->attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border">{{ $attendance->member->user->name ?? '-' }}</td>
                                <td class="p-3 border">{{ $attendance->waktu_scan ? \Carbon\Carbon::parse($attendance->waktu_scan)->format('d/m/Y H:i') : '-' }}</td>
                                <td class="p-3 border">
                                    <span class="px-2 py-1 rounded text-sm {{ $attendance->status == 'hadir' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">Belum ada kehadiran tercatat.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>