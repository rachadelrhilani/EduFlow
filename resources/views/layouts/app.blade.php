<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EduFlow') }} - Gestion Pédagogique</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Roboto', sans-serif; }
        h1, h2, h3, .font-poppins { font-family: 'Poppins', sans-serif; }
        
        /* Anti-flash : Masquer les éléments auth avant le check JS */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col h-full antialiased">

    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-[#1E3A8A] font-poppins tracking-tight">
                        Edu<span class="text-[#2563EB]">Flow</span>
                    </a>
                </div>

                <div class="flex items-center space-x-6">
                    
                    <div id="auth-nav" class="flex items-center space-x-4 border-l pl-6 border-gray-200">
                        <a href="/login" class="text-gray-600 hover:text-[#2563EB] text-sm font-medium">Connexion</a>
                        <a href="/register" class="bg-[#2563EB] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-[#1E3A8A] transition-all shadow-sm">
                            S'inscrire
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-[#1E3A8A] font-poppins font-bold text-lg">
                Edu<span class="text-[#2563EB]">Flow</span>
            </div>
            <div class="text-gray-400 text-sm font-light">
                &copy; {{ date('Y') }} Plateforme Pédagogique. Tous droits réservés.
            </div>
            <div class="flex space-x-4 text-gray-500 text-sm">
                <a href="#" class="hover:text-[#2563EB]">Conditions</a>
                <a href="#" class="hover:text-[#2563EB]">Contact</a>
            </div>
        </div>
    </footer>

</body>
</html>