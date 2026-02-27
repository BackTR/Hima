<x-app-sidebar>
    <x-slot name="header">Detail Event</x-slot>
    <x-slot name="breadcrumb">Home / Event / Detail</x-slot>

    <!-- Info Event -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
        <h3 class="text-xl font-bold text-white mb-4">{{ $event->nama_event }}</h3>
        <p class="text-gray-400 mb-2">📅 {{ \Carbon\Carbon::parse($event->tanggal)->format('d/m/Y') }}</p>
        <p class="text-gray-400 mb-2">📍 {{ $event->lokasi ?? '-' }}</p>
        <p class="text-gray-400 mb-2">👤 Dibuat oleh: {{ $event->creator->name ?? '-' }}</p>
        <p class="text-gray-400 mt-4">{{ $event->deskripsi ?? '-' }}</p>

        <div class="flex gap-2 mt-6">
            <a href="{{ route('attendances.index', $event->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Kelola Absensi
            </a>
            <a href="{{ route('events.edit', $event->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Edit Event
            </a>
            <a href="{{ route('events.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </div>

    <!-- Daftar Kehadiran -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Daftar Kehadiran ({{ $event->attendances->count() }})</h3>

        @if($event->attendances->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="p-3 border border-gray-600 text-gray-300">Nama</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Waktu Scan</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->attendances as $attendance)
                    <tr class="hover:bg-gray-700 border-b border-gray-700">
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $attendance->member->user->name ?? '-' }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $attendance->waktu_scan ? \Carbon\Carbon::parse($attendance->waktu_scan)->format('d/m/Y H:i') : '-' }}</td>
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
            <p class="text-gray-500">Belum ada kehadiran tercatat.</p>
        @endif
    </div>

</x-app-sidebar>