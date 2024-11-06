-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/11/2024 às 05:18
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
  `id_cliente` int(11) DEFAULT NULL,
  `parcelas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compras`
--

INSERT INTO `compras` (`id`, `valor`, `metodo_pagamento`, `data_compra`, `id_produto`, `quantidade`, `id_cliente`, `parcelas`) VALUES
(40, 1874.00, 'credito', '0000-00-00 00:00:00', 101036, 1, 2, 5),
(41, 104.55, 'pix', '0000-00-00 00:00:00', 101039, 1, 2, 1),
(42, 123.00, 'credito', '0000-00-00 00:00:00', 101040, 1, 2, 6),
(43, 0.85, 'pix', '0000-00-00 00:00:00', 101037, 1, 2, 1),
(44, 1874.00, 'boleto', '0000-00-00 00:00:00', 101036, 1, 2, 1),
(45, 1.70, 'pix', '0000-00-00 00:00:00', 101037, 2, 2, 1),
(46, 1.00, 'credito', '0000-00-00 00:00:00', 101037, 1, 2, 1),
(47, 1.00, 'boleto', '0000-00-00 00:00:00', 101037, 1, 2, 1),
(48, 1.00, 'boleto', '0000-00-00 00:00:00', 101037, 1, 2, 1),
(49, 1.00, 'credito', '0000-00-00 00:00:00', 101037, 1, 2, 5),
(50, 1048.90, 'pix', '0000-00-00 00:00:00', 101042, 1, 1, 1),
(51, 450.00, 'credito', '0000-00-00 00:00:00', 101043, 1, 1, 1),
(52, 450.00, 'boleto', '0000-00-00 00:00:00', 101043, 1, 1, 1),
(53, 382.50, 'pix', '0000-00-00 00:00:00', 101043, 1, 1, 1),
(54, 1900.00, 'credito', '0000-00-00 00:00:00', 101044, 1, 1, 6),
(55, 680.00, 'pix', '0000-00-00 00:00:00', 101045, 1, 1, 1),
(56, 680.00, 'pix', '0000-00-00 00:00:00', 101045, 1, 1, 1),
(57, 1360.00, 'pix', '0000-00-00 00:00:00', 101045, 2, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `id_venda` int(11) DEFAULT NULL,
  `data_insercao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_venda` timestamp NULL DEFAULT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id`, `id_produto`, `nome`, `id_venda`, `data_insercao`, `data_venda`, `quantidade`) VALUES
(1, 101037, '', NULL, '2024-11-05 20:28:58', NULL, 0),
(2, 101037, 'teste', 27, '2024-11-05 20:38:36', '2024-11-06 00:38:36', 0),
(3, 101041, '', NULL, '2024-11-05 22:08:04', NULL, 0),
(4, 101042, '', NULL, '2024-11-06 02:50:07', NULL, 66),
(5, 101043, '', NULL, '2024-11-06 02:52:59', NULL, 11),
(6, 101044, '', NULL, '2024-11-06 02:58:46', NULL, 1),
(7, 101045, '', NULL, '2024-11-06 04:00:05', NULL, 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `imagem` varchar(999) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos`
--

INSERT INTO `favoritos` (`id`, `id_produto`, `nome`, `valor`, `id_cliente`, `imagem`) VALUES
(2, 101037, 'teste', 1.00, 2, 'https://images6.kabum.com.br/produtos/fotos/99686/gabinete-gamer-cooler-master-mastercase-cosmos-c700m-rgb-4-coolers-lateral-em-vidro-mcc-c700m-mg5n-s00__1547029494_g.jpg'),
(3, 101037, 'teste', 1.00, 1, 'https://images6.kabum.com.br/produtos/fotos/99686/gabinete-gamer-cooler-master-mastercase-cosmos-c700m-rgb-4-coolers-lateral-em-vidro-mcc-c700m-mg5n-s00__1547029494_g.jpg'),
(4, 101036, 'MOBO ASUS ROG STRIX B760-F', 1874.00, 1, 'https://images1.kabum.com.br/produtos/fotos/404821/placa-mae-asus-rog-strix-b760-f-gaming-intel-lga-1700-atx-ddr5-wifi-90mb1ct0-m0eay0_1674050530_g.jpg'),
(5, 101040, '123', 123.00, 1, 'https://victorvision.com.br/wp-content/uploads/2023/05/tipos-de-hardware-930x620.jpg'),
(6, 101037, 'teste', 1.00, 2, 'https://images6.kabum.com.br/produtos/fotos/99686/gabinete-gamer-cooler-master-mastercase-cosmos-c700m-rgb-4-coolers-lateral-em-vidro-mcc-c700m-mg5n-s00__1547029494_g.jpg'),
(7, 101040, '123', 123.00, 2, 'https://victorvision.com.br/wp-content/uploads/2023/05/tipos-de-hardware-930x620.jpg'),
(8, 101032, 'mouse logitech', 150.00, 2, 'https://m.media-amazon.com/images/I/61UxfXTUyvL.jpg'),
(9, 101045, 'gtx 1060', 800.00, 1, 'https://m.media-amazon.com/images/I/81bkywxwHAL.jpg');

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
  `categoria_id` int(11) NOT NULL,
  `data_adicao` datetime NOT NULL DEFAULT current_timestamp(),
  `imagemdescricao` varchar(999) NOT NULL,
  `segundaimagemdesc` varchar(999) NOT NULL,
  `segundadescricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `descricao`, `imagem`, `categoria_id`, `data_adicao`, `imagemdescricao`, `segundaimagemdesc`, `segundadescricao`) VALUES
(101015, 'rtx 5090', 10000.00, 'usina nuclear', 'https://i.redd.it/fictionary-nvidia-rtx-5090-release-and-reviews-v0-x2j5uull701b1.jpg?width=1700&format=pjpg&auto=webp&s=165b3cd2ac9e7a51a26cc43c165db28f3daa031d', 0, '2024-11-05 19:00:10', '', '', ''),
(101016, 'memoria ram ', 150.00, 'memoria ram 16gb (2x8) ddr4 3600mhz', 'https://www.adrenaline.com.br/wp-content/uploads/2023/02/CHAMADA-RM.jpg', 5, '2024-11-05 19:00:10', '', '', ''),
(101030, 'rx 7600', 1800.00, 'produto de teste', 'https://images.kabum.com.br/produtos/fotos/475647/placa-de-video-rx-7600-gaming-oc-8g-radeon-gigabyte-8gb-gddr6-128bits-rgb-gv-r76gaming-oc-8gd_1698435450_gg.jpg', 1, '2024-11-05 19:00:10', '', '', ''),
(101031, 'ryzen 5 5600', 850.00, 'amd ryzen 5 5600 com 6 nucelos e 12 threads', 'https://salescdn.net/a15DCl4cot96WWw__ZtdG-k_4FU=/adaptive-fit-in/500x0/prod/store/13040/medias/product/processador-amd-ryzen-7-5700x-34ghz-46ghz-turbo-8-cores-16-threads-am4---100-100000926wof-0-1669090215346.webp', 2, '2024-11-05 19:00:10', '', '', ''),
(101032, 'mouse logitech', 150.00, 'mouse gamer de entrada logitech', 'https://m.media-amazon.com/images/I/61UxfXTUyvL.jpg', 3, '2024-11-05 19:00:10', '', '', ''),
(101033, 'monitor 24pol', 750.00, 'monitoer gamer 144hz 24 polegadas', 'https://images.tcdn.com.br/img/img_prod/15959/monitor_gamer_24_acer_nitro_qg240y_full_hd_180hz_1ms_95_srgb_hdr10_freesync_hdmi_displayport_19641_4_300f991d3fb15dc19018d5fa59aa853a.jpg', 4, '2024-11-05 19:00:10', '', '', ''),
(101034, 'psu corsair cx 750', 550.00, 'fonte de alimentaçao corsair cx 750 com selo 80 plus bronze', 'https://res.cloudinary.com/corsair-pwa/image/upload/f_auto,q_auto/v1665096094/akamai/landing/PSU-Family-Page/images/psu-with-badge-cx750f-rgb.png', 6, '2024-11-05 19:00:10', '', '', ''),
(101035, 'MOBO ASUS ROG STRIX B760-F', 1874.00, 'Salte para o futuro com o ROG Strix B760-F, uma atualização fantástica para a 13ª geração com sua solução de energia dominante e as melhores velocidades DDR5 da categoria', 'https://images1.kabum.com.br/produtos/fotos/404821/placa-mae-asus-rog-strix-b760-f-gaming-intel-lga-1700-atx-ddr5-wifi-90mb1ct0-m0eay0_1674050530_g.jpg', 7, '2024-11-05 19:00:10', '', '', ''),
(101036, 'MOBO ASUS ROG STRIX B760-F', 1874.00, 'Salte para o futuro com o ROG Strix B760-F, uma atualização fantástica para a 13ª geração com sua solução de energia dominante e as melhores velocidades DDR5 da categoria', 'https://images1.kabum.com.br/produtos/fotos/404821/placa-mae-asus-rog-strix-b760-f-gaming-intel-lga-1700-atx-ddr5-wifi-90mb1ct0-m0eay0_1674050530_g.jpg', 7, '2024-11-05 19:00:10', '', '', ''),
(101037, 'teste', 1.00, 'teste', 'https://images6.kabum.com.br/produtos/fotos/99686/gabinete-gamer-cooler-master-mastercase-cosmos-c700m-rgb-4-coolers-lateral-em-vidro-mcc-c700m-mg5n-s00__1547029494_g.jpg', 1, '2024-11-05 19:00:10', '', '', ''),
(101038, '123', 123.00, '123', 'https://victorvision.com.br/wp-content/uploads/2023/05/tipos-de-hardware-930x620.jpg', 1, '2024-11-05 19:00:10', '', '', ''),
(101039, '123', 123.00, '123', 'https://victorvision.com.br/wp-content/uploads/2023/05/tipos-de-hardware-930x620.jpg', 1, '2024-11-05 19:00:10', '', '', ''),
(101040, '123', 123.00, '123', 'https://victorvision.com.br/wp-content/uploads/2023/05/tipos-de-hardware-930x620.jpg', 1, '2024-11-05 19:01:14', '', '', ''),
(101041, '321', 6000.00, '123', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUTEhIWFRUXGBcaGBgXFxgXFxsaFxcdGxcXFx0aHSggGholHRoXITEhJSorLi4uGh8zODMtNygtLisBCgoKDg0NFQ8PFSsZFR0tLS0rLS0rNy0tKy0tKystKy0rLSsrLS0rNy0tKzcrKy0tLS0rKy0tLSsrNys3KysrK//AABEIALMBGQMBIgACEQEDEQH/xAAcAAEAAQUBAQAAAAAAAAAAAAAAAQIDBAUHBgj/xABBEAACAQICBgcECQMDBAMAAAABAgADEQQhBRIxQVFhBgcicYGRoRMysdEUI0JSYnKSwfAzQ+GCovEkNFOTVGOD/8QAFgEBAQEAAAAAAAAAAAAAAAAAAAEC/8QAFhEBAQEAAAAAAAAAAAAAAAAAABEB/9oADAMBAAIRAxEAPwDuMREBERAREQEREBERAREQEREBERAREQESGYAXJsBPL6Y6wdH4e4bECow+zR+sOW4leyp7yIHqYnHtMdclQ3GEwyrwesdY/oQgA/6jPDaW6X4vEkjEYupqn7C9hLcNVLAjvvA75pnpjgcLcVsSgYfYW9R/FUuR4zwumOuZBcYXDM346zBR36qXJHeyzlT4ejqA06oJ+6VKmY0sHpdM9PtIYjJ8S1NT9ij9UPNe35sZ6bql6aGlWbC4qsxp1SPZNUOsEqEkFSxzAfK27WHFpzOQRfKWD64icz6qenftwuDxL/XKLUnY51VA90k7aij9QF8yCZ0yZCIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiYukNJUaC61aqlJeLsFHhc5zxel+tjBUriiKmIb8I1Ev+Z7egMD30t4jEIilndUUbWYhQO8nKcN0x1rY6rcUvZ4dfwjXfxZ8vJRPG4/SFWu2tWqvVbcXYvbuubAd1pYO6aX6ztH0bhajV2G6iusv6zZPImeI0x1vYp7jD0adEfea9', 5, '2024-11-05 19:08:04', '', '', ''),
(101042, 'teste', 1234.00, '1234', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUTExMVFhUXGRkaGRgXGBsdGhoeGhoaGhseHhsZHSggGxslHR0bIzEiJSkrLi4uGCAzODMtNygtLisBCgoKDg0OGhAQGi0lHyUtLS0tKy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAKMBNgMBIgACEQEDEQH/xAAbAAACAgMBAAAAAAAAAAAAAAAEBQMGAAECB//EADoQAAECBQIEBAUDBAICAgMAAAECEQADEiExBEEFIlFhE3GBkQYyobHBQtHwFCNS4WLxM3IWkkOisv/EABgBAAMBAQAAAAAAAAAAAAAAAAABAgME/8QAHxEBAQEBAAMBAQEBAQAAAAAAAAERAhIhMQNBUYEi/9oADAMBAAIRAxEAPwC76U4tjfaB9VxhElXMoVZCHF+94XfHHEJumQlMsfMWrOB284oKApa6lqNRa5u/fyhcfnvup67z0sfFeOf1U2hYQjICme7WBMPeF6ZclKHDJUWU2Ds5EINBJTM1kslApBvZvl/V7xdE6ipbJul6SGx3iu7nqDib7qbTaVIJSDVLVYdUno/SItRpTKCqiAi5q29YJ0gABAwSd8EbiCpoRNllEwVJUGI7GMd/1rn+POuMfF61nwtMSkG1QyfLoIWFM5Ck80w1AVKIdjkDyhvxf4UOmUfDSVpWRQofMgi7H94k0Uy5Sp6ktzDe+DG+yT/yxy2+z7QmlLqAK6RU0OpE1JQLOD1/MI5M6piCHO21vzB8qeCrlwALdz94563gpWlCSyBYly20LeP8YTpkczKLcqXuT37Q0kTbM/MMmKz8X/DPjHx5blQ+ZPUDp0MVzJb7Lq2T0qeq1MzWznVMSKRypdgOw7wVwrQrTUCagWLE4YuG6RFwDRJfxCCCD8phkJM3+pCkpZ9xht3jfq/yMeZ/', 3, '2024-11-05 23:50:07', '', '', ''),
(101043, 'gtx 750 ti', 450.00, 'placa guerreira', 'https://cdn.shoppub.io/cdn-cgi/image/w=1000,h=1000,q=80,f=auto/oficinadosbits/media/uploads/produtos/foto/wtuutpyr/file.png', 1, '2024-11-05 23:52:59', '', '', ''),
(101044, 'rx 7700', 1900.00, 'dla', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQCmnkGL3RGp7Z5bjIpak-BBcSqoZUX1e_YZQ&s', 1, '2024-11-05 23:58:46', '', '', ''),
(101045, 'gtx 1060', 800.00, 'gtx 1060', 'https://m.media-amazon.com/images/I/81bkywxwHAL.jpg', 1, '2024-11-06 01:00:05', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSITR2F6ochHIQ_WgBHD8rm2AvmuBpl6nom0lg5JypnlBN2pkcqA1JWRQj6z30X6o-QHTg&usqp=CAU', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTLia9xU1GtfMQeaCyavUDO4jveHrGVL4ysV2UOq6Un_75xIxm-3TyizHj-9YSrq5K5xjU&usqp=CAU', 'gtx 1060');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio_insercao`
--

CREATE TABLE `relatorio_insercao` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `data_insercao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio_venda`
--

CREATE TABLE `relatorio_venda` (
  `id` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `metodo_pagamento` enum('credito','boleto','pix') NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `data_venda` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `relatorio_venda`
--

INSERT INTO `relatorio_venda` (`id`, `id_produto`, `nome`, `valor`, `quantidade`, `metodo_pagamento`, `imagem`, `data_venda`) VALUES
(20, 101043, 'gtx 750 ti', 450.00, 1, '', 'https://cdn.shoppub.io/cdn-cgi/image/w=1000,h=1000,q=80,f=auto/oficinadosbits/media/uploads/produtos/foto/wtuutpyr/file.png', '2024-11-06 06:54:55'),
(21, 101043, 'gtx 750 ti', 450.00, 1, '', 'https://cdn.shoppub.io/cdn-cgi/image/w=1000,h=1000,q=80,f=auto/oficinadosbits/media/uploads/produtos/foto/wtuutpyr/file.png', '2024-11-06 06:57:38'),
(22, 101044, 'rx 7700', 1900.00, 1, '', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQCmnkGL3RGp7Z5bjIpak-BBcSqoZUX1e_YZQ&s', '2024-11-06 06:59:00'),
(23, 101045, 'gtx 1060', 800.00, 1, '', 'https://m.media-amazon.com/images/I/81bkywxwHAL.jpg', '2024-11-06 08:01:46'),
(24, 101045, 'gtx 1060', 800.00, 1, '', 'https://m.media-amazon.com/images/I/81bkywxwHAL.jpg', '2024-11-06 08:02:19'),
(25, 101045, 'gtx 1060', 800.00, 2, '', 'https://m.media-amazon.com/images/I/81bkywxwHAL.jpg', '2024-11-06 08:03:08');

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
(4, 4, 25, 101032),
(5, 1, 26, 101037),
(6, 1, 27, 101037),
(7, 1, 28, 101038),
(8, 1, 29, 101037),
(9, 1, 30, 101037),
(10, 1, 31, 101037),
(11, 1, 32, 101037),
(12, 1, 33, 101039),
(13, 1, 34, 101041),
(14, 1, 35, 101041),
(15, 1, 36, 101041),
(16, 1, 37, 101039),
(17, 1, 38, 101040),
(18, 2, 39, 101036),
(19, 2, 40, 101036),
(20, 2, 41, 101039),
(21, 2, 42, 101040),
(22, 2, 43, 101037),
(23, 2, 44, 101036),
(24, 2, 45, 101037),
(25, 2, 46, 101037),
(26, 2, 47, 101037),
(27, 2, 48, 101037),
(28, 2, 49, 101037),
(29, 1, 50, 101042),
(30, 1, 51, 101043),
(31, 1, 52, 101043),
(32, 1, 53, 101043),
(33, 1, 54, 101044),
(34, 1, 55, 101045),
(35, 1, 56, 101045),
(36, 1, 57, 101045);

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
(4, 'felipe', 'felipe@gmail.com', '123', '$2y$10$rTEz6FIBA59f9ym1oYIJeufFSWWVqgZeFCq3kU8sNJiIwawP3FqU.', '2024-11-03', 'guarapuava', 'pa', 'rua sandero 2014', NULL),
(5, 'drako', 'drakkinho66@gmail.com', '123456789', '$2y$10$5xTMOikwcZ/3d9NSrYgSF.5HYGuENvV761yy2GrFC3ZL0FCxIKSte', '1853-05-19', 'arapongas', 'ce', 'rua severino', NULL),
(6, 'felipe', 'domareskifelipe44@gmail.com', '123456789', '$2y$10$xxAlIVSXNH8gvv1M.mlUn.5I/ppMy8yjdmTnTXUuZgCjtcvp/egCm', '2024-11-04', 'guarapuava', 'es', 'xiatado', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `relatorio_insercao`
--
ALTER TABLE `relatorio_insercao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `relatorio_venda`
--
ALTER TABLE `relatorio_venda`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101046;

--
-- AUTO_INCREMENT de tabela `relatorio_insercao`
--
ALTER TABLE `relatorio_insercao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatorio_venda`
--
ALTER TABLE `relatorio_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`);

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
