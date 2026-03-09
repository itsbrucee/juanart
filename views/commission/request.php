<?php $pageTitle = 'Request Commission'; ob_start(); ?>
<div class="container">
    <h1>Request commission from <?= htmlspecialchars($artist['name']) ?></h1>
    <form method="post" class="form">
        <input type="hidden" name="action" value="commission_request">
        <input type="hidden" name="artist_id" value="<?= (int)$artist['id'] ?>">
        <label>Description <textarea name="description" class="input" rows="4" required></textarea></label>
        <label>Budget (₱) <input type="number" name="budget" step="0.01" min="0" class="input" required></label>
        <label>Deadline <input type="date" name="deadline" class="input"></label>
        <label>Reference images (URLs, one per line) <textarea name="reference_images" class="input" rows="3"></textarea></label>
        <button type="submit" class="btn btn-primary">Send request</button>
    </form>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
