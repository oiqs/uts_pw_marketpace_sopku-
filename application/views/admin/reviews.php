<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-5 py-3">Produk</th>
                <th class="px-5 py-3">User</th>
                <th class="px-5 py-3">Rating</th>
                <th class="px-5 py-3">Komentar</th>
                <th class="px-5 py-3">Tanggal</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td class="px-5 py-4 font-bold text-slate-800"><?= html_escape($review['product_title']) ?></td>
                    <td class="px-5 py-4"><?= html_escape($review['user_name']) ?></td>
                    <td class="px-5 py-4 font-bold text-amber-500">
                        <?= str_repeat('★', $review['rating']) ?><span class="text-slate-300"><?= str_repeat('★', 5 - $review['rating']) ?></span>
                    </td>
                    <td class="px-5 py-4 text-slate-600 max-w-xs truncate" title="<?= html_escape($review['comment']) ?>">
                        <?= html_escape($review['comment'] ?: '-') ?>
                    </td>
                    <td class="px-5 py-4 text-slate-500"><?= date('d M Y, H:i', strtotime($review['created_at'])) ?></td>
                    <td class="px-5 py-4 text-right">
                        <a href="<?= base_url('admin/reviews/delete/' . $review['id']) ?>" onclick="return confirm('Hapus ulasan ini? Rating produk juga akan disesuaikan otomatis.')" class="font-bold text-red-600 hover:underline">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reviews)): ?>
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada ulasan produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
