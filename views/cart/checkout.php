<?php
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $row) { $total += $row['price'] * $row['qty']; }
$pageTitle = 'Checkout';
ob_start();
?>
<div class="container">
    <h1>Checkout</h1>
    <?php if (empty($cart)): ?>
        <p class="empty">Cart is empty. <a href="?page=cart">View cart</a></p>
    <?php else: ?>
        <p><strong>Total: ₱<?= number_format($total, 2) ?></strong></p>
        <form method="post" class="form">
            <input type="hidden" name="action" value="checkout">
            <label>Payment method
                <select name="payment_method" class="input">
                    <option value="GCash">GCash</option>
                    <option value="PayMaya">PayMaya</option>
                    <option value="Bank">Bank transfer</option>
                    <option value="Card">Card</option>
                    <option value="PayPal">PayPal</option>
                </select>
            </label>
            <label>Transaction reference <input type="text" name="transaction_reference" class="input" placeholder="Optional"></label>
            <button type="submit" class="btn btn-primary">Place order</button>
        </form>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
