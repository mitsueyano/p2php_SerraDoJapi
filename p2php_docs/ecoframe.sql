DROP DATABASE ecoframe;
CREATE DATABASE ecoframe;
USE ecoframe;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    hash_senha VARCHAR(255) NOT NULL,
    nivel_acesso ENUM('comum', 'especialista') NOT NULL,
    link_lattes VARCHAR(255)
);

INSERT INTO usuarios (cpf, nome, email, hash_senha, nivel_acesso, link_lattes)
VALUES 
('123.456.789-00', 'Ana Silva', 'ana@exemplo.com', 'hash123', 'comum', NULL),
('987.654.321-00', 'Dr. João Souza', 'joao@exemplo.com', 'hash456', 'especialista', 'http://lattes.cnpq.br/joaosouza');

-- Tabela de Classificação Taxonômica
CREATE TABLE classificacao_taxonomica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classe VARCHAR(100),
    familia VARCHAR(100),
    especie VARCHAR(100) NOT NULL
);

INSERT INTO classificacao_taxonomica (classe, familia, especie)
VALUES 
('Aves', 'Turdidae', 'Turdus rufiventris'),
('Mamíferos', 'Cervidae', 'Mazama americana');

-- Tabela de Geolocalizações
CREATE TABLE geolocalizacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    latitude DECIMAL(9,6) NOT NULL,
    longitude DECIMAL(9,6) NOT NULL,
    endereco_descritivo VARCHAR(255)
);

INSERT INTO geolocalizacoes (latitude, longitude, endereco_descritivo)
VALUES 
(-23.185700, -46.897000, 'Parque da Cidade - Jundiaí'),
(-23.210123, -46.875432, 'Rodovia Anhanguera - KM 58');

-- Tabela de Registros Biológicos
CREATE TABLE registros_biologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_popular VARCHAR(100),
    especie_id INT,
    usuario_id INT,
    data_observacao DATE,
    hora_observacao TIME,
    descricao TEXT,
    geolocalizacao_id INT,
    FOREIGN KEY (especie_id) REFERENCES classificacao_taxonomica(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (geolocalizacao_id) REFERENCES geolocalizacoes(id)
);

INSERT INTO registros_biologicos (nome_popular, especie_id, usuario_id, data_observacao, hora_observacao, descricao, geolocalizacao_id)
VALUES 
('Sabiá-laranjeira', 1, 1, '2025-05-07', '08:30:00', 'Ave avistada cantando no topo de uma árvore.', 1),
('Veado-mateiro', 2, 2, '2025-05-06', '19:15:00', 'Avistado cruzando uma trilha durante a noite.', 2);

-- Tabela de Sugestões de Alteração
CREATE TABLE sugestoes_alteracao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT,
    usuario_id INT,
    data_sugestao DATE,
    justificativa TEXT,
    FOREIGN KEY (registro_id) REFERENCES registros_biologicos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

INSERT INTO sugestoes_alteracao (registro_id, usuario_id, data_sugestao, justificativa)
VALUES 
(1, 2, '2025-05-08', 'Sugiro revisar a identificação da ave, pode ser outra espécie.');

-- Tabela de Comentários
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT,
    usuario_id INT,
    texto TEXT NOT NULL,
    data_comentario DATE,
    hora_comentario TIME,
    likes INT DEFAULT 0,
    FOREIGN KEY (registro_id) REFERENCES registros_biologicos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

INSERT INTO comentarios (registro_id, usuario_id, texto, data_comentario, hora_comentario, likes)
VALUES 
(1, 2, 'Lindo registro, muito comum nessa época do ano.', '2025-05-08', '10:00:00', 3);

-- Tabela de Registros de Atropelamentos (área restrita)
CREATE TABLE registros_atropelamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    especie_id INT,
    usuario_id INT,
    data_registro DATE,
    hora_registro TIME,
    descricao TEXT,
    geolocalizacao_id INT,
    validado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (especie_id) REFERENCES classificacao_taxonomica(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (geolocalizacao_id) REFERENCES geolocalizacoes(id)
);

INSERT INTO registros_atropelamentos (especie_id, usuario_id, data_registro, hora_registro, descricao, geolocalizacao_id, validado)
VALUES 
(2, 1, '2025-05-07', '22:00:00', 'Animal encontrado morto à beira da estrada.', 2, FALSE);

-- Tabela de Sinalizações de Erro
CREATE TABLE sinalizacoes_erro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT,
    usuario_id INT,
    descricao TEXT NOT NULL,
    data_sinalizacao DATE,
    FOREIGN KEY (registro_id) REFERENCES registros_biologicos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

INSERT INTO sinalizacoes_erro (registro_id, usuario_id, descricao, data_sinalizacao)
VALUES 
(1, 1, 'Informações imprecisas sobre a localização.', '2025-05-08');

-- Selecionar todos os usuários
SELECT * FROM usuarios;

-- Selecionar toda a classificação taxonômica
SELECT * FROM classificacao_taxonomica;

-- Selecionar todas as geolocalizações
SELECT * FROM geolocalizacoes;

-- Selecionar todos os registros biológicos
SELECT * FROM registros_biologicos;

-- Selecionar todas as sugestões de alteração
SELECT * FROM sugestoes_alteracao;

-- Selecionar todos os comentários
SELECT * FROM comentarios;

-- Selecionar todos os registros de atropelamentos
SELECT * FROM registros_atropelamentos;

-- Selecionar todas as sinalizações de erro
SELECT * FROM sinalizacoes_erro;
