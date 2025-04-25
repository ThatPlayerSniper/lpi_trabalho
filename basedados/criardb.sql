-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Abr-2025 às 21:57
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `felixbusdb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `alertas`
--

CREATE TABLE `alertas` (
  `id_alerta` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `tipo_alerta` enum('promocao','cancelamento','manutencao','alteracao_rota','outro') NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_criacao` date NOT NULL DEFAULT curdate(),
  `lido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `bilhetes`
--

CREATE TABLE `bilhetes` (
  `id_bilhete` int(11) NOT NULL,
  `id_rota` int(11) NOT NULL,
  `data_viagem` date NOT NULL,
  `estado` enum('disponivel','reservado','vendido','cancelado','utilizado') NOT NULL DEFAULT 'disponivel',
  `lugares_comprados` int(11) NOT NULL,
  `id_veiculo` int(11) NOT NULL,
  `hora_partida` time NOT NULL,
  `hora_chegada` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carteira`
--

CREATE TABLE `carteira` (
  `id_carteira` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `saldo` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rota`
--

CREATE TABLE `rota` (
  `id_rota` int(11) NOT NULL,
  `origem` varchar(255) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `tempo_viagem` time NOT NULL,
  `distancia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `rota`
--

INSERT INTO `rota` (`id_rota`, `origem`, `destino`, `tempo_viagem`, `distancia`) VALUES
(1, 'Lisboa', 'Porto', '03:30:00', 313.50),
(2, 'Porto', 'Faro', '05:45:00', 554.20),
(3, 'Coimbra', 'Braga', '01:15:00', 120.75),
(4, 'Faro', 'Lisboa', '02:50:00', 278.90),
(5, 'Braga', 'Coimbra', '01:20:00', 125.30),
(6, 'Lisboa', 'Évora', '01:45:00', 130.40),
(7, 'Porto', 'Viana do Castelo', '01:10:00', 85.60),
(8, 'Faro', 'Albufeira', '00:45:00', 42.30),
(9, 'Aveiro', 'Viseu', '01:25:00', 95.80),
(10, 'Leiria', 'Santarém', '00:50:00', 65.20),
(11, 'Guarda', 'Castelo Branco', '01:35:00', 110.70),
(12, 'Setúbal', 'Beja', '01:55:00', 142.90),
(13, 'Porto', 'Aveiro', '00:40:00', 68.40),
(14, 'Bragança', 'Vila Real', '01:50:00', 135.20),
(15, 'Funchal', 'Câmara de Lobos', '00:25:00', 12.80);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE `utilizador` (
  `id_utilizador` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `secretpass` varchar(255) NOT NULL,
  `data_registo` date NOT NULL DEFAULT curdate(),
  `cargo` enum('cliente','funcionario','admin') DEFAULT 'cliente',
  `estado_conta` enum('pendente','registado','rejeitado') DEFAULT 'pendente' 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `viatura`
--

CREATE TABLE `viatura` (
  `id_veiculo` int(11) NOT NULL,
  `nome_viatura` varchar(255) NOT NULL,
  `capacidade_lugares` int(11) NOT NULL,
  `matricula` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id_alerta`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `bilhetes`
--
ALTER TABLE `bilhetes`
  ADD PRIMARY KEY (`id_bilhete`),
  ADD KEY `id_rota` (`id_rota`),
  ADD KEY `id_veiculo` (`id_veiculo`);

--
-- Índices para tabela `carteira`
--
ALTER TABLE `carteira`
  ADD PRIMARY KEY (`id_carteira`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `rota`
--
ALTER TABLE `rota`
  ADD PRIMARY KEY (`id_rota`);

--
-- Índices para tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD UNIQUE KEY `endereco` (`endereco`);

--
-- Índices para tabela `viatura`
--
ALTER TABLE `viatura`
  ADD PRIMARY KEY (`id_veiculo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bilhetes`
--
ALTER TABLE `bilhetes`
  MODIFY `id_bilhete` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carteira`
--
ALTER TABLE `carteira`
  MODIFY `id_carteira` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `rota`
--
ALTER TABLE `rota`
  MODIFY `id_rota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `viatura`
--
ALTER TABLE `viatura`
  MODIFY `id_veiculo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`);

--
-- Limitadores para a tabela `bilhetes`
--
ALTER TABLE `bilhetes`
  ADD CONSTRAINT `bilhetes_ibfk_1` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`),
  ADD CONSTRAINT `bilhetes_ibfk_2` FOREIGN KEY (`id_veiculo`) REFERENCES `viatura` (`id_veiculo`);

--
-- Limitadores para a tabela `carteira`
--
ALTER TABLE `carteira`
  ADD CONSTRAINT `carteira_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
