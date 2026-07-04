@extends('layouts.app')

@section('title', 'Kasir POS')
@section('page_title', 'Kasir POS Terminal')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6" x-data="posApp()">
    
    <div class="xl:col-span-7 space-y-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-5">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Koneksi Perangkat</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl border" :class="scannerConnected ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50'">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center" :class="scannerConnected ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400'">
                                <i class="fas fa-barcode w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Barcode Scanner</p>
                                <p class="text-[10px] text-slate-500" x-text="scannerConnected ? scannerDeviceName : 'Belum terhubung'"></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md" :class="scannerConnected ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'">
                            <span x-text="scannerConnected ? 'Siap' : 'Belum Siap'"></span>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <template x-if="!scannerConnected">
                            <button type="button" @click="connectScanner()" :disabled="isConnecting"
                                    class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-200 disabled:text-slate-500 text-white font-bold text-[11px] rounded-xl transition-all flex items-center justify-center gap-1.5">
                                <i class="fas fa-plug w-3.5 h-3.5" x-show="!isConnecting"></i>
                                <i class="fas fa-spinner w-3.5 h-3.5 animate-spin" x-show="isConnecting" x-cloak></i>
                                <span x-text="isConnecting ? 'Menghubungkan...' : 'Hubungkan Scanner'"></span>
                            </button>
                        </template>
                        <template x-if="scannerConnected">
                            <button type="button" @click="disconnectScanner()"
                                    class="w-full py-2 px-3 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-[11px] rounded-xl transition-all flex items-center justify-center gap-1.5 border border-red-100">
                                <i class="fas fa-unlink w-3.5 h-3.5"></i>
                                <span>Putuskan Scanner</span>
                            </button>
                        </template>
                        <p class="text-[10px] text-slate-400 leading-relaxed" x-show="!webHidSupported && !scannerConnected" x-cloak>
                            Browser tidak mendukung WebHID. Gunakan input manual di bawah.
                        </p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl border" :class="printerDetected ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50'">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center" :class="printerDetected ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400'">
                                <i class="fas fa-print w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Printer Nota</p>
                                <p class="text-[10px] text-slate-500" x-text="printerDetected ? 'Printer tersedia' : 'Tidak terdeteksi'"></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md" :class="printerDetected ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'">
                            <span x-text="printerDetected ? 'Siap' : 'Belum Siap'"></span>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <button type="button" @click="testPrinter()"
                                class="w-full py-2 px-3 bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-[11px] rounded-xl transition-all flex items-center justify-center gap-1.5 border border-blue-100">
                            <i class="fas fa-file-alt w-3.5 h-3.5"></i>
                            <span>Uji Coba Cetak</span>
                        </button>
                        <p class="text-[10px] text-slate-400 leading-relaxed">
                            Cetak nota menggunakan dialog print browser. Pastikan printer thermal terpilih.
                        </p>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700">Input Kode Barcode Manual:</label>
                    <div class="relative max-w-md">
                        <input type="text" x-model="manualBarcode" @keydown.enter.prevent="lookupBarcode(manualBarcode)" placeholder="Ketik barcode produk dan tekan Enter..."
                               class="w-full pl-3 pr-10 py-2.5 bg-slate-50 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none text-slate-800">
                        <button @click="lookupBarcode(manualBarcode)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-blue-600 hover:text-blue-700">
                            <i class="fas fa-reply w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col min-h-[380px]">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 font-sans">Keranjang Belanja</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Daftar produk yang akan dibayar</p>
                </div>
                <button @click="clearCart()" :disabled="cart.length === 0"
                        class="text-xs font-bold text-red-500 hover:text-red-700 disabled:text-slate-300 transition-colors flex items-center gap-1">
                    <i class="fas fa-trash w-4 h-4"></i>
                    <span>Kosongkan</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto max-h-[320px]">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                            <th class="py-3 px-6">Produk</th>
                            <th class="py-3 px-6 text-center w-24">Jumlah</th>
                            <th class="py-3 px-6 text-right w-28">Harga Satuan</th>
                            <th class="py-3 px-6 text-right w-28">Subtotal</th>
                            <th class="py-3 px-6 text-center w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="py-3 px-6">
                                    <p class="font-bold text-slate-800 text-xs" x-text="item.name"></p>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5" x-text="item.barcode"></p>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex items-center justify-center gap-1.5 bg-slate-50 border border-slate-200/60 rounded-xl p-1 w-20">
                                        <button @click="decrementQty(index)" class="w-5 h-5 rounded-md hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs">-</button>
                                        <span class="text-xs font-bold text-slate-800 w-6 text-center" x-text="item.qty"></span>
                                        <button @click="incrementQty(index)" class="w-5 h-5 rounded-md hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs">+</button>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-right font-medium text-xs" x-text="formatPrice(item.price)"></td>
                                <td class="py-3 px-6 text-right font-bold text-slate-800 text-xs" x-text="formatPrice(item.price * item.qty)"></td>
                                <td class="py-3 px-6 text-center">
                                    <button @click="removeFromCart(index)" class="text-slate-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-times w-4.5 h-4.5"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <template x-if="cart.length === 0">
                            <tr>
                                <td colspan="5" class="py-20 text-center text-slate-400">
                                    <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 mb-3 border border-slate-100">
                                        <i class="fas fa-shopping-basket w-7 h-7"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-500">Keranjang masih kosong</p>
                                    <p class="text-[10px] mt-0.5">Scan produk atau tambah secara manual di samping kanan</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="xl:col-span-5 space-y-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-800">Cari Produk (Nama / Barcode)</h3>
            <div class="relative" x-data="{ showResults: false }">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search w-4 h-4"></i>
                    </span>
                    <input type="text" x-model="searchQuery" @input="showResults = true" @focus="showResults = true" @click.away="showResults = false"
                           placeholder="Ketik nama produk atau kode barcode..."
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 text-slate-800 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none transition-all">
                </div>

                <div class="absolute z-30 w-full mt-2 bg-white border border-slate-150 rounded-2xl shadow-xl max-h-60 overflow-y-auto"
                     x-show="showResults && searchQuery.length > 0" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    
                    <ul class="divide-y divide-slate-50">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <li>
                                <button type="button" @click="addToCart(product); searchQuery = ''; showResults = false"
                                        class="w-full text-left px-4 py-2.5 hover:bg-blue-50/50 flex items-center justify-between gap-3 transition-colors">
                                    <div>
                                        <p class="text-xs font-bold text-slate-800" x-text="product.name"></p>
                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5" x-text="product.barcode || '-'"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-bold text-slate-850" x-text="formatPrice(product.price)"></p>
                                        <p class="text-[9px]" :class="product.stock <= 10 ? 'text-red-500 font-bold' : 'text-slate-500'" x-text="'Stok: ' + product.stock"></p>
                                    </div>
                                </button>
                            </li>
                        </template>
                        
                        <template x-if="filteredProducts.length === 0">
                            <li class="px-4 py-4 text-center text-xs text-slate-400">
                                Produk tidak ditemukan
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-blue-500/5 space-y-6">
            <h3 class="text-sm font-bold text-slate-800 pb-3 border-b border-slate-50">Pembayaran</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between text-slate-600">
                    <span class="text-xs font-medium">Subtotal</span>
                    <span class="text-sm font-bold" x-text="formatPrice(totalBill)"></span>
                </div>
                
                <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-5 rounded-2xl text-white">
                    <span class="text-[10px] font-semibold tracking-wider text-blue-100 uppercase block">Total yang Harus Dibayar</span>
                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight mt-1" x-text="formatPrice(totalBill)"></h2>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 tracking-wide block">Uang Dibayar (Cash)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                            Rp
                        </div>
                        <input type="number" x-model.number="amountPaid" placeholder="0" min="0"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 focus:bg-white text-slate-800 font-bold text-lg rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition-all">
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <template x-for="cash in quickCashOptions" :key="cash">
                        <button type="button" @click="amountPaid = cash"
                                class="px-3 py-1.5 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 border border-slate-200/60 hover:border-blue-100 text-[11px] font-bold rounded-lg text-slate-600 transition-all"
                                x-text="formatPrice(cash)"></button>
                    </template>
                    <button type="button" @click="amountPaid = totalBill" :disabled="totalBill === 0"
                            class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 border border-blue-100 text-[11px] font-bold rounded-lg text-blue-600 transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:border-transparent">
                        Uang Pas
                    </button>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                    <span class="text-xs font-semibold text-slate-600">Uang Kembali</span>
                    <span class="text-base font-extrabold" :class="amountChange >= 0 ? 'text-emerald-600' : 'text-red-500'" x-text="formatPrice(amountChange)"></span>
                </div>
            </div>

            <button @click="processCheckout()" :disabled="cart.length === 0 || amountChange < 0 || processing"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:from-slate-100 disabled:to-slate-100 disabled:text-slate-400 disabled:border-transparent text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-500/10 hover:shadow-blue-500/25 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-print w-4.5 h-4.5"></i>
                <span x-text="processing ? 'Memproses Transaksi...' : 'Bayar & Cetak Nota'"></span>
            </button>
        </div>
    </div>

    <div class="fixed inset-0 z-50 overflow-y-auto" x-show="receiptModalOpen" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="receiptModalOpen = false"></div>

            <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl z-10 border border-slate-100 relative text-center"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="w-14 h-14 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center mx-auto mb-4 border border-emerald-500/20 shadow-inner">
                    <i class="fas fa-check-circle w-7 h-7"></i>
                </div>
                
                <h3 class="text-base font-bold text-slate-800">Transaksi Berhasil!</h3>
                <p class="text-xs text-slate-500 mt-1">Struk belanja telah dicetak ke printer nota yang terhubung.</p>

                <div class="my-5 p-4 border border-slate-200 border-dashed rounded-2xl bg-slate-50 text-left font-mono text-[10px] text-slate-700 leading-normal" id="receipt-preview">
                </div>

                <div class="grid grid-cols-2 gap-3 mt-6">
                    <button type="button" @click="receiptModalOpen = false" class="py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                        Tutup
                    </button>
                    <button type="button" @click="printReceipt()"
                            class="py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/10 flex items-center justify-center gap-1.5 transition-all">
                        <i class="fas fa-print w-4 h-4"></i>
                        <span>Cetak Ulang</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #print-area, #print-area * {
            visibility: visible;
        }
        #print-area {
            display: block !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 58mm;
            margin: 0;
            padding: 2mm;
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            line-height: 1.2;
            color: #000;
            background: #fff;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    function posApp() {
        return {
            cart: [],
            manualBarcode: '',
            searchQuery: '',
            amountPaid: 0,
            processing: false,
            receiptModalOpen: false,
            lastTransaction: null,
            quickCashOptions: [10000, 20000, 50000, 100000],
            productsList: @json($products),

            scannerConnected: false,
            scannerDevice: null,
            scannerDeviceName: '',
            isConnecting: false,
            webHidSupported: !!navigator.hid,

            printerDetected: false,

            get filteredProducts() {
                if (!this.searchQuery) return [];
                const q = this.searchQuery.toLowerCase();
                return this.productsList.filter(p => 
                    p.name.toLowerCase().includes(q) || 
                    (p.barcode && p.barcode.includes(q))
                );
            },

            get totalBill() {
                return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            get amountChange() {
                if (this.totalBill === 0) return 0;
                return this.amountPaid - this.totalBill;
            },

            formatPrice(value) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            },

            beep() {
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain();
                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);
                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(1200, audioCtx.currentTime);
                    gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                    oscillator.start();
                    setTimeout(() => { oscillator.stop(); audioCtx.close(); }, 80);
                } catch(e) {}
            },

            init() {
                this.checkWebHidSupport();
                this.detectPrinter();

                document.addEventListener('visibilitychange', () => {
                    if (!document.hidden) {
                        this.detectPrinter();
                    }
                });
            },

            checkWebHidSupport() {
                this.webHidSupported = !!navigator.hid;
                if (this.webHidSupported) {
                    navigator.hid.addEventListener('disconnect', (event) => {
                        if (this.scannerDevice && event.device.vendorId === this.scannerDevice.vendorId) {
                            this.scannerConnected = false;
                            this.scannerDevice = null;
                            this.scannerDeviceName = '';
                            window.dispatchEvent(new CustomEvent('toast', { 
                                detail: { message: 'Scanner barcode terputus.', type: 'error' } 
                            }));
                        }
                    });
                }
            },

            async connectScanner() {
                if (!navigator.hid) {
                    window.dispatchEvent(new CustomEvent('toast', { 
                        detail: { message: 'Browser tidak mendukung WebHID. Gunakan Chrome/Edge terbaru.', type: 'error' } 
                    }));
                    return;
                }

                this.isConnecting = true;

                try {
                    const devices = await navigator.hid.requestDevice({
                        filters: [
                            { usagePage: 0x0001 },
                            { usagePage: 0x000C },
                            { usagePage: 0xFF00 },
                            { usagePage: 0xFF01 }
                        ]
                    });

                    if (devices.length === 0) {
                        this.isConnecting = false;
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { message: 'Tidak ada perangkat yang dipilih.', type: 'error' } 
                        }));
                        return;
                    }

                    const device = devices[0];
                    
                    await device.open();

                    this.scannerDevice = device;
                    this.scannerDeviceName = device.productName || 'Scanner USB';
                    this.scannerConnected = true;
                    this.isConnecting = false;

                    device.oninputreport = (event) => {
                        const view = new DataView(event.data.buffer);
                        let barcodeChars = '';
                        for (let i = 0; i < event.data.byteLength; i++) {
                            const byte = view.getUint8(i);
                            if (byte > 0) {
                                barcodeChars += String.fromCharCode(byte);
                            }
                        }

                        const cleaned = barcodeChars.replace(/[^\x20-\x7E]/g, '').trim();
                        if (cleaned.length > 2) {
                            this.beep();
                            this.lookupBarcode(cleaned);
                        }
                    };

                    window.dispatchEvent(new CustomEvent('toast', { 
                        detail: { message: `Scanner "${this.scannerDeviceName}" terhubung!`, type: 'success' } 
                    }));

                } catch (error) {
                    this.isConnecting = false;
                    if (error.name !== 'NotFoundError') {
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { message: 'Gagal menghubungkan scanner: ' + error.message, type: 'error' } 
                        }));
                    }
                }
            },

            async disconnectScanner() {
                if (this.scannerDevice) {
                    try {
                        await this.scannerDevice.close();
                    } catch(e) {}
                    this.scannerDevice = null;
                    this.scannerConnected = false;
                    this.scannerDeviceName = '';
                    window.dispatchEvent(new CustomEvent('toast', { 
                        detail: { message: 'Scanner barcode diputuskan.', type: 'success' } 
                    }));
                }
            },

            detectPrinter() {
                this.printerDetected = typeof window.print !== 'undefined' && window.print !== null;
            },

            testPrinter() {
                const testContent = `
<div style="text-align:center;font-family:'Courier New',monospace;font-size:10pt;padding:10px;">
    <h2 style="font-size:14pt;margin:0;">KASIRKU RETAIL</h2>
    <p style="font-size:8pt;color:#666;margin:2px 0;">UJI CETAK PRINTER</p>
    <hr style="border-top:1px dashed #000;">
    <p style="font-size:9pt;">Tanggal: ${new Date().toLocaleString('id-ID')}</p>
    <hr style="border-top:1px dashed #000;">
    <p style="font-size:9pt;">Jika halaman ini tercetak,</p>
    <p style="font-size:9pt;">printer nota Anda sudah siap!</p>
    <hr style="border-top:1px dashed #000;">
    <p style="font-size:9pt;font-weight:bold;">*** TEST OK ***</p>
</div>`;
                document.getElementById('print-area').innerHTML = testContent;
                window.print();
                document.getElementById('print-area').innerHTML = '';
            },

            lookupBarcode(barcode) {
                if (!barcode) return;
                
                const pLocal = this.productsList.find(p => p.barcode === barcode);
                if (pLocal) {
                    this.beep();
                    this.addToCart(pLocal);
                    this.manualBarcode = '';
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Scanner: ${pLocal.name} ditambahkan`, type: 'success' } }));
                } else {
                    fetch(`/products/barcode/${barcode}`)
                        .then(res => {
                            if (!res.ok) throw new Error();
                            return res.json();
                        })
                        .then(data => {
                            this.beep();
                            this.addToCart(data.product);
                            this.manualBarcode = '';
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Scanner: ${data.product.name} ditambahkan`, type: 'success' } }));
                        })
                        .catch(() => {
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Barcode "${barcode}" tidak ditemukan`, type: 'error' } }));
                        });
                }
            },

            addProductById(id) {
                if (!id) return;
                const product = this.productsList.find(p => p.id == id);
                if (product) {
                    this.addToCart(product);
                }
            },

            addToCart(product) {
                const index = this.cart.findIndex(item => item.id === product.id);
                if (index !== -1) {
                    if (this.cart[index].qty >= product.stock) {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: `Stok '${product.name}' terbatas`, type: 'error' } }));
                        return;
                    }
                    this.cart[index].qty++;
                } else {
                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        barcode: product.barcode,
                        price: parseFloat(product.price),
                        qty: 1,
                        stock: product.stock
                    });
                }
            },

            incrementQty(index) {
                const item = this.cart[index];
                if (item.qty >= item.stock) {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Maksimum stok tercapai', type: 'error' } }));
                    return;
                }
                item.qty++;
            },

            decrementQty(index) {
                if (this.cart[index].qty > 1) {
                    this.cart[index].qty--;
                } else {
                    this.removeFromCart(index);
                }
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            clearCart() {
                this.cart = [];
                this.amountPaid = 0;
            },

            processCheckout() {
                if (this.cart.length === 0 || this.amountChange < 0) return;
                this.processing = true;

                fetch("{{ route('cashier.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cart: this.cart,
                        amount_paid: this.amountPaid
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.processing = false;
                    if (data.success) {
                        this.lastTransaction = data.transaction;
                        
                        this.renderReceiptHtml(data.transaction);

                        this.updateLocalStocks(this.cart);

                        this.clearCart();
                        
                        window.print();

                        this.receiptModalOpen = true;
                        
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Transaksi berhasil!', type: 'success' } }));
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message, type: 'error' } }));
                    }
                })
                .catch(() => {
                    this.processing = false;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Terjadi kesalahan sistem.', type: 'error' } }));
                });
            },

            updateLocalStocks(soldCart) {
                soldCart.forEach(item => {
                    const p = this.productsList.find(prod => prod.id == item.id);
                    if (p) {
                        p.stock -= item.qty;
                    }
                });
            },

            renderReceiptHtml(tx) {
                const dateStr = new Date(tx.created_at).toLocaleString('id-ID', { dateStyle: 'short', timeStyle: 'short' });
                
                let detailsRows = '';
                tx.details.forEach(d => {
                    const name = d.product ? d.product.name : 'Produk';
                    const qtyPrice = `${d.quantity} x Rp ${new Intl.NumberFormat('id-ID').format(d.price)}`;
                    const subtotal = `Rp ${new Intl.NumberFormat('id-ID').format(d.subtotal)}`;
                    detailsRows += `
<div class="flex justify-between mt-1">
    <span>${name}</span>
</div>
<div class="flex justify-between text-slate-500">
    <span class="pl-2">${qtyPrice}</span>
    <span>${subtotal}</span>
</div>`;
                });

                const html = `
<div class="text-center font-mono">
    <h3 class="font-extrabold text-sm uppercase">KASIRKU RETAIL</h3>
    <p class="text-[9px] text-slate-500">Jl. Raya Modern No.1, Jakarta</p>
    <p class="text-[9px] text-slate-500">Telp: 0812-3456-7890</p>
    <div class="border-b border-dashed border-slate-300 my-2"></div>
    <div class="text-left text-[9px] text-slate-600">
        <div>Nota: ${tx.invoice_number}</div>
        <div>Tgl : ${dateStr}</div>
        <div>Kasir: ${tx.user ? tx.user.name : 'Kasir'}</div>
    </div>
    <div class="border-b border-dashed border-slate-300 my-2"></div>
    <div class="text-left">
        ${detailsRows}
    </div>
    <div class="border-b border-dashed border-slate-300 my-2"></div>
    <div class="text-left text-[9px] space-y-1">
        <div class="flex justify-between font-bold">
            <span>TOTAL BILL</span>
            <span>Rp ${new Intl.NumberFormat('id-ID').format(tx.total_price)}</span>
        </div>
        <div class="flex justify-between">
            <span>DIBAYAR</span>
            <span>Rp ${new Intl.NumberFormat('id-ID').format(tx.amount_paid)}</span>
        </div>
        <div class="flex justify-between">
            <span>KEMBALI</span>
            <span>Rp ${new Intl.NumberFormat('id-ID').format(tx.amount_change)}</span>
        </div>
    </div>
    <div class="border-b border-dashed border-slate-300 my-2"></div>
    <p class="text-[9px] font-bold text-center mt-2">*** TERIMA KASIH ***</p>
    <p class="text-[8px] text-slate-400 text-center">Barang yang sudah dibeli tidak dapat ditukar</p>
</div>`;

                document.getElementById('receipt-preview').innerHTML = html;
                
                document.getElementById('print-area').innerHTML = html;
            },

            printReceipt() {
                window.print();
            }
        };
    }
</script>
@endsection