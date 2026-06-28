<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SopKu - Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.development.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/18.2.0/umd/react-dom.development.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/7.23.6/babel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/iqon/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css') ?>">
    <style>
        .cart-item-enter {
            animation: slideIn 0.3s ease forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .cart-item-remove {
            animation: fadeOut 0.25s ease forwards;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.95);
            }
        }
    </style>
</head>

<body class="bg-slate-50">
    <div id="root"></div>

    <script type="text/babel" data-presets="react,env">
        const { useState, useEffect, useMemo } = React;
        const API_BASE = "<?= base_url('api/') ?>";

        /* ─────────────────────────────────────────
           INITIAL CART (3 produk default dari API)
           ───────────────────────────────────────── */
        const DEFAULT_IDS = [1, 2, 3];

        /* ===================== NAVBAR ===================== */
        function Navbar({ cartCount }) {
            const [menuOpen, setMenuOpen] = useState(false);
            const [profileDropdownOpen, setProfileDropdownOpen] = useState(false);
            const isLoggedIn = <?= $this->session->userdata('user_id') ? 'true' : 'false' ?>;
            const userName = "<?= $this->session->userdata('user_name') ?? '' ?>";

            useEffect(() => {
                const handleClickOutside = (event) => {
                    if (profileDropdownOpen && !event.target.closest('.profile-dropdown-container')) {
                        setProfileDropdownOpen(false);
                    }
                };
                document.addEventListener('mousedown', handleClickOutside);
                return () => {
                    document.removeEventListener('mousedown', handleClickOutside);
                };
            }, [profileDropdownOpen]);

            return (
                <nav className="bg-white navbar-shadow sticky top-0 z-50">
                    <div className="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                        <a href="<?= base_url('home') ?>" className="flex items-center gap-2 text-2xl font-bold text-slate-800"
                            style={{ fontFamily: 'Playfair Display, serif' }}>
                            <img src="<?= base_url('assets/iqon/logo.png') ?>" alt="Logo SopKu" className="h-8 w-auto object-contain" />
                            <span>Sop<span className="text-blue-600">Ku</span></span>
                        </a>
                        <ul className="hidden md:flex gap-8 text-slate-600 font-medium items-center">
                            <li><a href="<?= base_url('home') ?>"    className="hover:text-blue-600 transition">Home</a></li>
                            <li><a href="<?= base_url('katalog') ?>" className="hover:text-blue-600 transition">Katalog</a></li>
                        </ul>
                        <div className="flex items-center gap-4">
                            <a href="<?= base_url('cart') ?>" className="relative mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-9H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {cartCount > 0 && (
                                    <span className="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {cartCount}
                                    </span>
                                )}
                            </a>

                            {/* Profile Dropdown */}
                            <div className="relative profile-dropdown-container">
                                <button 
                                    onClick={() => setProfileDropdownOpen(!profileDropdownOpen)} 
                                    className="flex items-center focus:outline-none rounded-full"
                                    aria-expanded={profileDropdownOpen}
                                    aria-haspopup="true"
                                >
                                    {isLoggedIn ? (
                                        <div className="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm hover:bg-blue-700 transition">
                                            {userName ? userName.charAt(0).toUpperCase() : 'U'}
                                        </div>
                                    ) : (
                                        <div className="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-blue-600 transition flex items-center justify-center border border-slate-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    )}
                                </button>
                                
                                {profileDropdownOpen && (
                                    <div className="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-xl py-2 z-50">
                                        {isLoggedIn ? (
                                            <>
                                                <div className="px-4 py-2 border-b border-slate-100 mb-1">
                                                    <p className="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                                    <p className="text-sm font-semibold text-slate-800 truncate">{userName}</p>
                                                </div>
                                                <a href="<?= base_url('login') ?>" className="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition font-medium">
                                                    👤 Akun Saya
                                                </a>
                                                <a href="<?= base_url('orders') ?>" className="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition font-medium">
                                                    📦 Pesanan Saya
                                                </a>
                                                <a href="<?= base_url('home/logout') ?>" className="flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition border-t border-slate-100 font-medium mt-1">
                                                    🚪 Logout
                                                </a>
                                            </>
                                        ) : (
                                            <>
                                                <a href="<?= base_url('login') ?>" className="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition font-medium">
                                                    🔑 Login / Masuk
                                                </a>
                                                <a href="<?= base_url('login?tab=register') ?>" className="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition font-medium">
                                                    ✨ Daftar Akun
                                                </a>
                                            </>
                                        )}
                                    </div>
                                )}
                            </div>

                            <button className="md:hidden" onClick={() => setMenuOpen(!menuOpen)}>
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    {menuOpen && (
                        <div className="md:hidden bg-white border-t px-4 py-3 flex flex-col gap-3 text-slate-700 font-medium">
                            <a href="<?= base_url('home') ?>">Home</a>
                            <a href="<?= base_url('katalog') ?>">Katalog</a>
                        </div>
                    )}
                </nav>
            );
        }

        /* ===================== PAGE HEADER ===================== */
        function PageHeader({ count }) {
            return (
                <header className="hero-bg text-white py-10 px-4">
                    <div className="max-w-7xl mx-auto">
                        <p className="text-blue-300 text-sm font-medium mb-1">🛒 Keranjang Saya</p>
                        <h1 className="text-3xl md:text-4xl font-bold mb-1">Keranjang Belanja</h1>
                        <p className="text-slate-400 text-sm">{count} item dalam keranjang</p>
                    </div>
                </header>
            );
        }

        /* ===================== SKELETON ===================== */
        function SkeletonCartItem() {
            return (
                <div className="bg-white rounded-2xl p-5 flex gap-4 shadow-sm border border-slate-100">
                    <div className="skeleton w-24 h-24 rounded-xl flex-shrink-0"></div>
                    <div className="flex-1 space-y-3 py-1">
                        <div className="skeleton h-3 w-1/4 rounded"></div>
                        <div className="skeleton h-4 w-3/4 rounded"></div>
                        <div className="skeleton h-4 w-1/3 rounded"></div>
                        <div className="skeleton h-8 w-32 rounded-lg mt-2"></div>
                    </div>
                    <div className="skeleton w-20 h-6 rounded self-start mt-1"></div>
                </div>
            );
        }

        /* ===================== CART ITEM CARD ===================== */
        function CartItemCard({ item, onQtyChange, onRemove }) {
            const [removing, setRemoving] = useState(false);

            const handleRemove = () => {
                setRemoving(true);
                setTimeout(() => onRemove(item.id), 240);
            };

            return (
                <div className={`bg-white rounded-2xl p-5 flex gap-4 shadow-sm border border-slate-100 cart-item-enter ${removing ? 'cart-item-remove' : ''}`}>
                    {/* Gambar */}
                    <a href={`<?= base_url('detail') ?>/${item.id}`} className="flex-shrink-0">
                        <div className="w-24 h-24 bg-slate-50 rounded-xl flex items-center justify-center p-2">
                            <img src={item.image} alt={item.title}
                                className="max-h-full max-w-full object-contain" />
                        </div>
                    </a>

                    {/* Info */}
                    <div className="flex-1 min-w-0">
                        <p className="text-xs text-blue-600 font-semibold uppercase tracking-wide mb-0.5">{item.category}</p>
                        <h3 className="text-slate-800 font-semibold text-sm leading-snug line-clamp-2 mb-2">{item.title}</h3>
                        <p className="text-blue-700 font-bold text-lg">Rp {(item.price * item.qty).toLocaleString('id-ID')}
                            {item.qty > 1 && <span className="text-slate-400 text-xs font-normal ml-1">(Rp {item.price.toLocaleString('id-ID')} × {item.qty})</span>}
                        </p>

                        {/* Qty control */}
                        <div className="flex items-center gap-3">
                            <div className="flex items-center border border-slate-200 rounded-xl overflow-hidden">
                                <button
                                    onClick={() => onQtyChange(item.id, item.qty - 1)}
                                    disabled={item.qty <= 1}
                                    className="px-3 py-1.5 text-slate-600 hover:bg-slate-100 transition font-bold disabled:opacity-30 disabled:cursor-not-allowed"
                                >−</button>
                                <span className="px-3 py-1.5 font-semibold text-slate-800 min-w-[32px] text-center text-sm">{item.qty}</span>
                                <button
                                    onClick={() => onQtyChange(item.id, item.qty + 1)}
                                    className="px-3 py-1.5 text-slate-600 hover:bg-slate-100 transition font-bold"
                                >+</button>
                            </div>
                            <button
                                onClick={handleRemove}
                                className="text-xs text-red-400 hover:text-red-600 font-medium transition flex items-center gap-1"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>

                    {/* Checkbox pilih */}
                    <div className="flex-shrink-0 pt-1">
                        <input type="checkbox" checked={item.selected}
                            onChange={() => {}}
                            onClick={() => onQtyChange(item.id, item.qty, !item.selected)}
                            className="w-4 h-4 accent-blue-600 cursor-pointer" />
                    </div>
                </div>
            );
        }

        /* ===================== ORDER SUMMARY ===================== */
        function OrderSummary({ items, onCheckout, showToast }) {
            const selected  = items.filter(i => i.selected);
            const subtotal  = selected.reduce((s, i) => s + (Number(i.price) || 0) * (Number(i.qty) || 1), 0);
            const shipping  = subtotal > 0 && subtotal < 1500000 ? 15000 : 0; // Ongkir Rp 15.000 jika di bawah Rp 1.500.000
            const discount  = subtotal > 3000000 ? subtotal * 0.05 : 0;
            const [coupon, setCoupon] = useState(localStorage.getItem('sopku_promo_code') || '');
            const [couponDiscount, setCouponDiscount] = useState(0);
            const [couponMsg, setCouponMsg] = useState(null);
            const total = Math.max(0, subtotal + shipping - discount - couponDiscount);

            const applyCoupon = () => {
                const code = coupon.trim().toUpperCase();
                if (!code) {
                    setCouponMsg({ ok: false, text: 'Masukkan kode kupon dulu.' });
                    return;
                }

                axios.post(API_BASE + 'promo/check', { code })
                    .then(res => {
                        if (res.data.valid) {
                            const disc = subtotal * ((parseFloat(res.data.discount_percent) || 0) / 100);
                            localStorage.setItem('sopku_promo_code', code);
                            setCoupon(code);
                            setCouponDiscount(disc);
                            setCouponMsg({ ok: true, text: `Kupon tersimpan. Hemat Rp ${disc.toLocaleString('id-ID')}` });
                            showToast(`Kupon ${code} dipakai di checkout.`);
                        } else {
                            localStorage.removeItem('sopku_promo_code');
                            setCouponDiscount(0);
                            setCouponMsg({ ok: false, text: 'Kode kupon tidak valid.' });
                        }
                    })
                    .catch(() => setCouponMsg({ ok: false, text: 'Gagal mengecek kode kupon.' }));
            };

            return (
                <aside className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 h-fit sticky top-24 space-y-4">
                    <h2 className="text-slate-800 font-bold text-lg">Ringkasan Pesanan</h2>

                    {/* Items terpilih */}
                    <div className="text-sm text-slate-500">
                        {selected.length > 0
                            ? <span>{selected.length} item dipilih</span>
                            : <span className="text-amber-500">⚠️ Belum ada item dipilih</span>}
                    </div>

                    <div className="border-t border-slate-100 pt-4 space-y-3 text-sm">
                        <div className="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span className="font-medium">Rp {subtotal.toLocaleString('id-ID')}</span>
                        </div>
                        <div className="flex justify-between text-slate-600">
                            <span>Ongkos Kirim</span>
                            {shipping === 0
                                ? <span className="text-green-600 font-medium">Gratis</span>
                                : <span className="font-medium">Rp {shipping.toLocaleString('id-ID')}</span>}
                        </div>
                        {discount > 0 && (
                            <div className="flex justify-between text-green-600">
                                <span>Diskon 5%</span>
                                <span className="font-medium">-Rp {discount.toLocaleString('id-ID')}</span>
                            </div>
                        )}
                        {couponDiscount > 0 && (
                            <div className="flex justify-between text-green-600">
                                <span>Kupon ({coupon})</span>
                                <span className="font-medium">-Rp {couponDiscount.toLocaleString('id-ID')}</span>
                            </div>
                        )}
                    </div>

                    <div className="border-t border-slate-100 pt-4 flex justify-between font-bold text-slate-800 text-base">
                        <span>Total</span>
                        <span className="text-blue-600 text-xl">Rp {total.toLocaleString('id-ID')}</span>
                    </div>

                    {/* Promo info */}
                    {subtotal > 0 && subtotal < 1500000 && (
                        <div className="bg-blue-50 rounded-xl px-4 py-3 text-xs text-blue-700">
                            💡 Tambahkan <strong>Rp {(1500000 - subtotal).toLocaleString('id-ID')}</strong> lagi untuk gratis ongkir!
                        </div>
                    )}
                    {subtotal >= 1500000 && subtotal < 3000000 && (
                        <div className="bg-green-50 rounded-xl px-4 py-3 text-xs text-green-700">
                            ✅ Kamu sudah dapat gratis ongkir! Belanja <strong>Rp {(3000000 - subtotal).toLocaleString('id-ID')}</strong> lagi untuk diskon 5%.
                        </div>
                    )}

                    {/* Input kupon */}
                    <div className="flex gap-2 pt-1">
                        <input type="text" placeholder="Kode kupon"
                            value={coupon}
                            onChange={e => {
                                setCoupon(e.target.value.toUpperCase());
                                setCouponDiscount(0);
                                setCouponMsg(null);
                            }}
                            className="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                        <button onClick={applyCoupon} className="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2 rounded-lg transition">
                            Pakai
                        </button>
                    </div>
                    {couponMsg && <p className={`text-xs font-semibold ${couponMsg.ok ? 'text-green-600' : 'text-red-500'}`}>{couponMsg.text}</p>}

                    <button
                        onClick={onCheckout}
                        disabled={selected.length === 0}
                        className="w-full btn-primary bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition text-center"
                    >
                        Checkout ({selected.length} item)
                    </button>

                    <a href="<?= base_url('katalog') ?>"
                        className="block text-center text-sm text-blue-600 hover:underline font-medium">
                        ← Lanjut Belanja
                    </a>

                    {/* Info jaminan */}
                    <div className="border-t border-slate-100 pt-4 space-y-2 text-xs text-slate-400">
                        <div className="flex items-center gap-2">🔒 <span>Pembayaran aman & terenkripsi</span></div>
                        <div className="flex items-center gap-2">↩️ <span>Garansi return 30 hari</span></div>
                        <div className="flex items-center gap-2">🚚 <span>Estimasi tiba 2-4 hari kerja</span></div>
                    </div>
                </aside>
            );
        }

        /* ===================== EMPTY STATE ===================== */
        function EmptyCart() {
            return (
                <div className="bg-white rounded-2xl p-16 text-center shadow-sm border border-slate-100">
                    <div className="text-7xl mb-4">🛒</div>
                    <h3 className="text-slate-700 font-bold text-xl mb-2">Keranjang Kosong</h3>
                    <p className="text-slate-400 text-sm mb-6">Yuk, mulai tambahkan produk ke keranjangmu!</p>
                    <a href="<?= base_url('katalog') ?>"
                        className="btn-primary inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition">
                        Mulai Belanja →
                    </a>
                </div>
            );
        }

        /* ===================== TOAST ===================== */
        function Toast({ message, visible }) {
            if (!visible) return null;
            return (
                <div className="toast-enter fixed bottom-6 right-6 bg-slate-800 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-lg z-50 flex items-center gap-2">
                    <span className="text-green-400">✓</span> {message}
                </div>
            );
        }

        /* ===================== FOOTER ===================== */
        function Footer() {
            return (
                <footer className="bg-slate-800 text-slate-300 mt-16">
                    <div className="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <div className="flex items-center gap-2 mb-3">
                                <img src="<?= base_url('assets/iqon/logo.png') ?>" alt="Logo SopKu" className="h-7 w-auto object-contain" />
                                <h3 className="text-white text-xl font-bold" style={{ fontFamily: 'Playfair Display, serif' }}>
                                    Sop<span className="text-blue-400">Ku</span>
                                </h3>
                            </div>
                            <p className="text-sm leading-relaxed">Platform belanja online terpercaya dengan ribuan produk pilihan.</p>
                        </div>
                        <div>
                            <h4 className="text-white font-semibold mb-3">Navigasi</h4>
                            <ul className="space-y-2 text-sm">
                                <li><a href="<?= base_url('home') ?>"    className="hover:text-white transition">Home</a></li>
                                <li><a href="<?= base_url('katalog') ?>" className="hover:text-white transition">Katalog</a></li>
                                <li><a href="<?= base_url('cart') ?>"    className="hover:text-white transition">Keranjang</a></li>
                                <li><a href="<?= base_url('login') ?>"   className="hover:text-white transition">Login</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="text-white font-semibold mb-3">Kontak</h4>
                            <ul className="space-y-2 text-sm">
                                <li><a href="https://mail.google.com/mail/?view=cm&fs=1&to=oiqgemink27@gmail.com" target="_blank" rel="noopener noreferrer" className="hover:text-white transition">📧 oiqgemink27@gmail.com</a></li>
                                <li><a href="https://wa.me/6287855068357" target="_blank" rel="noopener noreferrer" className="hover:text-white transition">📱 087855068357</a></li>
                                <li><a href="https://www.google.com/maps/search/?api=1&query=AMIKOM%20YOGYAKARTA" target="_blank" rel="noopener noreferrer" className="hover:text-white transition">📍 AMIKOM YOGYAKARTA</a></li>
                            </ul>
                        </div>
                    </div>
                    <div className="border-t border-slate-700 text-center py-4 text-sm text-slate-500">
                        © 2026 SopKu. All rights reserved.
                    </div>
                </footer>
            );
        }

        /* ===================== APP ROOT ===================== */
        function App() {
            const [cartItems, setCartItems] = useState([]);
            const [loading, setLoading]     = useState(true);
            const [error, setError]         = useState(null);
            const [toast, setToast]         = useState({ visible: false, message: '' });

        
        useEffect(() => {
            axios.get(API_BASE + 'cart')
                .then(res => setCartItems(res.data))
                .catch(() => setError('Gagal memuat produk keranjang. Silakan coba lagi.'))
                .finally(() => setLoading(false));
                }, []);

            // ── Computed values ──
            const totalQty = useMemo(
                () => cartItems.reduce((s, i) => s + i.qty, 0),
                [cartItems]
            );

            const allSelected = cartItems.length > 0 && cartItems.every(i => i.selected);

            // ── Handlers ──
            const showToast = (msg) => {
                setToast({ visible: true, message: msg });
                setTimeout(() => setToast({ visible: false, message: '' }), 2500);
            };

            // qty change; also handles checkbox toggle via 3rd param
            const handleQtyChange = (id, newQty, selectedOverride) => {
                const payload = { product_id: id };
                if (selectedOverride !== undefined) payload.selected = selectedOverride;
                else payload.qty = Math.max(1, newQty);

                setCartItems(prev =>
                    prev.map(item => {
                        if (item.id !== id) return item;
                        if (selectedOverride !== undefined)
                            return { ...item, selected: selectedOverride };
                        return { ...item, qty: Math.max(1, newQty) };
                    })
                );

                axios.post(API_BASE + 'cart/update', payload).catch(() => {});
            };

            const handleRemove = (id) => {
                const removed = cartItems.find(i => i.id === id);
                setCartItems(prev => prev.filter(i => i.id !== id));
                if (removed) showToast(`"${removed.title.substring(0, 25)}..." dihapus dari keranjang.`);
                axios.post(API_BASE + 'cart/remove/' + id).catch(() => {});
            };
            const handleSelectAll = () => {
                const newSelected = !allSelected;
                setCartItems(prev => prev.map(i => ({ ...i, selected: newSelected })));
                cartItems.forEach(i => {
                    axios.post(API_BASE + 'cart/update', { product_id: i.id, selected: newSelected }).catch(() => {});
                });
            };

            const handleClearAll = () => {
                setCartItems([]);
                showToast('Semua item dihapus dari keranjang.');
                axios.post(API_BASE + 'cart/clear').catch(() => {});
            };

            const handleCheckout = () => {
                const count = cartItems.filter(i => i.selected).length;
                showToast(`Memproses ${count} item... Menuju checkout!`);
                setTimeout(() => {
                    window.location.href = '<?= base_url('checkout') ?>';
                }, 1500);
            };

            return (
                <div>
                    <Navbar cartCount={totalQty} />
                    <PageHeader count={cartItems.length} />

                    <main className="max-w-7xl mx-auto px-4 py-8">

                        {/* Breadcrumb */}
                        <nav className="text-sm text-slate-400 mb-6 flex items-center gap-2">
                            <a href="<?= base_url('home') ?>"    className="hover:text-blue-600 transition">Home</a>
                            <span>›</span>
                            <a href="<?= base_url('katalog') ?>" className="hover:text-blue-600 transition">Katalog</a>
                            <span>›</span>
                            <span className="text-slate-600">Keranjang</span>
                        </nav>

                        {error && (
                            <div className="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm mb-6">
                                ⚠️ {error}
                            </div>
                        )}

                        {/* ── Layout grid ── */}
                        <div className="flex flex-col lg:flex-row gap-8 items-start">

                            {/* ── Left: Cart Items ── */}
                            <div className="flex-1 min-w-0 space-y-4">

                                {/* Toolbar pilih semua */}
                                {!loading && cartItems.length > 0 && (
                                    <div className="bg-white rounded-2xl px-5 py-3 flex items-center justify-between shadow-sm border border-slate-100">
                                        <label className="flex items-center gap-3 cursor-pointer select-none">
                                            <input
                                                type="checkbox"
                                                checked={allSelected}
                                                onChange={handleSelectAll}
                                                className="w-4 h-4 accent-blue-600"
                                            />
                                            <span className="text-sm font-semibold text-slate-700">
                                                Pilih Semua ({cartItems.length} item)
                                            </span>
                                        </label>
                                        <button
                                            onClick={handleClearAll}
                                            className="text-xs text-red-400 hover:text-red-600 font-medium transition flex items-center gap-1"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" className="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus Semua
                                        </button>
                                    </div>
                                )}

                                {/* Conditional rendering: loading / empty / items */}
                                {loading ? (
                                    <>
                                        <SkeletonCartItem />
                                        <SkeletonCartItem />
                                        <SkeletonCartItem />
                                    </>
                                ) : cartItems.length === 0 ? (
                                    <EmptyCart />
                                ) : (
                                    cartItems.map(item => (
                                        <CartItemCard
                                            key={item.id}
                                            item={item}
                                            onQtyChange={handleQtyChange}
                                            onRemove={handleRemove}
                                        />
                                    ))
                                )}

                                {/* Rekomendasi produk lain */}
                                {!loading && cartItems.length > 0 && (
                                    <div className="bg-blue-50 rounded-2xl px-5 py-4 border border-blue-100 text-sm text-blue-700 flex items-center justify-between">
                                        <span>🛍️ Mau tambah produk lain?</span>
                                        <a href="<?= base_url('katalog') ?>"
                                            className="font-semibold hover:underline">
                                            Lihat Katalog →
                                        </a>
                                    </div>
                                )}
                            </div>

                            {/* ── Right: Order Summary ── */}
                            <div className="w-full lg:w-80 flex-shrink-0">
                                {loading ? (
                                    <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                                        <div className="skeleton h-5 w-1/2 rounded"></div>
                                        <div className="skeleton h-4 w-full rounded"></div>
                                        <div className="skeleton h-4 w-3/4 rounded"></div>
                                        <div className="skeleton h-10 w-full rounded-xl mt-2"></div>
                                    </div>
                                ) : (
                                    <OrderSummary items={cartItems} onCheckout={handleCheckout} showToast={showToast} />
                                )}
                            </div>
                        </div>
                    </main>

                    <Footer />
                    <Toast visible={toast.visible} message={toast.message} />
                </div>
            );
        }

        ReactDOM.render(<App />, document.getElementById('root'));
    </script>
<script src="<?= base_url('assets/js/theme-toggle.js') ?>"></script>
</body>

</html>
