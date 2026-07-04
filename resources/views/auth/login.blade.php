@extends('layouts.guest')

@section('title', 'Login')
@section('alpine_data', 'x-data="{ showPassword: false }"')

@section('content')
<div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-6 md:p-8 shadow-blue-900/30">
    <div class="mb-4 md:mb-6 text-center">
        <h2 class="text-lg md:text-xl font-bold text-slate-800">Selamat Datang</h2>
        <p class="text-slate-500 text-xs mt-1">Silakan masuk menggunakan akun Anda</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-semibold rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-100 text-red-800 text-xs font-semibold rounded-xl flex flex-col gap-1">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-semibold text-slate-700 tracking-wide block">Email Admin</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                       class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all">
            </div>
        </div>

        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="password" class="text-xs font-semibold text-slate-700 tracking-wide block">Password</label>
                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">Lupa Password?</a>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required placeholder="••••••••"
                       class="w-full pl-10 pr-10 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all">
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                    <i data-lucide="eye" class="w-4 h-4" x-show="!showPassword"></i>
                    <i data-lucide="eye-off" class="w-4 h-4" x-show="showPassword" x-cloak></i>
                </button>
            </div>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" class="w-4.5 h-4.5 text-blue-600 border-slate-300 rounded-md focus:ring-blue-500/20">
            <label for="remember" class="ml-2.5 text-xs text-slate-600 font-medium select-none">Ingat perangkat ini</label>
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 border border-blue-600/10 transition-all duration-200 flex items-center justify-center gap-2">
            <span>Masuk ke Dashboard</span>
            <i data-lucide="arrow-right" class="w-4.5 h-4.5"></i>
        </button>
    </form>
</div>
@endsection
