-- CREATE TABLE IF NOT EXISTS users (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     email VARCHAR(255) UNIQUE NOT NULL,
--     password VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- CREATE TABLE IF NOT EXISTS medicines (
-- --     id INT AUTO_INCREMENT PRIMARY KEY,
-- --     user_id INT NOT NULL,
-- --     name VARCHAR(255) NOT NULL,
-- --     start_date DATE NOT NULL,
-- --     end_date DATE NOT NULL,
-- --     time TIME NOT NULL,
-- --     dosage VARCHAR(50) NOT NULL,
-- --     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
-- --     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
-- --     ALTER TABLE medicines ADD COLUMN recipient VARCHAR(50) NOT NULL DEFAULT 'self';
    
-- -- );

-- CREATE TABLE IF NOT EXISTS medicines (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     name VARCHAR(255) NOT NULL,
--     recipient VARCHAR(50) NOT NULL DEFAULT 'self',
--     start_date DATE NOT NULL,
--     end_date DATE NOT NULL,
--     time TIME NOT NULL,
--     dosage VARCHAR(50) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
-- );
CREATE DATABASE med_reminder;
USE med_reminder;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE medicines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    time TIME NOT NULL,
    dosage VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Remove recipient column if it exists (safe execution)
ALTER TABLE medicines DROP COLUMN IF EXISTS recipient;