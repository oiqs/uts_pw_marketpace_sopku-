<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
    <table class="w-full min-w-[900px] text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-5 py-3">Kode</th>
                <th class="px-5 py-3">Pelanggan</th>
                <th class="px-5 py-3">Kurir</th>
                <th class="px-5 py-3">Total</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Tanggal</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="px-5 py-4 font-bold text-blue-600"><?= $order['order_code'] ?></td>
                    <td class="px-5 py-4"><?= html_escape($order['nama']) ?><br><span class="text-xs text-slate-500"><?= html_escape($order['email']) ?></span></td>
                    <td class="px-5 py-4"><?= html_escape($order['courier_name'] ?: '-') ?></td>
                    <td class="px-5 py-4">Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                    <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700"><?= $order['status'] ?></span></td>
                    <td class="px-5 py-4 text-slate-500"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                    <td class="px-5 py-4 text-right"><a href="<?= base_url('admin/orders/' . $order['id']) ?>" class="font-bold text-blue-600 hover:text-blue-800">Detail</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
