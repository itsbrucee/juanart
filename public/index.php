<?php
require_once dirname(__DIR__) . '/bootstrap.php';

use JuanArt\Auth;
use JuanArt\Database;
use JuanArt\RoleGuard;

$page = $_GET['page'] ?? 'dashboard';
$page = preg_replace('/[^a-z0-9_\-\/]/', '', $page) ?: 'dashboard';

// API-style actions (POST)
$action = $_POST['action'] ?? $_GET['action'] ?? null;
if ($action) {
    require __DIR__ . '/actions.php';
    exit;
}

// Page routing
switch ($page) {
    case 'dashboard':
    case 'home':
    case '':
        $pdo = Database::get();
        $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
        
        // Get top 3 most viewed artworks for promo cards
        $topViewedQuery = "SELECT a.*, ap.id AS artist_profile_id, u.name AS artist_name 
            FROM artworks a 
            JOIN artist_profiles ap ON a.artist_id = ap.id 
            JOIN users u ON ap.user_id = u.id 
            WHERE a.status = 'available' 
            ORDER BY a.view_count DESC 
            LIMIT 3";
        $topViewedArtworks = $pdo->query($topViewedQuery)->fetchAll();
        
        $featured = $pdo->query("SELECT a.*, ap.id AS artist_profile_id, u.name AS artist_name FROM artworks a JOIN artist_profiles ap ON a.artist_id = ap.id JOIN users u ON ap.user_id = u.id WHERE a.status = 'available' ORDER BY a.created_at DESC LIMIT 12")->fetchAll();
        $categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        // Get all available artworks
        $allArtworksQuery = "SELECT a.*, ap.id AS artist_profile_id, u.name AS artist_name, u.profile_image AS artist_avatar, c.name AS category_name FROM artworks a JOIN artist_profiles ap ON a.artist_id = ap.id JOIN users u ON ap.user_id = u.id LEFT JOIN categories c ON a.category_id = c.id WHERE a.status = 'available'";
        $params = [];
        if ($categoryFilter) {
            $allArtworksQuery .= " AND a.category_id = ?";
            $params[] = $categoryFilter;
        }
        if ($search !== '') {
            $allArtworksQuery .= " AND (a.title LIKE ? OR a.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        $allArtworksQuery .= " ORDER BY a.created_at DESC LIMIT 48";
        $stmt = $pdo->prepare($allArtworksQuery);
        $stmt->execute($params);
        $allArtworks = $stmt->fetchAll();
        
        // Separate into for_sale and gallery_only
        $forSaleArtworks = [];
        $galleryArtworks = [];
        foreach ($allArtworks as $a) {
            if (($a['artwork_type'] ?? 'for_sale') === 'gallery_only') {
                $galleryArtworks[] = $a;
            } else {
                $forSaleArtworks[] = $a;
            }
        }
        
        view('dashboard', ['categories' => $categories, 'featured' => $featured, 'forSaleArtworks' => $forSaleArtworks, 'galleryArtworks' => $galleryArtworks, 'categoryFilter' => $categoryFilter, 'search' => $search, 'topViewedArtworks' => $topViewedArtworks]);
        break;

    case 'shop':
        $pdo = Database::get();
        $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
        
        $categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $sort = $_GET['sort'] ?? 'newest';
        
        // Get all for-sale artworks (status = 'available' AND artwork_type = 'for_sale')
        $forSaleQuery = "SELECT a.*, ap.id AS artist_profile_id, u.name AS artist_name, u.profile_image AS artist_avatar, c.name AS category_name 
            FROM artworks a 
            JOIN artist_profiles ap ON a.artist_id = ap.id 
            JOIN users u ON ap.user_id = u.id 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.status = 'available' 
            AND (a.artwork_type = 'for_sale' OR a.artwork_type IS NULL OR a.artwork_type = '')";
        
        $params = [];
        
        if ($categoryFilter) {
            $forSaleQuery .= " AND a.category_id = ?";
            $params[] = $categoryFilter;
        }
        
        if ($search !== '') {
            $forSaleQuery .= " AND (a.title LIKE ? OR a.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        // Sorting
        switch ($sort) {
            case 'price_low':
                $forSaleQuery .= " ORDER BY a.price ASC";
                break;
            case 'price_high':
                $forSaleQuery .= " ORDER BY a.price DESC";
                break;
            case 'popular':
                $forSaleQuery .= " ORDER BY a.view_count DESC, a.created_at DESC";
                break;
            case 'newest':
            default:
                $forSaleQuery .= " ORDER BY a.created_at DESC";
        }
        
        $forSaleQuery .= " LIMIT 100";
        
        $stmt = $pdo->prepare($forSaleQuery);
        $stmt->execute($params);
        $forSaleArtworks = $stmt->fetchAll();
        
        view('shop', [
            'categories' => $categories, 
            'forSaleArtworks' => $forSaleArtworks, 
            'categoryFilter' => $categoryFilter, 
            'search' => $search,
            'sort' => $sort
        ]);
        break;

    case 'gallery':
        $pdo = Database::get();
        
        // Get all gallery-only artworks (status = 'available' AND artwork_type = 'gallery_only')
        $galleryQuery = "SELECT a.*, ap.id AS artist_profile_id, u.name AS artist_name, u.profile_image AS artist_avatar, c.name AS category_name 
            FROM artworks a 
            JOIN artist_profiles ap ON a.artist_id = ap.id 
            JOIN users u ON ap.user_id = u.id 
            LEFT JOIN categories c ON a.category_id = c.id 
            WHERE a.status = 'available' 
            AND a.artwork_type = 'gallery_only'
            ORDER BY a.created_at DESC 
            LIMIT 100";
        
        $stmt = $pdo->query($galleryQuery);
        $galleryArtworks = $stmt->fetchAll();
        
        view('gallery', [
            'galleryArtworks' => $galleryArtworks
        ]);
        break;

    case 'login':
        if (Auth::check()) {
            redirect('?page=dashboard');
        }
        view('auth/login');
        break;

    case 'register':
        if (Auth::check()) {
            redirect('?page=dashboard');
        }
        view('auth/register');
        break;

    case 'logout':
        Auth::logout();
        redirect('?page=dashboard');
        break;

    case 'artist':
    case 'artist/profile':
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            redirect('?page=dashboard');
        }
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT ap.*, u.name, u.email, u.profile_image FROM artist_profiles ap JOIN users u ON ap.user_id = u.id WHERE ap.id = ?");
        $stmt->execute([$id]);
        $artist = $stmt->fetch();
        if (!$artist) {
            redirect('?page=dashboard');
        }
        $artworks = $pdo->prepare("SELECT * FROM artworks WHERE artist_id = ? AND status = 'available' ORDER BY created_at DESC");
        $artworks->execute([$id]);
        $artist['artworks'] = $artworks->fetchAll();
        
        // Track view for each artwork when artist profile is viewed
        // This increments view_count for all displayed artworks
        if (!empty($artist['artworks'])) {
            foreach ($artist['artworks'] as $artwork) {
                if (!empty($artwork['id'])) {
                    $pdo->prepare("UPDATE artworks SET view_count = view_count + 1 WHERE id = ?")->execute([$artwork['id']]);
                }
            }
        }
        
        view('artist/profile', ['artist' => $artist]);
        break;

    case 'cart':
        RoleGuard::requireAuth('?page=login');
        view('cart/index');
        break;

    case 'checkout':
        RoleGuard::requireAuth('?page=login');
        view('cart/checkout');
        break;

    case 'orders':
    case 'my-orders':
        RoleGuard::requireAuth('?page=login');
        $pdo = Database::get();
        $orders = $pdo->prepare("SELECT o.*, os.status_name FROM orders o JOIN order_statuses os ON o.status_id = os.status_id WHERE o.buyer_id = ? ORDER BY o.created_at DESC");
        $orders->execute([Auth::id()]);
        view('orders/list', ['orders' => $orders->fetchAll()]);
        break;

    case 'order':
        RoleGuard::requireAuth('?page=login');
        $id = (int)($_GET['id'] ?? 0);
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT o.*, os.status_name FROM orders o JOIN order_statuses os ON o.status_id = os.status_id WHERE o.id = ? AND o.buyer_id = ?");
        $stmt->execute([$id, Auth::id()]);
        $order = $stmt->fetch();
        if (!$order) {
            redirect('?page=orders');
        }
        $items = $pdo->prepare("SELECT oi.*, a.title, a.image_url FROM order_items oi JOIN artworks a ON oi.artwork_id = a.id WHERE oi.order_id = ?");
        $items->execute([$id]);
        $order['items'] = $items->fetchAll();
        view('orders/detail', ['order' => $order]);
        break;

    case 'become-artist':
        RoleGuard::requireAuth('?page=login');
        if (Auth::isArtist()) {
            redirect('?page=artist/dashboard');
        }
        view('artist/become');
        break;

    case 'artist/dashboard':
        RoleGuard::requireArtist();
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT * FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if (!$profile) {
            redirect('?page=become-artist');
        }
        $artworks = $pdo->prepare("SELECT a.*, c.name AS category_name FROM artworks a LEFT JOIN categories c ON a.category_id = c.id WHERE a.artist_id = ? ORDER BY a.created_at DESC");
        $artworks->execute([$profile['id']]);
        $profile['artworks'] = $artworks->fetchAll();
        $commissions = $pdo->prepare("SELECT cr.*, cs.status_name, u.name AS client_name FROM commission_requests cr JOIN commission_statuses cs ON cr.status_id = cs.status_id JOIN users u ON cr.client_id = u.id WHERE cr.artist_id = ? ORDER BY cr.created_at DESC LIMIT 20");
        $commissions->execute([$profile['id']]);
        $profile['commissions'] = $commissions->fetchAll();
        view('artist/dashboard', ['profile' => $profile]);
        break;

    case 'artist/artworks':
        RoleGuard::requireArtist();
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT * FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if (!$profile) redirect('?page=become-artist');
        $artworks = $pdo->prepare("SELECT a.*, c.name AS category_name FROM artworks a LEFT JOIN categories c ON a.category_id = c.id WHERE a.artist_id = ? ORDER BY a.created_at DESC");
        $artworks->execute([$profile['id']]);
        view('artist/artworks', ['profile' => $profile, 'artworks' => $artworks->fetchAll()]);
        break;

    case 'artist/subscribe':
        RoleGuard::requireArtist();
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT * FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if (!$profile) redirect('?page=become-artist');
        $plans = $pdo->query("SELECT * FROM subscriptions ORDER BY price")->fetchAll();
        view('artist/subscribe', ['profile' => $profile, 'plans' => $plans]);
        break;

    case 'artist/earnings':
        RoleGuard::requireArtist();
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT * FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if (!$profile) redirect('?page=become-artist');
        $payouts = $pdo->prepare("SELECT * FROM payout_requests WHERE artist_id = ? ORDER BY requested_at DESC");
        $payouts->execute([$profile['id']]);
        view('artist/earnings', ['profile' => $profile, 'payouts' => $payouts->fetchAll()]);
        break;

    case 'commission/request':
        RoleGuard::requireAuth('?page=login');
        $artistId = (int)($_GET['artist_id'] ?? 0);
        if (!$artistId) redirect('?page=dashboard');
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT ap.*, u.name FROM artist_profiles ap JOIN users u ON ap.user_id = u.id WHERE ap.id = ? AND ap.kyc_status_id = 2");
        $stmt->execute([$artistId]);
        $artist = $stmt->fetch();
        if (!$artist) redirect('?page=dashboard');
        view('commission/request', ['artist' => $artist]);
        break;

    case 'my-commissions':
        RoleGuard::requireAuth('?page=login');
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT cr.*, cs.status_name, u.name AS artist_name, ap.id AS artist_profile_id FROM commission_requests cr JOIN commission_statuses cs ON cr.status_id = cs.status_id JOIN artist_profiles ap ON cr.artist_id = ap.id JOIN users u ON ap.user_id = u.id WHERE cr.client_id = ? ORDER BY cr.created_at DESC");
        $stmt->execute([Auth::id()]);
        view('commission/list', ['commissions' => $stmt->fetchAll()]);
        break;

    case 'commission':
        RoleGuard::requireAuth('?page=login');
        $id = (int)($_GET['id'] ?? 0);
        $pdo = Database::get();
        $stmt = $pdo->prepare("SELECT cr.*, cs.status_name FROM commission_requests cr JOIN commission_statuses cs ON cr.status_id = cs.status_id WHERE cr.id = ? AND (cr.client_id = ? OR cr.artist_id IN (SELECT id FROM artist_profiles WHERE user_id = ?))");
        $stmt->execute([$id, Auth::id(), Auth::id()]);
        $commission = $stmt->fetch();
        if (!$commission) redirect('?page=my-commissions');
        $artist = $pdo->prepare("SELECT ap.*, u.name FROM artist_profiles ap JOIN users u ON ap.user_id = u.id WHERE ap.id = ?");
        $artist->execute([$commission['artist_id']]);
        $commission['artist'] = $artist->fetch();
        $client = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
        $client->execute([$commission['client_id']]);
        $commission['client'] = $client->fetch();
        view('commission/detail', ['commission' => $commission]);
        break;

    case 'admin':
    case 'admin/dashboard':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $stats = [
            'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'artists' => $pdo->query("SELECT COUNT(*) FROM artist_profiles")->fetchColumn(),
            'artworks' => $pdo->query("SELECT COUNT(*) FROM artworks")->fetchColumn(),
            'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'commissions' => $pdo->query("SELECT COUNT(*) FROM commission_requests")->fetchColumn(),
            'total_sales' => $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status_id IN (2,3,4)")->fetchColumn(),
            'pending_kyc' => $pdo->query("SELECT COUNT(*) FROM kyc_documents WHERE status_id = 1")->fetchColumn(),
            'open_disputes' => $pdo->query("SELECT COUNT(*) FROM disputes WHERE status = 'open'")->fetchColumn(),
        ];
        view('admin/dashboard', ['stats' => $stats]);
        break;

    case 'admin/kyc':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $pending = $pdo->query("SELECT k.*, ap.id AS artist_id, u.name, u.email FROM kyc_documents k JOIN artist_profiles ap ON k.artist_id = ap.id JOIN users u ON ap.user_id = u.id WHERE k.status_id = 1 ORDER BY k.submitted_at DESC")->fetchAll();
        view('admin/kyc', ['pending' => $pending]);
        break;

    case 'admin/categories':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
        view('admin/categories', ['categories' => $categories]);
        break;

    case 'admin/subscriptions':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $subscriptions = $pdo->query("SELECT * FROM subscriptions ORDER BY price")->fetchAll();
        view('admin/subscriptions', ['subscriptions' => $subscriptions]);
        break;

    case 'admin/users':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $users = $pdo->query("SELECT u.*, ur.role_name FROM users u JOIN user_roles ur ON u.role_id = ur.role_id ORDER BY u.created_at DESC")->fetchAll();
        view('admin/users', ['users' => $users]);
        break;

    case 'admin/transactions':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $tx = $pdo->query("SELECT p.*, u.name AS user_name, ps.status_name FROM payments p JOIN users u ON p.user_id = u.id JOIN payment_statuses ps ON p.status_id = ps.status_id ORDER BY p.created_at DESC LIMIT 100")->fetchAll();
        view('admin/transactions', ['transactions' => $tx]);
        break;

    case 'admin/disputes':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $disputes = $pdo->query("SELECT d.*, u.name AS raised_by_name FROM disputes d JOIN users u ON d.raised_by = u.id ORDER BY d.created_at DESC")->fetchAll();
        view('admin/disputes', ['disputes' => $disputes]);
        break;

    case 'admin/payouts':
        RoleGuard::requireAdmin();
        $pdo = Database::get();
        $payouts = $pdo->query("SELECT pr.*, ap.id AS artist_profile_id, u.name AS artist_name FROM payout_requests pr JOIN artist_profiles ap ON pr.artist_id = ap.id JOIN users u ON ap.user_id = u.id ORDER BY pr.requested_at DESC")->fetchAll();
        view('admin/payouts', ['payouts' => $payouts]);
        break;

    default:
        http_response_code(404);
        view('errors/404');
}
