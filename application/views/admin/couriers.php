<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="grid gap-6 xl:grid-cols-[420px_1fr]">
    <form method="post" action="<?= base_url('admin/couriers') ?>" class="rounded-lg border border-slate-200 bg-white p-5">
        <h2 class="mb-4 text-lg font-bold text-slate-900">Simpan Kurir</h2>
        <input type="hidden" name="id" id="courier_id">
        <div class="grid gap-4">
            <input name="code" id="courier_code" placeholder="Kode, contoh jne-reg" required class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
            <input name="name" id="courier_name" placeholder="Nama kurir" required class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
            <input name="estimasi" id="courier_estimasi" placeholder="Estimasi, contoh 2-3 hari" class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
            <input type="number" step="100" name="price" id="courier_price" placeholder="Harga" required class="rounded-lg border border-slate-200 px-4 py-3 text-sm">
        </div>
        <button class="mt-4 w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700">Simpan</button>
    </form>
    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr><th class="px-5 py-3">Kode</th><th class="px-5 py-3">Nama</th><th class="px-5 py-3">Estimasi</th><th class="px-5 py-3">Harga</th><th class="px-5 py-3 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($couriers as $courier): ?>
                    <tr>
                        <td class="px-5 py-4 font-bold"><?= html_escape($courier['code']) ?></td>
                        <td class="px-5 py-4"><?= html_escape($courier['name']) ?></td>
                        <td class="px-5 py-4"><?= html_escape($courier['estimasi']) ?></td>
                        <td class="px-5 py-4">Rp <?= number_format($courier['price'], 0, ',', '.') ?></td>
                        <td class="px-5 py-4 text-right">
                            <button type="button" onclick='editCourier(<?= json_encode($courier) ?>)' class="font-bold text-blue-600">Edit</button>
                            <a href="<?= base_url('admin/couriers/delete/' . $courier['id']) ?>" onclick="return confirm('Hapus kurir ini?')" class="ml-3 font-bold text-red-600">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
function editCourier(c) {
    document.getElementById('courier_id').value = c.id;
    document.getElementById('courier_code').value = c.code;
    document.getElementById('courier_name').value = c.name;
    document.getElementById('courier_estimasi').value = c.estimasi;
    document.getElementById('courier_price').value = c.price;
}
</script>
