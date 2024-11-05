CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telefone VARCHAR(20),
  senha VARCHAR(255) NOT NULL,
  data_nascimento DATE,
  cidade VARCHAR(100),
  estado VARCHAR(2),
  endereco TEXT,
  e_admin TINYINT(1)
);