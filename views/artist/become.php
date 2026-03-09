<?php $pageTitle = 'Become an Artist'; ob_start(); ?>
<div class="become-artist-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Become an Artist</h1>
            <p class="page-subtitle">Join our community of talented artists and share your creative works with the world</p>
        </div>

        <!-- Requirements Card -->
        <div class="requirements-card">
            <div class="requirements-header">
                <i data-feather="info"></i>
                <h3>Application Requirements</h3>
            </div>
            <ul class="requirements-list">
                <li><i data-feather="check-circle"></i> Valid government-issued ID (URL to uploaded document)</li>
                <li><i data-feather="check-circle"></i> Brief bio describing your artistic background</li>
                <li><i data-feather="check-circle"></i> At least 7 portfolio artwork URLs</li>
                <li><i data-feather="check-circle"></i> Description of your experience and art style</li>
            </ul>
            <p class="requirements-note">Your application will be reviewed by our admin team. You'll be notified once your application has been processed.</p>
        </div>

        <!-- Application Form -->
        <div class="form-card">
            <h2 class="form-title">Submit Your Application</h2>
            <form method="post" class="become-form">
                <input type="hidden" name="action" value="kyc_submit">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bio">Artist Bio</label>
                        <p class="form-help">Tell us about yourself and your artistic journey</p>
                        <textarea name="bio" id="bio" class="input" rows="4" required placeholder="Share your story as an artist..."></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="experience">Experience & Style</label>
                        <p class="form-help">Describe your art background, techniques, and style</p>
                        <textarea name="experience" id="experience" class="input" rows="4" required placeholder="e.g., I specialize in digital illustration with 5 years of experience..."></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="valid_id_url">Valid ID Document URL</label>
                        <p class="form-help">Link to your uploaded government-issued ID</p>
                        <div class="input-with-icon">
                            <i data-feather="link"></i>
                            <input type="url" name="valid_id_url" id="valid_id_url" class="input" required placeholder="https://your-storage.com/id-document.jpg">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="portfolio_urls">Portfolio Artwork URLs</label>
                        <p class="form-help">Provide at least 7 URLs to your best artworks (one per line)</p>
                        <textarea name="portfolio_urls" id="portfolio_urls" class="input" rows="8" required placeholder="https://example.com/artwork1.jpg
https://example.com/artwork2.jpg
https://example.com/artwork3.jpg
https://example.com/artwork4.jpg
https://example.com/artwork5.jpg
https://example.com/artwork6.jpg
https://example.com/artwork7.jpg"></textarea>
                        <span class="input-hint">Minimum 7 URLs required</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i data-feather="send"></i>
                        Submit Application
                    </button>
                </div>
            </form>
        </div>

        <!-- Benefits Section -->
        <div class="benefits-section">
            <h2 class="benefits-title">Why Join JuanArt?</h2>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i data-feather="users"></i>
                    </div>
                    <h3>Reach More Buyers</h3>
                    <p>Connect with art lovers and collectors worldwide</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i data-feather="dollar-sign"></i>
                    </div>
                    <h3>Earn from Your Art</h3>
                    <p>Sell originals, prints, and accept commissions</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i data-feather="trending-up"></i>
                    </div>
                    <h3>Grow Your Audience</h3>
                    <p>Build your reputation and expand your reach</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.become-artist-page {
    padding: var(--spacing-2xl) 0;
    background: var(--bg-secondary);
    min-height: calc(100vh - 200px);
}

.page-header {
    text-align: center;
    margin-bottom: var(--spacing-2xl);
}

.page-title {
    font-size: var(--font-size-4xl);
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: var(--spacing-sm);
}

.page-subtitle {
    font-size: var(--font-size-lg);
    color: var(--text-muted);
    max-width: 600px;
    margin: 0 auto;
}

/* Requirements Card */
.requirements-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
    box-shadow: var(--shadow-sm);
}

.requirements-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.requirements-header i {
    color: var(--accent-primary);
    width: 24px;
    height: 24px;
}

.requirements-header h3 {
    font-size: var(--font-size-lg);
    font-weight: 600;
    margin: 0;
}

.requirements-list {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.requirements-list li {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    color: var(--text-secondary);
    font-size: var(--font-size-sm);
}

.requirements-list li i {
    color: var(--success);
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.requirements-note {
    font-size: var(--font-size-sm);
    color: var(--text-muted);
    padding: var(--spacing-md);
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    margin: 0;
}

/* Form Card */
.form-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--spacing-2xl);
    margin-bottom: var(--spacing-2xl);
    box-shadow: var(--shadow-md);
}

.form-title {
    font-size: var(--font-size-2xl);
    font-weight: 600;
    margin-bottom: var(--spacing-xl);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--border-color);
}

.become-form .form-row {
    margin-bottom: var(--spacing-lg);
}

.become-form .form-group {
    margin-bottom: 0;
}

.become-form label {
    display: block;
    font-size: var(--font-size-base);
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: var(--spacing-xs);
}

.form-help {
    font-size: var(--font-size-sm);
    color: var(--text-muted);
    margin-bottom: var(--spacing-sm);
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: var(--spacing-md);
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    width: 18px;
    height: 18px;
}

.input-with-icon .input {
    padding-left: calc(var(--spacing-md) * 2 + 18px);
}

.input-hint {
    display: block;
    font-size: var(--font-size-xs);
    color: var(--text-muted);
    margin-top: var(--spacing-xs);
}

.form-actions {
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--border-color);
}

.form-actions .btn {
    min-width: 200px;
}

/* Benefits Section */
.benefits-section {
    text-align: center;
}

.benefits-title {
    font-size: var(--font-size-2xl);
    font-weight: 700;
    margin-bottom: var(--spacing-xl);
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-lg);
}

.benefit-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--spacing-xl);
    text-align: center;
    transition: all var(--transition-base);
}

.benefit-card:hover {
    border-color: var(--accent-primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.benefit-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto var(--spacing-md);
    background: var(--accent-primary-light-2);
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-primary);
    transition: all var(--transition-base);
}

.benefit-card:hover .benefit-icon {
    background: var(--accent-primary);
    color: white;
}

.benefit-icon i {
    width: 28px;
    height: 28px;
}

.benefit-card h3 {
    font-size: var(--font-size-lg);
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
}

.benefit-card p {
    font-size: var(--font-size-sm);
    color: var(--text-muted);
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: var(--font-size-3xl);
    }

    .requirements-list {
        grid-template-columns: 1fr;
    }

    .benefits-grid {
        grid-template-columns: 1fr;
    }

    .form-card {
        padding: var(--spacing-lg);
    }

    .form-actions .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
