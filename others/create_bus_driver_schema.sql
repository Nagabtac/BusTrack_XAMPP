-- Create buses table
CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_number VARCHAR(20) NOT NULL UNIQUE,
    bus_name VARCHAR(100) NOT NULL,
    route VARCHAR(100),
    capacity INT DEFAULT 50,
    status ENUM('running', 'stopped', 'maintenance') DEFAULT 'stopped',
    driver_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create drivers table
CREATE TABLE drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    license_number VARCHAR(50) NOT NULL UNIQUE,
    phone VARCHAR(20),
    email VARCHAR(100),
    status ENUM('active', 'inactive', 'on_leave') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add foreign key constraint
ALTER TABLE buses ADD CONSTRAINT fk_bus_driver 
FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL;

-- Insert sample data
INSERT INTO drivers (name, license_number, phone, email, status) VALUES
('John Smith', 'DL001234', '+1234567890', 'john.smith@email.com', 'active'),
('Maria Garcia', 'DL005678', '+1234567891', 'maria.garcia@email.com', 'active'),
('David Johnson', 'DL009012', '+1234567892', 'david.johnson@email.com', 'active'),
('Sarah Wilson', 'DL003456', '+1234567893', 'sarah.wilson@email.com', 'on_leave'),
('Michael Brown', 'DL007890', '+1234567894', 'michael.brown@email.com', 'active');

INSERT INTO buses (bus_number, bus_name, route, capacity, status, driver_id) VALUES
('BUS-001', 'City Express', 'Route A - Downtown Loop', 45, 'running', 1),
('BUS-002', 'Metro Bus', 'Route B - University District', 50, 'stopped', 2),
('BUS-003', 'Rapid Line', 'Route C - Airport Express', 40, 'running', 3),
('BUS-004', 'Local Transit', 'Route D - Residential Area', 35, 'maintenance', NULL),
('BUS-005', 'Express Service', 'Route E - Business District', 55, 'running', 5);