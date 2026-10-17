
CREATE DATABASE IF NOT EXISTS mobile_shop
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE mobile_shop;

CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(80) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    image VARCHAR(255) DEFAULT NULL,
    stock_quantity INT UNSIGNED NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE repair_services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(150) NOT NULL,
    description TEXT,
    starting_price DECIMAL(10, 2) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE repair_enquiries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    device_model VARCHAR(120) NOT NULL,
    problem_description TEXT NOT NULL,
    service_id INT UNSIGNED DEFAULT NULL,
    status ENUM(
        'New',
        'Contacted',
        'In Progress',
        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_enquiry_service
        FOREIGN KEY (service_id)
        REFERENCES repair_services(id)
        ON DELETE SET NULL
);

INSERT INTO repair_services
    (service_name, description, starting_price)
VALUES
    ('Screen Repair', 'Screen inspection and repair service.', NULL),
    ('Battery Replacement', 'Battery health check and replacement.', NULL),
    ('Charging Issue', 'Charging port and charging issue diagnosis.', NULL),
    ('Software Issue', 'Software troubleshooting and support.', NULL),
    ('General Repair', 'Diagnosis for other mobile issues.', NULL);

INSERT INTO products
    (name, category, description, price, stock_quantity)
VALUES
    ('Fast Charger', 'Chargers',
     'Compatible fast charging adapter.', 499.00, 10),
    ('USB Charging Cable', 'Cables',
     'Everyday charging and data cable.', 199.00, 20),
    ('Protective Phone Case', 'Covers',
     'Protective case; model compatibility to be confirmed.', 249.00, 15),
    ('Tempered Glass', 'Screen Protection',
     'Screen protector for compatible devices.', 149.00, 25);
