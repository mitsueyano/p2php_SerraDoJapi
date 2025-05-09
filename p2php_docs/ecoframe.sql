DROP DATABASE IF EXISTS ecoframe;
CREATE DATABASE ecoframe;
USE ecoframe;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel_acesso ENUM('comum', 'especialista') NOT NULL,
    link_lattes VARCHAR(255)
);

select * from usuarios;