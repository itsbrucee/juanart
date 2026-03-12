<?php

$pageTitle = 'Gallery - Artwork Collection';
ob_start();
?>

<!-- Gallery Header -->
<section class="shop-header">
    <div class="container">
        <div class="shop-header-inner">
            <div class="shop-header-content">
                <h1 class="shop-title">Gallery</h1>
                <p class="shop-subtitle">Explore a curated collection of inspiring artworks created by talented artists from our creative community.</p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Content -->
<section class="gallery-section">
    <div class="container">
        <!-- Filters -->
        <div class="gallery-filters">
            <form method="get" class="filter-form">
                <input type="hidden" name="page" value="gallery">
                <div class="filter-row">
                    <div class="filter-search">
                        <input type="text" name="q" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search gallery artworks..." class="input">
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
                            <option value="popular" <?= ($sort ?? '') === 'popular' ? 'selected' : '' ?>>Most Popular</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="search"></i> Search
                    </button>
                    <?php if (!empty($search) || !empty($categoryFilter) || !empty($sort)): ?>
                        <a href="?page=gallery" class="btn btn-secondary">
                            <i data-feather="x"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="gallery-results">
            <span class="results-count"><?= count($galleryArtworks) ?> artwork(s) in gallery</span>
        </div>

        <!-- Gallery Grid -->
        <?php if (!empty($galleryArtworks)): ?>
            <div class="gallery-grid">
                <?php foreach ($galleryArtworks as $index => $a): ?>
                    <article class="gallery-card animate-fade-in" style="animation-delay: <?= $index * 0.05 ?>s;">
                        <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>" class="gallery-card-link">
                            <div class="gallery-image">
                                <img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x400?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                                <div class="gallery-overlay">
                                    <div class="gallery-overlay-content">
                                        <?php if (!empty($a['category_name'])): ?>
                                            <span class="gallery-category"><?= htmlspecialchars($a['category_name']) ?></span>
                                        <?php endif; ?>
                                        <h3 class="gallery-title"><?= htmlspecialchars($a['title']) ?></h3>
                                        <p class="gallery-artist">By <?= htmlspecialchars($a['artist_name'] ?? '') ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i data-feather="image"></i>
                </div>
                <h3 class="empty-title">No Gallery Artworks</h3>
                <p class="empty-text">There are no gallery artworks available at the moment. Check back later!</p>
                <a href="?page=shop" class="btn btn-primary">Browse Shop</a>
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

    .gallery-section {
        padding-bottom: var(--spacing-3xl);
    }

    .gallery-filters {
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

    .gallery-results {
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
