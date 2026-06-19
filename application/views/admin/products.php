<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">Kelola produk, stok, kategori, harga, dan URL gambar.</p>
    <a href="<?= base_url('admin/products/create') ?>" class="inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700">Tambah Produk</a>
</div>

<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
    <table class="w-full min-w-[900px] text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-5 py-3">Produk</th>
                <th class="px-5 py-3">Kategori</th>
                <th class="px-5 py-3">Harga</th>
                <th class="px-5 py-3">Stok</th>
                <th class="px-5 py-3">Rating</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="<?= $product['image'] ?>" alt="" class="h-12 w-12 rounded-lg border border-slate-100 object-contain">
                            <div class="max-w-md">
                                <p class="font-bold text-slate-900"><?= html_escape($product['title']) ?></p>
                                <p class="truncate text-xs text-slate-500"><?= html_escape($product['description']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4"><?= html_escape($product['category']) ?></td>
                    <td class="px-5 py-4">Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                    <td class="px-5 py-4"><?= (int) $product['stock'] ?></td>
                    <td class="px-5 py-4"><?= $product['rating_rate'] ?> (<?= $product['rating_count'] ?>)</td>
                    <td class="px-5 py-4 text-right">
                        <a href="<?= base_url('admin/products/edit/' . $product['id']) ?>" class="font-bold text-blue-600 hover:text-blue-800">Edit</a>
                        <a href="<?= base_url('admin/products/delete/' . $product['id']) ?>" onclick="return confirm('Hapus produk ini?')" class="ml-3 font-bold text-red-600 hover:text-red-800">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
