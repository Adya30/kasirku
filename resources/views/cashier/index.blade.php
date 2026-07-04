@extends('layouts.app')

@section('title', 'Kasir POS')
@section('page_title', 'Kasir POS Terminal')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6" x-data="posApp()">
    
    <div class="xl:col-span-7 space-y-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></div>
                    <h3 class="text-sm font-bold text-slate-800">Deteksi Barcode Scanner Otomatis</h3>
                </div>
                <span class="text-[10px] bg-blue-50 text-blue-600 font-bold px-2 py-0.5 rounded-md">Scanner Siap</span>
            </div>
            
            <p class="text-xs text-slate-500 leading-relaxed">
                Anda dapat langsung memindai barcode produk menggunakan alat scanner Anda kapan saja. Sistem akan mendeteksi scan secara otomatis.
            </p>

            <div class="pt-2 border-t border-slate-50">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700">Input Kode Barcode Manual (Jika alat tidak terbaca):</label>
                    <div class="relative max-w-md">
                        <input type="text" x-model="manualBarcode" @keydown.enter.prevent="lookupBarcode(manualBarcode)" placeholder="Ketik barcode produk dan tekan Enter..."
                               class="w-full pl-3 pr-10 py-2.5 bg-slate-50 text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:outline-none text-slate-800">
                        <button @click="lookupBarcode(manualBarcode)" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-blue-600 hover:text-blue-700">
                            <i data-lucide="corner-down-left" class="w-4 h-4"></i>
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
                    <i data-lucide="trash" class="w-4 h-4"></i>
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
                                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <template x-if="cart.length === 0">
                            <tr>
                                <td colspan="5" class="py-20 text-center text-slate-400">
                                    <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 mb-3 border border-slate-100">
                                        <i data-lucide="shopping-basket" class="w-7 h-7"></i>
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
                        <i data-lucide="search" class="w-4 h-4"></i>
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
                <i data-lucide="printer" class="w-4.5 h-4.5"></i>
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
                    <i data-lucide="check-circle" class="w-7 h-7"></i>
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
                        <i data-lucide="printer" class="w-4 h-4"></i>
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
                let barcodeBuffer = '';
                let lastKeyTime = Date.now();

                window.addEventListener('keydown', (e) => {
                    if (e.target.tagName === 'INPUT' && e.target !== document.body) {
                        return;
                    }

                    const currentTime = Date.now();
                    
                    if (currentTime - lastKeyTime < 35) {
                        if (e.key === 'Enter') {
                            if (barcodeBuffer.trim().length > 3) {
                                this.lookupBarcode(barcodeBuffer.trim());
                                barcodeBuffer = '';
                            }
                        } else if (e.key !== 'Shift') {
                            barcodeBuffer += e.key;
                        }
                    } else {
                        barcodeBuffer = e.key === 'Shift' ? '' : e.key;
                    }
                    lastKeyTime = currentTime;
                });
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
