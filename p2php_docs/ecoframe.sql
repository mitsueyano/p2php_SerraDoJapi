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
INSERT INTO usuarios (cpf, nome, sobrenome, email, senha, nivel_acesso, link_lattes) VALUES
('123.456.789-00', 'Regina', 'Silva', 'ana.silva@example.com', '$2y$10$KckpFTTAbDgDtCGKEtnlUODlkKFPziZ52/zxDumCGGM6/0uBN50pu', 'comum', NULL),
('987.654.321-11', 'Bruno', 'Souza', 'bruno.souza@example.com', '$2y$10$20eejaVT.RDTEmC5q1NRm.Vc9c.z4w8lp0OqG84IRvknhHWN/CVEO', 'especialista', 'http://lattes.cnpq.br/1234567890123456'),
('456.789.123-22', 'Carla', 'Pereira', 'carla.pereira@example.com', '$2y$10$ncJQk.ZKyivWQ.En1KdNpOZo3ZTMKzwXajLfw26m4uCELhbA/zSH2', 'comum', NULL);


-- Tabela de classificação taxonômica
CREATE TABLE classificacao_taxonomica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classe VARCHAR(100) NOT NULL,
    familia VARCHAR(100) NOT NULL,
    especie VARCHAR(100) NOT NULL
);
INSERT INTO classificacao_taxonomica (classe, familia, especie) VALUES
('Aves', 'Psittacidae', 'Amazona aestiva'),
('Mammalia', 'Felidae', 'Leopardus pardalis'),
('Amphibia', 'Hylidae', 'Scinax fuscovarius');

-- Tabela de geolocalização
CREATE TABLE geolocalizacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    endereco VARCHAR(255)
);
INSERT INTO geolocalizacao (latitude, longitude, endereco) VALUES
(-23.1857, -46.8978, 'Serra do Japi, Jundiaí - SP'),
(-22.9519, -43.2105, 'Floresta da Tijuca, RJ'),
(-3.1072, -60.0261, 'Reserva Adolpho Ducke, Manaus - AM');

-- Tabela de registros biológicos
CREATE TABLE registros_biologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nome_popular VARCHAR(50) NOT NULL,
    id_taxonomia INT NOT NULL,
    data_observacao DATE NOT NULL,
    horario_observacao TIME NOT NULL,
    descricao TEXT,
    id_geolocalizacao INT NOT NULL,
    url_imagem VARCHAR(255),
    qtde_likes INT NOT NULL,
    qtde_coment INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_taxonomia) REFERENCES classificacao_taxonomica(id),
    FOREIGN KEY (id_geolocalizacao) REFERENCES geolocalizacao(id)
);

INSERT INTO registros_biologicos (
    id_usuario, nome_popular, id_taxonomia, data_observacao, horario_observacao, descricao, id_geolocalizacao, url_imagem, qtde_likes, qtde_coment) VALUES
('1', 'Papagaio-verdadeiro', 1, '2025-04-12', '08:30:00', 'Avistado em árvore frutífera.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0),
('2', 'Jaguatirica', 2, '2025-03-28', '22:15:00', 'Atravessando trilha noturna.', 2, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915960/ytbx8r46k4r9fv3qnirn.png', 0, 0),
('3', 'Perereca-de-banheiro', 3, '2025-02-10', '19:05:00', 'Pousada em janela durante chuva.', 3, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746916025/tviwtun4vxqcivfeiysy.png', 0, 0);


CREATE TABLE curtidas_usuarios(
	id_usuario INT NOT NULL,
    id_registro INT NOT NULL,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_registro) REFERENCES registros_biologicos(id)
);

select * from usuarios;
select * from classificacao_taxonomica;
select * from geolocalizacao;
select * from registros_biologicos;
select * from curtidas_usuarios;