<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$cards = [
    ['Total Produk', $stats['products'], 'bg-blue-600'],
    ['Total Order', $stats['orders'], 'bg-slate-900'],
    ['Total User', $stats['users'], 'bg-emerald-600'],
    ['Revenue', 'Rp ' . number_format($stats['revenue'], 0, ',', '.'), 'bg-amber-500'],
];
?>
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <?php foreach ($cards as $card): ?>
        <div class="rounded-lg <?= $card[2] ?> p-5 text-white shadow-sm">
            <p class="text-sm font-semibold text-white/75"><?= $card[0] ?></p>
            <p class="mt-3 text-2xl font-bold"><?= $card[1] ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt-8 rounded-lg border border-slate-200 bg-white">
    <div class="border-b border-slate-200 px-5 py-4 flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-900">Order Terbaru</h2>
        <a href="<?= base_url('admin/orders') ?>" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-3">Kode</th>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Total</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($latest_orders as $order): ?>
                    <tr>
                        <td class="px-5 py-4 font-semibold text-blue-600"><a href="<?= base_url('admin/orders/' . $order['id']) ?>"><?= $order['order_code'] ?></a></td>
                        <td class="px-5 py-4"><?= $order['nama'] ?></td>
                        <td class="px-5 py-4">Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                        <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700"><?= $order['status'] ?></span></td>
                        <td class="px-5 py-4 text-slate-500"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($latest_orders)): ?>
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">Belum ada order.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
