<?php

$pageTitle = 'Shop - All Artworks For Sale';
ob_start();
?>

<!-- Shop Header -->
<section class="shop-header">
    <div class="container">
        <div class="shop-header-inner">
            <div class="shop-header-content">
                <h1 class="shop-title">Shop Artworks</h1>
                <p class="shop-subtitle">Browse all available artworks from talented artists worldwide</p>
            </div>
        </div>
    </div>
</section>

<!-- Shop Content -->
<section class="shop-section">
    <div class="container">
        <!-- Filters -->
        <div class="shop-filters">
            <form method="get" class="filter-form">
                <input type="hidden" name="page" value="shop">
                <div class="filter-row">
                    <div class="filter-search">
                        <input type="text" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search artworks..." class="input">
                    </div>
                    <div class="filter-category">
                        <select name="category" class="input">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($categoryFilter ?? 0) == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-sort">
                        <select name="sort" class="input">
                            <option value="newest" <?= ($sort ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest First</option>
                            <option value="price_low" <?= ($sort ?? '') === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price_high" <?= ($sort ?? '') === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                            <option value="popular" <?= ($sort ?? '') === 'popular' ? 'selected' : '' ?>>Most Popular</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="search"></i> Search
                    </button>
                    <?php if (!empty($search) || !empty($categoryFilter) || !empty($sort)): ?>
                        <a href="?page=shop" class="btn btn-secondary">
                            <i data-feather="x"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="shop-results">
            <span class="results-count"><?= count($forSaleArtworks) ?> artwork(s) found</span>
        </div>

        <!-- Artworks Grid -->
        <?php if (!empty($forSaleArtworks)): ?>
            <div class="product-grid">
                <?php foreach ($forSaleArtworks as $index => $a): ?>
                    <article class="product-card animate-fade-in" style="animation-delay: <?= $index * 0.05 ?>s;">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                            <div class="product-actions">
                                <button class="product-action-btn" title="Quick View" onclick="alert('Quick view coming soon!')"><i data-feather="eye"></i></button>
                                <button class="product-action-btn" title="Add to Wishlist" onclick="alert('Wishlist feature coming soon!')"><i data-feather="heart"></i></button>
                            </div>
                        </div>
                        <div class="product-info">
                            <?php if (!empty($a['category_name'])): ?>
                                <span class="product-category"><?= htmlspecialchars($a['category_name']) ?></span>
                            <?php endif; ?>
                            <h3 class="product-title">
                                <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['title']) ?></a>
                            </h3>
                            <div class="product-rating">
                                <div class="stars">
                                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                </div>
                                <span class="rating-count">(5)</span>
                            </div>
                            <div class="product-price">
                                <span class="price-current">₱<?= number_format($a['price'] ?? 0, 2) ?></span>
                            </div>
                            <p class="product-artist">By <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['artist_name'] ?? '') ?></a></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i data-feather="image"></i>
                </div>
                <h3 class="empty-title">No Artworks Found</h3>
                <p class="empty-text">We couldn't find any artworks matching your criteria. Try adjusting your filters or search terms.</p>
                <a href="?page=shop" class="btn btn-primary">Browse All Artworks</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .shop-header {
        background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-primary-hover) 100%);
        padding: var(--spacing-3xl) 0;
        margin-bottom: var(--spacing-2xl);
    }

    .shop-header-inner {
        text-align: center;
    }

    .shop-title {
        font-family: var(--font-heading);
        font-size: var(--font-size-3xl);
        color: var(--text-inverse);
        margin-bottom: var(--spacing-sm);
    }

    .shop-subtitle {
        font-size: var(--font-size-lg);
        color: var(--text-inverse);
        opacity: 0.9;
    }

    .shop-section {
        padding-bottom: var(--spacing-3xl);
    }

    .shop-filters {
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-md);
        align-items: center;
    }

    .filter-search {
        flex: 1;
        min-width: 200px;
    }

    .filter-category,
    .filter-sort {
        min-width: 150px;
    }

    .filter-search input,
    .filter-category select,
    .filter-sort select {
        width: 100%;
    }

    .shop-results {
        margin-bottom: var(--spacing-lg);
    }

    .results-count {
        font-size: var(--font-size-sm);
        color: var(--text-muted);
    }

    .empty-state {
        text-align: center;
        padding: var(--spacing-3xl) var(--spacing-lg);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto var(--spacing-lg);
        background: var(--bg-secondary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        width: 40px;
        height: 40px;
        color: var(--text-muted);
    }

    .empty-title {
        font-size: var(--font-size-xl);
        color: var(--text-primary);
        margin-bottom: var(--spacing-sm);
    }

    .empty-text {
        color: var(--text-muted);
        margin-bottom: var(--spacing-lg);
    }

    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
        }

        .filter-search,
        .filter-category,
        .filter-sort {
            width: 100%;
        }

        .filter-row .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
