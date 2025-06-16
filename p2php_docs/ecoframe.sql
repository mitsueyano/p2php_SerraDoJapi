DROP DATABASE IF EXISTS ecoframe;
CREATE DATABASE ecoframe;
USE ecoframe;

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
('123.456.789-00', 'Regina', 'Silva', 'ana.silva@example.com', 'reSilva', '$2y$10$KckpFTTAbDgDtCGKEtnlUODlkKFPziZ52/zxDumCGGM6/0uBN50pu', 'especialista', NULL, NULL, "https://upload.wikimedia.org/wikipedia/commons/7/73/Sandra_Díaz.-foto-ipbes.1-1_CONICET_%28cropped%29.jpg"),
('987.654.321-11', 'Bruno', 'Souza', 'bruno.souza@example.com', 'bruSouza', '$2y$10$20eejaVT.RDTEmC5q1NRm.Vc9c.z4w8lp0OqG84IRvknhHWN/CVEO', 'especialista', 'http://lattes.cnpq.br/1234567890123456', 'Aracnólogo', 'https://upload.wikimedia.org/wikipedia/commons/b/b2/Tiago_Rocha_Fernandes%2C_cientista%2C_biologo_e_nutricionista.jpg'),
('456.789.123-22', 'Carla', 'Pereira', 'carla.pereira@example.com', 'caPereira', '$2y$10$ncJQk.ZKyivWQ.En1KdNpOZo3ZTMKzwXajLfw26m4uCELhbA/zSH2', 'comum', NULL, NULL, 'https://upload.wikimedia.org/wikipedia/commons/c/c9/Vera_Lúcia_no_Dia_Internacional_da_Mulher_Trabalhadora_2018_-_PSTU.jpg');

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
('Cogumelo-do-sol', 'Fungi', 'Agaricales', 'Agaricaceae', 'Agaricus blazei', 2),
('Guaxinim', 'Mammalia', 'Carnivora', 'Procyonidae', 'Procyon lotor', 3);

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
(1, 1, '2025-05-20', '10:00:00', 'Papagaio-verdadeiro avistado na copa da árvore', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915864/hdhsdtqgv7ea5cbhtl4z.png', 1, 0, '2025-05-21', '08:00:00', TRUE),
(2, 2, '2025-05-18', '14:30:00', 'Onça-pintada vista próxima ao rio', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746915960/ytbx8r46k4r9fv3qnirn.png', 3, 0, '2025-05-19', '09:00:00', TRUE),
(1, 3, '2025-05-22', '19:45:00', 'Perereca de banheiro encontrada próxima ao banheiro do parque', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1746916025/tviwtun4vxqcivfeiysy.png', 2, 0, '2025-05-23', '07:30:00', TRUE),
(1, 4, '2025-05-19', '11:15:00', 'Tamanduá-bandeira cruzando a trilha', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1747399815/imagem_2025-05-16_094936847_lfogo5.png', 1, 0 , '2025-05-20', '10:00:00', TRUE),
(1, 5, '2025-05-19', '17:45:00', 'Caranguejeira caminhando na trilha', 1, 'https://www.portal.zoo.bio.br/IMG/jpg/20210828_111014.jpg', 1, 0, '2025-05-22', '08:30:00', TRUE),
(3, 6, '2025-05-18', '09:20:00', 'Tucunaré pescado em rio próximo', 1, 'https://pescariasa.com.br/wp-content/uploads/2024/01/especies-de-tucunare-presentes-no-brasil-700x467.jpg', 2, 0, '2025-05-22', '09:00:00', TRUE),
(1, 7, '2025-05-17', '13:00:00', 'Ipê-amarelo florido na praça central', 1, 'https://live.staticflickr.com/4372/35964128804_fd13434b90_b.jpg', 0, 0, '2025-05-22', '09:30:00', TRUE),
(1, 8, '2025-05-20', '08:45:00', 'Formiga-cortadeira carregando folhas', 1, 'https://agropos.com.br/wp-content/uploads/2020/09/Imagem-2024-02-15T102829.573.png', 0, 0, '2025-05-22', '10:30:00', TRUE),
(3, 9, '2025-05-16', '22:10:00', 'Jararaca avistada à beira da trilha', 1, 'https://www.portal.zoo.bio.br/local/cache-gd2/b2/423ed49e85515eff13d4dd7ce64590.jpg?1640271823', 0, 0, '2025-05-22', '11:00:00', TRUE),
(1, NULL, '2025-05-15', '07:30:00', 'Musgo crescendo em tronco úmido', 1, 'https://live.staticflickr.com/8502/8327280895_dbe17f5c85_b.jpg', 0, 0, '2025-05-22', '11:30:00', FALSE),
(2, 11, '2025-05-21', '18:50:00', 'Cogumelo-do-sol encontrado na mata', 1, 'https://www.fungicultura.com.br/wp-content/uploads/2022/11/cogumelo-do-sol-1200x900-1.jpg', 0, 0, '2025-05-22', '12:00:00', TRUE),
(3, 1, '2025-02-20', '10:00:00', 'Papagaio-verdadeiro', 1, 'https://casadospassaros.net/wp-content/uploads/2016/12/Papagaio-Verdadeiro.jpg', 0, 0, '2025-02-21', '08:00:00', TRUE),
(1, 1, '2025-02-20', '10:00:00', 'Papagaio comendo', 1, 'https://cdn0.peritoanimal.com.br/pt/posts/3/0/3/o_que_comem_os_diferentes_tipos_de_papagaio_23303_5_600.jpg', 0, 0, '2025-02-21', '08:00:00', TRUE),
(2, 12, '2025-05-25', '15:40:00', 'Guaxinim avistado próximo à borda da mata', 1, 'https://res.cloudinary.com/djbatjpjn/image/upload/v1749916018/imagem_2025-06-14_124630030_y4b3ak.png', 0, 0, '2025-05-22', '10:36:00', TRUE);
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
(1, 2),
(1, 4),
(1, 6),
(2, 1),
(2, 2),
(2, 3),
(2, 6),
(3, 2),
(3, 3),
(3, 5);

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


-- Papagaio-verdadeiro
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(1, 2, 'Que foto incrível do papagaio! As cores estão muito vivas.', '2025-05-21 09:15:00'),
(1, 3, 'Nunca vi um papagaio-verdadeiro na natureza, que sorte!', '2025-05-21 10:30:00');
-- Papagaio-verdadeiro
INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo, data_publicacao) VALUES
(1, 1, 1, 'Obrigada, Bruno! Foi um momento mágico.', '2025-05-21 09:30:00'),
(1, 3, 1, 'Realmente ficou espetacular a foto!', '2025-05-21 11:45:00');

-- Onça-pintada
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(2, 3, 'Que encontro incrível! Tomei um susto quando vi.', '2025-05-19 10:20:00');
-- Onça-pintada
INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo, data_publicacao) VALUES
(2, 2, 5, 'Foi um momento de pura emoção, Carla! Ela passou a uns 10 metros de mim.', '2025-05-19 11:05:00');

-- Perereca de banheiro
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(3, 3, 'Essa espécie é rara de se encontrar?', '2025-05-23 08:15:00'),
(3, 2, 'Adorei o nome popular "perereca de banheiro" hahaha', '2025-05-23 09:30:00');

-- Tamanduá-bandeira
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(4, 2, 'Parece que ele estava procurando formigas, certo?', '2025-05-20 11:30:00');

-- Ipê-amarelo
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(7, 3, 'A floração dos ipês é um espetáculo à parte!', '2025-05-22 10:00:00');

-- Ipê-amarelo
INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo, data_publicacao) VALUES
(7, 1, 10, 'Sim, Carla! Nessa época do ano eles ficam ainda mais bonitos.', '2025-05-22 10:15:00');

-- Jararaca
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(9, 2, 'Tem que ter muito cuidado com essa espécie!', '2025-05-22 11:30:00');

-- Cogumelo-do-sol
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(11, 3, 'Esse cogumelo tem propriedades medicinais, não é?', '2025-05-22 12:30:00');
-- Cogumelo-do-sol
INSERT INTO comentarios (id_registro, id_usuario, id_comentario_pai, conteudo, data_publicacao) VALUES
(11, 1, 13, 'Sim, Carla! É muito estudado por seus benefícios à saúde.', '2025-05-22 12:45:00');

-- Formiga-cortadeira
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(8, 2, 'Elas são incríveis trabalhando em equipe!', '2025-05-22 11:00:00');

-- Tucunaré
INSERT INTO comentarios (id_registro, id_usuario, conteudo, data_publicacao) VALUES
(6, 2, 'Que tamanho tinha esse tucunaré?', '2025-05-22 09:30:00');

SELECT * FROM usuarios;
SELECT * FROM classificacao_taxonomica;
SELECT * FROM categoria;
SELECT * FROM geolocalizacao;
SELECT * FROM registros_biologicos;
SELECT * FROM curtidas_usuarios;
SELECT * FROM tipo_ocorrencia;
SELECT * FROM ocorrencias;
SELECT * FROM comentarios;
