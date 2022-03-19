-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 19-Mar-2022 às 03:02
-- Versão do servidor: 5.7.36
-- versão do PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `produtos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

DROP TABLE IF EXISTS `produto`;
CREATE TABLE IF NOT EXISTS `produto` (
  `idProduto` int(8) NOT NULL AUTO_INCREMENT,
  `produto` varchar(50) COLLATE utf8_bin NOT NULL,
  `sku` int(11) NOT NULL,
  `foto` varchar(255) COLLATE utf8_bin NOT NULL,
  `descricao` varchar(60) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`idProduto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `variacao`
--

DROP TABLE IF EXISTS `variacao`;
CREATE TABLE IF NOT EXISTS `variacao` (
  `idVariacao` int(11) NOT NULL AUTO_INCREMENT,
  `estoque` int(11) NOT NULL,
  `preco` int(11) NOT NULL,
  `tipo_variacao` varchar(20) COLLATE utf8_bin NOT NULL,
  `descricao_variacao` varchar(60) COLLATE utf8_bin NOT NULL,
  `idProduto` int(11) NOT NULL,
  PRIMARY KEY (`idVariacao`),
  KEY `fk_variacao_produto` (`idProduto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
