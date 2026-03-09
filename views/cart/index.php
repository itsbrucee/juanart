<?php
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $row) { $total += $row['price'] * $row['qty']; }
$pageTitle = 'Cart';
ob_start();
?>
<div class="container">
    <h1>Cart</h1>
    <?php if (empty($cart)): ?>
        <p class="empty">Your cart is empty. <a href="?page=dashboard">Browse artworks</a></p>
    <?php else: ?>
        <table class="table">
            <thead><tr><th>Artwork</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($cart as $key => $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= (int)$row['qty'] ?></td>
                        <td>₱<?= number_format($row['price'] * $row['qty'], 2) ?></td>
                        <td>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="action" value="remove_from_cart">
                                <input type="hidden" name="artwork_id" value="<?= (int)$row['artwork_id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="total"><strong>Total: ₱<?= number_format($total, 2) ?></strong></p>
        <a href="?page=checkout" class="btn btn-primary">Proceed to checkout</a>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
