<?php
use JuanArt\Auth;

$pageTitle = 'Discover Art';
ob_start();
?>
<div class="container">
    <section class="hero">
        <h1>Discover & Commission Art</h1>
        <p>Browse artworks, support artists, and request custom commissions.</p>
    </section>
    <section class="filters">
        <form method="get" class="filter-form">
            <input type="hidden" name="page" value="dashboard">
            <input type="text" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search..." class="input">
            <select name="category" class="input">
                <option value="">All categories</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($categoryFilter ?? 0) == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </section>

    <?php if (!empty($forSaleArtworks)): ?>
    <section class="artworks-section">
        <h2>For Sale</h2>
        <div class="artwork-grid">
            <?php foreach ($forSaleArtworks as $a): ?>
                <article class="artwork-card">
                    <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>" class="artwork-image-wrap">
                        <img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                    </a>
                    <div class="artwork-info">
                        <h3><a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['title']) ?></a></h3>
                        <p class="artist">By <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['artist_name'] ?? '') ?></a></p>
                        <?php if (!empty($a['category_name'])): ?><p class="category"><?= htmlspecialchars($a['category_name']) ?></p><?php endif; ?>
                        <p class="price">₱<?= number_format($a['price'] ?? 0, 2) ?></p>
                        <?php if (Auth::check()): ?>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="action" value="add_to_cart">
                                <input type="hidden" name="artwork_id" value="<?= (int)$a['id'] ?>">
                                <button type="submit" class="btn btn-sm">Add to cart</button>
                            </form>
                        <?php else: ?>
                            <a href="?page=login" class="btn btn-sm">Login to buy</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($galleryArtworks)): ?>
    <section class="artworks-section gallery-section">
        <h2>Gallery Collection</h2>
        <p class="section-desc">Artworks for display only - not available for purchase</p>
        <div class="artwork-grid">
            <?php foreach ($galleryArtworks as $a): ?>
                <article class="artwork-card gallery-card">
                    <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>" class="artwork-image-wrap">
                        <img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                    </a>
                    <div class="artwork-info">
                        <span class="badge">Gallery Collection</span>
                        <h3><a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['title']) ?></a></h3>
                        <p class="artist">By <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['artist_name'] ?? '') ?></a></p>
                        <?php if (!empty($a['category_name'])): ?><p class="category"><?= htmlspecialchars($a['category_name']) ?></p><?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (empty($forSaleArtworks) && empty($galleryArtworks)): ?>
    <p class="empty">No artworks yet. Check back later.</p>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Discover Art';
require __DIR__ . '/layout.php';
?>
