@extends('layouts.guest')

@section('title', 'Atur Ulang Password')
@section('alpine_data', 'x-data="{ showPasswords: false }"')

@section('content')
<div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-6 md:p-8 shadow-blue-900/30">
    <div class="mb-4 md:mb-6">
        <h2 class="text-lg md:text-xl font-bold text-slate-800">Atur Ulang Password</h2>
        <p class="text-slate-500 text-xs mt-1">Silakan masukkan password baru Anda.</p>
    </div>

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

    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-semibold text-slate-700 tracking-wide block">Email Konfirmasi</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </div>
                <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required readonly
                       class="w-full pl-10 pr-4 py-3 bg-slate-100 text-slate-500 text-sm rounded-xl border border-slate-200 cursor-not-allowed focus:outline-none">
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="password" class="text-xs font-semibold text-slate-700 tracking-wide block">Password Baru</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <input :type="showPasswords ? 'text' : 'password'" name="password" id="password" required placeholder="Min 6 karakter"
                       class="w-full pl-10 pr-10 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all">
                <button type="button" @click="showPasswords = !showPasswords" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors z-10">
                    <span x-show="!showPasswords">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                    <span x-show="showPasswords" x-cloak>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                    </span>
                </button>
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="password_confirmation" class="text-xs font-semibold text-slate-700 tracking-wide block">Ulangi Password Baru</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <input :type="showPasswords ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password"
                       class="w-full pl-10 pr-10 py-3 bg-slate-50 hover:bg-slate-100/50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all">
                <button type="button" @click="showPasswords = !showPasswords" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors z-10">
                    <span x-show="!showPasswords">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                    <span x-show="showPasswords" x-cloak>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                    </span>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full mt-2 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg border border-blue-600/10 transition-all flex items-center justify-center gap-2">
            <span>Perbarui Password</span>
            <i data-lucide="check" class="w-4.5 h-4.5"></i>
        </button>
    </form>
</div>
@endsection
