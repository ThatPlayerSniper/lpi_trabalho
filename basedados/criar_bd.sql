-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Jul-2025 às 19:23
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

CREATE DATABASE IF NOT EXISTS `felixbusdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `felixbusdb`;
  
-- --------------------------------------------------------

--
-- Estrutura da tabela `alertas`
--

CREATE TABLE `alertas` (
  `id_alerta` int(255) NOT NULL,
  `id_utilizador` int(255) NOT NULL,
  `tipo_alerta` enum('promocao','cancelamento','manutencao','alteracao_rota','outro') NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_criacao` date NOT NULL DEFAULT curdate(),
  `data_expira` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `alertas`
--

INSERT INTO `alertas` (`id_alerta`, `id_utilizador`, `tipo_alerta`, `descricao`, `data_criacao`, `data_expira`) VALUES
(1, 1, 'promocao', 'Desconto de 25% na rota Lisboa-Guarda durante o fim de semana', '2025-05-19', '2025-05-29'),
(2, 1, 'cancelamento', 'Cancelamento do serviço Lisboa-Guarda no dia 25/05/2025 devido a greve', '2025-05-19', '2025-05-26'),
(3, 1, 'manutencao', 'Manutenção na linha férrea entre Castelo Branco e Covilhã. Possíveis atrasos na rota Lisboa-Guarda', '2025-05-19', '2025-06-15'),
(4, 1, 'alteracao_rota', 'Alteração temporária no percurso Lisboa-Guarda com paragem adicional em Abrantes', '2025-05-19', '2025-07-01'),
(5, 1, 'promocao', 'Bilhetes de ida e volta Lisboa-Guarda com 30% de desconto para estudantes', '2025-05-19', '2025-06-30'),
(6, 1, 'outro', 'Novo serviço expresso Lisboa-Guarda sem paragens intermédias disponível a partir de 01/06/2025', '2025-05-19', '2025-06-02'),
(7, 1, 'manutencao', 'Obras na estação da Guarda. Embarque e desembarque temporariamente transferidos para plataforma alternativa', '2025-05-19', '2025-05-31'),
(8, 1, 'alteracao_rota', 'Desvio temporário na rota Lisboa-Guarda devido a obras na Serra da Estrela', '2025-05-19', '2025-06-20'),
(9, 1, 'cancelamento', 'Suspensão do serviço noturno Lisboa-Guarda entre 01/06/2025 e 05/06/2025', '2025-05-19', '2025-06-06'),
(10, 1, 'outro', 'Novo sistema de reserva online para viagens Lisboa-Guarda disponível no site e aplicação móvel', '2025-05-19', '2025-06-18'),
(11, 1, 'promocao', 'Dia de S.Valentim!! Desconto de 10% na compra de 2 bilhetes, pertinente a bilhetes entre 8 e 15 de fevereiro.', '2025-06-11', '2026-02-15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `bilhete`
--

CREATE TABLE `bilhete` (
  `id_bilhete` int(255) NOT NULL,
  `nome_cliente` varchar(255) NOT NULL,
  `data_compra` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_bilhete` enum('ativo','desativado') NOT NULL DEFAULT 'ativo',
  `id_rota` int(255) NOT NULL,
  `id_viagem` int(255) NOT NULL,
  `id_utilizador` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carteira`
--

CREATE TABLE `carteira` (
  `id_carteira` int(255) NOT NULL,
  `id_utilizador` int(255) NOT NULL,
  `saldo_atual` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `carteira`
--

INSERT INTO `carteira` (`id_carteira`, `id_utilizador`, `saldo_atual`) VALUES
(1, 1, 3091.00),
(2, 2, 487.50),
(3, 3, 3.50),
(4, 4, 0.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `rota`
--

CREATE TABLE `rota` (
  `id_rota` int(255) NOT NULL,
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
(10, 'Funchal', 'Câmara de Lobos', '00:20:00', 9.50),
(11, 'Braga', 'Castelo Branco', '05:00:00', 500.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id_transacao` int(255) NOT NULL,
  `id_utilizador` int(255) NOT NULL,
  `tipo_transacao` enum('deposito','levantamento','compra_bilhete','reembolso','transferencia','outro') NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `saldo_apos_transacao` decimal(10,2) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `id_bilhete` int(255) DEFAULT NULL,
  `data_transacao` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('processada','pendente','cancelada','erro') NOT NULL DEFAULT 'processada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE `utilizador` (
  `id_utilizador` int(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `secretpass` varchar(256) NOT NULL,
  `data_registo` date NOT NULL DEFAULT curdate(),
  `cargo` enum('cliente','funcionario','admin') DEFAULT 'cliente',
  `estado_conta` enum('pendente','registado','rejeitado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`id_utilizador`, `nome`, `endereco`, `secretpass`, `data_registo`, `cargo`, `estado_conta`) VALUES
(1, 'FelixBus', 'felixbus@email.com', '86543c121b376d421caf0f90ac4eacd20aac1b8b1f895ae362be3ca7b84f6440', '2025-01-01', 'admin', 'registado'),
(2, 'cliente', 'cliente@email.com', 'a60b85d409a01d46023f90741e01b79543a3cb1ba048eaefbe5d7a63638043bf', '2025-03-03', 'cliente', 'registado'),
(3, 'funcionario', 'funcionario@email.com', '24d96a103e8552cb162117e5b94b1ead596b9c0a94f73bc47f7d244d279cacf2', '2025-03-03', 'funcionario', 'registado'),
(4, 'admin', 'admin@email.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '2025-03-03', 'admin', 'registado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagem`
--

CREATE TABLE `viagem` (
  `id_viagem` int(255) NOT NULL,
  `id_rota` int(11) NOT NULL,
  `data_viagem` date NOT NULL,
  `estado` enum('disponivel','reservado','vendido','cancelado','utilizado') NOT NULL DEFAULT 'disponivel',
  `id_viatura` int(11) NOT NULL,
  `hora_partida` time NOT NULL,
  `hora_chegada` time NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `lugares_ocupados` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `viagem`
--

INSERT INTO `viagem` (`id_viagem`, `id_rota`, `data_viagem`, `estado`, `id_viatura`, `hora_partida`, `hora_chegada`, `preco`, `lugares_ocupados`) VALUES
(1, 1, '2025-06-01', 'disponivel', 1, '07:00:00', '08:30:00', 12.50, 5),
(2, 2, '2025-06-02', 'disponivel', 2, '08:15:00', '09:45:00', 11.00, 0),
(3, 3, '2025-06-03', 'disponivel', 3, '09:30:00', '11:00:00', 13.75, 0),
(4, 4, '2025-06-04', 'disponivel', 1, '06:45:00', '08:15:00', 10.00, 0),
(5, 5, '2025-06-05', 'disponivel', 2, '07:30:00', '09:00:00', 14.20, 0),
(6, 6, '2025-06-06', 'disponivel', 3, '10:00:00', '11:30:00', 17.00, 0),
(7, 7, '2025-06-07', 'disponivel', 1, '11:15:00', '12:45:00', 15.90, 0),
(8, 8, '2025-06-08', 'disponivel', 2, '13:00:00', '14:30:00', 16.50, 0),
(9, 9, '2025-06-09', 'disponivel', 3, '14:30:00', '16:00:00', 12.30, 0),
(10, 10, '2025-06-10', 'disponivel', 1, '15:45:00', '17:15:00', 17.80, 0),
(11, 1, '2025-06-13', 'disponivel', 8, '16:30:00', '21:30:00', 10.00, 0),
(12, 1, '2025-06-13', 'disponivel', 4, '14:06:00', '19:06:00', 10.00, 0),
(13, 1, '2025-06-12', 'disponivel', 4, '17:06:00', '22:06:00', 13.00, 0),
(14, 1, '2025-06-19', 'disponivel', 3, '17:08:00', '19:20:00', 12.00, 0),
(15, 1, '2025-02-10', 'disponivel', 6, '19:29:00', '03:29:00', 55.00, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `viatura`
--

CREATE TABLE `viatura` (
  `id_viatura` int(255) NOT NULL,
  `capacidade_lugares` int(11) NOT NULL DEFAULT 10,
  `matricula` varchar(12) NOT NULL
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
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `bilhete`
--
ALTER TABLE `bilhete`
  ADD PRIMARY KEY (`id_bilhete`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `id_viagem` (`id_viagem`),
  ADD KEY `id_rota` (`id_rota`);

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
-- Índices para tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id_transacao`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `id_bilhete` (`id_bilhete`);

--
-- Índices para tabela `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id_utilizador`);

--
-- Índices para tabela `viagem`
--
ALTER TABLE `viagem`
  ADD PRIMARY KEY (`id_viagem`),
  ADD KEY `id_rota` (`id_rota`),
  ADD KEY `id_viatura` (`id_viatura`);

--
-- Índices para tabela `viatura`
--
ALTER TABLE `viatura`
  ADD PRIMARY KEY (`id_viatura`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `bilhete`
--
ALTER TABLE `bilhete`
  MODIFY `id_bilhete` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `carteira`
--
ALTER TABLE `carteira`
  MODIFY `id_carteira` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `rota`
--
ALTER TABLE `rota`
  MODIFY `id_rota` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id_transacao` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id_utilizador` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `viagem`
--
ALTER TABLE `viagem`
  MODIFY `id_viagem` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `viatura`
--
ALTER TABLE `viatura`
  MODIFY `id_viatura` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`);

--
-- Limitadores para a tabela `bilhete`
--
ALTER TABLE `bilhete`
  ADD CONSTRAINT `bilhete_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`),
  ADD CONSTRAINT `bilhete_ibfk_2` FOREIGN KEY (`id_viagem`) REFERENCES `viagem` (`id_viagem`),
  ADD CONSTRAINT `fk_bilhete_rota` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`);

--
-- Limitadores para a tabela `carteira`
--
ALTER TABLE `carteira`
  ADD CONSTRAINT `carteira_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`);

--
-- Limitadores para a tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizador` (`id_utilizador`),
  ADD CONSTRAINT `transacoes_ibfk_2` FOREIGN KEY (`id_bilhete`) REFERENCES `bilhete` (`id_bilhete`);

--
-- Limitadores para a tabela `viagem`
--
ALTER TABLE `viagem`
  ADD CONSTRAINT `viagem_ibfk_1` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`),
  ADD CONSTRAINT `viagem_ibfk_2` FOREIGN KEY (`id_viatura`) REFERENCES `viatura` (`id_viatura`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
