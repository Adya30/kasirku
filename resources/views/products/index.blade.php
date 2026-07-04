@extends('layouts.app')

@section('title', 'Manajemen Produk')
@section('page_title', 'Daftar Produk')

@section('content')
<div class="space-y-6" x-data="{ 
    addModalOpen: false, 
    editModalOpen: false,
    scannerModalOpen: false,
    activeProduct: { id: '', barcode: '', name: '', category: '', stock: 0, price: 0 },
    openEditModal(product) {
        this.activeProduct = { ...product };
        this.editModalOpen = true;
    }
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="relative flex-1 max-w-md">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, barcode, atau kategori..."
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-colors shadow-lg shadow-blue-500/10">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition-colors flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="flex items-center gap-3">
            <button @click="scannerModalOpen = true" class="flex items-center gap-2 px-4.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl border border-slate-200 transition-all">
                <i data-lucide="barcode" class="w-4.5 h-4.5 text-blue-600"></i>
                <span>Hubungkan Barcode</span>
            </button>

            <button @click="addModalOpen = true" class="flex items-center gap-2 px-4.5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/10 transition-all">
                <i data-lucide="plus" class="w-4.5 h-4.5"></i>
                <span>Tambah Produk</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">Barcode</th>
                        <th class="py-4 px-6">Nama Produk</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6 text-right">Stok</th>
                        <th class="py-4 px-6 text-right">Harga</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500 select-all">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/50">
                                    {{ $product->barcode ?: '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">{{ $product->name }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-block text-[11px] font-semibold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-medium">
                                <span class="{{ $product->stock <= 10 ? 'text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded-lg' : 'text-slate-800' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-slate-800">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openEditModal({
                                        id: '{{ $product->id }}',
                                        barcode: '{{ $product->barcode }}',
                                        name: '{{ $product->name }}',
                                        category: '{{ $product->category }}',
                                        stock: '{{ $product->stock }}',
                                        price: '{{ $product->price }}'
                                    })" class="p-2 text-slate-400 hover:text-blue-600 bg-slate-50 hover:bg-blue-50 rounded-xl transition-colors">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 bg-slate-50 hover:bg-red-50 rounded-xl transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 mb-3 border border-slate-100">
                                    <i data-lucide="package-open" class="w-8 h-8"></i>
                                </div>
                                <p class="text-sm font-semibold">Tidak ada produk ditemukan</p>
                                <p class="text-xs mt-1">Silakan tambahkan produk baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <div class="fixed inset-0 z-50 overflow-y-auto" x-show="addModalOpen" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>

            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl z-10 border border-slate-100 relative"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <h3 class="text-base font-bold text-slate-800">Tambah Produk Baru</h3>
                    <button @click="addModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label for="add_barcode" class="text-xs font-semibold text-slate-700 block">Kode Barcode (Opsional)</label>
                        <input type="text" name="barcode" id="add_barcode" placeholder="Contoh: 8992761136056" @keydown.enter.prevent
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>

                    <div class="space-y-1">
                        <label for="add_name" class="text-xs font-semibold text-slate-700 block">Nama Produk</label>
                        <input type="text" name="name" id="add_name" required placeholder="Contoh: Aqua Air Mineral"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>

                    <div class="space-y-1">
                        <label for="add_category" class="text-xs font-semibold text-slate-700 block">Kategori</label>
                        <input type="text" name="category" id="add_category" required placeholder="Contoh: Sabun, Makanan, Minuman"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="add_stock" class="text-xs font-semibold text-slate-700 block">Stok awal</label>
                            <input type="number" name="stock" id="add_stock" required min="0" value="0"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-1">
                            <label for="add_price" class="text-xs font-semibold text-slate-700 block">Harga (Rp)</label>
                            <input type="number" name="price" id="add_price" required min="0" placeholder="0"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-6">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/10 transition-colors">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 overflow-y-auto" x-show="editModalOpen" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>

            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl z-10 border border-slate-100 relative"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <h3 class="text-base font-bold text-slate-800">Edit Produk</h3>
                    <button @click="editModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form :action="`{{ url('products') }}/${activeProduct.id}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1">
                        <label for="edit_barcode" class="text-xs font-semibold text-slate-700 block">Kode Barcode (Opsional)</label>
                        <input type="text" name="barcode" id="edit_barcode" x-model="activeProduct.barcode" placeholder="Contoh: 8992761136056" @keydown.enter.prevent
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>

                    <div class="space-y-1">
                        <label for="edit_name" class="text-xs font-semibold text-slate-700 block">Nama Produk</label>
                        <input type="text" name="name" id="edit_name" required x-model="activeProduct.name" placeholder="Contoh: Aqua Air Mineral"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>

                    <div class="space-y-1">
                        <label for="edit_category" class="text-xs font-semibold text-slate-700 block">Kategori</label>
                        <input type="text" name="category" id="edit_category" required x-model="activeProduct.category" placeholder="Contoh: Minuman, Makanan, Sabun"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label for="edit_stock" class="text-xs font-semibold text-slate-700 block">Stok</label>
                            <input type="number" name="stock" id="edit_stock" required min="0" x-model="activeProduct.stock"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-1">
                            <label for="edit_price" class="text-xs font-semibold text-slate-700 block">Harga (Rp)</label>
                            <input type="number" name="price" id="edit_price" required min="0" x-model="activeProduct.price"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-6">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/10 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 overflow-y-auto" x-show="scannerModalOpen" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="scannerModalOpen = false"></div>

            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl z-10 border border-slate-100 relative"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <h3 class="text-base font-bold text-slate-800">Sambungkan / Hubungkan Barcode Scanner</h3>
                    <button @click="scannerModalOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="space-y-5" x-data="barcodeTester()">
                    <div class="bg-blue-50 border border-blue-100 p-4.5 rounded-2xl flex gap-3 text-blue-900 text-xs">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                        <div class="space-y-1">
                            <h4 class="font-bold">Panduan Scanner Barcode Fisik:</h4>
                            <p class="leading-relaxed text-blue-800">
                                Proyek ini dirancang untuk mendeteksi scanner barcode hardware Anda secara langsung. Cukup colokkan kabel USB scanner ke PC atau hubungkan via Bluetooth. Alat scanner akan otomatis berfungsi sebagai alat input keyboard (*Keyboard Emulation*).
                            </p>
                        </div>
                    </div>

                    <div class="p-4 border-slate-100 border rounded-2xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500 absolute"></div>
                            <div class="pl-2">
                                <h4 class="text-xs font-bold text-slate-800">Status Modul Input Scanner</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Mendengarkan keyboard port emulasi... AKTIF</p>
                            </div>
                        </div>
                        <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 font-bold rounded-lg uppercase tracking-wider">SIAP</span>
                    </div>

                    <div class="space-y-2.5">
                        <h4 class="text-xs font-bold text-slate-700">Uji Coba Hubungan Scanner Barcode:</h4>
                        <p class="text-[10px] text-slate-500 leading-normal">
                            Fokuskan kursor pada kolom input di bawah ini, lalu arahkan scanner barcode fisik Anda ke kode produk untuk menguji koneksi langsung.
                        </p>
                        
                        <div class="relative">
                            <input type="text" x-model="testScanValue" @keydown.enter.prevent="checkBarcode()" placeholder="Klik di sini lalu tembak/scan kode barcode..."
                                   class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white text-slate-800 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                        </div>

                        <div x-show="testScanResult" class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between text-xs" x-cloak>
                            <div class="flex items-center gap-2">
                                <div class="p-1 rounded bg-emerald-500 text-white flex items-center justify-center">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <span class="font-bold text-slate-800" x-text="testScanResult"></span>
                            </div>
                            <button type="button" @click="testScanValue = ''; testScanResult = ''" class="text-slate-400 hover:text-slate-600">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3 mt-6">
                        <button type="button" @click="scannerModalOpen = false" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-md transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function barcodeTester() {
        return {
            testScanValue: '',
            testScanResult: '',
            productsList: @json($products->items()),
            playBeep() {
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);
                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(1000, audioCtx.currentTime);
                    gainNode.gain.setValueAtTime(0.15, audioCtx.currentTime);
                    oscillator.start();
                    setTimeout(() => { oscillator.stop(); audioCtx.close(); }, 120);
                } catch (e) {}
            },
            checkBarcode() {
                if (!this.testScanValue) return;
                this.playBeep();
                this.testScanResult = 'Mendeteksi...';
                
                const match = this.productsList.find(p => p.barcode === this.testScanValue);
                if (match) {
                    this.testScanResult = 'Terdeteksi: ' + match.name + ' (Harga: Rp ' + new Intl.NumberFormat('id-ID').format(match.price) + ')';
                } else {
                    this.testScanResult = 'Terdeteksi Barcode Baru: ' + this.testScanValue;
                }
            }
        };
    }
</script>
@endsection
