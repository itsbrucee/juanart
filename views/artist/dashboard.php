<?php
$kycStatus = ['1' => 'Pending', '2' => 'Approved', '3' => 'Rejected'];
$pageTitle = 'Artist Dashboard';
ob_start();
?>
<div class="container">
    <h1>Artist Dashboard</h1>
    <p><strong>KYC:</strong> <?= $kycStatus[$profile['kyc_status_id']] ?? 'Unknown' ?></p>
    <p><strong>Balance:</strong> ₱<?= number_format($profile['balance'], 2) ?> · <strong>Total earnings:</strong> ₱<?= number_format($profile['total_earnings'], 2) ?></p>
    <p>
        <a href="?page=artist/artworks" class="btn">My Artworks</a>
        <?php if ($profile['kyc_status_id'] == 2): ?>
            <a href="?page=artist/subscribe" class="btn">Subscribe</a>
            <a href="?page=artist/earnings" class="btn">Earnings & Payout</a>
        <?php endif; ?>
    </p>
    <h2>Recent commissions</h2>
    <table class="table">
        <thead><tr><th>ID</th><th>Client</th><th>Budget</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($profile['commissions'] as $c): ?>
                <tr>
                    <td>#<?= (int)$c['id'] ?></td>
                    <td><?= htmlspecialchars($c['client_name']) ?></td>
                    <td>₱<?= number_format($c['budget'], 2) ?></td>
                    <td><?= htmlspecialchars($c['status_name']) ?></td>
                    <td><a href="?page=commission&id=<?= (int)$c['id'] ?>" class="btn btn-sm">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($profile['commissions'])): ?><p class="empty">No commissions yet.</p><?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
