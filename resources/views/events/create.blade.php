<x-app-sidebar>
    <x-slot name="header">Buat Event</x-slot>
    <x-slot name="breadcrumb">Home / Event / Buat</x-slot>

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

        @if($errors->any())
            <div class="bg-red-900 text-red-300 p-3 rounded mb-4 border border-red-700">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('events.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400">Nama Event</label>
                    <input type="text" name="nama_event" value="{{ old('nama_event') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                </div>

                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">{{ old('deskripsi') }}</textarea>
                </div>

            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Simpan
                </button>
                <a href="{{ route('events.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Batal
                </a>
            </div>

        </form>
    </div>

</x-app-sidebar>