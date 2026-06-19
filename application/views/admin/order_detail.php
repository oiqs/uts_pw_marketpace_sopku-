<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!$order): ?>
    <div class="rounded-lg border border-red-200 bg-red-50 p-5 text-red-700">Order tidak ditemukan.</div>
<?php else: ?>
<div class="grid gap-6 xl:grid-cols-[1fr_360px]">
    <div class="rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-lg font-bold text-slate-900"><?= $order['order_code'] ?></h2>
            <p class="text-sm text-slate-500"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <div class="divide-y divide-slate-100">
            <?php foreach ($items as $item): ?>
                <div class="flex gap-4 px-5 py-4">
                    <img src="<?= $item['image'] ?>" alt="" class="h-16 w-16 rounded-lg border border-slate-100 object-contain">
                    <div class="flex-1">
                        <p class="font-bold text-slate-900"><?= html_escape($item['title']) ?></p>
                        <p class="text-sm text-slate-500"><?= (int) $item['qty'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                    </div>
                    <p class="font-bold">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <aside class="space-y-5">
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <h3 class="mb-4 text-base font-bold text-slate-900">Ubah Status</h3>
            <form method="post" action="<?= base_url('admin/orders/status/' . $order['id']) ?>" class="flex gap-2">
                <select name="status" class="min-w-0 flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <?php foreach (['pending', 'diproses', 'dikirim', 'selesai', 'batal'] as $status): ?>
                        <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700">Simpan</button>
            </form>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 text-sm">
            <h3 class="mb-4 text-base font-bold text-slate-900">Pelanggan</h3>
            <p class="font-bold"><?= html_escape($order['nama']) ?></p>
            <p class="text-slate-500"><?= html_escape($order['email']) ?></p>
            <p class="mt-3 text-slate-600"><?= html_escape($order['telepon']) ?></p>
            <p class="mt-3 text-slate-600"><?= nl2br(html_escape($order['alamat'])) ?><br><?= html_escape($order['kota']) ?> <?= html_escape($order['kodepos']) ?></p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 text-sm">
            <h3 class="mb-4 text-base font-bold text-slate-900">Ringkasan</h3>
            <div class="space-y-2">
                <div class="flex justify-between"><span>Subtotal</span><b>Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></b></div>
                <div class="flex justify-between"><span>Promo</span><b>- Rp <?= number_format($order['promo_discount'], 0, ',', '.') ?></b></div>
                <div class="flex justify-between"><span>Ongkir</span><b>Rp <?= number_format($order['shipping_cost'], 0, ',', '.') ?></b></div>
                <div class="flex justify-between border-t border-slate-100 pt-3 text-base"><span>Total</span><b>Rp <?= number_format($order['total'], 0, ',', '.') ?></b></div>
            </div>
        </div>
    </aside>
</div>
<?php endif; ?>
