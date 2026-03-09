<?php $pageTitle = 'Disputes'; ob_start(); ?>
<div class="container">
    <h1>Disputes</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>Commission/Order</th><th>Raised by</th><th>Reason</th><th>Status</th><th>Created</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($disputes as $d): ?>
                <tr>
                    <td>#<?= (int)$d['id'] ?></td>
                    <td>Comm: <?= (int)($d['commission_id'] ?? 0) ?> / Order: <?= (int)($d['order_id'] ?? 0) ?></td>
                    <td><?= htmlspecialchars($d['raised_by_name']) ?></td>
                    <td><?= htmlspecialchars(substr($d['reason'] ?? '', 0, 100)) ?></td>
                    <td><?= htmlspecialchars($d['status']) ?></td>
                    <td><?= date('M j, Y', strtotime($d['created_at'])) ?></td>
                    <td>
                        <?php if ($d['status'] === 'open'): ?>
                            <form method="post">
                                <input type="hidden" name="action" value="dispute_resolve">
                                <input type="hidden" name="dispute_id" value="<?= (int)$d['id'] ?>">
                                <input type="text" name="admin_notes" placeholder="Notes" class="input input-sm">
                                <button type="submit" class="btn btn-sm btn-primary">Resolve</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
