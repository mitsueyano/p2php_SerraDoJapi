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

-- Tabela de geolocalização
CREATE TABLE geolocalizacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    nome_lugar VARCHAR(255)
);
INSERT INTO geolocalizacao (latitude, longitude, nome_lugar) VALUES
(-23.1857, -46.8978, 'Serra do Japi, Jundiaí - SP'),         
(-22.9519, -43.2105, 'Floresta da Tijuca, RJ'),              
(-3.1072, -60.0261, 'Reserva Adolpho Ducke, Manaus - AM');  

-- Tabela de classificação taxonômica
CREATE TABLE classificacao_taxonomica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classe VARCHAR(100) NOT NULL,
    ordem VARCHAR(100) NOT NULL,
    familia VARCHAR(100) NOT NULL,
    especie VARCHAR(100) NOT NULL,
    tipo VARCHAR (20) NOT NULL
);
INSERT INTO classificacao_taxonomica (classe, ordem, familia, especie, tipo) VALUES
('Aves', 'Psittaciformes', 'Psittacidae', 'Amazona aestiva', 'animais'),             
('Mammalia', 'Carnivora', 'Felidae', 'Leopardus pardalis', 'animais'),          
('Amphibia', 'Anura', 'Hylidae', 'Scinax fuscovarius', 'animais'),          
('Mammalia', 'Pilosa', 'Myrmecophagidae', 'Myrmecophaga tridactyla', 'animais'),

('Insecta', 'Lepidoptera', 'Nymphalidae', 'Morpho menelaus', 'insetos'),
('Insecta', 'Coleoptera', 'Coccinellidae', 'Harmonia axyridis', 'insetos'),
('Insecta', 'Diptera', 'Culicidae', 'Aedes aegypti', 'insetos'),
('Insecta', 'Hymenoptera', 'Apidae', 'Apis mellifera', 'insetos'),

('Magnoliopsida', 'Rosales', 'Moraceae', 'Ficus benjamina', 'plantas'),
('Liliopsida', 'Poales', 'Poaceae', 'Saccharum officinarum', 'plantas'),
('Magnoliopsida', 'Fabales', 'Fabaceae', 'Mimosa pudica', 'plantas'),
('Magnoliopsida', 'Asterales', 'Asteraceae', 'Helianthus annuus', 'plantas');

 

 

-- Tabela de registros biológicos com data_publicacao
CREATE TABLE registros_biologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nome_popular VARCHAR(50) NOT NULL,
    id_taxonomia INT NOT NULL,
    data_observacao DATE NOT NULL,
    hora_observacao TIME NOT NULL,
    descricao TEXT,
    id_geolocalizacao INT NOT NULL,
    url_imagem VARCHAR(255),
    qtde_likes INT NOT NULL,
    qtde_coment INT NOT NULL,
    data_publicacao DATE NOT NULL,
    hora_publicacao TIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_taxonomia) REFERENCES classificacao_taxonomica(id),
    FOREIGN KEY (id_geolocalizacao) REFERENCES geolocalizacao(id)
);

-- Inserção de registros com data_publicacao (data_observacao + 12:00:00)
INSERT INTO registros_biologicos (
    id_usuario, nome_popular, id_taxonomia, data_observacao, hora_observacao, descricao, id_geolocalizacao, url_imagem, qtde_likes, qtde_coment, data_publicacao, hora_publicacao
) VALUES
(1, 'Papagaio-verdadeiro', 1, '2025-05-16', '08:30:00', 'Avistado em árvore frutífera.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-16', '12:00:00'),
(1, 'Papagaio-verdadeiro', 1, '2025-05-10', '07:30:00', 'Visto empoleirado em árvore alta.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-10', '12:00:00'),
(2, 'Papagaio-verdadeiro', 1, '2025-05-11', '06:45:00', 'Grupo vocalizando próximo a rio.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-11', '12:00:00'),
(3, 'Papagaio-verdadeiro', 1, '2025-05-12', '08:10:00', 'Apenas um indivíduo em voo.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-12', '12:00:00'),
(1, 'Papagaio-verdadeiro', 1, '2025-05-13', '09:20:00', 'Dois indivíduos alimentando-se de frutas.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-13', '12:00:00'),
(2, 'Papagaio-verdadeiro', 1, '2025-05-14', '07:50:00', 'Observado próximo a área urbana.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-14', '12:00:00'),
(3, 'Papagaio-verdadeiro', 1, '2025-05-15', '10:00:00', 'Papagaio solitário vocalizando.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-15', '12:00:00'),
(1, 'Papagaio-verdadeiro', 1, '2025-05-16', '06:40:00', 'Casal de papagaios em árvore frutífera.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-16', '12:00:00'),
(2, 'Papagaio-verdadeiro', 1, '2025-05-17', '08:30:00', 'Trio observado voando em círculo.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-17', '12:00:00'),
(2, 'Jaguatirica', 2, '2025-05-16', '22:15:00', 'Atravessando trilha noturna.', 2, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915960/ytbx8r46k4r9fv3qnirn.png', 0, 0, '2025-05-16', '11:30:15'),
(3, 'Perereca-de-banheiro', 3, '2025-05-15', '19:05:00', 'Pousada em janela durante chuva.', 3, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746916025/tviwtun4vxqcivfeiysy.png', 0, 0, '2025-05-15', '09:32:50'),
(1, 'Tamanduá-bandeira', 4, '2025-01-15', '10:20:00', 'Avistado próximo a campo aberto.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1747399815/imagem_2025-05-16_094936847_lfogo5.png', 0, 0, '2025-01-15', '09:43:26'),
(1, 'Arara-azul', 1, '2025-05-17', '07:45:00', 'Arara observada no topo da árvore.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-17', '12:00:00'),
(2, 'Onça-pintada', 2, '2025-05-17', '19:30:00', 'Onça caminhando pela trilha.', 2, '', 0, 0, '2025-05-17', '12:00:00'),
(3, 'Sapo-cururu', 3, '2025-05-16', '21:00:00', 'Sapo perto do lago.', 3, '', 0, 0, '2025-05-16', '12:00:00'),
(1, 'Tatu-canastra', 4, '2025-05-15', '16:20:00', 'Tatu cruzando a estrada.', 1, '', 0, 0, '2025-05-15', '12:00:00'),
(2, 'Gavião-carijó', 1, '2025-05-14', '10:00:00', 'Gavião voando no céu.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-14', '12:00:00'),
(3, 'Tamanduá-mirim', 4, '2025-05-13', '13:45:00', 'Pequeno tamanduá visto em mata.', 1, '', 0, 0, '2025-05-13', '12:00:00'),
(1, 'Borboleta Morphos', 5, '2025-05-13', '10:20:00', 'Borboleta azul brilhante vista em campo aberto.', 1, '', 0, 0, '2025-05-13', '12:00:00'),
(2, 'Joaninha Asiática', 6, '2025-05-14', '14:05:00', 'Joaninha em folhas de arbusto.', 2, '', 0, 0, '2025-05-14', '12:00:00'),
(3, 'Mosquito Aedes', 7, '2025-05-15', '18:40:00', 'Mosquito encontrado próximo à água parada.', 3, '', 0, 0, '2025-05-15', '12:00:00'),
(1, 'Borboleta Azul', 8, '2025-05-10', '09:00:00', 'Borboleta azul vista perto do riacho.', 1, '', 0, 0, '2025-05-10', '12:00:00'),
(1, 'Borboleta Monarca', 5, '2025-05-17', '09:00:00', 'Borboleta laranja e preta.', 1, '', 0, 0, '2025-05-17', '12:00:00'),
(2, 'Joaninha Comum', 6, '2025-05-16', '15:30:00', 'Joaninha em folha verde.', 2, '', 0, 0, '2025-05-16', '12:00:00'),
(3, 'Mosca-doméstica', 7, '2025-05-15', '11:15:00', 'Mosca perto de janela.', 3, '', 0, 0, '2025-05-15', '12:00:00'),
(1, 'Abelha Européia', 8, '2025-05-14', '14:50:00', 'Abelha coletando pólen.', 1, '', 0, 0, '2025-05-14', '12:00:00'),
(2, 'Vaga-lume', 5, '2025-05-13', '20:30:00', 'Inseto com luzes no corpo.', 2, '', 0, 0, '2025-05-13', '12:00:00'),
(3, 'Formiga Carpinteira', 6, '2025-05-12', '08:45:00', 'Formiga carregando folha.', 3, '', 0, 0, '2025-05-12', '12:00:00'),
(2, 'Figueira-benjamim', 9, '2025-05-10', '09:15:00', 'Árvore de interior comum.', 1, '', 0, 0, '2025-05-10', '12:00:00'),
(3, 'Cana-de-açúcar', 10, '2025-05-11', '07:30:00', 'Plantação de cana próxima à trilha.', 2, '', 0, 0, '2025-05-11', '12:00:00'),
(1, 'Sensitiva', 11, '2025-05-12', '11:45:00', 'Planta que fecha as folhas ao toque.', 1, '', 0, 0, '2025-05-12', '12:00:00'),
(2, 'Figueira-da-India', 12, '2025-05-13', '08:50:00', 'Árvore comum em áreas urbanas.', 2, '', 0, 0, '2025-05-13', '12:00:00'),
(1, 'Samambaia', 9, '2025-05-17', '08:00:00', 'Planta encontrada em sombra.', 1, '', 0, 0, '2025-05-17', '12:00:00'),
(2, 'Bambu', 10, '2025-05-16', '09:30:00', 'Bambus próximos ao riacho.', 2, '', 0, 0, '2025-05-16', '12:00:00'),
(3, 'Maracujá', 11, '2025-05-15', '12:20:00', 'Planta com frutos amarelos.', 3, '', 0, 0, '2025-05-15', '12:00:00'),
(1, 'Girassol', 12, '2025-05-14', '10:10:00', 'Flores girando para o sol.', 1, '', 0, 0, '2025-05-14', '12:00:00'),
(2, 'Ipê-amarelo', 9, '2025-05-13', '14:30:00', 'Árvore com flores amarelas.', 2, '', 0, 0, '2025-05-13', '12:00:00'),
(3, 'Capim-limão', 10, '2025-05-12', '16:45:00', 'Planta aromática para chá.', 3, '', 0, 0, '2025-05-12', '12:00:00');


-- Tabela de curtidas
CREATE TABLE curtidas_usuarios(
	id_usuario INT NOT NULL,
    id_registro INT NOT NULL,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_registro) REFERENCES registros_biologicos(id)
);

-- Visualizações
SELECT * FROM usuarios;
SELECT * FROM classificacao_taxonomica;
SELECT * FROM geolocalizacao;
SELECT * FROM registros_biologicos;
SELECT * FROM curtidas_usuarios;
