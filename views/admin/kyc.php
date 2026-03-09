<?php $pageTitle = 'KYC Approval'; ob_start(); ?>
<div class="container">
    <h1>KYC approval</h1>
    <table class="table">
        <thead><tr><th>Artist</th><th>Email</th><th>Valid ID</th><th>Portfolio</th><th>Submitted</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($pending as $k): ?>
                <tr>
                    <td><?= htmlspecialchars($k['name']) ?></td>
                    <td><?= htmlspecialchars($k['email']) ?></td>
                    <td><a href="<?= htmlspecialchars($k['valid_id_url'] ?? '#') ?>" target="_blank" rel="noopener">View ID</a></td>
                    <td><?= nl2br(htmlspecialchars(substr($k['portfolio_urls'] ?? '', 0, 200))) ?></td>
                    <td><?= date('M j, Y', strtotime($k['submitted_at'])) ?></td>
                    <td>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="kyc_approve">
                            <input type="hidden" name="doc_id" value="<?= (int)$k['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                        </form>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="kyc_reject">
                            <input type="hidden" name="doc_id" value="<?= (int)$k['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($pending)): ?><p class="empty">No pending KYC.</p><?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
