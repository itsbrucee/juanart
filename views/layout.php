<?php
use JuanArt\Auth;

$currentPage = $_GET['page'] ?? 'dashboard';
$isAdmin = Auth::isAdmin();
$isArtist = Auth::isArtist();
$loggedIn = Auth::check();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'JuanArt') ?> – JuanArt</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="top-bar-left">
                    <span><i data-feather="phone"></i> 09544580361 </span>
                    <span><i data-feather="mail"></i> createwithbruce.ph@gmail.com</span>
                </div>
                <div class="top-bar-right">
                    <a href="#">English</a>
                    <a href="#">USD</a>
                    <?php if ($loggedIn): ?>
                        <span>Welcome, <?= htmlspecialchars(Auth::name()) ?></span>
                    <?php else: ?>
                        <a href="?page=login">Sign In</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <a href="?page=dashboard" class="logo">JuanArt</a>
                
                <nav class="nav-main">
                    <div class="nav-menu">
                        <a href="?page=dashboard" class="nav-link <?= in_array($currentPage, ['dashboard','home','']) ? 'active' : '' ?>">Home</a>
<a href="?page=shop" class="nav-link <?= $currentPage === 'shop' ? 'active' : '' ?>">Shop</a>
                        <a href="?page=gallery" class="nav-link <?= $currentPage === 'gallery' ? 'active' : '' ?>">Gallery</a>
                        
                        <?php if ($loggedIn): ?>
                            <a href="?page=my-orders" class="nav-link <?= $currentPage === 'orders' || $currentPage === 'order' ? 'active' : '' ?>">My Orders</a>
                            <a href="?page=my-commissions" class="nav-link <?= strpos($currentPage, 'commission') !== false ? 'active' : '' ?>">Commissions</a>
                            
                            <?php if (!$isAdmin && !$isArtist): ?>
                                <a href="?page=become-artist" class="nav-link">Become Artist</a>
                            <?php endif; ?>
                            
                            <?php if ($isAdmin): ?>
                                <a href="?page=admin/dashboard" class="nav-link admin-link">Admin Panel</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="?page=become-artist" class="nav-link">Become Artist</a>
                        <?php endif; ?>
                    </div>
                </nav>

                <div class="header-actions">
                    <div class="search-dropdown">
                        <button type="button" class="header-icon search-toggle" title="Search">
                            <i data-feather="search"></i>
                        </button>
                        <div class="search-panel">
                            <form method="get" class="search-form">
                                <input type="hidden" name="page" value="dashboard">
                                <input type="text" name="q" value="" placeholder="Search artworks..." class="input">
                                <select name="category" class="input">
                                    <option value="">All categories</option>
                                    <option value="2">Digital Art</option>
                                    <option value="3">Illustration</option>
                                    <option value="6">Mixed Media</option>
                                    <option value="1">Painting</option>
                                    <option value="5">Photography</option>
                                    <option value="4">Sculpture</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                    
                    <?php if ($loggedIn): ?>
                        <a href="?page=cart" class="header-icon <?= $currentPage === 'cart' || $currentPage === 'checkout' ? 'active' : '' ?>" title="Cart">
                            <i data-feather="shopping-cart"></i>
                        </a>
                        
                        <div class="user-dropdown">
                            <a href="#" class="header-icon user-profile-btn" title="Account">
                                <i data-feather="user"></i>
                            </a>
                            <div class="user-dropdown-menu">
                                <div class="user-dropdown-header">
                                    <span class="user-name"><?= htmlspecialchars(Auth::name()) ?></span>
                                    <span class="user-role"><?= $isAdmin ? 'Administrator' : ($isArtist ? 'Artist' : 'Member') ?></span>
                                </div>
                                <div class="user-dropdown-divider"></div>
                                <a href="?page=profile" class="user-dropdown-item">
                                    <i data-feather="user"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="?page=my-orders" class="user-dropdown-item">
                                    <i data-feather="shopping-bag"></i>
                                    <span>My Orders</span>
                                </a>
                                <a href="?page=my-commissions" class="user-dropdown-item">
                                    <i data-feather="briefcase"></i>
                                    <span>My Commissions</span>
                                </a>
                                <?php if ($isArtist): ?>
                                <a href="?page=artist/dashboard" class="user-dropdown-item">
                                    <i data-feather="layout"></i>
                                    <span>Artist Dashboard</span>
                                </a>
                                <a href="?page=artist/artworks" class="user-dropdown-item">
                                    <i data-feather="image"></i>
                                    <span>My Artworks</span>
                                </a>
                                <a href="?page=artist/earnings" class="user-dropdown-item">
                                    <i data-feather="dollar-sign"></i>
                                    <span>Earnings</span>
                                </a>
                                <?php endif; ?>
                                <?php if ($isAdmin): ?>
                                <a href="?page=admin/dashboard" class="user-dropdown-item">
                                    <i data-feather="settings"></i>
                                    <span>Admin Panel</span>
                                </a>
                                <?php endif; ?>
                                <div class="user-dropdown-divider"></div>
                                <a href="?page=logout" class="user-dropdown-item logout-item">
                                    <i data-feather="log-out"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="?page=login" class="btn btn-sm btn-secondary">Login</a>
                        <a href="?page=register" class="btn btn-sm btn-primary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="container" style="padding-top: var(--spacing-lg);">
                <div class="flash flash-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="container" style="padding-top: var(--spacing-lg);">
                <div class="flash flash-error"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
        
        <?= $content ?>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-main">
                <div class="footer-brand">
                    <a href="?page=dashboard" class="logo">JuanArt</a>
                    <p>Discover unique artworks from talented artists around the world. Buy, commission, and support creativity.</p>
                    <div class="footer-social">
                        <a href="#" class="social-link"><i data-feather="facebook"></i></a>
                        <a href="#" class="social-link"><i data-feather="instagram"></i></a>
                        <a href="#" class="social-link"><i data-feather="twitter"></i></a>
                        <a href="#" class="social-link"><i data-feather="youtube"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Shop</h4>
                    <ul class="footer-links">
                        <li><a href="?page=dashboard">All Artworks</a></li>
                        <li><a href="?page=dashboard">New Arrivals</a></li>
                        <li><a href="?page=dashboard">Best Sellers</a></li>
                        <li><a href="?page=dashboard">Featured Artists</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">For Artists</h4>
                    <ul class="footer-links">
                        <li><a href="?page=become-artist">Become a Seller</a></li>
                        <?php if ($isArtist): ?>
                            <li><a href="?page=artist/dashboard">Artist Dashboard</a></li>
                            <li><a href="?page=artist/artworks">My Artworks</a></li>
                            <li><a href="?page=artist/earnings">Earnings</a></li>
                        <?php endif; ?>
                        <li><a href="#">Seller Handbook</a></li>
                        <li><a href="#">Insights</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns</a></li>
                    </ul>
                </div>
                <?php if ($isAdmin): ?>
                <div class="footer-column">
                    <h4 class="footer-title">Admin</h4>
                    <ul class="footer-links">
                        <li><a href="?page=admin/dashboard">Dashboard</a></li>
                        <li><a href="?page=admin/users">Users</a></li>
                        <li><a href="?page=admin/kyc">KYC Requests</a></li>
                        <li><a href="?page=admin/transactions">Transactions</a></li>
                        <li><a href="?page=admin/disputes">Disputes</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <div class="footer-bottom">
                &copy; <?= date('Y') ?> JuanArt. All rights reserved. | Designed with passion for art.
            </div>
        </div>
    </footer>
    
    <style>
        .user-dropdown {
            position: relative;
        }
        
        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 220px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-sm);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all var(--transition-base);
            z-index: 1000;
            margin-top: var(--spacing-sm);
        }
        
        .user-dropdown:hover .user-dropdown-menu,
        .user-dropdown:focus-within .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .user-dropdown-header {
            padding: var(--spacing-md);
            text-align: center;
        }
        
        .user-dropdown-header .user-name {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            font-size: var(--font-size-base);
        }
        
        .user-dropdown-header .user-role {
            display: block;
            font-size: var(--font-size-xs);
            color: var(--text-muted);
            margin-top: 2px;
        }
        
        .user-dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: var(--spacing-sm) 0;
        }
        
        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-sm) var(--spacing-md);
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            text-decoration: none;
        }
        
        .user-dropdown-item:hover {
            background: var(--accent-primary-light-2);
            color: var(--accent-primary);
        }
        
        .user-dropdown-item i {
            width: 16px;
            height: 16px;
        }
        
        .user-dropdown-item.logout-item {
            color: var(--danger);
        }
        
        .user-dropdown-item.logout-item:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }
        
        .admin-link {
            color: var(--accent-primary) !important;
            font-weight: 600 !important;
        }
        
        .header-icon.active {
            color: var(--accent-primary);
            background: var(--accent-primary-light-2);
        }
        
        .btn-sm {
            padding: var(--spacing-sm) var(--spacing-md);
            font-size: var(--font-size-sm);
        }
        
        .btn-secondary {
            background: var(--bg-primary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all var(--transition-base);
        }
        
        .btn-secondary:hover {
            border-color: var(--accent-primary);
            color: var(--accent-primary);
        }
        
        .btn-primary {
            background: var(--accent-primary);
            color: var(--text-inverse);
            border: none;
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all var(--transition-base);
        }
        
        .btn-primary:hover {
            background: var(--accent-primary-hover);
        }
        
        /* Search Dropdown */
        .search-dropdown { position: relative; }
        .search-toggle { background: none; border: none; cursor: pointer; padding: 0; }
        .search-panel { position: absolute; top: 100%; right: 0; width: 320px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); padding: var(--spacing-md); opacity: 0; visibility: hidden; transform: translateY(10px); transition: all var(--transition-base); z-index: 1000; margin-top: var(--spacing-sm); }
        .search-panel.open { opacity: 1; visibility: visible; transform: translateY(0); }
        .search-form { display: flex; flex-direction: column; gap: var(--spacing-sm); }
        .search-form .input { width: 100%; }
        .search-form .btn { width: 100%; }
    </style>
    
    <script>
        // Search dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            var searchToggle = document.querySelector('.search-toggle');
            var searchPanel = document.querySelector('.search-panel');
            
            if (searchToggle && searchPanel) {
                searchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    searchPanel.classList.toggle('open');
                });
                
                document.addEventListener('click', function(e) {
                    if (!searchPanel.contains(e.target) && !searchToggle.contains(e.target)) {
                        searchPanel.classList.remove('open');
                    }
                });
            }
        });
        
        feather.replace();
    </script>
    <script src="app.js"></script>
</body>
</html>
