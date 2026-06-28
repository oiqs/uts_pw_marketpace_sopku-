<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>SopKu Admin — Login</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
	<link rel="icon" type="image/png" href="<?= base_url('assets/iqon/logo.png') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/theme.css') ?>">
	<style>
		body { font-family: 'DM Sans', sans-serif; }
		.admin-bg { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f4c75 100%); min-height: 100vh; }
		.input-field { transition: border-color 0.2s, box-shadow 0.2s; }
		.input-field:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
		@keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
		.fade-up { animation: fadeUp .4s ease forwards; }
		@keyframes float { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-8px); } }
		.float { animation: float 4s ease-in-out infinite; }
	</style>
</head>
<body class="admin-bg flex items-center justify-center px-4 py-12">
<div class="w-full max-w-md fade-up">

	<!-- Logo -->
	<div class="text-center mb-8">
		<div class="inline-flex items-center gap-3 mb-4">
			<img src="<?= base_url('assets/iqon/logo.png') ?>" alt="SopKu" class="h-12 w-auto object-contain">
			<div class="text-left">
				<h1 class="text-3xl font-bold text-white" style="font-family:'Playfair Display',serif">Sop<span class="text-blue-400">Ku</span></h1>
				<p class="text-blue-300 text-xs font-semibold tracking-widest uppercase mt-0.5">Admin Panel</p>
			</div>
		</div>
		<p class="text-slate-400 text-sm">Masuk dengan akun SopKu, lalu sistem mengarahkan sesuai role</p>
	</div>

	<!-- Card -->
	<div class="bg-white rounded-3xl shadow-2xl p-8">

		<?php if ($this->session->flashdata('error')): ?>
		<div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-2 text-sm">
			<span>⚠️</span><span><?= $this->session->flashdata('error') ?></span>
		</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('success')): ?>
		<div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 flex items-start gap-2 text-sm">
			<span>✅</span><span><?= $this->session->flashdata('success') ?></span>
		</div>
		<?php endif; ?>

		<h2 class="text-xl font-bold text-slate-800 mb-6 text-center" style="font-family:'Playfair Display',serif">Selamat Datang</h2>

		<?php echo form_open('admin/login', ['class' => 'space-y-5']); ?>

			<!-- Email -->
			<div>
				<label class="block text-xs font-semibold text-slate-500 mb-1.5">Email *</label>
				<div class="relative">
					<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
					</span>
					<input type="email" name="email" value="<?= set_value('email') ?>" placeholder="admin@sopku.com"
						class="input-field w-full border border-slate-200 rounded-xl px-4 py-3 pl-9 text-sm text-slate-700 bg-white" required />
				</div>
			</div>

			<!-- Password -->
			<div>
				<label class="block text-xs font-semibold text-slate-500 mb-1.5">Password *</label>
				<div class="relative">
					<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
					</span>
					<input type="password" name="password" id="adminPass" placeholder="••••••••"
						class="input-field w-full border border-slate-200 rounded-xl px-4 py-3 pl-9 pr-10 text-sm text-slate-700 bg-white" required />
					<button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 transition">
						<svg id="eyeShow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
						<svg id="eyeHide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
					</button>
				</div>
			</div>

			<!-- Remember -->
			<label class="flex items-center gap-2.5 cursor-pointer select-none">
				<input type="checkbox" name="remember" value="1" class="w-4 h-4 accent-blue-600 rounded" />
				<span class="text-sm text-slate-600">Ingat saya selama 7 hari</span>
			</label>

			<!-- Submit -->
			<button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition text-sm flex items-center justify-center gap-2">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
				Masuk
			</button>

		<?php echo form_close(); ?>

		<div class="mt-6 pt-5 border-t border-slate-100 text-center">
			<p class="text-xs text-slate-400">Ingin kembali belanja?
				<a href="<?= base_url('home') ?>" class="text-blue-600 hover:underline font-medium ml-1">Kembali ke Toko →</a>
			</p>
		</div>
	</div>


	<p class="text-center text-xs text-slate-500 mt-5">© 2026 SopKu Admin Panel. All rights reserved.</p>
</div>

<script>
function togglePass() {
	const el = document.getElementById('adminPass');
	const s = document.getElementById('eyeShow');
	const h = document.getElementById('eyeHide');
	if (el.type === 'password') { el.type='text'; s.classList.add('hidden'); h.classList.remove('hidden'); }
	else { el.type='password'; s.classList.remove('hidden'); h.classList.add('hidden'); }
}
</script>
<script src="<?= base_url('assets/js/theme-toggle.js') ?>"></script>
</body>
</html>
