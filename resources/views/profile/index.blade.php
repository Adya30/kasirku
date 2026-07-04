@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Pengaturan Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Ubah Detail Profil</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Kelola detail identitas admin Anda</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="name" class="text-xs font-semibold text-slate-700 block">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-user w-4.5 h-4.5"></i>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-semibold text-slate-700 block">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-envelope w-4.5 h-4.5"></i>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 space-y-6" x-data="{ changePwd: false, showP1: false, showP2: false, showP3: false }">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Ubah Password</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5">Kosongkan jika Anda tidak ingin memperbarui password</p>
                    </div>
                    <button type="button" @click="changePwd = !changePwd" 
                            class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-xs rounded-lg border border-blue-100 transition-all flex items-center gap-1">
                        <i class="fas fa-key w-3.5 h-3.5"></i>
                        <span x-text="changePwd ? 'Batal Ubah' : 'Atur Ulang Password'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-show="changePwd" x-cloak
                     x-transition:enter="transition ease-out duration-200 transform"
                     x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    <div class="space-y-1.5">
                        <label for="current_password" class="text-xs font-semibold text-slate-700 block">Password Saat Ini</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i class="fas fa-lock w-4.5 h-4.5"></i>
                            </span>
                            <input :type="showP1 ? 'text' : 'password'" name="current_password" id="current_password" placeholder="Password lama" ::required="changePwd"
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                            <button type="button" @click="showP1 = !showP1" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors z-10">
                                <span x-show="!showP1">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span x-show="showP1" x-cloak>
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="new_password" class="text-xs font-semibold text-slate-700 block">Password Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i class="fas fa-shield-alt w-4.5 h-4.5"></i>
                            </span>
                            <input :type="showP2 ? 'text' : 'password'" name="new_password" id="new_password" placeholder="Min 6 karakter" ::required="changePwd"
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                            <button type="button" @click="showP2 = !showP2" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors z-10">
                                <span x-show="!showP2">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span x-show="showP2" x-cloak>
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="new_password_confirmation" class="text-xs font-semibold text-slate-700 block">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i class="fas fa-shield-alt w-4.5 h-4.5"></i>
                            </span>
                            <input :type="showP3 ? 'text' : 'password'" name="new_password_confirmation" id="new_password_confirmation" placeholder="Ulangi password" ::required="changePwd"
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                            <button type="button" @click="showP3 = !showP3" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors z-10">
                                <span x-show="!showP3">
                                    <i class="fas fa-eye"></i>
                                </span>
                                <span x-show="showP3" x-cloak>
                                    <i class="fas fa-eye-slash"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-blue-500/25 transition-all flex items-center gap-1.5">
                    <i class="fas fa-check w-4.5 h-4.5"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
