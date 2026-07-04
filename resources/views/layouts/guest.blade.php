<!DOCTYPE html>
<html lang="id" class="h-dvh overflow-hidden bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasirku') - Web Kasir Modern</title>
    <link rel="icon" href="{{ asset('image/icon.svg') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
        }
        [x-cloak] { display: none !important; }
        
        button, select, a, [type="button"], [type="submit"] {
            cursor: pointer !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-dvh overflow-hidden flex items-center justify-center p-4 antialiased relative bg-gradient-to-tr from-blue-600 via-blue-500 to-indigo-700">
    
    <div class="absolute w-[500px] h-[500px] rounded-full bg-white/10 blur-3xl -top-40 -left-40 pointer-events-none"></div>
    <div class="absolute w-[600px] h-[600px] rounded-full bg-white/10 blur-3xl -bottom-40 -right-40 pointer-events-none"></div>

    <div class="w-full max-w-md z-10" @yield('alpine_data')>
        <div class="text-center mb-4 md:mb-6">
            <img src="{{ asset('image/icon.svg') }}" alt="Logo" class="w-16 h-16 mx-auto object-contain mb-3 bg-white p-2 rounded-2xl shadow-md border border-white/20">
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-wider">KASIRKU</h1>
        </div>

        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
