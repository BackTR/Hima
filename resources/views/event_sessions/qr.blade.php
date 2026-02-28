<x-app-sidebar>
    <x-slot name="header">QR Absensi - {{ $session->event->nama_event }}</x-slot>
    <x-slot name="breadcrumb">Home / Event / QR Absensi</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 text-center">

            <h3 class="text-xl font-bold text-white mb-2">{{ $session->event->nama_event }}</h3>
            <p class="text-gray-400 mb-6">Scan QR Code untuk absensi</p>

            <!-- QR Code -->
            <div class="bg-white p-4 rounded-lg inline-block mb-6">
                {!! QrCode::size(250)->generate(route('event-sessions.scan', $session->token)) !!}
            </div>

            <!-- Timer -->
            <div class="mb-6">
                <p class="text-gray-400 text-sm mb-2">QR Code expired dalam:</p>
                <div id="timer" class="text-3xl font-bold text-indigo-400"></div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                @if($session->isValid())
                    <span class="px-3 py-1 bg-green-900 text-green-300 rounded-full text-sm">● Aktif</span>
                @else
                    <span class="px-3 py-1 bg-red-900 text-red-300 rounded-full text-sm">● Expired</span>
                @endif
            </div>

            <!-- Tombol Tutup -->
            <form action="{{ route('event-sessions.stop', $session->event_id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700"
                    onclick="return confirm('Yakin tutup sesi absensi?')">
                    🔒 Tutup Absensi
                </button>
            </form>

            <div class="mt-4">
                <a href="{{ route('events.show', $session->event_id) }}" class="text-gray-400 hover:text-gray-300 text-sm">
                    Kembali ke Detail Event
                </a>
            </div>

        </div>
    </div>

    <script>
        // Countdown timer
        const expiredAt = new Date("{{ $session->expired_at->toISOString() }}");

        function updateTimer() {
            const now = new Date();
            const diff = expiredAt - now;

            if (diff <= 0) {
                document.getElementById('timer').innerHTML = '<span class="text-red-400">Expired!</span>';
                setTimeout(() => location.reload(), 2000);
                return;
            }

            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('timer').textContent =
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    </script>

</x-app-sidebar>