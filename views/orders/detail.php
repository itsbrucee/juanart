<?php $pageTitle = 'Order #' . $order['id']; ob_start(); ?>
<div class="container">
    <h1>Order #<?= (int)$order['id'] ?></h1>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['status_name']) ?></p>
    <p><strong>Total:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>
    <p><strong>Date:</strong> <?= date('M j, Y H:i', strtotime($order['created_at'])) ?></p>
    <h2>Items</h2>
    <table class="table">
        <thead><tr><th>Artwork</th><th>Price</th></tr></thead>
        <tbody>
            <?php foreach ($order['items'] as $i): ?>
                <tr>
                    <td><?= htmlspecialchars($i['title']) ?></td>
                    <td>₱<?= number_format($i['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="?page=orders" class="btn">Back to orders</a>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
