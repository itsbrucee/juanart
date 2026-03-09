<?php $pageTitle = 'Subscriptions'; ob_start(); ?>
<div class="container">
    <h1>Subscriptions</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Commission %</th><th>Days</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($subscriptions as $s): ?>
                <tr>
                    <td><?= (int)$s['id'] ?></td>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td>₱<?= number_format($s['price'], 2) ?></td>
                    <td><?= (int)$s['commission_percentage'] ?>%</td>
                    <td><?= (int)$s['duration_days'] ?></td>
                    <td>
                        <form method="post" class="form-inline">
                            <input type="hidden" name="action" value="subscription_edit">
                            <input type="hidden" name="subscription_id" value="<?= (int)$s['id'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>" class="input input-sm">
                            <input type="number" name="price" step="0.01" value="<?= $s['price'] ?>" class="input input-sm">
                            <input type="number" name="commission_percentage" value="<?= $s['commission_percentage'] ?>" class="input input-sm">
                            <input type="number" name="duration_days" value="<?= $s['duration_days'] ?>" class="input input-sm">
                            <button type="submit" class="btn btn-sm">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
