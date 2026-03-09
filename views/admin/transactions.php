<?php $pageTitle = 'Transactions'; ob_start(); ?>
<div class="container">
    <h1>Transactions</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>User</th><th>Amount</th><th>Commission</th><th>Artist earnings</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            <?php foreach ($transactions as $t): ?>
                <tr>
                    <td>#<?= (int)$t['id'] ?></td>
                    <td><?= htmlspecialchars($t['user_name']) ?></td>
                    <td>₱<?= number_format($t['amount'], 2) ?></td>
                    <td>₱<?= number_format($t['platform_commission'] ?? 0, 2) ?></td>
                    <td>₱<?= number_format($t['artist_earnings'] ?? 0, 2) ?></td>
                    <td><?= htmlspecialchars($t['payment_method'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($t['status_name']) ?></td>
                    <td><?= date('M j, Y', strtotime($t['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
