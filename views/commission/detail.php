<?php
use JuanArt\Auth;

$c = $commission;
$pageTitle = 'Commission #' . $c['id'];
ob_start();
?>
<div class="container">
    <h1>Commission #<?= (int)$c['id'] ?></h1>
    <p><strong>Status:</strong> <?= htmlspecialchars($c['status_name']) ?></p>
    <p><strong>Artist:</strong> <?= htmlspecialchars($c['artist']['name'] ?? '') ?></p>
    <p><strong>Budget:</strong> ₱<?= number_format($c['budget'], 2) ?></p>
    <?php if (!empty($c['deadline'])): ?><p><strong>Deadline:</strong> <?= htmlspecialchars($c['deadline']) ?></p><?php endif; ?>
    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($c['description'] ?? '')) ?></p>
    <?php if (!empty($c['reference_images'])): ?><p><strong>Reference images:</strong><br><?= nl2br(htmlspecialchars($c['reference_images'])) ?></p><?php endif; ?>

    <?php if (Auth::id() == (int)($c['artist']['user_id'] ?? 0)): ?>
        <?php if ($c['status_id'] == 1): ?>
            <form method="post" style="display:inline">
                <input type="hidden" name="action" value="commission_accept">
                <input type="hidden" name="commission_id" value="<?= (int)$c['id'] ?>">
                <button type="submit" class="btn btn-primary">Accept</button>
            </form>
            <form method="post" style="display:inline">
                <input type="hidden" name="action" value="commission_reject">
                <input type="hidden" name="commission_id" value="<?= (int)$c['id'] ?>">
                <button type="submit" class="btn btn-danger">Reject</button>
            </form>
        <?php elseif ($c['status_id'] == 4): ?>
            <form method="post" class="form">
                <input type="hidden" name="action" value="commission_deliver">
                <input type="hidden" name="commission_id" value="<?= (int)$c['id'] ?>">
                <label>Delivery URL <input type="url" name="delivery_url" class="input" required></label>
                <button type="submit" class="btn btn-primary">Submit delivery</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (Auth::id() == $c['client_id']): ?>
        <?php if ($c['status_id'] == 2): ?>
            <form method="post" class="form">
                <input type="hidden" name="action" value="commission_pay">
                <input type="hidden" name="commission_id" value="<?= (int)$c['id'] ?>">
                <label>Transaction reference <input type="text" name="transaction_reference" class="input"></label>
                <button type="submit" class="btn btn-primary">Mark as paid (escrow)</button>
            </form>
        <?php elseif ($c['status_id'] == 4 && !empty($c['delivery_url'])): ?>
            <p><a href="<?= htmlspecialchars($c['delivery_url']) ?>" target="_blank" rel="noopener">View delivery</a></p>
            <form method="post">
                <input type="hidden" name="action" value="commission_approve">
                <input type="hidden" name="commission_id" value="<?= (int)$c['id'] ?>">
                <button type="submit" class="btn btn-primary">Approve delivery & release payment</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <h3>Open dispute</h3>
    <form method="post">
        <input type="hidden" name="action" value="dispute_create">
        <input type="hidden" name="commission_id" value="<?= (int)$c['id'] ?>">
        <label>Reason <textarea name="reason" class="input" rows="2" required></textarea></label>
        <button type="submit" class="btn">Submit dispute</button>
    </form>
    <p><a href="?page=my-commissions">Back to commissions</a></p>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
