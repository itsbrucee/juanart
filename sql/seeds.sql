-- JuanArt – Seed reference data
-- Run after schema.sql

INSERT IGNORE INTO user_roles (role_id, role_name) VALUES
(1, 'client'),
(2, 'artist'),
(3, 'admin');

INSERT IGNORE INTO kyc_statuses (status_id, status_name) VALUES
(1, 'pending'),
(2, 'approved'),
(3, 'rejected');

INSERT IGNORE INTO order_statuses (status_id, status_name) VALUES
(1, 'pending'),
(2, 'paid'),
(3, 'shipped'),
(4, 'completed'),
(5, 'cancelled');

INSERT IGNORE INTO commission_statuses (status_id, status_name) VALUES
(1, 'pending'),
(2, 'accepted'),
(3, 'rejected'),
(4, 'in_progress'),
(5, 'completed'),
(6, 'cancelled');

INSERT IGNORE INTO payment_statuses (status_id, status_name) VALUES
(1, 'pending'),
(2, 'paid'),
(3, 'failed'),
(4, 'refunded');

INSERT IGNORE INTO subscriptions (id, name, price, commission_percentage, duration_days) VALUES
(1, 'Basic', 100.00, 20, 30),
(2, 'Pro', 500.00, 10, 30);

INSERT IGNORE INTO categories (name) VALUES
('Painting'),
('Digital Art'),
('Illustration'),
('Sculpture'),
('Photography'),
('Mixed Media');

-- Optional: demo admin (password: admin123)
-- INSERT INTO users (name, email, password_hash, role_id) VALUES
-- ('Admin', 'admin@juanart.dev', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);
