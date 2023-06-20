
-- change column name from name to username
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS pictures;

--create tables
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    gender VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(1024) NOT NULL,
    points INT DEFAULT 0,
    ranking INT DEFAULT 2147483647,
    created_at TIMESTAMP DEFAULT GETDATE()
);

CREATE TABLE pictures (
  id INT IDENTITY (1,1) PRIMARY KEY,
  text varchar(255) NOT NULL,
  download_link varchar(1024) UNIQUE NOT NULL
);

-- add unique constraint to username
ALTER TABLE users
ADD CONSTRAINT unique_username UNIQUE (username);

ALTER TABLE users
ADD CONSTRAINT unique_email UNIQUE (email);

-- add column 'created_at' to users table
ALTER TABLE users
ADD created_at DATETIME DEFAULT GETDATE();