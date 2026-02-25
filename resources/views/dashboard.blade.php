<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Total Anggota</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_anggota'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Total Pengurus</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_pengurus'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Total Admin</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_admin'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Anggota Aktif</p>
                    <p class="text-3xl font-bold text-emerald-600">{{ $stats['anggota_aktif'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Anggota Alumni</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['anggota_alumni'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-500">Anggota Nonaktif</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['anggota_nonaktif'] }}</p>
                </div>

            </div>

            <!-- Info User Login -->
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-700">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
                <p class="text-sm text-gray-500 mt-1">Role: <span class="capitalize">{{ Auth::user()->role }}</span></p>
            </div>

        </div>
    </div>
</x-app-layout>