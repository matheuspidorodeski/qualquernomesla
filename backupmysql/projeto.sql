-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/11/2024 às 03:05
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `metodo_pagamento` varchar(50) DEFAULT NULL,
  `data_compra` datetime DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compras`
--

INSERT INTO `compras` (`id`, `valor`, `metodo_pagamento`, `data_compra`, `id_produto`, `quantidade`, `id_cliente`) VALUES
(4, 850.00, 'credito', '0000-00-00 00:00:00', 101031, 1, NULL),
(5, 750.00, 'boleto', '0000-00-00 00:00:00', 101033, 1, NULL),
(6, 722.50, 'pix', '0000-00-00 00:00:00', 101031, 1, NULL),
(7, 550.00, 'boleto', '0000-00-00 00:00:00', 101034, 1, NULL),
(8, 467.50, 'pix', '0000-00-00 00:00:00', 101034, 1, NULL),
(9, 550.00, 'credito', '0000-00-00 00:00:00', 101034, 1, NULL),
(10, 550.00, 'boleto', '0000-00-00 00:00:00', 101034, 1, NULL),
(11, 550.00, 'boleto', '0000-00-00 00:00:00', 101034, 1, NULL),
(12, 550.00, 'boleto', '0000-00-00 00:00:00', 101034, 1, 3),
(13, 550.00, 'credito', '0000-00-00 00:00:00', 101034, 1, 3),
(14, 750.00, 'credito', '0000-00-00 00:00:00', 101033, 1, 3),
(15, 850.00, 'boleto', '0000-00-00 00:00:00', 101031, 1, 3),
(16, 722.50, 'pix', '0000-00-00 00:00:00', 101031, 1, 3),
(17, 722.50, 'pix', '0000-00-00 00:00:00', 101031, 1, 3),
(18, 850.00, 'boleto', '0000-00-00 00:00:00', 101031, 1, 3),
(19, 550.00, 'credito', '0000-00-00 00:00:00', 101034, 1, 3),
(20, 150.00, 'boleto', '0000-00-00 00:00:00', 101032, 1, 3),
(21, 850.00, 'credito', '0000-00-00 00:00:00', 101031, 1, 3),
(22, 850.00, 'credito', '0000-00-00 00:00:00', 101031, 1, 3),
(23, 850.00, 'boleto', '0000-00-00 00:00:00', 101031, 1, 3),
(24, 750.00, 'boleto', '0000-00-00 00:00:00', 101033, 1, 3),
(25, 150.00, 'credito', '0000-00-00 00:00:00', 101032, 1, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(999) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `descricao`, `imagem`, `categoria_id`) VALUES
(101015, 'rtx 5090', 10000.00, 'usina nuclear', 'https://i.redd.it/fictionary-nvidia-rtx-5090-release-and-reviews-v0-x2j5uull701b1.jpg?width=1700&format=pjpg&auto=webp&s=165b3cd2ac9e7a51a26cc43c165db28f3daa031d', 0),
(101016, 'memoria ram ', 150.00, 'memoria ram 16gb (2x8) ddr4 3600mhz', 'https://www.adrenaline.com.br/wp-content/uploads/2023/02/CHAMADA-RM.jpg', 5),
(101030, 'rx 7600', 1800.00, 'produto de teste', 'https://images.kabum.com.br/produtos/fotos/475647/placa-de-video-rx-7600-gaming-oc-8g-radeon-gigabyte-8gb-gddr6-128bits-rgb-gv-r76gaming-oc-8gd_1698435450_gg.jpg', 1),
(101031, 'ryzen 5 5600', 850.00, 'amd ryzen 5 5600 com 6 nucelos e 12 threads', 'https://salescdn.net/a15DCl4cot96WWw__ZtdG-k_4FU=/adaptive-fit-in/500x0/prod/store/13040/medias/product/processador-amd-ryzen-7-5700x-34ghz-46ghz-turbo-8-cores-16-threads-am4---100-100000926wof-0-1669090215346.webp', 2),
(101032, 'mouse logitech', 150.00, 'mouse gamer de entrada logitech', 'https://m.media-amazon.com/images/I/61UxfXTUyvL.jpg', 3),
(101033, 'monitor 24pol', 750.00, 'monitoer gamer 144hz 24 polegadas', 'https://images.tcdn.com.br/img/img_prod/15959/monitor_gamer_24_acer_nitro_qg240y_full_hd_180hz_1ms_95_srgb_hdr10_freesync_hdmi_displayport_19641_4_300f991d3fb15dc19018d5fa59aa853a.jpg', 4),
(101034, 'psu corsair cx 750', 550.00, 'fonte de alimentaçao corsair cx 750 com selo 80 plus bronze', 'https://res.cloudinary.com/corsair-pwa/image/upload/f_auto,q_auto/v1665096094/akamai/landing/PSU-Family-Page/images/psu-with-badge-cx750f-rgb.png', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `status`
--

INSERT INTO `status` (`id`, `id_cliente`, `id_compra`, `id_produto`) VALUES
(1, NULL, 22, 101031),
(2, 3, 23, 101031),
(3, 3, 24, 101033),
(4, 4, 25, 101032);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `e_admin` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `telefone`, `senha`, `data_nascimento`, `cidade`, `estado`, `endereco`, `e_admin`) VALUES
(1, 'usuario', 'usuario@gmail.com', '123', '$2y$10$f3o7d8jB0HwqBjcWQJrjkunJUCaFR0DQ7IkDozBGyzc7ltDDd0/Ky', '2024-11-02', 'guarapuava', 'pa', 'rua sandero 2014', 1),
(2, 'lipe', 'lipe@gmail.com', '123456789', '$2y$10$atOR/De7Y/zVjCBrqJRC0O9i.Y6iWVXU0QrcQGQ7tCplwV9/Jiyhy', '2024-11-02', 'guarapuava', '13', 'sla', NULL),
(3, 'adm', 'adm@gmail.com', '1234', '$2y$10$dTFoyax6QsQCWInQvYKgTu8VN./u59t92QorWKV9F8AwhL/2gowCC', '2024-11-03', 'guarapuava', 'pa', 'rua severino', NULL),
(4, 'felipe', 'felipe@gmail.com', '123', '$2y$10$rTEz6FIBA59f9ym1oYIJeufFSWWVqgZeFCq3kU8sNJiIwawP3FqU.', '2024-11-03', 'guarapuava', 'pa', 'rua sandero 2014', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101035;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `status_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `status_ibfk_2` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `status_ibfk_3` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
