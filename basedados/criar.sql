-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07-Maio-2025 às 12:30
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
  `data_expira` date NOT NULL,
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
  `id_viatura` int(11) NOT NULL,
  `hora_partida` time NOT NULL,
  `hora_chegada` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_compra`
--

CREATE TABLE `historico_compra` (
  `id_compra` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `id_bilhete` int(11) NOT NULL
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
(1, 'Lisboa', 'Porto', '03:15:00', 313.00),
(2, 'Lisboa', 'Coimbra', '02:00:00', 205.00),
(3, 'Porto', 'Braga', '00:45:00', 54.00),
(4, 'Faro', 'Lisboa', '02:30:00', 278.00),
(5, 'Évora', 'Lisboa', '01:30:00', 132.00),
(6, 'Lisboa', 'Setúbal', '00:50:00', 50.00),
(7, 'Coimbra', 'Aveiro', '00:45:00', 66.00),
(8, 'Braga', 'Guimarães', '00:30:00', 25.00),
(9, 'Porto', 'Viseu', '01:45:00', 134.00),
(10, 'Funchal', 'Câmara de Lobos', '00:20:00', 9.50);

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
  `estado_conta` enum('pendente','registado','rejeitado') DEFAULT 'pendente',
  `saldo` float(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`id_utilizador`, `nome`, `endereco`, `secretpass`, `data_registo`, `cargo`, `estado_conta`, `saldo`) VALUES
(1, 'Ana Silva', 'Ana@gmail.com', 'senha123', '2025-05-07', 'cliente', 'registado', 25.50),
(2, 'Carlos Mendes', 'carlo@gmaill.com', 'segredo456', '2025-05-06', 'funcionario', 'pendente', 0.00),
(3, 'Maria Rocha', 'mari@gmail.com', 'adminpass789', '2025-05-01', 'admin', 'registado', 1000.00),
(4, 'João Costa', 'jao@gmail.com', 'joaopass321', '2025-04-25', 'cliente', 'rejeitado', 10.75);

-- --------------------------------------------------------

--
-- Estrutura da tabela `viatura`
--

CREATE TABLE `viatura` (
  `id_viatura` int(11) NOT NULL,
  `capacidade_lugares` int(11) NOT NULL DEFAULT 10,
  `matricula` varchar(12) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `viatura`
--

INSERT INTO `viatura` (`id_viatura`, `capacidade_lugares`, `matricula`) VALUES
(1, 9, 'AA-01-01'),
(2, 15, 'BB-02-02'),
(3, 20, 'CC-03-03'),
(4, 25, 'DD-04-04'),
(5, 30, 'EE-05-05'),
(6, 12, 'FF-06-06'),
(7, 50, 'GG-07-07'),
(8, 8, 'HH-08-08');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id_alerta`),
  ADD KEY `alertas_ibfk_1` (`id_utilizador`);

--
-- Índices para tabela `bilhetes`
--
ALTER TABLE `bilhetes`
  ADD PRIMARY KEY (`id_bilhete`),
  ADD KEY `fk_bilhete_rota` (`id_rota`),
  ADD KEY `fk_bilhete_viatura` (`id_viatura`);

--
-- Índices para tabela `historico_compra`
--
ALTER TABLE `historico_compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `fk_compra_utilizador` (`id_utilizador`),
  ADD KEY `fk_compra_bilhete` (`id_bilhete`);

--
-- Índices para tabela `rota`
--
ALTER TABLE `rota`
  ADD PRIMARY KEY (`id_rota`);

--
-- Índices para tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id_utilizador`);

--
-- Índices para tabela `viatura`
--
ALTER TABLE `viatura`
  ADD PRIMARY KEY (`id_viatura`);

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
-- AUTO_INCREMENT de tabela `historico_compra`
--
ALTER TABLE `historico_compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `rota`
--
ALTER TABLE `rota`
  MODIFY `id_rota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `viatura`
--
ALTER TABLE `viatura`
  MODIFY `id_viatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `fk_bilhete_rota` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`),
  ADD CONSTRAINT `fk_bilhete_viatura` FOREIGN KEY (`id_viatura`) REFERENCES `viatura` (`id_viatura`);

--
-- Limitadores para a tabela `historico_compra`
--
ALTER TABLE `historico_compra`
  ADD CONSTRAINT `fk_compra_bilhete` FOREIGN KEY (`id_bilhete`) REFERENCES `bilhetes` (`id_bilhete`),
  ADD CONSTRAINT `fk_compra_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
