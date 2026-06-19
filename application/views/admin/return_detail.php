<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!$return): ?>
    <div class="rounded-lg border border-red-200 bg-red-50 p-5 text-red-700">Return tidak ditemukan.</div>
<?php else: ?>
<div class="grid gap-6 xl:grid-cols-[1fr_380px]">
    <div class="rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-lg font-bold text-slate-900"><?= html_escape($return['return_code']) ?></h2>
            <p class="text-sm text-slate-500">Order <?= html_escape($return['order_code']) ?> oleh <?= html_escape($return['user_name'] ?: '-') ?></p>
        </div>
        <div class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
                <div class="flex gap-4 px-5 py-4">
                    <img src="<?= html_escape($item['image']) ?>" alt="" class="h-16 w-16 rounded-lg border border-slate-100 object-contain">
                    <div class="flex-1">
                        <p class="font-bold text-slate-900"><?= html_escape($item['title']) ?></p>
                        <p class="text-sm text-slate-500"><?= (int) $item['qty'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                    </div>
                    <p class="font-bold">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <aside class="space-y-5">
        <div class="rounded-lg border border-slate-200 bg-white p-5 text-sm">
            <h3 class="mb-4 text-base font-bold text-slate-900">Informasi Return</h3>
            <div class="space-y-3 text-slate-600">
                <p><b>Alasan:</b><br><?= html_escape($return['reason']) ?></p>
                <p><b>Deskripsi:</b><br><?= nl2br(html_escape($return['description'] ?: '-')) ?></p>
                <p><b>Bukti:</b><br>
                    <?php if ($return['evidence_image']): ?>
                        <a href="<?= html_escape($return['evidence_image']) ?>" target="_blank" class="font-bold text-blue-600">Buka gambar</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </p>
                <p><b>Estimasi Refund:</b><br>Rp <?= number_format($return['refund_amount'], 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <h3 class="mb-4 text-base font-bold text-slate-900">Ubah Status</h3>
            <form method="post" action="<?= base_url('admin/returns/status/' . $return['id']) ?>" class="space-y-3">
                <select name="status" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <?php foreach (['diajukan', 'disetujui', 'ditolak', 'barang_dikirim', 'barang_diterima', 'refund_diproses', 'selesai', 'dibatalkan'] as $status): ?>
                        <option value="<?= $status ?>" <?= $return['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="admin_note" rows="4" placeholder="Catatan admin" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"><?= html_escape($return['admin_note'] ?? '') ?></textarea>
                <button class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700">Simpan Status</button>
            </form>
        </div>
    </aside>
</div>
<?php endif; ?>
