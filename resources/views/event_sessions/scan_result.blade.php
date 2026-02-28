<x-app-sidebar>
    <x-slot name="header">Hasil Scan</x-slot>
    <x-slot name="breadcrumb">Home / Scan Absensi / Hasil</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-8 text-center">

            @if($status == 'success')
                <div class="text-6xl mb-4">✅</div>
                <h3 class="text-2xl font-bold text-green-400 mb-2">Berhasil!</h3>
                <p class="text-gray-300 mb-2">{{ $message }}</p>
                @isset($event)
                    <p class="text-gray-400 text-sm">Event: {{ $event->nama_event }}</p>
                @endisset

            @elseif($status == 'warning')
                <div class="text-6xl mb-4">⚠️</div>
                <h3 class="text-2xl font-bold text-yellow-400 mb-2">Perhatian!</h3>
                <p class="text-gray-300">{{ $message }}</p>

            @else
                <div class="text-6xl mb-4">❌</div>
                <h3 class="text-2xl font-bold text-red-400 mb-2">Gagal!</h3>
                <p class="text-gray-300">{{ $message }}</p>
            @endif

            <div class="mt-8">
                <a href="{{ route('anggota.riwayat') }}" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                    Lihat Riwayat Kehadiran
                </a>
            </div>

        </div>
    </div>

</x-app-sidebar>