<x-app-sidebar>
    <x-slot name="header">Riwayat Kehadiran</x-slot>
    <x-slot name="breadcrumb">Home / Riwayat Kehadiran</x-slot>

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

        <h3 class="text-lg font-semibold text-white mb-4">Riwayat Kehadiran Saya</h3>

        @if($attendances->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="p-3 border border-gray-600 text-gray-300">Event</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Tanggal Event</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Waktu Scan</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
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

            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        @else
            <p class="text-gray-500">Belum ada riwayat kehadiran.</p>
        @endif

    </div>

</x-app-sidebar>