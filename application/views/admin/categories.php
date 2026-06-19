<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="grid gap-6 lg:grid-cols-[360px_1fr]">
    <form method="post" action="<?= base_url('admin/categories') ?>" class="rounded-lg border border-slate-200 bg-white p-5">
        <h2 class="mb-4 text-lg font-bold text-slate-900">Simpan Kategori</h2>
        <input type="hidden" name="id" id="category_id">
        <label class="block">
            <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Nama Kategori</span>
            <input name="name" id="category_name" required class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
        </label>
        <button class="mt-4 w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700">Simpan</button>
    </form>
    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr><th class="px-5 py-3">Kategori</th><th class="px-5 py-3 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td class="px-5 py-4 font-bold"><?= html_escape($category['name']) ?></td>
                        <td class="px-5 py-4 text-right">
                            <button type="button" onclick='editCategory(<?= json_encode($category) ?>)' class="font-bold text-blue-600">Edit</button>
                            <a href="<?= base_url('admin/categories/delete/' . $category['id']) ?>" onclick="return confirm('Hapus kategori ini?')" class="ml-3 font-bold text-red-600">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
function editCategory(category) {
    document.getElementById('category_id').value = category.id;
    document.getElementById('category_name').value = category.name;
}
</script>
