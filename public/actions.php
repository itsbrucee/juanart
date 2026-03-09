<?php
use JuanArt\Auth;
use JuanArt\Database;
use JuanArt\RoleGuard;

$pdo = Database::get();
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?: '';

function redirect_action(string $page = 'dashboard', string $extra = ''): void
{
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?: '';
    $url = ($base ? $base . '/' : '') . '?page=' . $page . ($extra ? '&' . $extra : '');
    header('Location: ' . $url);
    exit;
}

switch ($action) {
    case 'login':
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            $_SESSION['flash_error'] = 'Email and password required.';
            redirect_action('login');
        }
        $stmt = $pdo->prepare("SELECT u.id, u.name, u.password_hash, ur.role_name FROM users u JOIN user_roles ur ON u.role_id = ur.role_id WHERE u.email = ? AND u.status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Invalid email or password.';
            redirect_action('login');
        }
        Auth::login((int)$user['id'], $user['role_name'], $user['name']);
        if (Auth::isAdmin()) redirect_action('admin/dashboard');
        if (Auth::isArtist()) redirect_action('artist/dashboard');
        redirect_action();
        break;

    case 'register':
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$name || !$email || !$password) {
            $_SESSION['flash_error'] = 'Name, email and password required.';
            redirect_action('register');
        }
        if (strlen($password) < 6) {
            $_SESSION['flash_error'] = 'Password must be at least 6 characters.';
            redirect_action('register');
        }
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['flash_error'] = 'Email already registered.';
            redirect_action('register');
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (name, email, password_hash, phone, role_id) VALUES (?,?,?,?,1)")->execute([$name, $email, $hash, $phone ?: null]);
        $userId = (int)$pdo->lastInsertId();
        Auth::login($userId, 'client', $name);
        redirect_action();
        break;

    case 'add_to_cart':
        RoleGuard::requireAuth();
        $artworkId = (int)($_POST['artwork_id'] ?? 0);
        if (!$artworkId) redirect_action();
        $stmt = $pdo->prepare("SELECT id, title, price, image_url FROM artworks WHERE id = ? AND status = 'available'");
        $stmt->execute([$artworkId]);
        $art = $stmt->fetch();
        if (!$art) redirect_action();
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $key = (string)$artworkId;
        if (!isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key] = ['artwork_id' => $artworkId, 'title' => $art['title'], 'price' => $art['price'], 'image_url' => $art['image_url'], 'qty' => 0];
        }
        $_SESSION['cart'][$key]['qty'] += 1;
        $_SESSION['flash_success'] = 'Added to cart.';
        redirect_action();
        break;

    case 'remove_from_cart':
        RoleGuard::requireAuth();
        $artworkId = (string)($_POST['artwork_id'] ?? '');
        if (isset($_SESSION['cart'][$artworkId])) unset($_SESSION['cart'][$artworkId]);
        redirect_action('cart');
        break;

    case 'checkout':
        RoleGuard::requireAuth();
        $method = trim($_POST['payment_method'] ?? '');
        $ref = trim($_POST['transaction_reference'] ?? '');
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['flash_error'] = 'Cart is empty.';
            redirect_action('cart');
        }
        $total = 0;
        $items = [];
        foreach ($_SESSION['cart'] as $row) {
            $total += $row['price'] * $row['qty'];
            $items[] = $row;
        }
        $pdo->beginTransaction();
        try {
            $pdo->prepare("INSERT INTO orders (buyer_id, total_amount, status_id) VALUES (?,?,2)")->execute([Auth::id(), $total]);
            $orderId = (int)$pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, artwork_id, price) VALUES (?,?,?)");
            foreach ($items as $row) {
                for ($i = 0; $i < $row['qty']; $i++) {
                    $stmt->execute([$orderId, $row['artwork_id'], $row['price']]);
                }
            }
            $pdo->prepare("INSERT INTO payments (user_id, order_id, amount, platform_commission, artist_earnings, payment_method, status_id, transaction_reference) VALUES (?,?,?,0,?,?,2,?)")
                ->execute([Auth::id(), $orderId, $total, $total, $method ?: 'placeholder', $ref ?: null]);
            foreach ($items as $row) {
                $art = $pdo->prepare("SELECT a.artist_id, a.price, s.commission_percentage FROM artworks a JOIN artist_profiles ap ON a.artist_id = ap.id LEFT JOIN subscriptions s ON ap.subscription_id = s.id WHERE a.id = ?");
                $art->execute([$row['artwork_id']]);
                $a = $art->fetch();
                if ($a) {
                    $pct = $a['commission_percentage'] ?? 20;
                    $commission = round($a['price'] * $pct / 100, 2);
                    $earnings = $a['price'] - $commission;
                    $pdo->prepare("UPDATE artist_profiles SET balance = balance + ?, total_earnings = total_earnings + ? WHERE id = ?")
                        ->execute([$earnings, $earnings, $a['artist_id']]);
                }
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['flash_error'] = 'Order failed.';
            redirect_action('cart');
        }
        $_SESSION['cart'] = [];
        $_SESSION['flash_success'] = 'Order placed successfully.';
        redirect_action('order', 'id=' . $orderId);
        break;

    case 'kyc_submit':
        RoleGuard::requireAuth();
        $stmt = $pdo->prepare("SELECT id FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if ($profile) {
            $_SESSION['flash_error'] = 'You already have an artist profile.';
            redirect_action('artist/dashboard');
        }
        $bio = trim($_POST['bio'] ?? '');
        $experience = trim($_POST['experience'] ?? '');
        $validIdUrl = trim($_POST['valid_id_url'] ?? '');
        $portfolioUrls = trim($_POST['portfolio_urls'] ?? '');
        if (!$validIdUrl || !$bio || !$experience) {
            $_SESSION['flash_error'] = 'Bio, experience and valid ID required.';
            redirect_action('become-artist');
        }
        $pdo->beginTransaction();
        try {
            $pdo->prepare("INSERT INTO artist_profiles (user_id, bio, experience, kyc_status_id) VALUES (?,?,?,1)")->execute([Auth::id(), $bio, $experience]);
            $artistId = (int)$pdo->lastInsertId();
            $pdo->prepare("INSERT INTO kyc_documents (artist_id, valid_id_url, portfolio_urls, status_id) VALUES (?,?,?,1)")->execute([$artistId, $validIdUrl, $portfolioUrls ?: null]);
            $pdo->prepare("UPDATE users SET role_id = 2 WHERE id = ?")->execute([Auth::id()]);
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['flash_error'] = 'Submission failed.';
            redirect_action('become-artist');
        }
        $_SESSION['user_role'] = 'artist';
        $_SESSION['flash_success'] = 'KYC submitted. Waiting for admin approval.';
        redirect_action('artist/dashboard');
        break;

    case 'kyc_approve':
        RoleGuard::requireAdmin();
        $docId = (int)($_POST['doc_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT artist_id FROM kyc_documents WHERE id = ? AND status_id = 1");
        $stmt->execute([$docId]);
        $row = $stmt->fetch();
        if ($row) {
            $pdo->prepare("UPDATE kyc_documents SET status_id = 2 WHERE id = ?")->execute([$docId]);
            $pdo->prepare("UPDATE artist_profiles SET kyc_status_id = 2 WHERE id = ?")->execute([$row['artist_id']]);
            $_SESSION['flash_success'] = 'KYC approved.';
        }
        redirect_action('admin/kyc');
        break;

    case 'kyc_reject':
        RoleGuard::requireAdmin();
        $docId = (int)($_POST['doc_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT artist_id FROM kyc_documents WHERE id = ? AND status_id = 1");
        $stmt->execute([$docId]);
        $row = $stmt->fetch();
        if ($row) {
            $pdo->prepare("UPDATE kyc_documents SET status_id = 3 WHERE id = ?")->execute([$docId]);
            $pdo->prepare("UPDATE artist_profiles SET kyc_status_id = 3 WHERE id = ?")->execute([$row['artist_id']]);
            $_SESSION['flash_success'] = 'KYC rejected.';
        }
        redirect_action('admin/kyc');
        break;

    case 'subscribe':
        RoleGuard::requireArtist();
        $planId = (int)($_POST['subscription_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if (!$profile || $profile['kyc_status_id'] != 2) redirect_action('artist/dashboard');
        $plan = $pdo->prepare("SELECT * FROM subscriptions WHERE id = ?");
        $plan->execute([$planId]);
        $plan = $plan->fetch();
        if (!$plan) redirect_action('artist/subscribe');
        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+' . $plan['duration_days'] . ' days'));
        $pdo->prepare("INSERT INTO artist_subscriptions (artist_id, subscription_id, start_date, end_date, status) VALUES (?,?,?,?, 'active')")->execute([$profile['id'], $planId, $start, $end]);
        $pdo->prepare("UPDATE artist_profiles SET subscription_id = ? WHERE id = ?")->execute([$planId, $profile['id']]);
        $_SESSION['flash_success'] = 'Subscribed to ' . $plan['name'] . '.';
        redirect_action('artist/dashboard');
        break;

    case 'artwork_add':
        RoleGuard::requireArtist();
        $stmt = $pdo->prepare("SELECT id FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if (!$profile) redirect_action('become-artist');
        $activeSub = $pdo->prepare("SELECT id FROM artist_subscriptions WHERE artist_id = ? AND status = 'active' AND end_date >= CURDATE()");
        $activeSub->execute([$profile['id']]);
        if (!$activeSub->fetch()) {
            $_SESSION['flash_error'] = 'Active subscription required to add artworks.';
            redirect_action('artist/artworks');
        }
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $artworkType = $_POST['artwork_type'] ?? 'for_sale';
        $price = (float)($_POST['price'] ?? 0);
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        
        // Handle file upload - save to public folder for web access
        $imageUrl = '';
        if (isset($_FILES['artwork_image']) && $_FILES['artwork_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/artworks/';
            $fileName = uniqid('artwork_') . '_' . basename($_FILES['artwork_image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['artwork_image']['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['artwork_image']['tmp_name'], $targetPath)) {
                    $imageUrl = 'uploads/artworks/' . $fileName;
                }
            }
        }
        
        // Fallback to URL if no file uploaded
        if (!$imageUrl) {
            $imageUrl = trim($_POST['image_url'] ?? '');
            if (!$imageUrl) $imageUrl = 'https://placehold.co/400x300?text=Art';
        }
        
        // Validate based on artwork type
        if (!$title) {
            $_SESSION['flash_error'] = 'Title required.';
            redirect_action('artist/artworks');
        }
        
        if ($artworkType === 'for_sale' && $price <= 0) {
            $_SESSION['flash_error'] = 'Price required for artworks for sale.';
            redirect_action('artist/artworks');
        }
        
        // Set price to 0 for gallery-only artworks
        if ($artworkType === 'gallery_only') {
            $price = 0;
        }
        
        $pdo->prepare("INSERT INTO artworks (artist_id, title, description, price, category_id, image_url, artwork_type) VALUES (?,?,?,?,?,?,?)")
            ->execute([$profile['id'], $title, $description, $price, $categoryId, $imageUrl, $artworkType]);
        $_SESSION['flash_success'] = 'Artwork added.';
        redirect_action('artist/artworks');
        break;

    case 'artwork_edit':
        RoleGuard::requireArtist();
        $id = (int)($_POST['artwork_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT a.id FROM artworks a JOIN artist_profiles ap ON a.artist_id = ap.id WHERE ap.user_id = ? AND a.id = ?");
        $stmt->execute([Auth::id(), $id]);
        if (!$stmt->fetch()) redirect_action('artist/artworks');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $imageUrl = trim($_POST['image_url'] ?? '');
        if ($title && $price > 0) {
            $pdo->prepare("UPDATE artworks SET title=?, description=?, price=?, category_id=?, image_url=? WHERE id=?")
                ->execute([$title, $description, $price, $categoryId ?: null, $imageUrl ?: null, $id]);
            $_SESSION['flash_success'] = 'Artwork updated.';
        }
        redirect_action('artist/artworks');
        break;

    case 'artwork_delete':
        RoleGuard::requireArtist();
        $id = (int)($_POST['artwork_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT a.id FROM artworks a JOIN artist_profiles ap ON a.artist_id = ap.id WHERE ap.user_id = ? AND a.id = ?");
        $stmt->execute([Auth::id(), $id]);
        if ($stmt->fetch()) {
            $pdo->prepare("UPDATE artworks SET status = 'unavailable' WHERE id = ?")->execute([$id]);
            $_SESSION['flash_success'] = 'Artwork removed.';
        }
        redirect_action('artist/artworks');
        break;

    case 'commission_request':
        RoleGuard::requireAuth();
        $artistId = (int)($_POST['artist_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $budget = (float)($_POST['budget'] ?? 0);
        $deadline = trim($_POST['deadline'] ?? '');
        $refImages = trim($_POST['reference_images'] ?? '');
        if (!$artistId || !$description || $budget <= 0) {
            $_SESSION['flash_error'] = 'Description and budget required.';
            redirect_action('commission/request', 'artist_id=' . $artistId);
        }
        $stmt = $pdo->prepare("SELECT id FROM artist_profiles WHERE id = ? AND kyc_status_id = 2");
        $stmt->execute([$artistId]);
        if (!$stmt->fetch()) redirect_action();
        $pdo->prepare("INSERT INTO commission_requests (client_id, artist_id, description, reference_images, budget, deadline, status_id) VALUES (?,?,?,?,?,?,1)")
            ->execute([Auth::id(), $artistId, $description, $refImages ?: null, $budget, $deadline ?: null]);
        $_SESSION['flash_success'] = 'Commission request sent.';
        redirect_action('my-commissions');
        break;

    case 'commission_accept':
        RoleGuard::requireArtist();
        $id = (int)($_POST['commission_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT cr.id FROM commission_requests cr JOIN artist_profiles ap ON cr.artist_id = ap.id WHERE ap.user_id = ? AND cr.id = ? AND cr.status_id = 1");
        $stmt->execute([Auth::id(), $id]);
        if ($stmt->fetch()) {
            $pdo->prepare("UPDATE commission_requests SET status_id = 2 WHERE id = ?")->execute([$id]);
            $_SESSION['flash_success'] = 'Commission accepted. Client can now pay.';
        }
        redirect_action('commission', 'id=' . $id);
        break;

    case 'commission_reject':
        RoleGuard::requireArtist();
        $id = (int)($_POST['commission_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT cr.id FROM commission_requests cr JOIN artist_profiles ap ON cr.artist_id = ap.id WHERE ap.user_id = ? AND cr.id = ? AND cr.status_id = 1");
        $stmt->execute([Auth::id(), $id]);
        if ($stmt->fetch()) {
            $pdo->prepare("UPDATE commission_requests SET status_id = 3 WHERE id = ?")->execute([$id]);
            $_SESSION['flash_success'] = 'Commission rejected.';
        }
        redirect_action('artist/dashboard');
        break;

    case 'commission_pay':
        RoleGuard::requireAuth();
        $id = (int)($_POST['commission_id'] ?? 0);
        $ref = trim($_POST['transaction_reference'] ?? '');
        $stmt = $pdo->prepare("SELECT * FROM commission_requests WHERE id = ? AND client_id = ? AND status_id = 2");
        $stmt->execute([$id, Auth::id()]);
        $comm = $stmt->fetch();
        if (!$comm) redirect_action('my-commissions');
        $pdo->beginTransaction();
        try {
            $pdo->prepare("INSERT INTO payments (user_id, commission_id, amount, payment_method, status_id, transaction_reference) VALUES (?,?,?,'placeholder',2,?)")
                ->execute([Auth::id(), $id, $comm['budget'], $ref]);
            $pdo->prepare("UPDATE commission_requests SET status_id = 4 WHERE id = ?")->execute([$id]);
            $pdo->commit();
            $_SESSION['flash_success'] = 'Payment recorded. Artist will deliver.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['flash_error'] = 'Payment failed.';
        }
        redirect_action('commission', 'id=' . $id);
        break;

    case 'commission_deliver':
        RoleGuard::requireArtist();
        $id = (int)($_POST['commission_id'] ?? 0);
        $url = trim($_POST['delivery_url'] ?? '');
        $stmt = $pdo->prepare("SELECT cr.id FROM commission_requests cr JOIN artist_profiles ap ON cr.artist_id = ap.id WHERE ap.user_id = ? AND cr.id = ? AND cr.status_id = 4");
        $stmt->execute([Auth::id(), $id]);
        if ($stmt->fetch() && $url) {
            $pdo->prepare("UPDATE commission_requests SET delivery_url = ?, delivered_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$url, $id]);
            $_SESSION['flash_success'] = 'Delivery submitted. Waiting for client approval.';
        }
        redirect_action('commission', 'id=' . $id);
        break;

    case 'commission_approve':
        RoleGuard::requireAuth();
        $id = (int)($_POST['commission_id'] ?? 0);
        $stmt = $pdo->prepare("SELECT cr.* FROM commission_requests cr WHERE cr.id = ? AND cr.client_id = ? AND cr.status_id = 4 AND cr.delivered_at IS NOT NULL");
        $stmt->execute([$id, Auth::id()]);
        $comm = $stmt->fetch();
        if (!$comm) redirect_action('my-commissions');
        $pdo->beginTransaction();
        try {
            $pdo->prepare("UPDATE commission_requests SET client_approved_at = CURRENT_TIMESTAMP, status_id = 5 WHERE id = ?")->execute([$id]);
            $sub = $pdo->prepare("SELECT s.commission_percentage FROM artist_profiles ap LEFT JOIN subscriptions s ON ap.subscription_id = s.id WHERE ap.id = ?");
            $sub->execute([$comm['artist_id']]);
            $s = $sub->fetch();
            $pct = $s['commission_percentage'] ?? 20;
            $commission = round($comm['budget'] * $pct / 100, 2);
            $earnings = $comm['budget'] - $commission;
            $pdo->prepare("UPDATE artist_profiles SET balance = balance + ?, total_earnings = total_earnings + ? WHERE id = ?")->execute([$earnings, $earnings, $comm['artist_id']]);
            $pdo->prepare("UPDATE payments SET platform_commission = ?, artist_earnings = ? WHERE commission_id = ?")->execute([$commission, $earnings, $id]);
            $pdo->prepare("UPDATE payments SET status_id = 2 WHERE commission_id = ?")->execute([$id]);
            $pdo->commit();
            $_SESSION['flash_success'] = 'Commission completed. Payment released to artist.';
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['flash_error'] = 'Failed to release payment.';
        }
        redirect_action('commission', 'id=' . $id);
        break;

    case 'dispute_create':
        RoleGuard::requireAuth();
        $commissionId = !empty($_POST['commission_id']) ? (int)$_POST['commission_id'] : null;
        $orderId = !empty($_POST['order_id']) ? (int)$_POST['order_id'] : null;
        $reason = trim($_POST['reason'] ?? '');
        if (($commissionId || $orderId) && $reason) {
            $pdo->prepare("INSERT INTO disputes (commission_id, order_id, raised_by, reason, status) VALUES (?,?,?,?,'open')")
                ->execute([$commissionId, $orderId, Auth::id(), $reason]);
            $_SESSION['flash_success'] = 'Dispute submitted.';
        }
        redirect_action($commissionId ? 'commission' : 'orders', $commissionId ? 'id=' . $commissionId : '');
        break;

    case 'dispute_resolve':
        RoleGuard::requireAdmin();
        $disputeId = (int)($_POST['dispute_id'] ?? 0);
        $notes = trim($_POST['admin_notes'] ?? '');
        $pdo->prepare("UPDATE disputes SET status = 'resolved', admin_notes = ?, resolved_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$notes, $disputeId]);
        $_SESSION['flash_success'] = 'Dispute resolved.';
        redirect_action('admin/disputes');
        break;

    case 'payout_request':
        RoleGuard::requireArtist();
        $stmt = $pdo->prepare("SELECT id, balance FROM artist_profiles WHERE user_id = ?");
        $stmt->execute([Auth::id()]);
        $profile = $stmt->fetch();
        if (!$profile || $profile['balance'] <= 0) redirect_action('artist/earnings');
        $pdo->beginTransaction();
        try {
            $pdo->prepare("INSERT INTO payout_requests (artist_id, amount, status) VALUES (?,?, 'pending')")->execute([$profile['id'], $profile['balance']]);
            $pdo->prepare("UPDATE artist_profiles SET balance = 0 WHERE id = ?")->execute([$profile['id']]);
            $pdo->commit();
            $_SESSION['flash_success'] = 'Payout requested.';
        } catch (Exception $e) {
            $pdo->rollBack();
        }
        redirect_action('artist/earnings');
        break;

    case 'payout_process':
        RoleGuard::requireAdmin();
        $id = (int)($_POST['payout_id'] ?? 0);
        $ref = trim($_POST['payment_reference'] ?? '');
        $pdo->prepare("UPDATE payout_requests SET status = 'processed', payment_reference = ?, processed_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$ref, $id]);
        $_SESSION['flash_success'] = 'Payout marked processed.';
        redirect_action('admin/payouts');
        break;

    case 'category_add':
        RoleGuard::requireAdmin();
        $name = trim($_POST['name'] ?? '');
        if ($name) {
            $pdo->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$name]);
            $_SESSION['flash_success'] = 'Category added.';
        }
        redirect_action('admin/categories');
        break;

    case 'category_edit':
        RoleGuard::requireAdmin();
        $id = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        if ($id && $name) $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?")->execute([$name, $id]);
        redirect_action('admin/categories');
        break;

    case 'category_delete':
        RoleGuard::requireAdmin();
        $id = (int)($_POST['category_id'] ?? 0);
        if ($id) $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        redirect_action('admin/categories');
        break;

    case 'subscription_edit':
        RoleGuard::requireAdmin();
        $id = (int)($_POST['subscription_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $pct = (int)($_POST['commission_percentage'] ?? 0);
        $days = (int)($_POST['duration_days'] ?? 30);
        if ($id && $name) $pdo->prepare("UPDATE subscriptions SET name=?, price=?, commission_percentage=?, duration_days=? WHERE id=?")->execute([$name, $price, $pct, $days, $id]);
        redirect_action('admin/subscriptions');
        break;

    case 'user_suspend':
        RoleGuard::requireAdmin();
        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId) $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ?")->execute([$userId]);
        redirect_action('admin/users');
        break;

    case 'user_activate':
        RoleGuard::requireAdmin();
        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId) $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?")->execute([$userId]);
        redirect_action('admin/users');
        break;

    case 'order_status':
        RoleGuard::requireAdmin();
        $orderId = (int)($_POST['order_id'] ?? 0);
        $statusId = (int)($_POST['status_id'] ?? 0);
        if ($orderId && $statusId) $pdo->prepare("UPDATE orders SET status_id = ? WHERE id = ?")->execute([$statusId, $orderId]);
        redirect_action('admin/transactions');
        break;

    default:
        redirect_action();
}
