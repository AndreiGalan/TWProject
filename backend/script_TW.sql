
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

-- table for equations
CREATE TABLE equations(
    id INT IDENTITY (1,1) PRIMARY KEY,
    equation_text VARCHAR(1024) NOT NULL,
);
-- equation_text : "x + y = 10; x + 10 = 11;"

--  tables for questions and answers
DROP TABLE IF EXISTS questions;
CREATE TABLE questions(
    id INT IDENTITY (1,1) PRIMARY KEY,
    question_text VARCHAR(255) NOT NULL,
    difficulty INT NOT NULL,-- 0 = easy , 1 = medium, 2 = hard
    points INT NOT NULL,
    id_picture INT,
    id_equation INT,
    FOREIGN KEY (id_picture) REFERENCES pictures(id) ON DELETE CASCADE,
    FOREIGN KEY (id_equation) REFERENCES equations(id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS answers;
CREATE TABLE answers(
    id INT IDENTITY (1,1) PRIMARY KEY,
    question_id INT NOT NULL,
    answer_text VARCHAR(255) NOT NULL,
    is_correct BIT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);
