<x-app-sidebar>
    <x-slot name="header">Scan Absensi</x-slot>
    <x-slot name="breadcrumb">Home / Scan Absensi</x-slot>

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

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

        @if($events->count() > 0)
            <h3 class="text-lg font-semibold text-white mb-4">Event Hari Ini</h3>

            <form action="{{ route('anggota.submitScan') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Pilih Event</label>
                    <select name="event_id" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                        <option value="">-- Pilih Event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->nama_event }} - {{ $event->lokasi ?? '-' }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                    ✅ Scan Hadir
                </button>

            </form>
        @else
            <div class="text-center py-12">
                <p class="text-4xl mb-4">📅</p>
                <p class="text-gray-400 text-lg">Tidak ada event hari ini.</p>
                <p class="text-gray-500 text-sm mt-2">Scan absensi hanya bisa dilakukan pada hari event berlangsung.</p>
            </div>
        @endif

    </div>

</x-app-sidebar>