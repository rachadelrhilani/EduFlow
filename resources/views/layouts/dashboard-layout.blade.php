<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduFlow - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-roboto">

    <div class="flex min-h-screen" id="dashboard-app">
        <aside class="w-64 bg-[#1E3A8A] text-white hidden md:flex flex-col shadow-lg">
            <div class="p-8 text-2xl font-poppins font-bold tracking-tight">
                EduFlow
            </div>
            
            <nav class="flex-1 px-4 space-y-2 mt-4" id="sidebar-menu">
                </nav>

            <div class="p-6 border-t border-blue-800">
                <button onclick="logout()" class="flex items-center text-red-300 hover:text-white transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Déconnexion
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-8">
                <h2 class="text-xl font-poppins font-semibold text-gray-800" id="current-page-title">
                    @yield('title', 'Tableau de bord')
                </h2>
                <div class="flex items-center space-x-4">
                    <span id="user-display-name" class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                        Chargement...
                    </span>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                @yield('dashboard-content')
            </div>
        </main>
    </div>

</body>
</html>