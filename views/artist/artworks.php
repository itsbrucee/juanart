<?php $pageTitle = 'My Artworks'; ob_start(); ?>
<div class="container">
    <h1>My Artworks</h1>
    <?php
    $pdo = \JuanArt\Database::get();
    $subs = $pdo->prepare("SELECT id FROM artist_subscriptions WHERE artist_id = ? AND status = 'active' AND end_date >= CURDATE()");
    $subs->execute([$profile['id']]);
    $hasActiveSub = (bool)$subs->fetch();
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    ?>
    <?php if ($profile['kyc_status_id'] != 2): ?>
        <p class="flash flash-error">KYC must be approved to add artworks.</p>
    <?php elseif (!$hasActiveSub): ?>
        <p class="flash flash-error">Active subscription required. <a href="?page=artist/subscribe">Subscribe</a></p>
    <?php else: ?>
        <h2>Add artwork</h2>
        <form method="post" class="form form-inline" enctype="multipart/form-data">
            <input type="hidden" name="action" value="artwork_add">
            <input type="text" name="title" placeholder="Title" class="input" required>
            <textarea name="description" placeholder="Description" class="input" rows="1"></textarea>
            
            <!-- Artwork Type Selection -->
            <select name="artwork_type" class="input" id="artworkType" onchange="togglePriceField()">
                <option value="for_sale">For Sale</option>
                <option value="gallery_only">Gallery / Collection Only (Not for Sale)</option>
            </select>
            
            <!-- Price - shown only when For Sale is selected -->
            <input type="number" name="price" id="priceField" step="0.01" min="0" placeholder="Price (₱)" class="input">
            
            <select name="category_id" class="input"><option value="">Category</option><?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option><?php endforeach; ?></select>
            
            <!-- File Upload -->
            <div class="form-group" style="width: 100%;">
                <label class="input" for="artworkImage" style="display: flex; align-items: center; justify-content: center; padding: 2rem; border: 2px dashed var(--border-color); cursor: pointer;">
                    <span>Upload Photo</span>
                    <input type="file" name="artwork_image" id="artworkImage" accept="image/*" style="display: none;" onchange="updateFileName(this)">
                </label>
                <p id="fileName" style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.875rem;"></p>
            </div>
            
            <!-- Fallback URL input -->
            <input type="url" name="image_url" placeholder="Or Image URL" class="input">
            
            <button type="submit" class="btn btn-primary">Add Artwork</button>
        </form>
        
        <script>
        function togglePriceField() {
            var type = document.getElementById('artworkType').value;
            var priceField = document.getElementById('priceField');
            if (type === 'gallery_only') {
                priceField.style.display = 'none';
                priceField.removeAttribute('required');
            } else {
                priceField.style.display = 'block';
                priceField.setAttribute('required', 'required');
            }
        }
        
        function updateFileName(input) {
            var fileNameDisplay = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileNameDisplay.textContent = 'Selected: ' + input.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        }
        
        // Initialize
        togglePriceField();
        </script>
    <?php endif; ?>
    
    <h2>My Artworks</h2>
    <div class="artwork-grid">
        <?php foreach ($artworks as $a): ?>
            <article class="artwork-card">
                <img src="<?= htmlspecialchars($a['image_url'] ?: 'https://placehold.co/400x300?text=Art') ?>" alt="">
                <div class="artwork-info">
                    <h3><?= htmlspecialchars($a['title']) ?></h3>
                    <p>
                        <?php if (($a['artwork_type'] ?? 'for_sale') === 'gallery_only'): ?>
                            <span class="badge">Gallery Only</span>
                        <?php else: ?>
                            ₱<?= number_format($a['price'] ?? 0, 2) ?>
                        <?php endif; ?>
                        · <?= htmlspecialchars($a['category_name'] ?? '-') ?>
                    </p>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="action" value="artwork_delete">
                        <input type="hidden" name="artwork_id" value="<?= (int)$a['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (empty($artworks)): ?><p class="empty">No artworks yet.</p><?php endif; ?>
</div>

<style>
.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background: var(--accent-primary);
    color: var(--text-inverse);
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
}
</style>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
