<x-app-sidebar>
    <x-slot name="header">Dashboard</x-slot>
    <x-slot name="breadcrumb">Home / Dashboard</x-slot>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-sm text-gray-400">Total Anggota</p>
            <p class="text-3xl font-bold text-indigo-400 mt-1">{{ $stats['total_anggota'] }}</p>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-sm text-gray-400">Total Pengurus</p>
            <p class="text-3xl font-bold text-green-400 mt-1">{{ $stats['total_pengurus'] }}</p>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-sm text-gray-400">Total Admin</p>
            <p class="text-3xl font-bold text-purple-400 mt-1">{{ $stats['total_admin'] }}</p>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-sm text-gray-400">Anggota Aktif</p>
            <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $stats['anggota_aktif'] }}</p>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-sm text-gray-400">Anggota Alumni</p>
            <p class="text-3xl font-bold text-yellow-400 mt-1">{{ $stats['anggota_alumni'] }}</p>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <p class="text-sm text-gray-400">Anggota Nonaktif</p>
            <p class="text-3xl font-bold text-red-400 mt-1">{{ $stats['anggota_nonaktif'] }}</p>
        </div>

    </div>

    <!-- Info User -->
    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
        <p class="text-gray-300">Selamat datang, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>!</p>
        <p class="text-sm text-gray-500 mt-1">Role: <span class="capitalize text-indigo-400">{{ Auth::user()->role }}</span></p>
    </div>

</x-app-sidebar>