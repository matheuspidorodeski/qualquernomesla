CREATE TABLE produtos (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL,
  preco decimal(10,2) DEFAULT NULL,
  descricao text DEFAULT NULL,
  imagem varchar(999) NOT NULL,
  categoria_id int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
