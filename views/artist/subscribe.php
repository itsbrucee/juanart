<?php $pageTitle = 'Subscribe'; ob_start(); ?>
<div class="container">
    <h1>Subscribe to a plan</h1>
    <p>Active subscription is required to upload artworks and accept commissions.</p>
    <div class="plan-grid">
        <?php foreach ($plans as $p): ?>
            <div class="plan-card">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p class="price">₱<?= number_format($p['price'], 0) ?> / <?= (int)$p['duration_days'] ?> days</p>
                <p>Commission: <?= (int)$p['commission_percentage'] ?>%</p>
                <form method="post">
                    <input type="hidden" name="action" value="subscribe">
                    <input type="hidden" name="subscription_id" value="<?= (int)$p['id'] ?>">
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
