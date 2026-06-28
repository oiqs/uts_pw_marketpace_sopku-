<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SopKu - Login</title>
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
        .input-field {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-field.error {
            border-color: #ef4444;
        }

        .input-field.error:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-slide-up {
            animation: fadeSlideUp 0.4s ease forwards;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-8px);
            }

            40% {
                transform: translateX(8px);
            }

            60% {
                transform: translateX(-5px);
            }

            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.4s ease;
        }

        @keyframes checkPop {
            0% {
                transform: scale(0) rotate(-10deg);
            }

            70% {
                transform: scale(1.2) rotate(5deg);
            }

            100% {
                transform: scale(1) rotate(0deg);
            }
        }

        .check-pop {
            animation: checkPop 0.5s ease forwards;
        }

        .password-toggle {
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        /* Split layout background */
        .login-bg-panel {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f4c75 100%);
        }

        .floating-card {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .social-btn {
            transition: all 0.2s ease;
            border: 1.5px solid #e2e8f0;
        }

        .social-btn:hover {
            border-color: #2563eb;
            background: #eff6ff;
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="bg-slate-50">
    <div id="root"></div>

    <script type="text/babel" data-presets="react,env">
        const { useState, useEffect } = React;
        const API_BASE = "<?= base_url('api/') ?>";



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
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-slate-700 hover:text-blue-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

        /* ===================== EYE ICON ===================== */
        function EyeIcon({ open }) {
            return open ? (
                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            ) : (
                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            );
        }

        /* ===================== LOGIN SUCCESS VIEW ===================== */
        function LoginSuccess({ user, onLogout }) {
            return (
                <div className="fade-slide-up min-h-[60vh] flex flex-col items-center justify-center py-16 px-4 text-center">
                    <div className="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 check-pop">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 className="text-3xl font-bold text-slate-800 mb-2">
                        Selamat Datang, {user.name}! 👋
                    </h2>
                    <p className="text-slate-500 text-sm mb-2">
                        Kamu berhasil masuk sebagai <strong>{user.email}</strong>
                    </p>
                    <p className="text-slate-400 text-xs mb-8">
                        Kamu sekarang bisa menikmati semua fitur SopKu
                    </p>

                    {/* User Badge */}
                    <div className="bg-blue-50 border border-blue-100 rounded-2xl px-8 py-5 mb-8 inline-block">
                        <div className="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span className="text-white text-xl font-bold">
                                {user.name.charAt(0)}
                            </span>
                        </div>
                        <p className="font-bold text-slate-800">{user.name}</p>
                        <p className="text-slate-500 text-xs">{user.email}</p>
                        <span className="inline-block mt-2 bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                            ● Online
                        </span>
                    </div>

                    <div className="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="<?= base_url('home') ?>"
                            className="btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl transition text-center">
                            🏠 Ke Halaman Home
                        </a>
                        <a href="<?= base_url('katalog') ?>"
                            className="btn-primary bg-slate-800 hover:bg-slate-900 text-white font-semibold px-8 py-3 rounded-xl transition text-center">
                            🛍️ Mulai Belanja
                        </a>
                        <button onClick={onLogout}
                            className="border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold px-8 py-3 rounded-xl transition">
                            Logout
                        </button>
                    </div>
                </div>
            );
        }

        /* ===================== LOGIN FORM ===================== */
        function LoginForm({ onLoginSuccess }) {
            const [mode, setMode]               = useState('login'); // 'login' | 'register'
            const [email, setEmail]             = useState('');
            const [password, setPassword]       = useState('');
            const [nama, setNama]               = useState('');
            const [showPass, setShowPass]       = useState(false);
            const [rememberMe, setRememberMe]   = useState(false);
            const [loading, setLoading]         = useState(false);
            const [errors, setErrors]           = useState({});
            const [shakeKey, setShakeKey]       = useState(0);
            const [registerSuccess, setRegisterSuccess] = useState(false);

            useEffect(() => {
                const urlParams = new URLSearchParams(window.location.search);
                const tab = urlParams.get('tab');
                if (tab === 'register') {
                    setMode('register');
                } else {
                    setMode('login');
                }
            }, []);

            /* ---- Validation ---- */
            const validateLogin = () => {
                const e = {};
                if (!email.trim())    e.email    = 'Email wajib diisi';
                else if (!/\S+@\S+\.\S+/.test(email)) e.email = 'Format email tidak valid';
                if (!password.trim()) e.password = 'Password wajib diisi';
                setErrors(e);
                return Object.keys(e).length === 0;
            };

            const validateRegister = () => {
                const e = {};
                if (!nama.trim())     e.nama     = 'Nama wajib diisi';
                if (!email.trim())    e.email    = 'Email wajib diisi';
                else if (!/\S+@\S+\.\S+/.test(email)) e.email = 'Format email tidak valid';
                if (!password.trim()) e.password = 'Password wajib diisi';
                else if (password.length < 6) e.password = 'Password minimal 6 karakter';
                setErrors(e);
                return Object.keys(e).length === 0;
            };

            /* ---- Submit Login ---- */
            const handleLogin = () => {
                if (!validateLogin()) return;
                setLoading(true);
                axios.post(API_BASE + 'login', { email, password })
                    .then(res => {
                        setLoading(false);
                        if (res.data.redirect_url && res.data.user?.role === 'admin') {
                            window.location.href = res.data.redirect_url;
                            return;
                        }
                        onLoginSuccess(res.data.user);
                    })
                    .catch(err => {
                        setLoading(false);
                        setErrors({ general: err.response?.data?.message || 'Email atau password salah.' });
                        setShakeKey(k => k + 1);
                    });
            };

            /* ---- Submit Register (simulasi) ---- */
            const handleRegister = () => {
                if (!validateRegister()) return;
                setLoading(true);
                axios.post(API_BASE + 'register', { nama, email, password })
                    .then(() => {
                        setLoading(false);
                        setRegisterSuccess(true);
                        setTimeout(() => {
                            setMode('login');
                            setRegisterSuccess(false);
                            setNama(''); setEmail(''); setPassword('');
                        }, 2000);
                    })
                    .catch(err => {
                        setLoading(false);
                        setErrors({ general: err.response?.data?.message || 'Registrasi gagal.' });
                    });
            };
            const handleKeyDown = (e) => {
                if (e.key === 'Enter') {
                    mode === 'login' ? handleLogin() : handleRegister();
                }
            };

            const inputClass = (field) =>
                `input-field w-full border rounded-xl px-4 py-3 text-sm text-slate-700 bg-white
                ${errors[field] ? 'error border-red-300' : 'border-slate-200'}`;

            /* ---- Register Success ---- */
            if (registerSuccess) {
                return (
                    <div className="text-center py-8 fade-slide-up">
                        <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 check-pop">
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-bold text-slate-800 mb-1">Akun Berhasil Dibuat!</h3>
                        <p className="text-slate-500 text-sm">Mengalihkan ke halaman login...</p>
                    </div>
                );
            }

            return (
                <div className="fade-slide-up">
                    <div className="flex flex-col items-center mb-6">
                        <img src="<?= base_url('assets/iqon/logo.png') ?>" alt="Logo SopKu" className="h-12 w-auto object-contain mb-2" />
                        <h2 className="text-xl font-bold text-slate-800" style={{ fontFamily: 'Playfair Display, serif' }}>
                            Sop<span className="text-blue-600">Ku</span>
                        </h2>
                    </div>
                    {/* Tab Toggle */}
                    <div className="flex bg-slate-100 rounded-xl p-1 mb-6">
                        {['login', 'register'].map(m => (
                            <button key={m} onClick={() => { setMode(m); setErrors({}); }}
                                className={`flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 capitalize
                                    ${mode === m ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}>
                                {m === 'login' ? 'Masuk' : 'Daftar'}
                            </button>
                        ))}
                    </div>

                    {/* Error umum */}
                    {errors.general && (
                        <div key={shakeKey} className="shake bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-4 flex items-start gap-2">
                            <span className="text-red-500 mt-0.5">⚠️</span>
                            <span>{errors.general}</span>
                        </div>
                    )}

                    <div className="space-y-4" onKeyDown={handleKeyDown}>

                        {/* Nama (Register only) */}
                        {mode === 'register' && (
                            <div>
                                <label className="block text-xs font-semibold text-slate-500 mb-1.5">
                                    Nama Lengkap *
                                </label>
                                <input type="text" placeholder="Contoh: Budi Santoso"
                                    value={nama} onChange={e => setNama(e.target.value)}
                                    className={inputClass('nama')} />
                                {errors.nama && <p className="text-red-500 text-xs mt-1">{errors.nama}</p>}
                            </div>
                        )}

                        {/* Email */}
                        <div>
                            <label className="block text-xs font-semibold text-slate-500 mb-1.5">
                                Alamat Email *
                            </label>
                            <div className="relative">
                                <span className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <input type="email" placeholder="email@contoh.com"
                                    value={email} onChange={e => setEmail(e.target.value)}
                                    className={inputClass('email') + ' pl-9'} />
                            </div>
                            {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
                        </div>

                        {/* Password */}
                        <div>
                            <div className="flex items-center justify-between mb-1.5">
                                <label className="text-xs font-semibold text-slate-500">Password *</label>
                                {mode === 'login' && (
                                    <button type="button" className="text-xs text-blue-600 hover:underline font-medium">
                                        Lupa password?
                                    </button>
                                )}
                            </div>
                            <div className="relative">
                                <span className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input
                                    type={showPass ? 'text' : 'password'}
                                    placeholder={mode === 'register' ? 'Min. 6 karakter' : 'Masukkan password'}
                                    value={password} onChange={e => setPassword(e.target.value)}
                                    className={inputClass('password') + ' pl-9 pr-10'} />
                                <button type="button"
                                    onClick={() => setShowPass(p => !p)}
                                    className="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <EyeIcon open={showPass} />
                                </button>
                            </div>
                            {errors.password && <p className="text-red-500 text-xs mt-1">{errors.password}</p>}
                        </div>

                        {/* Remember Me (Login only) */}
                        {mode === 'login' && (
                            <label className="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" checked={rememberMe}
                                    onChange={() => setRememberMe(p => !p)}
                                    className="w-4 h-4 accent-blue-600 rounded" />
                                <span className="text-sm text-slate-600">Ingat saya</span>
                            </label>
                        )}

                        {/* Submit Button */}
                        <button
                            onClick={mode === 'login' ? handleLogin : handleRegister}
                            disabled={loading}
                            className="w-full btn-primary bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold py-3.5 rounded-xl transition flex items-center justify-center gap-2 mt-2">
                            {loading ? (
                                <>
                                    <svg className="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    {mode === 'login' ? 'Sedang masuk...' : 'Mendaftarkan...'}
                                </>
                            ) : (
                                mode === 'login' ? '🔐 Masuk ke Akun' : '✨ Buat Akun Baru'
                            )}
                        </button>
                    </div>

                    {/* Divider */}
                    <div className="flex items-center gap-3 my-5">
                        <div className="flex-1 h-px bg-slate-200"></div>
                        <span className="text-xs text-slate-400 font-medium">atau lanjutkan dengan</span>
                        <div className="flex-1 h-px bg-slate-200"></div>
                    </div>

                    {/* Social Login (simulasi) */}
                    <div className="grid grid-cols-2 gap-3">
                        <button className="social-btn flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white text-slate-700 text-sm font-semibold">
                            <svg className="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Google
                        </button>
                        <button className="social-btn flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white text-slate-700 text-sm font-semibold">
                            <svg className="w-4 h-4" fill="#1877F2" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </button>
                    </div>


                </div>
            );
        }

        /* ===================== APP ROOT ===================== */
        function App() {
            const [loggedInUser, setLoggedInUser] = useState(null);
            const [cartCount, setCartCount] = useState(0);

            useEffect(() => {
                axios.get(API_BASE + 'me')
                    .then(res => {
                        if (res.data.logged_in) {
                            setLoggedInUser({ name: res.data.name, email: res.data.email });
                        }
                    })
                    .catch(() => {});

                axios.get(API_BASE + 'cart')
                    .then(res => setCartCount(res.data.reduce((s, i) => s + i.qty, 0)))
                    .catch(() => {});
            }, []);

            return (
                <div className="min-h-screen flex flex-col">
                    <Navbar cartCount={cartCount} />

                    {/* Page Header */}
                    <header className="hero-bg text-white py-10 px-4">
                        <div className="max-w-7xl mx-auto">
                            <p className="text-blue-300 text-sm font-medium mb-1">🔐 Akun Saya</p>
                            <h1 className="text-3xl md:text-4xl font-bold mb-1">
                                {loggedInUser ? `Halo, ${loggedInUser.name}!` : 'Masuk ke ShopKu'}
                            </h1>
                            <p className="text-slate-400 text-sm">
                                {loggedInUser
                                    ? 'Kamu sudah berhasil masuk ke akun ShopKu'
                                    : 'Login atau daftar untuk pengalaman belanja yang lebih baik'}
                            </p>
                        </div>
                    </header>

                    <main className="flex-1 max-w-7xl mx-auto w-full px-4 py-10">

                        {/* Breadcrumb */}
                        <nav className="text-sm text-slate-400 mb-8 flex items-center gap-2">
                            <a href="<?= base_url('home') ?>"    className="hover:text-blue-600 transition">Home</a>
                            <span>›</span>
                            <span className="text-slate-600">Login</span>
                        </nav>

                        {loggedInUser ? (
                            <LoginSuccess
                                user={loggedInUser}
                                onLogout={() => {
                                    window.location.href = "<?= base_url('home/logout') ?>";
                                }}
                            />
                        ) : (
                            <div className="flex flex-col lg:flex-row gap-10 items-start justify-center">

                                {/* ── LEFT: Info Panel ── */}
                                <div className="hidden lg:flex flex-col justify-center w-full max-w-md">
                                    <div className="login-bg-panel rounded-3xl p-10 text-white relative overflow-hidden">
                                        {/* Decorative circles */}
                                        <div className="absolute -top-12 -right-12 w-40 h-40 bg-blue-500 bg-opacity-20 rounded-full"></div>
                                        <div className="absolute -bottom-8 -left-8 w-32 h-32 bg-blue-400 bg-opacity-10 rounded-full"></div>

                                        <div className="relative z-10">
                                            <h2 className="text-3xl font-bold mb-3"
                                                style={{ fontFamily: 'Playfair Display, serif' }}>
                                                Belanja Lebih <span className="text-blue-400">Mudah</span>
                                            </h2>
                                            <p className="text-slate-400 text-sm leading-relaxed mb-8">
                                                Masuk ke akun SopKu dan nikmati berbagai keuntungan eksklusif untuk pelanggan setia kami.
                                            </p>

                                            {/* Benefits */}
                                            {[
                                                { icon: '📦', title: 'Lacak Pesanan',      desc: 'Pantau status pengiriman real-time' },
                                                { icon: '❤️', title: 'Wishlist Produk',    desc: 'Simpan produk favoritmu'           },
                                                { icon: '🏷️', title: 'Promo Eksklusif',   desc: 'Akses diskon member spesial'       },
                                                { icon: '🔒', title: 'Transaksi Aman',     desc: 'Data terlindungi 100%'             },
                                            ].map((b, i) => (
                                                <div key={i} className="flex items-start gap-4 mb-5">
                                                    <div className="w-10 h-10 bg-white bg-opacity-10 rounded-xl flex items-center justify-center flex-shrink-0 text-lg">
                                                        {b.icon}
                                                    </div>
                                                    <div>
                                                        <p className="font-semibold text-sm">{b.title}</p>
                                                        <p className="text-slate-400 text-xs">{b.desc}</p>
                                                    </div>
                                                </div>
                                            ))}

                                            {/* Floating stats */}
                                            <div className="floating-card mt-8 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-2xl p-4 flex items-center gap-4">
                                                <div className="flex -space-x-2">
                                                    {['B', 'D', 'T'].map((l, i) => (
                                                        <div key={i}
                                                            className="w-8 h-8 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-xs font-bold text-white">
                                                            {l}
                                                        </div>
                                                    ))}
                                                </div>
                                                <div>
                                                    <p className="text-sm font-semibold">10.000+ Pelanggan</p>
                                                    <p className="text-xs text-slate-400">Sudah bergabung dengan SopKu</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* ── RIGHT: Form ── */}
                                <div className="w-full max-w-md mx-auto lg:mx-0">
                                    <div className="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                                        <LoginForm onLoginSuccess={() => window.location.reload()} />
                                    </div>

                                    {/* Terms */}
                                    <p className="text-center text-xs text-slate-400 mt-4 px-4">
                                        Dengan masuk atau mendaftar, kamu menyetujui{' '}
                                        <a href="#" className="text-blue-600 hover:underline">Syarat & Ketentuan</a>{' '}
                                        dan{' '}
                                        <a href="#" className="text-blue-600 hover:underline">Kebijakan Privasi</a>{' '}
                                        SopKu.
                                    </p>
                                </div>
                            </div>
                        )}
                    </main>

                    {/* Footer */}
                    <footer className="bg-slate-800 text-slate-300 mt-8">
                        <div className="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <div className="flex items-center gap-2 mb-3">
                                    <img src="<?= base_url('assets/iqon/logo.png') ?>" alt="Logo SopKu" className="h-7 w-auto object-contain" />
                                    <h3 className="text-white text-xl font-bold"
                                        style={{ fontFamily: 'Playfair Display, serif' }}>
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
                </div>
            );
        }

        ReactDOM.render(<App />, document.getElementById('root'));
    </script>
<script src="<?= base_url('assets/js/theme-toggle.js') ?>"></script>
</body>

</html>
