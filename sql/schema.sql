-- JuanArt – Full schema (phpMyAdmin / MySQL)
-- Run reference tables and subscriptions before tables that reference them

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Roles
CREATE TABLE IF NOT EXISTS user_roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL
);

-- KYC Status
CREATE TABLE IF NOT EXISTS kyc_statuses (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL
);

-- Order Status
CREATE TABLE IF NOT EXISTS order_statuses (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL
);

-- Commission Status
CREATE TABLE IF NOT EXISTS commission_statuses (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL
);

-- Payment Status
CREATE TABLE IF NOT EXISTS payment_statuses (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL
);

-- Subscriptions (before artist_profiles)
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    commission_percentage INT NOT NULL,
    duration_days INT NOT NULL
);

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    phone VARCHAR(20),
    role_id INT DEFAULT 1,
    profile_image TEXT,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES user_roles(role_id)
);

-- Artist Profiles (with balance for wallet)
CREATE TABLE IF NOT EXISTS artist_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT,
    experience TEXT,
    kyc_status_id INT DEFAULT 1,
    subscription_id INT,
    balance DECIMAL(12,2) DEFAULT 0,
    total_earnings DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (kyc_status_id) REFERENCES kyc_statuses(status_id),
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
);

-- Artist subscription period (active plan period)
CREATE TABLE IF NOT EXISTS artist_subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artist_id INT NOT NULL,
    subscription_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    payment_id INT,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artist_id) REFERENCES artist_profiles(id),
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
);

-- KYC Documents
CREATE TABLE IF NOT EXISTS kyc_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artist_id INT NOT NULL,
    valid_id_url TEXT NOT NULL,
    portfolio_urls TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_id INT DEFAULT 1,
    FOREIGN KEY (artist_id) REFERENCES artist_profiles(id),
    FOREIGN KEY (status_id) REFERENCES kyc_statuses(status_id)
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- Artworks
CREATE TABLE IF NOT EXISTS artworks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artist_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(12,2) NOT NULL,
    category_id INT,
    image_url TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artist_id) REFERENCES artist_profiles(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    total_amount DECIMAL(12,2),
    status_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id),
    FOREIGN KEY (status_id) REFERENCES order_statuses(status_id)
);

-- Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    artwork_id INT NOT NULL,
    price DECIMAL(12,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (artwork_id) REFERENCES artworks(id)
);

-- Commission Requests (with reference_images, delivery_url, delivered_at, client_approved_at)
CREATE TABLE IF NOT EXISTS commission_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    artist_id INT NOT NULL,
    description TEXT,
    reference_images TEXT,
    budget DECIMAL(12,2),
    deadline DATE,
    delivery_url TEXT,
    delivered_at TIMESTAMP NULL,
    client_approved_at TIMESTAMP NULL,
    status_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (artist_id) REFERENCES artist_profiles(id),
    FOREIGN KEY (status_id) REFERENCES commission_statuses(status_id)
);

-- Payments (order_id or commission_id)
CREATE TABLE IF NOT EXISTS payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_id INT,
    commission_id INT,
    amount DECIMAL(12,2),
    platform_commission DECIMAL(12,2),
    artist_earnings DECIMAL(12,2),
    payment_method VARCHAR(50),
    status_id INT DEFAULT 1,
    transaction_reference TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (commission_id) REFERENCES commission_requests(id),
    FOREIGN KEY (status_id) REFERENCES payment_statuses(status_id)
);

-- Disputes
CREATE TABLE IF NOT EXISTS disputes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commission_id INT,
    order_id INT,
    raised_by INT NOT NULL,
    reason TEXT,
    status VARCHAR(50) DEFAULT 'open',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (commission_id) REFERENCES commission_requests(id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (raised_by) REFERENCES users(id)
);

-- Payout requests
CREATE TABLE IF NOT EXISTS payout_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artist_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_reference TEXT,
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL,
    FOREIGN KEY (artist_id) REFERENCES artist_profiles(id)
);

-- Reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    artwork_id INT NOT NULL,
    buyer_id INT NOT NULL,
    rating INT CHECK(rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artwork_id) REFERENCES artworks(id),
    FOREIGN KEY (buyer_id) REFERENCES users(id)
);

SET FOREIGN_KEY_CHECKS = 1;
