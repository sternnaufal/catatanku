CREATE DATABASE digital_note CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE digital_note;

-- Tabel user
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel matkul
CREATE TABLE matkul (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

-- Tabel pertemuan
CREATE TABLE pertemuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matkul_id INT NOT NULL,
    nomor_pertemuan INT NOT NULL,
    FOREIGN KEY (matkul_id) REFERENCES matkul(id) ON DELETE CASCADE
);

-- Tabel catatan
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pertemuan_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pertemuan_id) REFERENCES pertemuan(id) ON DELETE CASCADE
);
