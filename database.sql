-- Database for PHP Hands-on Exercises (Outputs #3, #4 and #5)
CREATE DATABASE IF NOT EXISTS php_exercises CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_exercises;

-- Outputs #3 and #4: registered persons
CREATE TABLE IF NOT EXISTS persons (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name  VARCHAR(50)  NOT NULL,
  middle_name VARCHAR(50)  DEFAULT NULL,
  last_name   VARCHAR(50)  NOT NULL,
  age         TINYINT UNSIGNED NOT NULL,
  gender      ENUM('Male','Female','Other') NOT NULL,
  email       VARCHAR(100) NOT NULL,
  address     VARCHAR(255) NOT NULL,
  contact     VARCHAR(15)  NOT NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Output #5: faculty (CRUD)
CREATE TABLE IF NOT EXISTS faculty (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name  VARCHAR(50)  NOT NULL,
  middle_name VARCHAR(50)  DEFAULT NULL,
  last_name   VARCHAR(50)  NOT NULL,
  age         TINYINT UNSIGNED NOT NULL,
  gender      ENUM('Male','Female','Other') NOT NULL,
  address     VARCHAR(255) NOT NULL,
  position    VARCHAR(100) NOT NULL,
  salary      DECIMAL(10,2) NOT NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Sample data
INSERT INTO persons (first_name, middle_name, last_name, age, gender, email, address, contact) VALUES
('Juan','Roxas','Dela Cruz',21,'Male','juan@example.com','Nasipit, Agusan del Norte','09123456789'),
('Leni','Gerona','Robredo',35,'Female','leni@example.com','Butuan City','09987654321');

INSERT INTO faculty (first_name, middle_name, last_name, age, gender, address, position, salary) VALUES
('Marlon','Marsado','Timogan',30,'Male','Nasipit, Agusan del Norte','Instructor',28500.00),
('Maria','Santos','Reyes',42,'Female','Butuan City','Associate Professor',42000.00);
