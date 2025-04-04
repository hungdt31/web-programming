-- Create database
CREATE DATABASE IF NOT EXISTS product_management;
USE product_management;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255)
);

-- Insert sample data
INSERT INTO products (name, description, price, image) VALUES
('Sample Product 1', 'This is a sample product description', 19.99, 'https://via.placeholder.com/150'),
('Sample Product 2', 'Another sample product with details', 29.99, 'https://via.placeholder.com/150'),
('Sample Product 3', 'Yet another product description', 39.99, 'https://via.placeholder.com/150'); 