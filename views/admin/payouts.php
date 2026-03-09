<?php $pageTitle = 'Payouts'; ob_start(); ?>
<div class="container">
    <h1>Payout requests</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>Artist</th><th>Amount</th><th>Status</th><th>Requested</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($payouts as $p): ?>
                <tr>
                    <td>#<?= (int)$p['id'] ?></td>
                    <td><?= htmlspecialchars($p['artist_name']) ?></td>
                    <td>₱<?= number_format($p['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($p['status']) ?></td>
                    <td><?= date('M j, Y', strtotime($p['requested_at'])) ?></td>
                    <td>
                        <?php if ($p['status'] === 'pending'): ?>
                            <form method="post">
                                <input type="hidden" name="action" value="payout_process">
                                <input type="hidden" name="payout_id" value="<?= (int)$p['id'] ?>">
                                <input type="text" name="payment_reference" placeholder="Reference" class="input input-sm">
                                <button type="submit" class="btn btn-sm btn-primary">Mark processed</button>
                            </form>
                        <?php else: ?>
                            <?= htmlspecialchars($p['payment_reference'] ?? '-') ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
