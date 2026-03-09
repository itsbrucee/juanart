<?php
use JuanArt\Auth;

$pageTitle = 'Artist – ' . htmlspecialchars($artist['name'] ?? ''); ob_start(); ?>
<div class="container">
    <section class="artist-profile">
        <div class="artist-header">
            <?php if (!empty($artist['profile_image'])): ?>
                <img src="<?= htmlspecialchars($artist['profile_image']) ?>" alt="" class="avatar">
            <?php endif; ?>
            <div>
                <h1><?= htmlspecialchars($artist['name']) ?></h1>
                <?php if (!empty($artist['bio'])): ?><p class="bio"><?= nl2br(htmlspecialchars($artist['bio'])) ?></p><?php endif; ?>
                <?php if (Auth::check()): ?>
                    <a href="?page=commission/request&artist_id=<?= (int)$artist['id'] ?>" class="btn btn-primary">Request commission</a>
                <?php else: ?>
                    <a href="?page=login" class="btn btn-primary">Login to request commission</a>
                <?php endif; ?>
            </div>
        </div>

        <?php 
        $forSaleArtworks = [];
        $galleryArtworks = [];
        foreach ($artist['artworks'] as $a) {
            if (($a['artwork_type'] ?? 'for_sale') === 'gallery_only') {
                $galleryArtworks[] = $a;
            } else {
                $forSaleArtworks[] = $a;
            }
        }
        ?>

        <?php if (!empty($forSaleArtworks)): ?>
        <h2>For Sale</h2>
        <div class="artwork-grid">
            <?php foreach ($forSaleArtworks as $a): ?>
                <article class="artwork-card">
                    <a href="?page=artist&id=<?= (int)$artist['id'] ?>"><img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>"></a>
                    <div class="artwork-info">
                        <h3><?= htmlspecialchars($a['title']) ?></h3>
                        <p class="price">₱<?= number_format($a['price'] ?? 0, 2) ?></p>
                        <?php if (Auth::check()): ?>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="action" value="add_to_cart">
                                <input type="hidden" name="artwork_id" value="<?= (int)$a['id'] ?>">
                                <button type="submit" class="btn btn-sm">Add to cart</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($galleryArtworks)): ?>
        <h2>Gallery Collection</h2>
        <div class="artwork-grid">
            <?php foreach ($galleryArtworks as $a): ?>
                <article class="artwork-card gallery-card">
                    <a href="?page=artist&id=<?= (int)$artist['id'] ?>"><img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="<?= htmlspecialchars($a['title']) ?>"></a>
                    <div class="artwork-info">
                        <span class="badge">Gallery Collection</span>
                        <h3><?= htmlspecialchars($a['title']) ?></h3>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($artist['artworks'])): ?><p class="empty">No artworks yet.</p><?php endif; ?>
    </section>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
?>
