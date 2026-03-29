<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir POS - You and Me Coffe</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-full bg-slate-50 flex flex-col overflow-hidden">

    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                <i data-lucide="coffee" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="font-bold text-gray-800 text-lg">You & Me Coffe</h1>
                <p class="text-xs text-gray-500"><?= date('d F Y') ?></p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-800"><?= session()->get('name') ?? 'Kasir' ?></p>
                <p class="text-xs text-blue-600 capitalize"><?= session()->get('role') ?? 'kasir' ?></p>
            </div>
            
            <button onclick="openResModal()" class="p-2 bg-purple-50 rounded-full text-purple-600 hover:bg-purple-100 transition" title="Manajemen Reservasi">
                <i data-lucide="calendar-clock" class="w-5 h-5"></i>
            </button>

            <button onclick="openHistoryModal()" class="p-2 bg-blue-50 rounded-full text-blue-600 hover:bg-blue-100 transition" title="Riwayat Transaksi">
                <i data-lucide="history" class="w-5 h-5"></i>
            </button>

            <a href="<?= base_url('logout') ?>" class="p-2 bg-red-50 rounded-full text-red-600 hover:bg-red-100 transition">
                <i data-lucide="log-out" class="w-5 h-5"></i>
            </a>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        
        <div class="flex-1 flex flex-col bg-slate-50">
            <div class="p-6 pb-0 shrink-0">
                
                <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-4">
                    <button onclick="filterCategory('all')" id="btn-cat-all" class="cat-btn px-5 py-2.5 rounded-full text-sm font-medium whitespace-nowrap bg-blue-600 text-white transition-all shadow-sm">
                        Semua Menu
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 pt-2 scrollbar-hide">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="menu-container">
                    </div>
            </div>
        </div>

        <div class="w-[380px] bg-white border-l border-gray-200 flex flex-col shrink-0">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-500"></i> Pesanan Saat Ini
                </h2>
                <button onclick="clearCart()" class="text-xs font-semibold text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded">Clear</button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 scrollbar-hide" id="cart-container">
                <div id="empty-cart" class="h-full flex flex-col items-center justify-center text-gray-400">
                    <i data-lucide="shopping-bag" class="w-12 h-12 mb-3 opacity-50"></i>
                    <p class="text-sm">Keranjang masih kosong</p>
                </div>
            </div>

            <div class="bg-slate-50 p-5 border-t border-gray-200">
                
                <div class="flex gap-2 mb-4">
                    <input type="text" id="promo-code" placeholder="Kode Promo" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase">
                    <button onclick="applyPromo()" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900">Apply</button>
                </div>

                <div class="space-y-2 text-sm text-gray-600 mb-4 border-b border-gray-200 pb-4">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="txt-subtotal" class="font-medium text-gray-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-rose-500 hidden" id="discount-row">
                        <span>Diskon (<span id="txt-disc-name">Promo</span>)</span>
                        <span id="txt-discount">- Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-800 mt-2 pt-2 border-t border-gray-200">
                        <span>Total Tagihan</span>
                        <span id="txt-total">Rp 0</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button onclick="processCheckout('cash')" class="pay-btn flex flex-col items-center justify-center gap-1 p-3 rounded-xl bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 transition">
                        <i data-lucide="banknote" class="w-5 h-5"></i>
                        <span class="text-xs font-bold">Cash</span>
                    </button>
                    <button onclick="processCheckout('qris')" class="pay-btn flex flex-col items-center justify-center gap-1 p-3 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                        <i data-lucide="qr-code" class="w-5 h-5"></i>
                        <span class="text-xs font-bold">QRIS</span>
                    </button>
                    <button onclick="processCheckout('card')" class="pay-btn flex flex-col items-center justify-center gap-1 p-3 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        <span class="text-xs font-bold">Card</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="res-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity">
        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh] m-4">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-purple-500"></i> Reservasi Meja
                </h2>
                <button onclick="closeResModal()" class="text-gray-400 hover:text-red-500 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="flex-1 overflow-hidden flex flex-col md:flex-row">
                
                <div class="w-full md:w-1/2 border-r border-gray-100 flex flex-col bg-white">
                    <div class="p-5 border-b border-gray-100 shrink-0">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Tanggal</label>
                        <input type="date" id="res-filter-date" value="<?= date('Y-m-d') ?>" onchange="fetchReservations(this.value)" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="flex-1 overflow-y-auto p-5 space-y-3 scrollbar-hide bg-slate-50" id="res-list-container">
                        <div class="text-center text-gray-400 py-10">Memuat data...</div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 p-6 bg-white overflow-y-auto scrollbar-hide">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Tambah Reservasi Baru</h3>
                    <form id="pos-res-form" onsubmit="submitReservation(event)">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                            <input type="text" id="new-res-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1">No. HP/WhatsApp</label>
                            <input type="text" id="new-res-phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" id="new-res-date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jam</label>
                                <input type="time" id="new-res-time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Tamu (Pax)</label>
                            <input type="number" id="new-res-guest" min="1" value="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea id="new-res-notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl text-sm transition">
                            Simpan Reservasi
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="history-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center transition-opacity">
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh] m-4">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-blue-500"></i> Riwayat Transaksi Saya
                </h2>
                <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-red-500 transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="p-5 border-b border-gray-100 shrink-0 bg-white">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Tanggal</label>
                <input type="date" id="history-filter-date" value="<?= date('Y-m-d') ?>" onchange="fetchHistory(this.value)" 
                    class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex-1 overflow-y-auto p-5 bg-slate-50" id="history-list-container">
                <div class="text-center text-gray-400 py-10">Memuat data...</div>
            </div>
        </div>
    </div>

    <script>
        const menusData = <?= json_encode($menus) ?>;
        const discountsData = <?= json_encode($discounts) ?>;
        
        let cart = [];
        let appliedDiscount = null;
        let finalTotal = 0;
        let currentCsrf = '<?= csrf_hash() ?>';

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            renderMenus('all');
        });

        const formatRp = (angka) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        };

        function renderMenus(categoryId) {
            document.querySelectorAll('.cat-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-white', 'text-gray-600');
            });
            document.getElementById('btn-cat-' + categoryId).classList.add('bg-blue-600', 'text-white');
            document.getElementById('btn-cat-' + categoryId).classList.remove('bg-white', 'text-gray-600');

            const container = document.getElementById('menu-container');
            container.innerHTML = '';

            const filteredMenus = categoryId === 'all' 
                ? menusData 
                : menusData.filter(m => m.category_id == categoryId);

            if (filteredMenus.length === 0) {
                container.innerHTML = '<p class="col-span-full text-center text-gray-400 py-10">Tidak ada menu di kategori ini.</p>';
                return;
            }

            filteredMenus.forEach(menu => {
                const card = document.createElement('div');
                card.className = "bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-300 transition-all cursor-pointer flex flex-col";
                card.onclick = () => addToCart(menu);
                
                let varianHtml = menu.variants ? `<p class="text-[10px] text-gray-400 mt-1 truncate">Varian: ${menu.variants}</p>` : '';
                
                card.innerHTML = `
                    <div class="flex-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500 bg-blue-50 px-2 py-1 rounded-md mb-2 inline-block">${menu.category_name || 'Menu'}</span>
                        <h3 class="font-bold text-gray-800 leading-tight mb-1">${menu.name}</h3>
                        ${varianHtml}
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-50 flex items-center justify-between">
                        <span class="font-bold text-blue-600">${formatRp(menu.price)}</span>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
            
            lucide.createIcons();
        }

        function addToCart(menu) {
            const existingItem = cart.find(item => item.id === menu.id);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({
                    id: menu.id,
                    name: menu.name,
                    price: parseInt(menu.price),
                    qty: 1
                });
            }
            updateCartUI();
        }

        function updateQty(index, delta) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1); 
            }
            updateCartUI();
        }

        function clearCart() {
            if(cart.length === 0) return;
            Swal.fire({
                title: 'Kosongkan keranjang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kosongkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if(result.isConfirmed) {
                    cart = [];
                    appliedDiscount = null;
                    document.getElementById('promo-code').value = '';
                    updateCartUI();
                }
            });
        }

        function updateCartUI() {
            const container = document.getElementById('cart-container');
            
            if (cart.length === 0) {
                container.innerHTML = `
                    <div id="empty-cart" class="h-full flex flex-col items-center justify-center text-gray-400">
                        <i data-lucide="shopping-bag" class="w-12 h-12 mb-3 opacity-50"></i>
                        <p class="text-sm">Keranjang masih kosong</p>
                    </div>
                `;
            } else {
                container.innerHTML = '';
                cart.forEach((item, index) => {
                    const row = document.createElement('div');
                    row.className = "flex items-center justify-between p-3 mb-2 bg-slate-50 rounded-xl border border-gray-100";
                    row.innerHTML = `
                        <div class="flex-1 pr-3">
                            <p class="font-bold text-sm text-gray-800 leading-tight">${item.name}</p>
                            <p class="text-xs text-blue-600 font-medium">${formatRp(item.price * item.qty)}</p>
                        </div>
                        <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-1">
                            <button onclick="updateQty(${index}, -1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 rounded"><i data-lucide="minus" class="w-3 h-3"></i></button>
                            <span class="text-sm font-bold w-4 text-center">${item.qty}</span>
                            <button onclick="updateQty(${index}, 1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-blue-500 hover:bg-blue-50 rounded"><i data-lucide="plus" class="w-3 h-3"></i></button>
                        </div>
                    `;
                    container.appendChild(row);
                });
            }
            
            calculateTotals();
            
            lucide.createIcons();
        }

        function applyPromo() {
            const codeInput = document.getElementById('promo-code').value.trim().toUpperCase();
            if(!codeInput) return;

            if(cart.length === 0) {
                Swal.fire('Ups!', 'Isi keranjang terlebih dahulu', 'warning');
                return;
            }

            const promo = discountsData.find(d => d.code === codeInput);
            if (!promo) {
                Swal.fire('Gagal', 'Kode promo tidak ditemukan atau sudah kadaluarsa', 'error');
                return;
            }

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            if (parseInt(promo.min_purchase) > 0 && subtotal < parseInt(promo.min_purchase)) {
                Swal.fire('Info', `Minimal belanja untuk promo ini adalah ${formatRp(promo.min_purchase)}`, 'info');
                return;
            }

            appliedDiscount = promo;
            Swal.fire({
                title: 'Berhasil!',
                text: 'Promo ' + promo.name + ' diterapkan',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            calculateTotals();
        }

        function calculateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let discountAmount = 0;

            if (appliedDiscount) {
                if (parseInt(appliedDiscount.min_purchase) > 0 && subtotal < parseInt(appliedDiscount.min_purchase)) {
                    appliedDiscount = null; 
                    document.getElementById('promo-code').value = '';
                } else {
                    if (appliedDiscount.type === 'percentage') {
                        discountAmount = subtotal * (parseInt(appliedDiscount.value) / 100);
                    } else {
                        discountAmount = parseInt(appliedDiscount.value);
                    }
                }
            }

            finalTotal = subtotal - discountAmount;
            if (finalTotal < 0) finalTotal = 0;

            document.getElementById('txt-subtotal').innerText = formatRp(subtotal);
            document.getElementById('txt-total').innerText = formatRp(finalTotal);

            const discRow = document.getElementById('discount-row');
            if (discountAmount > 0) {
                discRow.classList.remove('hidden');
                document.getElementById('txt-disc-name').innerText = appliedDiscount.code;
                document.getElementById('txt-discount').innerText = '- ' + formatRp(discountAmount);
            } else {
                discRow.classList.add('hidden');
            }
        }

        function processCheckout(method) {
            if (cart.length === 0) {
                Swal.fire('Keranjang Kosong', 'Tambahkan menu terlebih dahulu', 'warning');
                return;
            }

            Swal.fire({
                title: 'Proses Pembayaran?',
                text: `Total Tagihan: ${formatRp(finalTotal)} via ${method.toUpperCase()}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Proses',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    const payload = {
                        items: cart,
                        total: finalTotal,
                        payment_method: method
                    };

                    fetch('<?= base_url("pos/checkout") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_header() ?>': currentCsrf
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Gagal terhubung ke server");
                        return response.json();
                    })
                    .then(data => {
                        if (data.csrf) currentCsrf = data.csrf; 

                        if(data.status === 'success') {
                            showReceipt(data.trx_id, method);
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Terjadi kesalahan sistem atau sesi habis. Coba muat ulang halaman.', 'error');
                        console.error(err);
                    });
                }
            });
        }

        function showReceipt(trxId, method) {
            let itemsHtml = cart.map(item => `
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                    <div>
                        <div style="font-weight: bold;">${item.name}</div>
                        <div style="color: #666;">${item.qty} x ${formatRp(item.price)}</div>
                    </div>
                    <div style="font-weight: bold;">${formatRp(item.price * item.qty)}</div>
                </div>
            `).join('');

            let discHtml = '';
            if (appliedDiscount) {
                let discNominal = document.getElementById('txt-discount').innerText;
                discHtml = `
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; color: #e11d48;">
                    <span>Diskon (${appliedDiscount.code})</span>
                    <span>${discNominal}</span>
                </div>`;
            }

            const receiptHtml = `
                <div id="print-area" style="text-align: left; font-family: monospace; padding: 10px; color: black;">
                    <div style="text-align: center; margin-bottom: 16px;">
                        <h3 style="margin: 0; font-size: 18px;">You & Me Coffe</h3>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #666;">Jl. Ukong Sutaatmaja Karanganyar, Subang.</p>
                    </div>
                    <hr style="border: none; border-top: 1px dashed #ccc; margin-bottom: 16px;">
                    <div style="font-size: 12px; margin-bottom: 16px; color: #444;">
                        <div>No: <b>${trxId}</b></div>
                        <div>Kasir: <?= session()->get('name') ?? 'Kasir' ?></div>
                        <div>Waktu: ${new Date().toLocaleString('id-ID')}</div>
                    </div>
                    <hr style="border: none; border-top: 1px dashed #ccc; margin-bottom: 16px;">
                    ${itemsHtml}
                    <hr style="border: none; border-top: 1px dashed #ccc; margin-top: 16px; margin-bottom: 16px;">
                    ${discHtml}
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: bold; margin-bottom: 16px;">
                        <span>TOTAL</span>
                        <span>${formatRp(finalTotal)}</span>
                    </div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; background: #f3f4f6; padding: 8px; border-radius: 8px; color: black;">
                        PEMBAYARAN VIA ${method.toUpperCase()}
                    </div>
                    <p style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">Terima kasih atas kunjungan Anda!</p>
                </div>
            `;
            
            Swal.fire({
                html: receiptHtml,
                showCancelButton: true,
                confirmButtonText: 'Cetak Struk',
                cancelButtonText: 'Tutup & Layani Berikutnya',
                confirmButtonColor: '#10b981', 
                cancelButtonColor: '#3b82f6', 
                width: 350,
                allowOutsideClick: false 
            }).then((result) => {
                if (result.isConfirmed) {
                    printReceipt(receiptHtml);
                }
                
                cart = [];
                appliedDiscount = null;
                document.getElementById('promo-code').value = '';
                updateCartUI();
            });
        }

        function printReceipt(htmlContent) {
            const printWindow = window.open('', '_blank', 'width=400,height=600');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Cetak Struk</title>
                        <style>
                            body { font-family: monospace; margin: 0; padding: 10px; color: #000; }
                            @media print {
                                @page { margin: 0; }
                                body { margin: 0.5cm; }
                            }
                        </style>
                    </head>
                    <body>
                        ${htmlContent}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        const resModal = document.getElementById('res-modal');
        const resListContainer = document.getElementById('res-list-container');
        const resFilterDate = document.getElementById('res-filter-date');

        function openResModal() {
            resModal.classList.remove('hidden');
            document.getElementById('new-res-date').value = resFilterDate.value;
            fetchReservations(resFilterDate.value);
            lucide.createIcons();
        }

        function closeResModal() {
            resModal.classList.add('hidden');
        }

        function fetchReservations(dateStr) {
            resListContainer.innerHTML = '<div class="text-center text-gray-400 py-10"><i class="lucide-loader animate-spin w-6 h-6 mx-auto mb-2"></i> Memuat...</div>';
            
            fetch(`<?= base_url('pos/reservations') ?>?date=${dateStr}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    renderReservationList(data.data);
                }
            })
            .catch(err => {
                resListContainer.innerHTML = '<div class="text-center text-red-400 py-10">Gagal memuat data.</div>';
                console.error(err);
            });
        }

        function renderReservationList(reservations) {
            if (reservations.length === 0) {
                resListContainer.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                        <i data-lucide="calendar-x" class="w-10 h-10 mb-2 opacity-50"></i>
                        <p class="text-sm">Tidak ada reservasi di tanggal ini</p>
                    </div>`;
                lucide.createIcons();
                return;
            }

            resListContainer.innerHTML = '';
            reservations.forEach(res => {
                const time = res.reservation_time.substring(0, 5);
                
                let statusBadge = '';
                if(res.status === 'pending') statusBadge = '<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold uppercase">Pending</span>';
                else if(res.status === 'confirmed') statusBadge = '<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-bold uppercase">Confirmed</span>';
                else if(res.status === 'completed') statusBadge = '<span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-[10px] font-bold uppercase">Selesai</span>';
                else statusBadge = '<span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-bold uppercase">Batal</span>';

                const card = document.createElement('div');
                card.className = "bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col";
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-bold text-gray-800 text-sm">${res.customer_name}</div>
                        ${statusBadge}
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 mb-1">
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3 text-purple-500"></i> ${time} WIB</span>
                        <span class="flex items-center gap-1"><i data-lucide="users" class="w-3 h-3 text-blue-500"></i> ${res.guest_count} Pax</span>
                    </div>
                    ${res.notes ? `<div class="text-[10px] text-gray-400 italic mt-1 bg-gray-50 p-1.5 rounded">"${res.notes}"</div>` : ''}
                `;
                resListContainer.appendChild(card);
            });
            lucide.createIcons();
        }

        function submitReservation(e) {
            e.preventDefault(); 

            const payload = {
                customer_name: document.getElementById('new-res-name').value,
                customer_phone: document.getElementById('new-res-phone').value,
                reservation_date: document.getElementById('new-res-date').value,
                reservation_time: document.getElementById('new-res-time').value,
                guest_count: document.getElementById('new-res-guest').value,
                notes: document.getElementById('new-res-notes').value
            };

            fetch('<?= base_url("pos/reservations/store") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': currentCsrf 
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.csrf) currentCsrf = data.csrf;

                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Sukses!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    document.getElementById('pos-res-form').reset();
                    resFilterDate.value = payload.reservation_date;
                    fetchReservations(payload.reservation_date);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
                console.error(err);
            });
        }

        const historyModal = document.getElementById('history-modal');
        const historyListContainer = document.getElementById('history-list-container');
        const historyFilterDate = document.getElementById('history-filter-date');

        function openHistoryModal() {
            historyModal.classList.remove('hidden');
            fetchHistory(historyFilterDate.value);
            lucide.createIcons();
        }

        function closeHistoryModal() {
            historyModal.classList.add('hidden');
        }

        function fetchHistory(dateStr) {
            historyListContainer.innerHTML = '<div class="text-center text-gray-400 py-10"><i class="lucide-loader animate-spin w-6 h-6 mx-auto mb-2"></i> Memuat...</div>';

            fetch(`<?= base_url('pos/transactions') ?>?date=${dateStr}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    renderHistoryList(data.data);
                }
            })
            .catch(err => {
                historyListContainer.innerHTML = '<div class="text-center text-red-400 py-10">Gagal memuat data.</div>';
                console.error(err);
            });
        }

        function renderHistoryList(transactions) {
            if (transactions.length === 0) {
                historyListContainer.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                        <i data-lucide="file-x" class="w-10 h-10 mb-2 opacity-50"></i>
                        <p class="text-sm">Tidak ada transaksi di tanggal ini</p>
                    </div>`;
                lucide.createIcons();
                return;
            }

            historyListContainer.innerHTML = '';
            transactions.forEach(trx => {
                const time = new Date(trx.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});

                let methodColor = 'bg-gray-100 text-gray-700';
                if(trx.payment_method === 'cash') methodColor = 'bg-green-100 text-green-700';
                else if(trx.payment_method === 'qris') methodColor = 'bg-blue-100 text-blue-700';
                else if(trx.payment_method === 'card') methodColor = 'bg-amber-100 text-amber-700';

                const itemsStr = encodeURIComponent(trx.items);

                const card = document.createElement('div');
                card.className = "bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between md:items-center gap-4 mb-3 transition hover:border-blue-300";
                card.innerHTML = `
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono font-bold text-purple-600">${trx.transaction_id}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase ${methodColor}">${trx.payment_method}</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-gray-500">
                            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3 text-gray-400"></i> ${time} WIB</span>
                            <span class="font-bold text-gray-800 text-sm">${formatRp(trx.total)}</span>
                        </div>
                    </div>
                    <button onclick="reprintReceipt('${trx.transaction_id}', '${itemsStr}', ${trx.total}, '${trx.payment_method}', '${trx.created_at}')" 
                        class="shrink-0 flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                        <i data-lucide="printer" class="w-4 h-4"></i> Cetak Ulang
                    </button>
                `;
                historyListContainer.appendChild(card);
            });
            lucide.createIcons();
        }

        function reprintReceipt(trxId, itemsEncoded, total, method, createdAt) {
            const items = JSON.parse(decodeURIComponent(itemsEncoded));

            let itemsHtml = items.map(item => `
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                    <div>
                        <div style="font-weight: bold;">${item.name}</div>
                        <div style="color: #666;">${item.qty} x ${formatRp(item.price)}</div>
                    </div>
                    <div style="font-weight: bold;">${formatRp(item.price * item.qty)}</div>
                </div>
            `).join('');

            const subtotal = items.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let discHtml = '';
            if (subtotal > total) {
                discHtml = `
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; color: #e11d48;">
                    <span>Diskon Promo</span>
                    <span>- ${formatRp(subtotal - total)}</span>
                </div>`;
            }

            const receiptHtml = `
                <div id="print-area" style="text-align: left; font-family: monospace; padding: 10px; color: black;">
                    <div style="text-align: center; margin-bottom: 16px;">
                        <h3 style="margin: 0; font-size: 18px;">CAFE POS SYSTEM</h3>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #666;">Jl. Kopi Nusantara No. 1</p>
                        <p style="margin: 4px 0 0 0; font-size: 10px; color: #666; font-weight:bold;">(CETAK ULANG)</p>
                    </div>
                    <hr style="border: none; border-top: 1px dashed #ccc; margin-bottom: 16px;">
                    <div style="font-size: 12px; margin-bottom: 16px; color: #444;">
                        <div>No: <b>${trxId}</b></div>
                        <div>Kasir: <?= session()->get('name') ?? 'Kasir' ?></div>
                        <div>Waktu: ${new Date(createdAt).toLocaleString('id-ID')}</div>
                    </div>
                    <hr style="border: none; border-top: 1px dashed #ccc; margin-bottom: 16px;">
                    ${itemsHtml}
                    <hr style="border: none; border-top: 1px dashed #ccc; margin-top: 16px; margin-bottom: 16px;">
                    ${discHtml}
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: bold; margin-bottom: 16px;">
                        <span>TOTAL</span>
                        <span>${formatRp(total)}</span>
                    </div>
                    <div style="text-align: center; font-size: 12px; font-weight: bold; background: #f3f4f6; padding: 8px; border-radius: 8px; color: black;">
                        PEMBAYARAN VIA ${method.toUpperCase()}
                    </div>
                    <p style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">Terima kasih atas kunjungan Anda!</p>
                </div>
            `;
            
            Swal.fire({
                html: receiptHtml,
                showCancelButton: true,
                confirmButtonText: 'Cetak Struk',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                width: 350
            }).then((result) => {
                if (result.isConfirmed) {
                    printReceipt(receiptHtml);
                }
            });
        }
    </script>
</body>
</html>