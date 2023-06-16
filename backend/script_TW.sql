
DROP TABLE IF EXISTS cats;
CREATE TABLE cats (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name VARCHAR(255),
    breed VARCHAR(255)
);


-- change column name from name to username
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    gender VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(1024) NOT NULL,
    points INT DEFAULT 0,
    ranking INT DEFAULT 2147483647,
);

-- add unique constraint to username
ALTER TABLE users
ADD CONSTRAINT unique_username UNIQUE (username);