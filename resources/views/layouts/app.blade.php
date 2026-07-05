<!DOCTYPE html>
<html lang="id" class="min-h-screen bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="min-h-screen text-slate-800 antialiased" x-data="{ sidebarOpen: false }">
    
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm pointer-events-none" 
         x-data="{ 
             toasts: [],
             addToast(message, type = 'success') {
                 const id = Date.now();
                 this.toasts.push({ id, message, type });
                 setTimeout(() => { this.removeToast(id) }, 4000);
             },
             removeToast(id) {
                 this.toasts = this.toasts.filter(t => t.id !== id);
             }
         }"
         @toast.window="addToast($event.detail.message, $event.detail.type)"
         x-init="
            @if(session('success'))
                $dispatch('toast', { message: '{{ session('success') }}', type: 'success' });
            @endif
            @if(session('error'))
                $dispatch('toast', { message: '{{ session('error') }}', type: 'error' });
            @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    $dispatch('toast', { message: '{{ $error }}', type: 'error' });
                @endforeach
            @endif
         ">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="flex items-center gap-3 p-4 rounded-xl border shadow-lg pointer-events-auto backdrop-blur-md"
                 :class="{
                     'bg-white/95 border-emerald-100 text-emerald-800 shadow-emerald-50': toast.type === 'success',
                     'bg-white/95 border-red-100 text-red-800 shadow-red-50': toast.type === 'error'
                 }">
                <div class="p-1.5 rounded-lg flex items-center justify-center"
                     :class="{
                         'bg-emerald-50 text-emerald-600': toast.type === 'success',
                         'bg-red-50 text-red-600': toast.type === 'error'
                     }">
                    <i :class="'fas ' + (toast.type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + ' w-5 h-5'"></i>
                </div>
                <div class="flex-1 text-sm font-medium" x-text="toast.message"></div>
                <button @click="removeToast(toast.id)" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times w-4 h-4"></i>
                </button>
            </div>
        </template>
    </div>

    <div id="print-area" class="hidden"></div>

    <div class="min-h-screen w-screen flex flex-col md:flex-row">
        <aside class="hidden md:flex flex-col w-64 bg-gradient-to-b from-blue-700 to-indigo-800 text-white shrink-0 border-r border-indigo-900 shadow-xl relative overflow-hidden">
            <div class="absolute -top-16 -right-16 w-32 h-32 rounded-full bg-white/5 blur-xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-32 h-32 rounded-full bg-white/5 blur-xl pointer-events-none"></div>
            
            <div class="sticky top-0 h-screen flex flex-col justify-between w-full z-10">
                <div class="h-16 px-6 flex items-center gap-3 border-b border-white/10 shrink-0">
                    <img src="{{ asset('image/icon.svg') }}" alt="Logo" class="w-8 h-8 object-contain bg-white p-1 rounded-lg shadow-sm">
                    <div>
                        <h1 class="text-lg font-extrabold tracking-wider bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">KASIRKU</h1>
                        <p class="text-[10px] text-blue-200 font-medium">Smart Retail Solution</p>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-th-large w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-blue-200 group-hover:text-white' }}"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('cashier.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('cashier.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-shopping-cart w-5 h-5 {{ request()->routeIs('cashier.index') ? 'text-blue-700' : 'text-blue-200 group-hover:text-white' }}"></i>
                        <span>Kasir POS</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('products.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-box w-5 h-5 {{ request()->routeIs('products.index') ? 'text-blue-700' : 'text-blue-200 group-hover:text-white' }}"></i>
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('reports.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-chart-line w-5 h-5 {{ request()->routeIs('reports.index') ? 'text-blue-700' : 'text-blue-200 group-hover:text-white' }}"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="{{ route('profile.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('profile.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-user w-5 h-5 {{ request()->routeIs('profile.index') ? 'text-blue-700' : 'text-blue-200 group-hover:text-white' }}"></i>
                        <span>Profil</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-white/10 bg-indigo-950/20 shrink-0">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 font-bold shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-blue-200 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 bg-white/10 hover:bg-red-500/20 hover:text-red-200 border border-white/10 hover:border-red-500/30 rounded-xl text-xs font-semibold text-blue-100 transition-all">
                            <i class="fas fa-sign-out-alt w-4 h-4"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="md:hidden flex items-center justify-between px-6 h-16 bg-gradient-to-r from-blue-700 to-indigo-700 text-white shadow-md relative z-20">
            <div class="flex items-center gap-2">
                <img src="{{ asset('image/icon.svg') }}" alt="Logo" class="w-7 h-7 object-contain bg-white p-0.5 rounded-md shadow-sm">
                <span class="text-base font-extrabold tracking-wider">KASIRKU</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg bg-white/15 focus:outline-none">
                <i class="fas fa-bars w-6 h-6" x-show="!sidebarOpen"></i>
                <i class="fas fa-times w-6 h-6" x-show="sidebarOpen" x-cloak></i>
            </button>
        </div>

        <div class="fixed inset-0 z-10 md:hidden" x-show="sidebarOpen" x-cloak>
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="sidebarOpen = false"></div>
            
            <aside class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-700 to-indigo-800 text-white flex flex-col shadow-2xl z-20"
                   x-transition:enter="transition ease-in-out duration-300 transform"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in-out duration-300 transform"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full">
                
                <div class="h-16 px-6 flex items-center gap-2 border-b border-white/10">
                    <img src="{{ asset('image/icon.svg') }}" alt="Logo" class="w-7 h-7 object-contain bg-white p-0.5 rounded-md shadow-sm">
                    <h1 class="text-lg font-extrabold">KASIRKU</h1>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fas fa-th-large w-5 h-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('cashier.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('cashier.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fas fa-shopping-cart w-5 h-5"></i>
                        <span>Kasir POS</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('products.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fas fa-box w-5 h-5"></i>
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('reports.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fas fa-chart-line w-5 h-5"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('profile.index') ? 'bg-white text-blue-700 shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fas fa-user w-5 h-5"></i>
                        <span>Profil</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-white/10 bg-indigo-950/20">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-blue-200 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 bg-white/10 hover:bg-red-500/20 hover:text-red-200 border border-white/10 hover:border-red-500/30 rounded-xl text-xs font-semibold text-blue-100 transition-all">
                            <i class="fas fa-sign-out-alt w-4 h-4"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>

        <main class="flex-1 flex flex-col min-w-0">
            <header class="hidden md:flex items-center justify-between px-8 h-16 bg-white border-b border-slate-100 shadow-sm shrink-0 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <h2 class="text-lg font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('cashier.index') }}" class="flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-100 hover:border-blue-200 py-1.5 px-3.5 rounded-xl text-xs font-semibold shadow-inner transition-all duration-200">
                        <i class="fas fa-shopping-cart w-4 h-4"></i>
                        <span>Kasir POS</span>
                    </a>
                    
                    <span class="h-4 w-px bg-slate-200"></span>

                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-md shadow-blue-500/20">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <div class="flex-1 px-4 py-6 md:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>
</html>
