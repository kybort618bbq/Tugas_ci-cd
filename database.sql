CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  failed_attempts INT DEFAULT 0,
  lock_time INT DEFAULT 0
);
