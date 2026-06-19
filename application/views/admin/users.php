<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
    <table class="w-full min-w-[720px] text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-5 py-3">Nama</th>
                <th class="px-5 py-3">Email</th>
                <th class="px-5 py-3">Role</th>
                <th class="px-5 py-3">Terdaftar</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="px-5 py-4 font-bold"><?= html_escape($user['name']) ?></td>
                    <td class="px-5 py-4"><?= html_escape($user['email']) ?></td>
                    <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold"><?= html_escape($user['role'] ?? 'user') ?></span></td>
                    <td class="px-5 py-4 text-slate-500"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                    <td class="px-5 py-4 text-right">
                        <?php if (($user['role'] ?? 'user') !== 'admin'): ?>
                            <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" onclick="return confirm('Hapus user ini?')" class="font-bold text-red-600 hover:text-red-800">Hapus</a>
                        <?php else: ?>
                            <span class="text-slate-400">Dilindungi</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
