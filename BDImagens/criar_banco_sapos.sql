CREATE DATABASE IF NOT EXISTS sapos;
USE sapos;
CREATE TABLE IF NOT EXISTS post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    path_imagem VARCHAR(255)
);