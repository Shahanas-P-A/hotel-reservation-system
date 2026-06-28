CREATE DATABASE IF NOT EXISTS hotel_reservation;
USE hotel_reservation;

-- Customers Table
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rooms Table
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_type ENUM('Standard','Deluxe','Suite') NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('Available','Booked') DEFAULT 'Available'
);

-- Default Rooms
INSERT INTO rooms (room_type, price, status) VALUES
('Standard',100,'Available'),
('Standard',100,'Available'),
('Deluxe',150,'Available'),
('Deluxe',150,'Available'),
('Suite',300,'Available');

-- Reservations Table
CREATE TABLE reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    room_id INT,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE
);

-- Available Rooms View
CREATE VIEW available_rooms AS
SELECT room_id, room_type, price
FROM rooms
WHERE status='Available';

-- Trigger to Prevent Double Booking
DELIMITER $$

CREATE TRIGGER prevent_double_booking
BEFORE INSERT ON reservations
FOR EACH ROW
BEGIN
    DECLARE overlap_count INT;

    SELECT COUNT(*)
    INTO overlap_count
    FROM reservations
    WHERE room_id = NEW.room_id
      AND NEW.check_in < check_out
      AND NEW.check_out > check_in;

    IF overlap_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT='Room already booked for selected dates.';
    END IF;

    UPDATE rooms
    SET status='Booked'
    WHERE room_id=NEW.room_id;
END $$

DELIMITER ;