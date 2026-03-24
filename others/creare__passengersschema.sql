CREATE TABLE passengers (
    passenger_id VARCHAR(10) PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    address VARCHAR(255),
    pickup_location VARCHAR(150),
    dropoff_location VARCHAR(150),
    travel_date DATE NOT NULL,
    payment_method VARCHAR(50),
    fare_amount DECIMAL(10,2)
);