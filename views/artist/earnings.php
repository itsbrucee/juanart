<?php $pageTitle = 'Earnings'; ob_start(); ?>
<div class="container">
    <h1>Earnings & Payout</h1>
    <p><strong>Balance:</strong> ₱<?= number_format($profile['balance'], 2) ?></p>
    <p><strong>Total earnings:</strong> ₱<?= number_format($profile['total_earnings'], 2) ?></p>
    <?php if ($profile['balance'] > 0): ?>
        <form method="post">
            <input type="hidden" name="action" value="payout_request">
            <button type="submit" class="btn btn-primary">Request payout</button>
        </form>
    <?php endif; ?>
    <h2>Payout history</h2>
    <table class="table">
        <thead><tr><th>Amount</th><th>Status</th><th>Requested</th><th>Reference</th></tr></thead>
        <tbody>
            <?php foreach ($payouts as $p): ?>
                <tr>
                    <td>₱<?= number_format($p['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($p['status']) ?></td>
                    <td><?= date('M j, Y', strtotime($p['requested_at'])) ?></td>
                    <td><?= htmlspecialchars($p['payment_reference'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($payouts)): ?><p class="empty">No payouts yet.</p><?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
