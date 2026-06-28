<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $is_edit = !empty($product); ?>
<form method="post" enctype="multipart/form-data" action="<?= $is_edit ? base_url('admin/products/edit/' . $product['id']) : base_url('admin/products/create') ?>" class="max-w-4xl rounded-lg border border-slate-200 bg-white p-5 md:p-6">
    <div class="grid gap-5 md:grid-cols-2">
        <label class="block md:col-span-2">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Nama Produk</span>
            <input name="title" value="<?= html_escape($product['title'] ?? '') ?>" required class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Kategori</span>
            <select name="category_id" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                <option value="">Tanpa kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= (int) ($product['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= html_escape($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="block">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Harga</span>
            <input type="number" step="100" name="price" value="<?= html_escape($product['price'] ?? '') ?>" required class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        </label>
        <label class="block md:col-span-2">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Gambar Produk</span>
            <input type="file" name="image" accept="image/*" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
            <?php if (!empty($product['image'])): ?>
                <div class="mt-2 text-xs text-slate-500">Gambar saat ini: <a href="<?= html_escape($product['image']) ?>" target="_blank" class="text-blue-500 hover:underline">Lihat Gambar</a></div>
            <?php endif; ?>
        </label>
        <label class="block md:col-span-2">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Deskripsi</span>
            <textarea name="description" rows="5" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none"><?= html_escape($product['description'] ?? '') ?></textarea>
        </label>
        <label class="block">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Rating</span>
            <input type="number" step="0.1" max="5" name="rating_rate" value="<?= html_escape($product['rating_rate'] ?? '0') ?>" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Jumlah Rating</span>
            <input type="number" name="rating_count" value="<?= html_escape($product['rating_count'] ?? '0') ?>" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        </label>
        <label class="block">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Stok</span>
            <input type="number" name="stock" value="<?= html_escape($product['stock'] ?? '0') ?>" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        </label>
    </div>
    <div class="mt-6 flex gap-3">
        <button class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-bold text-white hover:bg-blue-700">Simpan Produk</button>
        <a href="<?= base_url('admin/products') ?>" class="rounded-lg border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50">Batal</a>
    </div>
</form>
