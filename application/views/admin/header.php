<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> - SopKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= base_url('assets/iqon/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css') ?>">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3, .brand { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">
<?php
$menu = [
    'dashboard' => ['Dashboard', 'admin/dashboard'],
    'products' => ['Produk', 'admin/products'],
    'orders' => ['Order', 'admin/orders'],
    'returns' => ['Return', 'admin/returns'],
    'users' => ['User', 'admin/users'],
    'categories' => ['Kategori', 'admin/categories'],
    'couriers' => ['Kurir', 'admin/couriers'],
    'promos' => ['Promo', 'admin/promos'],
    'reviews' => ['Ulasan', 'admin/reviews'],
];
$segment = $this->uri->segment(2) ?: 'dashboard';
?>
<div class="min-h-screen lg:flex">
    <aside class="bg-slate-950 text-white lg:w-72">
        <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between lg:block">
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3">
                <img src="<?= base_url('assets/iqon/logo.png') ?>" alt="SopKu" class="h-10 w-10 object-contain">
                <div>
                    <div class="brand text-2xl font-bold">Sop<span class="text-blue-400">Ku</span></div>
                    <div class="text-[11px] uppercase tracking-[0.25em] text-slate-400">Admin</div>
                </div>
            </a>
            <a href="<?= base_url('admin/logout') ?>" class="lg:hidden text-xs text-slate-300">Logout</a>
        </div>
        <nav class="px-3 py-4 grid grid-cols-2 gap-2 lg:block lg:space-y-1">
            <?php foreach ($menu as $key => $item): ?>
                <?php $active = $segment === $key || ($segment === 'product_create' && $key === 'products'); ?>
                <a href="<?= base_url($item[1]) ?>" class="<?= $active ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' ?> block rounded-lg px-4 py-3 text-sm font-semibold transition">
                    <?= $item[0] ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="flex-1 min-w-0">
        <header class="bg-white border-b border-slate-200 px-5 md:px-8 py-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-600">Panel Admin</p>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mt-1"><?= $title ?? 'Dashboard' ?></h1>
            </div>
            <div class="hidden sm:flex items-center gap-3">
                <a href="<?= base_url() ?>" class="text-sm font-semibold text-slate-500 hover:text-blue-600">Lihat Toko</a>
                <a href="<?= base_url('admin/logout') ?>" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Logout</a>
            </div>
        </header>

        <section class="p-5 md:p-8">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
