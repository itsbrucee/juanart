<?php

$pageTitle = 'Gallery - Artwork Collection';
ob_start();
?>

<!-- Gallery Header -->
<section class="gallery-header">
    <div class="container">
        <div class="gallery-header-inner">
            <div class="gallery-header-content">
                <h1 class="gallery-title">The Art Gallery</h1>
                <p class="gallery-subtitle">Discover extraordinary artworks curated from our community of visionary artists - where every piece tells a unique story and sparks imagination</p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Content -->
<section class="gallery-content">
    <div class="container">
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
    .gallery-header {
        background: linear-gradient(135deg, var(--accent-secondary) 0%, var(--accent-secondary-hover) 100%);
        padding: var(--spacing-3xl) 0;
        margin-bottom: var(--spacing-2xl);
    }

    .gallery-header-inner {
        text-align: center;
    }

    .gallery-title {
        font-family: var(--font-heading);
        font-size: var(--font-size-3xl);
        color: var(--text-inverse);
        margin-bottom: var(--spacing-sm);
    }

    .gallery-subtitle {
        font-size: var(--font-size-lg);
        color: var(--text-inverse);
        opacity: 0.9;
    }

    .gallery-content {
        padding-bottom: var(--spacing-3xl);
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
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
