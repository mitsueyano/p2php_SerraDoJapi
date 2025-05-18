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
    ordem VARCHAR(100) NOT NULL,
    familia VARCHAR(100) NOT NULL,
    especie VARCHAR(100) NOT NULL
);
INSERT INTO classificacao_taxonomica (classe, ordem, familia, especie) VALUES
('Aves', 'Psittaciformes', 'Psittacidae', 'Amazona aestiva'),             
('Mammalia', 'Carnivora', 'Felidae', 'Leopardus pardalis'),          
('Amphibia', 'Anura', 'Hylidae', 'Scinax fuscovarius'),          
('Mammalia', 'Pilosa', 'Myrmecophagidae', 'Myrmecophaga tridactyla'),  
('Mammalia', 'Rodentia', 'Caviidae', 'Hydrochoerus hydrochaeris'),       
('Mammalia', 'Cingulata', 'Dasypodidae', 'Dasypus novemcinctus'),         
('Aves', 'Strigiformes', 'Strigidae', 'Athene cunicularia'),                 
('Mammalia', 'Pilosa', 'Bradypodidae', 'Bradypus variegatus'),         
('Reptilia', 'Squamata', 'Boidae', 'Eunectes murinus'),                  
('Reptilia', 'Crocodylia', 'Alligatoridae', 'Caiman latirostris'),         
('Aves', 'Accipitriformes', 'Accipitridae', 'Rupornis magnirostris'),           
('Mammalia', 'Primates', 'Callitrichidae', 'Leontopithecus rosalia'),    
('Mammalia', 'Pilosa', 'Myrmecophagidae', 'Tamandua tetradactyla');
 

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
(2, 'Jaguatirica', 2, '2025-05-16', '22:15:00', 'Atravessando trilha noturna.', 2, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915960/ytbx8r46k4r9fv3qnirn.png', 0, 0, '2025-05-16', '11:30:15'),
(3, 'Perereca-de-banheiro', 3, '2025-05-15', '19:05:00', 'Pousada em janela durante chuva.', 3, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746916025/tviwtun4vxqcivfeiysy.png', 0, 0, '2025-05-15', '09:32:50'),
(1, 'Tamanduá-bandeira', 4, '2025-01-15', '10:20:00', 'Avistado próximo a campo aberto.', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1747399815/imagem_2025-05-16_094936847_lfogo5.png', 0, 0, '2025-01-15', '09:43:26'),
(2, 'Capivara', 5, '2025-04-03', '17:45:00', 'Grupo se alimentando na margem do rio.', 1, '', 0, 0, '2025-04-03', '11:00:00'),
(3, 'Tatu-galinha', 6, '2025-03-14', '06:50:00', 'Observado escavando o solo.', 1, '', 0, 0, '2025-03-14', '12:00:00'),
(1, 'Coruja-buraqueira', 7, '2025-05-01', '20:10:00', 'Empoleirada em cerca de madeira.', 1, '', 0, 0, '2025-05-01', '06:24:00'),
(2, 'Bicho-preguiça', 8, '2025-01-30', '13:30:00', 'Em descanso em galho alto.', 1, '', 0, 0, '2025-01-30', '12:00:00'),
(3, 'Sucuri-verde', 9, '2025-02-22', '15:00:00', 'Deslocando-se próxima a curso d’água.', 1, '', 0, 0, '2025-02-22', '12:00:00'),
(1, 'Jacaré-do-papo-amarelo', 10, '2025-04-10', '12:40:00', 'Tomando sol em área alagada.', 1, '', 0, 0, '2025-04-10', '12:00:00'),
(2, 'Gavião-carijó', 11, '2025-03-19', '09:10:00', 'Sobrevoando zona de mata.', 1, '', 0, 0, '2025-03-19', '12:00:00'),
(3, 'Mico-leão-dourado', 12, '2025-01-22', '14:15:00', 'Saltando entre galhos.', 1, '', 0, 0, '2025-01-22', '12:00:00'),
(1, 'Tamanduá-mirim', 13, '2025-02-18', '07:55:00', 'Movimentando-se entre árvores baixas.', 1, '', 0, 0, '2025-02-18', '12:00:00'),
(2, 'Papagaio-verdadeiro', 1, '2025-05-10', '09:45:00', 'Cantando em galho alto de árvore próxima a trilha.', 1, '', 0, 0, '2025-05-10', '12:00:00'),
(3, 'Papagaio-verdadeiro', 1, '2025-05-11', '07:20:00', 'Sobrevoando área de mata fechada.', 2, '', 0, 0, '2025-05-11', '12:00:00'),
(1, 'Papagaio-verdadeiro', 1, '2025-05-12', '10:00:00', 'Par de papagaios interagindo em ninho.', 1, '', 0, 0, '2025-05-12', '12:00:00'),
(2, 'Papagaio-verdadeiro', 1, '2025-05-13', '16:35:00', 'Avistado comendo frutos em palmeira.', 3, '', 0, 0, '2025-05-13', '12:00:00'),
(3, 'Papagaio-verdadeiro', 1, '2025-05-14', '11:10:00', 'Vocalização intensa em grupo de 3 indivíduos.', 2, '', 0, 0, '2025-05-14', '12:00:00'),
(1, 'Papagaio-verdadeiro', 1, '2025-05-17', '08:05:00', 'Empoleirado em poste próximo à mata.', 1, '', 0, 0, '2025-05-17', '12:00:00');

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
