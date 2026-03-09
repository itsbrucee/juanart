<?php $pageTitle = 'My Commissions'; ob_start(); ?>
<div class="container">
    <h1>My Commissions</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>Artist</th><th>Budget</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($commissions as $c): ?>
                <tr>
                    <td>#<?= (int)$c['id'] ?></td>
                    <td><?= htmlspecialchars($c['artist_name']) ?></td>
                    <td>₱<?= number_format($c['budget'], 2) ?></td>
                    <td><?= htmlspecialchars($c['status_name']) ?></td>
                    <td><a href="?page=commission&id=<?= (int)$c['id'] ?>" class="btn btn-sm">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($commissions)): ?><p class="empty">No commissions yet.</p><?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
