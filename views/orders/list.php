<?php $pageTitle = 'My Orders'; ob_start(); ?>
<div class="container">
    <h1>My Orders</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?= (int)$o['id'] ?></td>
                    <td>₱<?= number_format($o['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars($o['status_name']) ?></td>
                    <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                    <td><a href="?page=order&id=<?= (int)$o['id'] ?>" class="btn btn-sm">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($orders)): ?><p class="empty">No orders yet.</p><?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
