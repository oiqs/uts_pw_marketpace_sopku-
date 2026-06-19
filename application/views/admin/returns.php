<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
    <table class="w-full min-w-[900px] text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-5 py-3">Kode Return</th>
                <th class="px-5 py-3">Order</th>
                <th class="px-5 py-3">User</th>
                <th class="px-5 py-3">Alasan</th>
                <th class="px-5 py-3">Refund</th>
                <th class="px-5 py-3">Catatan</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($returns as $row): ?>
                <tr>
                    <td class="px-5 py-4 font-bold text-blue-600"><?= html_escape($row['return_code']) ?></td>
                    <td class="px-5 py-4"><?= html_escape($row['order_code']) ?></td>
                    <td class="px-5 py-4">
                        <?= html_escape($row['user_name'] ?: '-') ?><br>
                        <span class="text-xs text-slate-500"><?= html_escape($row['user_email'] ?: '') ?></span>
                    </td>
                    <td class="px-5 py-4"><?= html_escape($row['reason']) ?></td>
                    <td class="px-5 py-4">Rp <?= number_format($row['refund_amount'], 0, ',', '.') ?></td>
                    <td class="px-5 py-4 text-slate-500"><?= html_escape($row['admin_note'] ?: '-') ?></td>
                    <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700"><?= html_escape($row['status']) ?></span></td>
                    <td class="px-5 py-4 text-right"><a href="<?= base_url('admin/returns/' . $row['id']) ?>" class="font-bold text-blue-600 hover:text-blue-800">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($returns)): ?>
                <tr><td colspan="8" class="px-5 py-8 text-center text-slate-500">Belum ada pengajuan return.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
