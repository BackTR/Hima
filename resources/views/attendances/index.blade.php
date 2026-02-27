<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Absensi - {{ $event->nama_event }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Form Input Kehadiran -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Input Kehadiran</h3>

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('attendances.store', $event->id) }}" method="POST" class="flex gap-3">
                    @csrf

                    <select name="member_id" class="border rounded p-2 flex-1" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->user->name }} ({{ $member->divisi ?? '-' }})</option>
                        @endforeach
                    </select>

                    <select name="status" class="border rounded p-2" required>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                    </select>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Catat
                    </button>
                </form>
            </div>

            <!-- Daftar Kehadiran -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Daftar Kehadiran ({{ $attendances->count() }})</h3>

                @if($attendances->count() > 0)
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 border">Nama</th>
                                <th class="p-3 border">Divisi</th>
                                <th class="p-3 border">Waktu Scan</th>
                                <th class="p-3 border">Status</th>
                                <th class="p-3 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border">{{ $attendance->member->user->name ?? '-' }}</td>
                                <td class="p-3 border">{{ $attendance->member->divisi ?? '-' }}</td>
                                <td class="p-3 border">{{ \Carbon\Carbon::parse($attendance->waktu_scan)->format('d/m/Y H:i') }}</td>
                                <td class="p-3 border">
                                    <span class="px-2 py-1 rounded text-sm {{ $attendance->status == 'hadir' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td class="p-3 border">
                                    <form action="{{ route('attendances.destroy', [$event->id, $attendance->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Yakin hapus kehadiran ini?')">Hapus</button>
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
                    <a href="{{ route('events.show', $event->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Kembali ke Detail Event
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>