<x-app-sidebar>
    <x-slot name="header">Absensi - {{ $event->nama_event }}</x-slot>
    <x-slot name="breadcrumb">Home / Event / Absensi</x-slot>

    <!-- Form Input Kehadiran -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Input Kehadiran</h3>

        @if(session('success'))
            <div class="bg-green-900 text-green-300 p-3 rounded mb-4 border border-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-900 text-red-300 p-3 rounded mb-4 border border-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('attendances.store', $event->id) }}" method="POST" class="flex gap-3">
            @csrf

            <select name="member_id" class="bg-gray-700 border border-gray-600 rounded p-2 text-white flex-1" required>
                <option value="">-- Pilih Anggota --</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}">{{ $member->user->name }} ({{ $member->divisi ?? '-' }})</option>
                @endforeach
            </select>

            <select name="status" class="bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                <option value="hadir">Hadir</option>
                <option value="izin">Izin</option>
            </select>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Catat
            </button>
        </form>
    </div>

    <!-- Daftar Kehadiran -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Daftar Kehadiran ({{ $attendances->count() }})</h3>

        @if($attendances->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="p-3 border border-gray-600 text-gray-300">Nama</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Divisi</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Waktu Scan</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Status</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr class="hover:bg-gray-700 border-b border-gray-700">
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $attendance->member->user->name ?? '-' }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $attendance->member->divisi ?? '-' }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ \Carbon\Carbon::parse($attendance->waktu_scan)->format('d/m/Y H:i') }}</td>
                        <td class="p-3 border border-gray-700">
                            <span class="px-2 py-1 rounded text-sm {{ $attendance->status == 'hadir' ? 'bg-green-900 text-green-300' : 'bg-yellow-900 text-yellow-300' }}">
                                {{ $attendance->status }}
                            </span>
                        </td>
                        <td class="p-3 border border-gray-700">
                            <form action="{{ route('attendances.destroy', [$event->id, $attendance->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline" onclick="return confirm('Yakin hapus kehadiran ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Belum ada kehadiran tercatat.</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('events.show', $event->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                Kembali ke Detail Event
            </a>
        </div>
    </div>

</x-app-sidebar>