<?php
use JuanArt\Auth;

$pageTitle = 'Discover Art';
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-content">
                <span class="hero-label">Art Shop and Gallery</span>
                <h1 class="hero-title">Discover Unique <span>Artworks</span> From Talented Artists</h1>
                <p class="hero-text">Explore our curated collection of original artworks, prints, and commissioned pieces. Support artists directly and find the perfect piece for your space.</p>
                <div class="hero-buttons">
                    <a href="#shop" class="btn btn-primary btn-lg">Shop Now</a>
                    <a href="#gallery" class="btn btn-secondary btn-lg">View Gallery</a>
                </div>
            </div>
                <div class="hero-image">
                <img src="assets/images/juanartimage.png" alt="Featured Artwork">
            </div>
        </div>
    </div>
</section>

<!-- Feature Highlights Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i data-feather="truck"></i>
                </div>
                <h4 class="feature-title">Secure Shipping</h4>
                <p class="feature-desc">All orders are well packed!</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i data-feather="shield"></i>
                </div>
                <h4 class="feature-title">Secure Payment</h4>
                <p class="feature-desc">100% secure payment</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i data-feather="rotate-ccw"></i>
                </div>
                <h4 class="feature-title">Easy Returns</h4>
                <p class="feature-desc">30-day return policy</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i data-feather="headphones"></i>
                </div>
                <h4 class="feature-title">24/7 Support</h4>
                <p class="feature-desc">Dedicated support team</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Artworks with Promo Cards and For Sale Artworks -->
<section class="products-section">
    <div class="container">
        <div class="section-header-inline">
            <h2 class="section-title-inline">Featured Artworks</h2>
            <div class="product-tabs">
                <button class="product-tab active">New Arrival</button>
                <button class="product-tab">Best Selling</button>
                <button class="product-tab">Top Rated</button>
            </div>
        </div>

        <!-- Promotional Banner Grid - Top 3 Most Viewed Artworks -->
        <div class="promo-grid" style="margin-bottom: var(--spacing-2xl);">
            <?php if (!empty($topViewedArtworks)): ?>
                <?php 
                // First artwork - large card
                $firstArtwork = $topViewedArtworks[0];
                ?>
                <div class="promo-card large">
                    <img src="<?= htmlspecialchars($firstArtwork['image_url'] ?: 'https://placehold.co/600x600?text=Art') ?>" alt="<?= htmlspecialchars($firstArtwork['title']) ?>">
                    <div class="promo-overlay">
                        <span class="promo-label">Most Popular</span>
                        <h3 class="promo-title"><?= htmlspecialchars($firstArtwork['title']) ?></h3>
                        <p class="promo-text">By <?= htmlspecialchars($firstArtwork['artist_name'] ?? 'Unknown Artist') ?></p>
                        <a href="?page=artist&id=<?= (int)$firstArtwork['artist_profile_id'] ?>" class="promo-link">View Artist <i data-feather="arrow-right"></i></a>
                    </div>
                </div>
                
                <?php 
                // Second artwork - medium card
                if (isset($topViewedArtworks[1])) {
                    $secondArtwork = $topViewedArtworks[1];
                ?>
                <div class="promo-card">
                    <img src="<?= htmlspecialchars($secondArtwork['image_url'] ?: 'https://placehold.co/400x250?text=Art') ?>" alt="<?= htmlspecialchars($secondArtwork['title']) ?>">
                    <div class="promo-overlay">
                        <span class="promo-label">Trending</span>
                        <h3 class="promo-title"><?= htmlspecialchars($secondArtwork['title']) ?></h3>
                        <p class="promo-text">By <?= htmlspecialchars($secondArtwork['artist_name'] ?? 'Unknown Artist') ?></p>
                        <a href="?page=artist&id=<?= (int)$secondArtwork['artist_profile_id'] ?>" class="promo-link">View Artist <i data-feather="arrow-right"></i></a>
                    </div>
                </div>
                <?php } ?>
                
                <?php 
                // Third artwork - medium card
                if (isset($topViewedArtworks[2])) {
                    $thirdArtwork = $topViewedArtworks[2];
                ?>
                <div class="promo-card">
                    <img src="<?= htmlspecialchars($thirdArtwork['image_url'] ?: 'https://placehold.co/400x250?text=Art') ?>" alt="<?= htmlspecialchars($thirdArtwork['title']) ?>">
                    <div class="promo-overlay">
                        <span class="promo-label">Popular</span>
                        <h3 class="promo-title"><?= htmlspecialchars($thirdArtwork['title']) ?></h3>
                        <p class="promo-text">By <?= htmlspecialchars($thirdArtwork['artist_name'] ?? 'Unknown Artist') ?></p>
                        <a href="?page=artist&id=<?= (int)$thirdArtwork['artist_profile_id'] ?>" class="promo-link">View Artist <i data-feather="arrow-right"></i></a>
                    </div>
                </div>
                <?php } ?>
            <?php else: ?>
                <!-- Fallback static promo cards if no artworks available -->
                <div class="promo-card large">
                    <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=600&h=600&fit=crop" alt="Summer Collection">
                    <div class="promo-overlay">
                        <span class="promo-label">New Collection</span>
                        <h3 class="promo-title">Summer Art Collection</h3>
                        <p class="promo-text">Discover vibrant artworks inspired by summer</p>
                        <a href="#" class="promo-link">Shop Now <i data-feather="arrow-right"></i></a>
                    </div>
                </div>
                <div class="promo-card">
                    <img src="https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=400&h=250&fit=crop" alt="Abstract Art">
                    <div class="promo-overlay">
                        <span class="promo-label">Trending</span>
                        <h3 class="promo-title">Abstract Art</h3>
                        <a href="#" class="promo-link">Explore <i data-feather="arrow-right"></i></a>
                    </div>
                </div>
                <div class="promo-card">
                    <img src="https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=400&h=250&fit=crop" alt="Portraits">
                    <div class="promo-overlay">
                        <span class="promo-label">Featured</span>
                        <h3 class="promo-title">Portraits</h3>
                        <a href="#" class="promo-link">View All <i data-feather="arrow-right"></i></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- For Sale Artworks -->
        <h3 class="section-title-inline" style="font-size: var(--font-size-xl); margin-bottom: var(--spacing-lg);">For Sale Artworks</h3>
        <div id="shop" class="product-grid">
            <?php if (!empty($forSaleArtworks)): ?>
                <?php foreach (array_slice($forSaleArtworks, 0, 8) as $index => $a): ?>
                    <article class="product-card animate-fade-in">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                            <div class="product-actions">
                                <button class="product-action-btn" title="Quick View"><i data-feather="eye"></i></button>
                                <button class="product-action-btn" title="Add to Wishlist"><i data-feather="heart"></i></button>
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
            <?php else: ?>
                <p class="empty">No artworks available yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Promotional Split Banner Section -->
<section class="split-banner-section">
    <div class="container">
        <div class="split-banners">
            <div class="split-banner">
                <img src="assets/images/commissions.jpg" alt="Commission an Artist">
                <div class="split-banner-content">
                    <span class="split-banner-label">Custom Art</span>
                    <h3 class="split-banner-title">Commission an Artist</h3>
                    <p class="split-banner-text">Request a custom artwork from your favorite artists</p>
                    <a href="?page=commission/request&artist_id=1" class="btn btn-primary">Learn More</a>
                </div>
            </div>
            <div class="split-banner">
                <img src="assets/images/sell.jpg" alt="Become a Seller">
                <div class="split-banner-content">
                    <span class="split-banner-label">Sell Your Art</span>
                    <h3 class="split-banner-title">Become a Seller</h3>
                    <p class="split-banner-text">Join our community of talented artists</p>
                    <a href="?page=become-artist" class="btn btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Collection Section -->
<?php if (!empty($galleryArtworks)): ?>
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Gallery Collection</h2>
            <p class="section-subtitle">Artworks for display only - not available for purchase</p>
        </div>

        <div class="gallery-grid">
            <?php foreach (array_slice($galleryArtworks, 0, 8) as $a): ?>
                <article class="gallery-card animate-fade-in">
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
    </div>
</section>
<?php endif; ?>

<!-- Search/Filter Section (for additional artworks) -->
<section class="products-section" style="background: var(--bg-primary);">
    <div class="container">
        <?php if (!empty($forSaleArtworks) && count($forSaleArtworks) > 8): ?>
        <div class="product-grid">
            <?php foreach (array_slice($forSaleArtworks, 8) as $a): ?>
                <article class="product-card animate-fade-in">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                        <div class="product-actions">
                            <button class="product-action-btn" title="Quick View"><i data-feather="eye"></i></button>
                            <button class="product-action-btn" title="Add to Wishlist"><i data-feather="heart"></i></button>
                        </div>
                    </div>
                    <div class="product-info">
                        <?php if (!empty($a['category_name'])): ?>
                            <span class="product-category"><?= htmlspecialchars($a['category_name']) ?></span>
                        <?php endif; ?>
                        <h3 class="product-title">
                            <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['title']) ?></a>
                        </h3>
                        <div class="product-price">
                            <span class="price-current">₱<?= number_format($a['price'] ?? 0, 2) ?></span>
                        </div>
                        <p class="product-artist">By <a href="?page=artist&id=<?= (int)$a['artist_profile_id'] ?>"><?= htmlspecialchars($a['artist_name'] ?? '') ?></a></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($forSaleArtworks) && empty($galleryArtworks)): ?>
            <div class="empty">
                <p>No artworks yet. Check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
$pageTitle = 'Discover Art';
require __DIR__ . '/layout.php';
?>
