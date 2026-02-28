<x-app-sidebar>
    <x-slot name="header">Scan Absensi QR</x-slot>
    <x-slot name="breadcrumb">Home / Scan Absensi</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 text-center">

            <p class="text-gray-400 mb-6">Arahkan kamera ke QR Code absensi</p>

            <!-- Video Camera -->
            <div class="relative mb-6">
                <video id="video" class="w-full rounded-lg" autoplay playsinline></video>
                <canvas id="canvas" class="hidden"></canvas>

                <!-- Scanning overlay -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-48 border-2 border-indigo-400 rounded-lg opacity-70"></div>
                </div>
            </div>

            <!-- Status -->
            <div id="status" class="text-gray-400 text-sm mb-4">Menginisialisasi kamera...</div>

            <!-- Result -->
            <div id="result" class="hidden p-4 rounded-lg mb-4"></div>

            <!-- Manual input fallback -->
            <div class="mt-6 border-t border-gray-700 pt-4">
                <p class="text-gray-500 text-xs mb-2">Tidak bisa scan? Masukkan token manual:</p>
                <div class="flex gap-2">
                    <input type="text" id="manualToken" placeholder="Masukkan token..."
                        class="flex-1 bg-gray-700 border border-gray-600 rounded p-2 text-white text-sm">
                    <button onclick="submitManual()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                        Submit
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- jsQR library untuk scan QR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js"></script>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const status = document.getElementById('status');
        const result = document.getElementById('result');
        let scanning = true;

        // Start camera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                video.srcObject = stream;
                video.play();
                status.textContent = 'Kamera aktif. Arahkan ke QR Code...';
                requestAnimationFrame(tick);
            })
            .catch(err => {
                status.textContent = 'Tidak bisa akses kamera. Gunakan input manual.';
            });

        function tick() {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    scanning = false;
                    status.textContent = 'QR Code terdeteksi!';
                    processQr(code.data);
                    return;
                }
            }

            requestAnimationFrame(tick);
        }

        function processQr(url) {
            // Ekstrak token dari URL
            const token = url.split('/absen/')[1];
            if (token) {
                submitToken(token);
            } else {
                showResult('error', 'QR Code tidak valid!');
                setTimeout(() => { scanning = true; requestAnimationFrame(tick); }, 3000);
            }
        }

        function submitToken(token) {
            status.textContent = 'Memproses...';
            window.location.href = '/absen/' + token;
        }

        function submitManual() {
            const token = document.getElementById('manualToken').value.trim();
            if (!token) {
                alert('Token tidak boleh kosong!');
                return;
            }
            submitToken(token);
        }

        function showResult(type, message) {
            result.classList.remove('hidden');
            if (type === 'success') {
                result.className = 'p-4 rounded-lg mb-4 bg-green-900 text-green-300';
            } else {
                result.className = 'p-4 rounded-lg mb-4 bg-red-900 text-red-300';
            }
            result.textContent = message;
        }
    </script>

</x-app-sidebar>