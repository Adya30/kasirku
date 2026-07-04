@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-6 md:p-8 shadow-blue-900/30">
    <div class="mb-4 md:mb-6">
        <h2 class="text-lg md:text-xl font-bold text-slate-800">Lupa Password</h2>
        <p class="text-slate-500 text-xs mt-1">Masukkan alamat email Anda untuk menerima instruksi reset password.</p>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-medium rounded-xl flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span class="font-semibold">{{ session('status') }}</span>
            </div>
            
            @if(session('demo_reset_link'))
                <div class="mt-2 pt-2 border-t border-emerald-200">
                    <p class="text-slate-600 font-semibold mb-1 text-[11px]">Demo Mode:</p>
                    <a href="{{ session('demo_reset_link') }}" class="inline-block py-1.5 px-3 bg-blue-600 text-white hover:bg-blue-700 font-bold rounded-lg transition-all text-[11px] select-all shadow-md">
                        Klik Langsung di Sini untuk Reset Password
                    </a>
                </div>
            @endif
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

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-semibold text-slate-700 tracking-wide block">Email Terdaftar</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <input type="email" name="email" id="email" required placeholder="nama@email.com"
                       class="w-full pl-10 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all">
            </div>
        </div>

        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg border border-blue-600/10 transition-all flex items-center justify-center gap-2">
            <span>Kirim Tautan Reset</span>
            <i data-lucide="send" class="w-4.5 h-4.5"></i>
        </button>
    </form>

    <div class="mt-6 pt-4 border-t border-slate-100 text-center">
        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center gap-1">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            <span>Kembali ke Login</span>
        </a>
    </div>
</div>
@endsection
