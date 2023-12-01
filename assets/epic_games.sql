-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/11/2023 às 18:37
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `epic_games`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `games`
--

CREATE TABLE `games` (
  `id_game` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `data_criacao` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `games`
--

INSERT INTO `games` (`id_game`, `nome`, `preco`, `data_criacao`) VALUES
(1, 'God Of War', 130.00, '2023-11-15'),
(4, 'Mario World', 70.00, '2023-11-17'),
(5, 'Minecraft', 30.00, '2023-11-24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `admin` varchar(1) NOT NULL DEFAULT 'N',
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `token` text NOT NULL,
  `data_criacao` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `admin`, `nome`, `email`, `senha`, `token`, `data_criacao`) VALUES
(8, 'N', 'mariana', 'mariana@mariana.com', '$2y$10$6suO92QgX35yLI5vLbrS3eVQ55cwXSa2GGmNP1ZBtyWHV5soKf/N6', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDA1MDUzMTcsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvZXBpY19nYW1lcy8iLCJuYmYiOjE3MDA1MDUzMTcsImV4cCI6MTcwMDUwODkxNywic3ViIjoibWFyaWFuYUBtYXJpYW5hLmNvbSJ9.jdKO7b3tEsVXi0FtyK_KAAc9wD3iQ8MvobiJRsK9z5k', '2023-11-20'),
(9, 'N', 'leandro', 'leandro@leandro.com', '$2y$10$kI1GHXxshktYbiqmD2F2mu3C44UdmHkBMVfhrDUCVFw9FtxhZd3jC', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDA4MjYwNjMsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvZXBpY19nYW1lcy8iLCJuYmYiOjE3MDA4MjYwNjMsImV4cCI6MTcwMDgyOTY2Mywic3ViIjoibGVhbmRyb0BsZWFuZHJvLmNvbSJ9.bb-zk7WIGzTToNG-uvgy5xwOUutfrP3r45eDi3milvw', '2023-11-20'),
(10, 'S', 'tiago henrique', 'tiago@tiago.com', '$2y$10$9RFQfw7TT9vmFQ0U.7LHWO37MGJx1WxVsHlOkz4C8UOM03XayzGaK', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE3MDA4NDY2ODQsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvZXBpY19nYW1lcy8iLCJuYmYiOjE3MDA4NDY2ODQsImV4cCI6MTcwMDg1MDI4NCwic3ViIjoidGlhZ29AdGlhZ28uY29tIn0.yICGy0Qx4AG8CjiHmzFNODs_j5Mcf9p6xi7S--1FyPM', '2023-11-24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id_venda` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_venda` datetime NOT NULL DEFAULT current_timestamp(),
  `quantidade` int(11) NOT NULL,
  `preco_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `venda`
--

INSERT INTO `venda` (`id_venda`, `id_game`, `id_usuario`, `data_venda`, `quantidade`, `preco_total`) VALUES
(11, 4, 8, '2023-11-20 08:58:31', 5, 350.00),
(12, 1, 8, '2023-11-20 08:58:47', 5, 650.00),
(13, 1, 9, '2023-11-20 15:56:02', 1, 130.00),
(14, 1, 9, '2023-11-20 16:04:43', 1, 130.00),
(15, 1, 9, '2023-11-20 16:04:48', 1, 130.00),
(16, 1, 10, '2023-11-24 14:24:55', 1, 130.00),
(17, 1, 10, '2023-11-24 14:26:02', 10, 1300.00),
(18, 1, 10, '2023-11-24 14:29:28', 10, 1300.00),
(19, 1, 10, '2023-11-24 14:29:34', 10, 1339.00),
(20, 1, 10, '2023-11-24 14:31:19', 10, 1339.00),
(21, 1, 10, '2023-11-24 14:31:32', 10, 1300.00);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_venda_games`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_venda_games` (
`id_game` int(11)
,`nome_game` varchar(255)
,`id_usuario` int(11)
,`nome_usuario` varchar(255)
,`email_usuario` varchar(255)
,`quantidade` int(11)
,`preco_total` decimal(10,2)
,`data_venda` datetime
);

-- --------------------------------------------------------

--
-- Estrutura para view `vw_venda_games`
--
DROP TABLE IF EXISTS `vw_venda_games`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_venda_games`  AS SELECT `games`.`id_game` AS `id_game`, `games`.`nome` AS `nome_game`, `usuario`.`id_usuario` AS `id_usuario`, `usuario`.`nome` AS `nome_usuario`, `usuario`.`email` AS `email_usuario`, `venda`.`quantidade` AS `quantidade`, `venda`.`preco_total` AS `preco_total`, `venda`.`data_venda` AS `data_venda` FROM ((`venda` left join `games` on(`venda`.`id_game` = `games`.`id_game`)) left join `usuario` on(`venda`.`id_usuario` = `venda`.`id_usuario`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id_game`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id_venda`),
  ADD UNIQUE KEY `id_game` (`id_game`,`id_usuario`,`data_venda`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `games`
--
ALTER TABLE `games`
  MODIFY `id_game` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `games` (`id_game`),
  ADD CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
