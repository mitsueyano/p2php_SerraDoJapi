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
    link_lattes VARCHAR(255),
    cargo VARCHAR(50)
);
INSERT INTO usuarios (cpf, nome, sobrenome, email, senha, nivel_acesso, link_lattes, cargo) VALUES
('123.456.789-00', 'Regina', 'Silva', 'ana.silva@example.com', '$2y$10$KckpFTTAbDgDtCGKEtnlUODlkKFPziZ52/zxDumCGGM6/0uBN50pu', 'comum', NULL, NULL),
('987.654.321-11', 'Bruno', 'Souza', 'bruno.souza@example.com', '$2y$10$20eejaVT.RDTEmC5q1NRm.Vc9c.z4w8lp0OqG84IRvknhHWN/CVEO', 'especialista', 'http://lattes.cnpq.br/1234567890123456', 'Aracnólogo'),
('456.789.123-22', 'Carla', 'Pereira', 'carla.pereira@example.com', '$2y$10$ncJQk.ZKyivWQ.En1KdNpOZo3ZTMKzwXajLfw26m4uCELhbA/zSH2', 'comum', NULL, NULL);

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
(-3.1072, -60.0261, 'Reserva Adolpho Ducke, Manaus - AM'),
(-27.0984, -48.6100, 'Rodovia BR-101, SC'),
(-25.6953, -54.4367, 'Parque Nacional do Iguaçu, PR'),
(-9.3891, -40.5027, 'Zona rural de Petrolina, PE');

CREATE TABLE categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(50) NOT NULL
);
INSERT INTO categoria (nome) VALUES
("Fauna"),
("Flora"),
("Espécie invasora");

CREATE TABLE classificacao_taxonomica (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome_popular VARCHAR(50),
    classe VARCHAR(100),
    ordem VARCHAR(100),
    familia VARCHAR(100),
    especie VARCHAR(100),
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id)
);
-- Exemplo de inserção
INSERT INTO classificacao_taxonomica 
(nome_popular, classe, ordem, familia, especie, id_categoria) VALUES
('Papagaio-verdadeiro', 'Aves', 'Psittaciformes', 'Psittacidae', 'Amazona aestiva', 1),
('Onça-pintada', 'Mammalia', 'Carnivora', 'Felidae', 'Panthera onca', 1),
('Perereca de banheiro', 'Amphibia', 'Anura', 'Hylidae', 'Phyllomedusa rohdei', 1),
('Tamanduá-bandeira', 'Mammalia', 'Pilosa', 'Myrmecophagidae', 'Myrmecophaga tridactyla', 1),
('Caranguejeira', 'Arachnida', 'Araneae', 'Theraphosidae', 'Grammostola actaeon', 1),
('Tucunaré', 'Actinopterygii', 'Perciformes', 'Cichlidae', 'Cichla ocellaris', 1),
('Ipê-amarelo', 'Magnoliopsida', 'Lamiales', 'Bignoniaceae', 'Handroanthus albus', 2),
('Formiga-cortadeira', 'Insecta', 'Hymenoptera', 'Formicidae', 'Atta sexdens', 1),
('Jararaca', 'Reptilia', 'Squamata', 'Viperidae', 'Bothrops jararaca', 1),
('Musgo-estrela', 'Bryopsida', 'Funariales', 'Funariaceae', 'Funaria hygrometrica', 2),
('Cogumelo-do-sol', 'Fungi', 'Agaricales', 'Agaricaceae', 'Agaricus blazei', 2);

-- Tabela de registros biológicos com data_publicacao
CREATE TABLE registros_biologicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_taxonomia INT,
    data_observacao DATE NOT NULL,
    hora_observacao TIME NOT NULL,
    descricao TEXT,
    id_geolocalizacao INT NOT NULL,
    url_imagem VARCHAR(255),
    qtde_likes INT NOT NULL,
    qtde_coment INT NOT NULL,
    data_publicacao DATE NOT NULL,
    hora_publicacao TIME NOT NULL,
    identificacao BOOLEAN NOT NULL DEFAULT FALSE,
    especie_invasora BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_taxonomia) REFERENCES classificacao_taxonomica(id),
    FOREIGN KEY (id_geolocalizacao) REFERENCES geolocalizacao(id)
);
INSERT INTO registros_biologicos (
    id_usuario, id_taxonomia, data_observacao, hora_observacao, descricao, id_geolocalizacao, url_imagem, qtde_likes, qtde_coment, data_publicacao, hora_publicacao, identificacao, especie_invasora
) VALUES
(1, 1, '2025-05-20', '10:00:00', 'Papagaio-verdadeiro avistado na copa da árvore', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-21', '08:00:00', TRUE, FALSE),
(1, 1, '2025-05-20', '10:00:00', 'Papagaio-verdadeiro avistado na copa da árvore', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-21', '08:00:00', TRUE, FALSE),
(1, 1, '2025-05-20', '10:00:00', 'Papagaio-verdadeiro avistado na copa da árvore', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-21', '08:00:00', TRUE, FALSE),
(1, 1, '2025-05-20', '10:00:00', 'Papagaio-verdadeiro avistado na copa da árvore', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 0, 0, '2025-05-21', '08:00:00', TRUE, FALSE),
(1, 2, '2025-05-18', '14:30:00', 'Onça-pintada vista próxima ao rio', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915960/ytbx8r46k4r9fv3qnirn.png', 0, 0, '2025-05-19', '09:00:00', TRUE, FALSE),
(1, 3, '2025-05-22', '19:45:00', 'Perereca de banheiro encontrada próxima ao banheiro do parque', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746916025/tviwtun4vxqcivfeiysy.png', 0, 0, '2025-05-23', '07:30:00', TRUE, FALSE),
(1, 4, '2025-05-19', '11:15:00', 'Tamanduá-bandeira cruzando a trilha', 1, '', 0, 0 , '2025-05-20', '10:00:00', TRUE, FALSE),
(1, 5, '2025-05-19', '17:45:00', 'Caranguejeira caminhando na trilha', 1, '', 0, 0, '2025-05-22', '08:30:00', TRUE, FALSE),
(1, 6, '2025-05-18', '09:20:00', 'Tucunaré pescado em rio próximo', 1, '', 0, 0, '2025-05-22', '09:00:00', TRUE, FALSE),
(1, 7, '2025-05-17', '13:00:00', 'Ipê-amarelo florido na praça central', 1, '', 0, 0, '2025-05-22', '09:30:00', TRUE, FALSE),
(1, 8, '2025-05-20', '08:45:00', 'Formiga-cortadeira carregando folhas', 1, '', 0, 0, '2025-05-22', '10:30:00', TRUE, FALSE),
(1, 9, '2025-05-16', '22:10:00', 'Jararaca avistada à beira da trilha', 1, '', 0, 0, '2025-05-22', '11:00:00', TRUE, FALSE),
(1, NULL, '2025-05-15', '07:30:00', 'Musgo crescendo em tronco úmido', 1, '', 0, 0, '2025-05-22', '11:30:00', TRUE, FALSE),
(1, 11, '2025-05-21', '18:50:00', 'Cogumelo-do-sol encontrado na mata', 1, '', 0, 0, '2025-05-22', '12:00:00', TRUE, FALSE);

CREATE TABLE tipo_ocorrencia(
	id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20) NOT NULL
) ;
INSERT INTO tipo_ocorrencia (tipo) VALUES 
('animal'),
('ambiental');

-- Tabela de Ocorrências
CREATE TABLE ocorrencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_geolocalizacao INT NOT NULL,
    data_publicacao DATE NOT NULL,
    hora_publicacao TIME NOT NULL,
    img_url_ocorrencia VARCHAR(255) NOT NULL,
    titulo_ocorrencia VARCHAR(255) NOT NULL,
    descricao_ocorrencia TEXT NOT NULL,
    sensivel BOOLEAN DEFAULT FALSE,
    exibicao BOOLEAN DEFAULT FALSE,
    id_taxonomia INT,
    id_tipo_ocorrencia INT,
    FOREIGN KEY (id_tipo_ocorrencia) REFERENCES tipo_ocorrencia(id),
    FOREIGN KEY (id_taxonomia) REFERENCES classificacao_taxonomica(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_geolocalizacao) REFERENCES geolocalizacao(id)
);

INSERT INTO ocorrencias (id_usuario, id_geolocalizacao, data_publicacao, hora_publicacao, img_url_ocorrencia, titulo_ocorrencia, descricao_ocorrencia, sensivel, id_taxonomia, id_tipo_ocorrencia, exibicao) VALUES
(1, 1,'2025-05-19', '10:15:00', 'https://super.abril.com.br/wp-content/uploads/2020/10/SI_Inteligencia_animal_Abre.jpg?quality=70&strip=info&crop=1&resize=1080,565', 'Nova trilha ecológica aberta ao público', 'Uma nova trilha foi aberta na Serra do Japi para visitantes interessados em turismo ecológico. A iniciativa visa fomentar a educação ambiental e preservar a biodiversidade local.', true, 3, 1, true),
(2, 2,'2025-05-18', '09:00:00', 'https://super.abril.com.br/wp-content/uploads/2020/10/SI_Inteligencia_animal_Abre.jpg?quality=70&strip=info&crop=1&resize=1080,565', 'Projeto de reflorestamento é iniciado', 'O projeto “Verde Sempre” iniciou o plantio de mais de 5.000 mudas na Floresta da Tijuca com o objetivo de restaurar áreas degradadas nos últimos anos.', true, NULL, 2, true),
(3, 3,'2025-05-17', '15:45:00', 'https://super.abril.com.br/wp-content/uploads/2020/10/SI_Inteligencia_animal_Abre.jpg?quality=70&strip=info&crop=1&resize=1080,565', 'Nova espécie de inseto descoberta na reserva', 'Pesquisadores do INPA descobriram uma nova espécie de besouro na Reserva Adolpho Ducke. A espécie ainda está em processo de classificação científica.', false, 3, 1, true),
(1, 4,'2025-05-16', '18:30:00', 'https://s2.glbimg.com/MhXnNwzZ5WwuhO6pJ-FXYlKfTjE=/0x0:1500x1000/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_bc8228b6673f488aa253bbcb03c80ec5/internal_photos/bs/2023/Y/K/Mt9UbdTXWVa6QnbcIRlQ/animal-acidente.jpg', 'Capivara causa acidente em rodovia', 'Um acidente foi registrado na BR-101 após uma capivara atravessar a pista repentinamente. Motoristas alertam para a necessidade de sinalização em áreas de fauna.', true, 3, 1, true),
(1, 5,'2025-05-15', '12:10:00', '', 'Filhote de macaco resgatado por veterinários', 'Visitantes encontraram um filhote de macaco ferido próximo à trilha principal. Ele foi encaminhado para atendimento e está em recuperação.', false, 3, 1, true),
(1, 6,'2025-05-14', '07:50:00', '', 'Tamanduá é atropelado e resgatado por ONG', 'Um tamanduá-bandeira foi atropelado em estrada rural e socorrido por voluntários da ONG Vida Selvagem. O animal está em observação veterinária.', false, 3, 1, false);


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
SELECT * FROM categoria;
SELECT * FROM geolocalizacao;
SELECT * FROM registros_biologicos;
SELECT * FROM curtidas_usuarios;
SELECT * FROM tipo_ocorrencia;
SELECT * FROM ocorrencias;