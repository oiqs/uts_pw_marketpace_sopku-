<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="grid gap-6 lg:grid-cols-[360px_1fr]">
    <form method="post" action="<?= base_url('admin/promos') ?>" class="rounded-lg border border-slate-200 bg-white p-5">
        <h2 class="mb-4 text-lg font-bold text-slate-900">Simpan Promo</h2>
        <input type="hidden" name="id" id="promo_id">
        <div class="grid gap-4">
            <input name="code" id="promo_code" placeholder="Kode promo" required class="uppercase rounded-lg border border-slate-200 px-4 py-3 text-sm">
            <input type="number" step="0.01" name="discount_percent" id="promo_discount" placeholder="Diskon %" required class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                <input type="checkbox" name="is_active" id="promo_active" value="1" checked class="h-4 w-4 accent-blue-600"> Aktif
            </label>
        </div>
        <button class="mt-4 w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700">Simpan</button>
    </form>
    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr><th class="px-5 py-3">Kode</th><th class="px-5 py-3">Diskon</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($promos as $promo): ?>
                    <tr>
                        <td class="px-5 py-4 font-bold"><?= html_escape($promo['code']) ?></td>
                        <td class="px-5 py-4"><?= $promo['discount_percent'] ?>%</td>
                        <td class="px-5 py-4"><?= $promo['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                        <td class="px-5 py-4 text-right">
                            <button type="button" onclick='editPromo(<?= json_encode($promo) ?>)' class="font-bold text-blue-600">Edit</button>
                            <a href="<?= base_url('admin/promos/delete/' . $promo['id']) ?>" onclick="return confirm('Hapus promo ini?')" class="ml-3 font-bold text-red-600">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
function editPromo(p) {
    document.getElementById('promo_id').value = p.id;
    document.getElementById('promo_code').value = p.code;
    document.getElementById('promo_discount').value = p.discount_percent;
    document.getElementById('promo_active').checked = p.is_active == 1;
}
</script>
