DROP DATABASE IF EXISTS ecoframeanch;
CREATE DATABASE ecoframeanch;
USE ecoframeanch;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    nome VARCHAR(50) NOT NULL,
    sobrenome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    nome_usuario VARCHAR(20) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel_acesso ENUM('comum', 'especialista') NOT NULL,
    link_lattes VARCHAR(255),
    cargo VARCHAR(50),
    imagem_perfil VARCHAR(255)
);
INSERT INTO usuarios (cpf, nome, sobrenome, email, nome_usuario, senha, nivel_acesso, link_lattes, cargo, imagem_perfil) VALUES
('123.456.789-00', 'Claudio', 'da Cunha', 'claudio.cunha@example.com', 'claudioCunha', '$2y$10$KckpFTTAbDgDtCGKEtnlUODlkKFPziZ52/zxDumCGGM6/0uBN50pu', 'especialista', 'http://lattes.cnpq.br/1234567890123456', 'Aracnólogo', '../img/claudio.png'),
('987.654.321-11', 'Luiza', 'Mitsue', 'luiza.mitsue@example.com', 'luMitsue', '$2y$10$20eejaVT.RDTEmC5q1NRm.Vc9c.z4w8lp0OqG84IRvknhHWN/CVEO', 'especialista', NULL, NULL, "../img/mitsue.png"),
('456.789.123-22', 'Carla', 'Pereira', 'carla.pereira@example.com', 'caPereira', '$2y$10$ncJQk.ZKyivWQ.En1KdNpOZo3ZTMKzwXajLfw26m4uCELhbA/zSH2', 'comum', NULL, NULL, '../img/userdefault.png');

-- >>>>>>>>>>>>>>>>>>>>>>>>>>ARRUMAR
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
("Espécie invasora"),
("-");

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
INSERT INTO classificacao_taxonomica (nome_popular, classe, ordem, familia, especie, id_categoria) VALUES
('Surucuá variado', 'Aves', 'Trogoniformes', 'Trogonidae', 'Trogon surrucura', 1),
('Borboleta verde', 'Insecta', 'Lepidoptera', '-', '-', 4),
('Caranguejo de água doce', 'Malacostraca', 'Decapoda', 'Aeglidae', 'Aegla spp.', 3),
('Libélula de asa escura', 'Insecta', 'Odonata', 'Libellulidae', 'Diastatops pullata', 1),
('Lagartinho de folhiço', 'Reptilia', 'Squamata', 'Gymnophthalmidae', 'Colobodactylus spp.', 1),
('Caranguejeira', 'Arachnida', 'Araneae', 'Theraphosidae', 'Grammostola spp.', 1),
('Cafezinho do mato', 'Magnoliopsida', 'Gentianales', 'Rubiaceae', 'Palicourea macgravii', 2),
('Cágado de barbela', 'Reptilia', 'Testudines', 'Chelidae', 'Phrynops geoffroanus', 1),
('Sapo pingo de ouro', 'Amphibia', 'Anura', 'Brachycephalidae', 'Brachycephalus ephippium', 1),
('Borboleta Adelpha', 'Insecta', 'Lepidoptera', 'Nymphalidae', 'Adelpha spp.', 1);



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
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_taxonomia) REFERENCES classificacao_taxonomica(id),
    FOREIGN KEY (id_geolocalizacao) REFERENCES geolocalizacao(id)
);
INSERT INTO registros_biologicos (
    id_usuario, id_taxonomia, data_observacao, hora_observacao, descricao, id_geolocalizacao, url_imagem, qtde_likes, qtde_coment, data_publicacao, hora_publicacao, identificacao
) VALUES
(1, 1, '2025-08-08', '07:45:00', 'Surucuá variado fotografado empoleirado em galho baixo', 1, '../img/surucua.jpg', 1, 0, '2025-08-08', '08:07:00', TRUE),
(1, 2, '2025-07-08', '08:30:00', 'Borboleta verde pousada em folha larga', 1, '../img/borboletaverde.jpg', 0, 0, '2025-07-08', '08:38:00', FALSE),
(2, 3, '2025-08-08', '08:55:00', 'Crustáceo do gênero Aegla próximo a córrego raso', 1, '../img/crustaceo.jpg', 3, 0, '2025-08-08', '09:01:00', TRUE),
(1, 4, '2025-08-08', '10:10:00', 'Libélula sobrevoando área alagada no parque da cidade', 1, '../img/libelula.png', 1, 0, '2025-08-08', '10:23:00', TRUE),
(1, 5, '2025-08-07', '10:05:00', 'Lagarto do gênero Colobodactylus próximo a rocha ensolarada', 1, '../img/colobodactylus.jpg', 0, 0, '2025-08-07', '10:15:00', TRUE),
(1, 6, '2025-08-06', '10:25:00', 'Aranha Epicadus heterogaster camuflada entre folhas secas', 1, '../img/aranha.png', 1, 0, '2025-08-06', '10:35:00', TRUE),
(2, 7, '2025-08-06', '08:20:00', 'Palicourea macgravii florescendo na trilha do Mirante', 1, '../img/palicourea.png', 1, 0, '2025-08-06', '08:32:00', TRUE),
(3, 8, '2025-08-05', '08:55:00', 'Cágado nadando lentamente no Rio Jundiaí', 1, '../img/cagado.png', 0, 0, '2025-08-05', '09:07:00', TRUE),
(2, 9, '2025-08-05', '09:25:00', 'Sapo pingo de ouro encontrado sob folhagem úmida', 1, '../img/sapo.png', 0, 0, '2025-08-05', '09:34:00', TRUE),
(1, 10, '2025-08-05', '08:50:00', 'Borboleta Adelpha vista na Serra do Japi', 1, '../img/adelpha.jpg', 2, 0, '2025-08-05', '09:02:00', TRUE);


CREATE TABLE tipo_ocorrencia(
	id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20) NOT NULL
) ;
INSERT INTO tipo_ocorrencia (tipo) VALUES 
('animal'),
('ambiental');

CREATE TABLE ocorrencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_geolocalizacao INT NOT NULL,
    data_publicacao DATE NOT NULL,
    hora_publicacao TIME NOT NULL,
    img_url_ocorrencia VARCHAR(255) NOT NULL,
    titulo_ocorrencia VARCHAR(100) NOT NULL,
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
(1, 1,'2025-05-19', '10:15:00', 'https://images.pexels.com/photos/3002082/pexels-photo-3002082.jpeg', 'Nova trilha ecológica aberta ao público', 'Uma nova trilha foi aberta na Serra do Japi para visitantes interessados em turismo ecológico. A iniciativa visa fomentar a educação ambiental e preservar a biodiversidade local.', false, 3, 1, true),
(2, 2,'2025-05-18', '09:00:00', 'https://live.staticflickr.com/65535/52553758525_4a817b46eb_b.jpg', 'Projeto de reflorestamento é iniciado', 'O projeto “Verde Sempre” iniciou o plantio de mais de 5.000 mudas na Floresta da Tijuca com o objetivo de restaurar áreas degradadas nos últimos anos.', false, NULL, 2, true),
(3, 3,'2025-05-17', '15:45:00', 'https://www.mongabay.com/images/peru/tambopata/Tambopata_1026_3864.JPG', 'Nova espécie de inseto descoberta na reserva', 'Pesquisadores do INPA descobriram uma nova espécie de besouro na Reserva Adolpho Ducke. A espécie ainda está em processo de classificação científica.', false, 3, 1, true),
(1, 4,'2025-05-16', '18:30:00', 'https://www.rdnews.com.br//storage/webdisco/2018/07/03/395x253/fd188d674508d2e6b13af7de155c9892.jpg', 'Capivara causa acidente em rodovia', 'Um acidente foi registrado na BR-101 após uma capivara atravessar a pista repentinamente. Motoristas alertam para a necessidade de sinalização em áreas de fauna.', true, 3, 1, true),
(1, 5,'2025-05-15', '12:10:00', 'https://f.i.uol.com.br/fotografia/2024/09/18/172670475666eb6c742c829_1726704756_3x2_md.jpg', 'Filhote de macaco resgatado por veterinários', 'Visitantes encontraram um filhote de macaco ferido próximo à trilha principal. Ele foi encaminhado para atendimento e está em recuperação.', false, 3, 1, true),
(1, 6,'2025-05-14', '07:50:00', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcdn.pixabay.com%2Fphoto%2F2016%2F02%2F14%2F20%2F27%2Fflag-anteater-1200160_1280.jpg&f=1&nofb=1&ipt=f64816e082579269a3eb7dc9a7df138f826e082c7c64bfcbb95e80edcdb76793', 'Tamanduá é atropelado e resgatado por ONG', 'Um tamanduá-bandeira foi atropelado em estrada rural e socorrido por voluntários da ONG Vida Selvagem. O animal está em observação veterinária.', false, 3, 1, false);

CREATE TABLE curtidas_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_registro INT NOT NULL,
    data_curtida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_registro) REFERENCES registros_biologicos(id) ON DELETE CASCADE
);
INSERT INTO curtidas_usuarios (id_usuario, id_registro) VALUES
(1, 1),  -- Regina curtiu Surucuá variado (id 1)
(2, 10),  -- Bruno curtiu Borboleta (id 10)
(1, 10),  -- Regina curtiu Borboleta(id 10)
(2, 4),  -- Bruno curtiu Libélula (id 4)
(3, 6),  -- Carla curtiu Aranha caranguejeira (id 6)
(1, 7),  -- Regina curtiu Palicourea macgravii (id 7)
(1, 3),  -- Claudio curtiu carang
(2, 3),  -- Luiza curtiu carang
(3, 3);  -- Carla curtiu carang


CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_registro INT NOT NULL,
    id_usuario INT NOT NULL,
    id_comentario_pai INT NULL,
    conteudo TEXT NOT NULL,
    data_publicacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_registro) REFERENCES registros_biologicos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_comentario_pai) REFERENCES comentarios(id) ON DELETE CASCADE
);

-- Comentários diretos
INSERT INTO comentarios (id_registro, id_usuario, conteudo) VALUES
(1, 1, 'Nunca tinha visto esse pássaro por aqui'),                          -- id = 1 (Regina no Surucuá)
(2, 2, 'Linda borboleta! Vi uma parecida aqui em casa'),               -- id = 2 (Bruno na Borboleta)
(7, 1, 'Florescendo bem na trilha, que sorte a sua');                      -- id = 3 (Regina na Palicourea)

-- Respostas aos comentários acima
INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo) VALUES
(2, 3, 2, 'Sim! Espero que alguém identifique logo.'),                -- Carla responde comentário 2
(7, 2, 3, 'Eu vi na mesma trilha semana passada também');                  -- Bruno responde comentário 3

-- Comentários diretos no Caranguejo de água doce
INSERT INTO comentarios (id_registro, id_usuario, conteudo) VALUES
(3, 2, 'Nunca pensei que encontraria essa espécie por aqui'),  -- Luiza comenta
(3, 1, 'Lindo exemplar! Parece saudável');                    -- Claudio comenta

-- Respostas aos comentários do Caranguejo de água doce
INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo) VALUES
(3, 3, 4, 'Concordo! Acho que está se adaptando bem ao ambiente'), -- Carla responde Luiza (comentário id 4)
(3, 2, 5, 'Sim, estava bem ativo no dia da observação');           -- Luiza responde Claudio (comentário id 5)

