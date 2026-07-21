/*
phpMyAdmin SQL Dump
version 5.2.1
https://www.phpmyadmin.net/

Host: 127.0.0.1
Tempo de geração: 19/05/2026 às 13:35
Versão do servidor: 10.4.32-MariaDB
Versão do PHP: 8.2.12
*/

DROP DATABASE IF EXISTS `sistema_bd_indexpedia`;
CREATE DATABASE IF NOT EXISTS `sistema_bd_indexpedia` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_bd_indexpedia`;

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

/* Banco de dados: `sistema_bd_indexpedia` */

/* -------------------------------------------------------- */

/* Estrutura para tabela `artigo` */
CREATE TABLE `artigo` (
  `artigo_id` int(11) NOT NULL,
  `artigo_titulo` varchar(200) NOT NULL,
  `artigo_breve_descricao` varchar(300) NOT NULL,
  `artigo_data_criacao` datetime DEFAULT current_timestamp(),
  `artigo_status` enum('pendente','em análise','aprovado','rejeitado') NOT NULL DEFAULT 'pendente',
  `artigo_autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `artigobloco` */
CREATE TABLE `artigobloco` (
  `bloco_id` int(11) NOT NULL,
  `artigo_id` int(11) NOT NULL,
  `bloco_versao_id` int(11) NOT NULL,
  `bloco_tipo` enum('texto','imagem') NOT NULL DEFAULT 'texto',
  `bloco_conteudo` text NOT NULL,
  `bloco_ordem_atual` int(11) NOT NULL,
  `bloco_tamanho` varchar(10) NOT NULL DEFAULT 'w-100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `artigocategorias` */
CREATE TABLE `artigocategorias` (
  `artigo_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `categoriaartigos` */
CREATE TABLE `categoriaartigos` (
  `categoria_id` int(11) NOT NULL,
  `categoria_nome` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `comentario` */
CREATE TABLE `comentario` (
  `comentario_id` int(11) NOT NULL,
  `comentario_autor` int(11) NOT NULL,
  `comentario_artigo` int(11) NOT NULL,
  `comentario_conteudo` text NOT NULL,
  `comentario_data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `usuario` */
CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL,
  `usuario_nome_fixo` varchar(50) NOT NULL,
  `usuario_nome_exibicao` varchar(255) DEFAULT `usuario_nome_fixo`,
  `usuario_email` varchar(254) NOT NULL,
  `usuario_senha` varchar(255) NOT NULL,
  `usuario_codigo_verificacao` varchar(32) DEFAULT NULL,
  `usuario_verificado` tinyint(1) NOT NULL DEFAULT 0,
  `usuario_nivel` enum('admin','curador','usuário') NOT NULL DEFAULT 'usuário',
  `usuario_foto_perfil` varchar(255) DEFAULT NULL,
  `usuario_data_criacao` datetime DEFAULT current_timestamp(),
  `usuario_data_modificacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `usuarioartigoscurtidos` */
CREATE TABLE `usuarioartigoscurtidos` (
  `usuario_id` int(11) NOT NULL,
  `artigo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `usuarioartigossalvos` */
CREATE TABLE `usuarioartigossalvos` (
  `usuario_id` int(11) NOT NULL,
  `artigo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `usuarioseguidores` */
CREATE TABLE `usuarioseguidores` (
  `seguidor_usuario_id` int(11) NOT NULL,
  `usuario_seguindo_id` int(11) NOT NULL,
  `data_seguimento` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* -------------------------------------------------------- */

/* Estrutura para tabela `versoes` */
CREATE TABLE `versoes` (
  `versao_id` int(11) NOT NULL,
  `artigo_id` int(11) NOT NULL,
  `numero_versao` int(11) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `nota_edicao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* Índices para tabelas despejadas */

/* Índices de tabela `artigo` */
ALTER TABLE `artigo`
  ADD PRIMARY KEY (`artigo_id`),
  ADD KEY `artigo_autor` (`artigo_autor`);

/* Índices de tabela `artigobloco` */
ALTER TABLE `artigobloco`
  ADD PRIMARY KEY (`bloco_id`),
  ADD UNIQUE KEY `bloco_versao_id` (`bloco_versao_id`,`bloco_ordem_atual`),
  ADD KEY `artigo_id` (`artigo_id`);

/* Índices de tabela `artigocategorias` */
ALTER TABLE `artigocategorias`
  ADD PRIMARY KEY (`artigo_id`,`categoria_id`),
  ADD KEY `categoria_id` (`categoria_id`);

/* Índices de tabela `categoriaartigos` */
ALTER TABLE `categoriaartigos`
  ADD PRIMARY KEY (`categoria_id`),
  ADD UNIQUE KEY `categoria_nome` (`categoria_nome`);

/* Índices de tabela `comentario` */
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`comentario_id`),
  ADD KEY `comentario_autor` (`comentario_autor`),
  ADD KEY `comentario_artigo` (`comentario_artigo`);

/* Índices de tabela `usuario` */
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `usuario_nome_fixo` (`usuario_nome_fixo`),
  ADD UNIQUE KEY `usuario_email` (`usuario_email`);

/* Índices de tabela `usuarioartigoscurtidos` */
ALTER TABLE `usuarioartigoscurtidos`
  ADD PRIMARY KEY (`usuario_id`,`artigo_id`),
  ADD KEY `artigo_id` (`artigo_id`);

/* Índices de tabela `usuarioartigossalvos` */
ALTER TABLE `usuarioartigossalvos`
  ADD PRIMARY KEY (`usuario_id`,`artigo_id`),
  ADD KEY `artigo_id` (`artigo_id`);

/* Índices de tabela `usuarioseguidores` */
ALTER TABLE `usuarioseguidores`
  ADD PRIMARY KEY (`seguidor_usuario_id`,`usuario_seguindo_id`),
  ADD KEY `usuario_seguindo_id` (`usuario_seguindo_id`);

/* Índices de tabela `versoes` */
ALTER TABLE `versoes`
  ADD PRIMARY KEY (`versao_id`),
  ADD UNIQUE KEY `artigo_id` (`artigo_id`,`numero_versao`);

/* AUTO_INCREMENT para tabelas despejadas */

/* AUTO_INCREMENT de tabela `artigo` */
ALTER TABLE `artigo`
  MODIFY `artigo_id` int(11) NOT NULL AUTO_INCREMENT;

/* AUTO_INCREMENT de tabela `artigobloco` */
ALTER TABLE `artigobloco`
  MODIFY `bloco_id` int(11) NOT NULL AUTO_INCREMENT;

/* AUTO_INCREMENT de tabela `categoriaartigos` */
ALTER TABLE `categoriaartigos`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT;

/* AUTO_INCREMENT de tabela `comentario` */
ALTER TABLE `comentario`
  MODIFY `comentario_id` int(11) NOT NULL AUTO_INCREMENT;

/* AUTO_INCREMENT de tabela `usuario` */
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT;

/* AUTO_INCREMENT de tabela `versoes` */
ALTER TABLE `versoes`
  MODIFY `versao_id` int(11) NOT NULL AUTO_INCREMENT;

/* Restrições para tabelas despejadas */

/* Restrições para tabelas `artigo` */
ALTER TABLE `artigo`
  ADD CONSTRAINT `artigo_ibfk_1` FOREIGN KEY (`artigo_autor`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE;

/* Restrições para tabelas `artigobloco` */
ALTER TABLE `artigobloco`
  ADD CONSTRAINT `artigobloco_ibfk_1` FOREIGN KEY (`artigo_id`) REFERENCES `artigo` (`artigo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `artigobloco_ibfk_2` FOREIGN KEY (`bloco_versao_id`) REFERENCES `versoes` (`versao_id`) ON DELETE CASCADE;

/* Restrições para tabelas `artigocategorias` */
ALTER TABLE `artigocategorias`
  ADD CONSTRAINT `artigocategorias_ibfk_1` FOREIGN KEY (`artigo_id`) REFERENCES `artigo` (`artigo_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `artigocategorias_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoriaartigos` (`categoria_id`) ON DELETE CASCADE;

/* Restrições para tabelas `comentario` */
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`comentario_autor`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`comentario_artigo`) REFERENCES `artigo` (`artigo_id`) ON DELETE CASCADE;

/* Restrições para tabelas `usuarioartigoscurtidos` */
ALTER TABLE `usuarioartigoscurtidos`
  ADD CONSTRAINT `usuarioartigoscurtidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarioartigoscurtidos_ibfk_2` FOREIGN KEY (`artigo_id`) REFERENCES `artigo` (`artigo_id`) ON DELETE CASCADE;

/* Restrições para tabelas `usuarioartigossalvos` */
ALTER TABLE `usuarioartigossalvos`
  ADD CONSTRAINT `usuarioartigossalvos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarioartigossalvos_ibfk_2` FOREIGN KEY (`artigo_id`) REFERENCES `artigo` (`artigo_id`) ON DELETE CASCADE;

/* Restrições para tabelas `usuarioseguidores` */
ALTER TABLE `usuarioseguidores`
  ADD CONSTRAINT `usuarioseguidores_ibfk_1` FOREIGN KEY (`seguidor_usuario_id`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarioseguidores_ibfk_2` FOREIGN KEY (`usuario_seguindo_id`) REFERENCES `usuario` (`usuario_id`) ON DELETE CASCADE;

/* Restrições para tabelas `versoes` */
ALTER TABLE `versoes`
  ADD CONSTRAINT `versoes_ibfk_1` FOREIGN KEY (`artigo_id`) REFERENCES `artigo` (`artigo_id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
