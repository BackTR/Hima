<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 min-h-screen flex flex-col fixed top-0 left-0">

        <!-- Logo -->
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold text-white">🏢 Sistem Anggota</h1>
            <p class="text-xs text-gray-400 mt-1">{{ Auth::user()->role }}</p>
        </div>

        <!-- Menu -->
        <nav class="flex-1 p-4 space-y-1">

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                📊 Dashboard
            </a>

            @if(in_array(Auth::user()->role, ['superadmin', 'admin', 'pengurus']))
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('events.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    📅 Event
                </a>
            @endif

            @if(in_array(Auth::user()->role, ['superadmin', 'admin']))
                <a href="{{ route('members.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('members.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    👥 Anggota
                </a>
                <a href="{{ route('kaderisasi.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('kaderisasi.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    🎓 Kaderisasi
                </a>
                <a href="{{ route('activity-log.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('activity-log.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    📋 Activity Log
                </a>
            @endif

        </nav>

        <!-- User Info & Logout -->
        <div class="p-4 border-t border-gray-700">
            <p class="text-sm text-gray-300 font-semibold">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500 mb-3">{{ Auth::user()->email }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-red-400 hover:text-red-300">
                    🚪 Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <div class="ml-64 flex-1 flex flex-col min-h-screen">

        <!-- Top Bar -->
        <header class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center">
            <div>
                <!-- Breadcrumb -->
                @isset($breadcrumb)
                    <nav class="text-sm text-gray-400">
                        {{ $breadcrumb }}
                    </nav>
                @endisset
            </div>
            <div class="text-sm text-gray-400">
                {{ now()->format('d F Y') }}
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            @isset($header)
                <h2 class="text-xl font-bold text-white mb-6">{{ $header }}</h2>
            @endisset

            {{ $slot }}
        </main>

    </div>

</body>
</html>