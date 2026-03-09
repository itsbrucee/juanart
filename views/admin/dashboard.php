<?php $pageTitle = 'Admin Dashboard'; ob_start(); ?>
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="stats-grid">
        <div class="stat-card"><span class="stat-value"><?= (int)$stats['users'] ?></span><span class="stat-label">Users</span></div>
        <div class="stat-card"><span class="stat-value"><?= (int)$stats['artists'] ?></span><span class="stat-label">Artists</span></div>
        <div class="stat-card"><span class="stat-value"><?= (int)$stats['artworks'] ?></span><span class="stat-label">Artworks</span></div>
        <div class="stat-card"><span class="stat-value"><?= (int)$stats['orders'] ?></span><span class="stat-label">Orders</span></div>
        <div class="stat-card"><span class="stat-value"><?= (int)$stats['commissions'] ?></span><span class="stat-label">Commissions</span></div>
        <div class="stat-card"><span class="stat-value">₱<?= number_format($stats['total_sales'], 0) ?></span><span class="stat-label">Total sales</span></div>
        <div class="stat-card warning"><span class="stat-value"><?= (int)$stats['pending_kyc'] ?></span><span class="stat-label">Pending KYC</span></div>
        <div class="stat-card warning"><span class="stat-value"><?= (int)$stats['open_disputes'] ?></span><span class="stat-label">Open disputes</span></div>
    </div>
    <nav class="admin-nav">
        <a href="?page=admin/kyc">KYC approval</a>
        <a href="?page=admin/categories">Categories</a>
        <a href="?page=admin/subscriptions">Subscriptions</a>
        <a href="?page=admin/users">Users</a>
        <a href="?page=admin/transactions">Transactions</a>
        <a href="?page=admin/disputes">Disputes</a>
        <a href="?page=admin/payouts">Payouts</a>
    </nav>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
