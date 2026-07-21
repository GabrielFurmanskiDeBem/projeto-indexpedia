/*
phpMyAdmin SQL Dump
version 5.2.1
https://www.phpmyadmin.net/

Host: 127.0.0.1
Tempo de geração: 19/05/2026 às 13:37
Versão do servidor: 10.4.32-MariaDB
Versão do PHP: 8.2.12
*/
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

/* Banco de dados: `sistema_bd_indexpedia` */

/* Despejando dados para a tabela `usuario` */
INSERT INTO `usuario` (`usuario_id`, `usuario_nome_fixo`, `usuario_nome_exibicao`, `usuario_email`, `usuario_senha`, `usuario_nivel`, `usuario_data_criacao`) VALUES
(1, 'Usuário01', 'Vanessa Administradora', 'admin@endereco.com', 'senha123', 'admin', '2026-05-19 08:34:46'),
(2, 'Usuário02', 'Mariza Curadora', 'curador@endereco.com', 'senha123', 'curador', '2026-05-19 08:34:46'),
(3, 'Usuário03', 'Lorenzo Machado Neves', 'lorenzo@endereco.com', 'senha123', 'usuário', '2026-05-19 08:34:46'),
(4, 'Usuário04', 'Pamela Gonçalves de Freit', 'pamela@endereco.com', 'senha123', 'usuário', '2026-05-19 08:34:46');

/* Despejando dados para a tabela `categoriaartigos` */
INSERT INTO `categoriaartigos` (`categoria_id`, `categoria_nome`) VALUES
(3, 'Artes'),
(5, 'Astrologia'),
(4, 'Ciências da Natureza'),
(6, 'Cultura'),
(2, 'História do Mundo'),
(1, 'Tecnologia');

/* Despejando dados para a tabela `artigo` */
INSERT INTO `artigo` (`artigo_id`, `artigo_titulo`, `artigo_breve_descricao`, `artigo_data_criacao`, `artigo_status`, `artigo_autor`) VALUES
(1, 'A Revolução Russa', 'Queda do Imperialismo e criação do primeiro Estado socialista.', '2026-05-19 08:34:46', 'aprovado', 3),
(2, 'Buraco Negro', 'Estudo de características, descoberta e fatores do Buraco Negro.', '2026-05-19 08:34:46', 'aprovado', 4),
(3, 'Inteligências Artificais (AI)', 'O que é uma Inteligência Artificial? O que ela pode fazer?', '2026-05-19 08:34:46', 'em análise', 4),
(4, 'História do Teatro', 'Conheça a história de uma das Artes mais importantes do mundo.', '2026-05-19 08:34:46', 'pendente', 3),
(5, 'A Religião Cristã', 'A Religião no qual possui mais fiéis em relação ao resto das Religiões!', '2026-05-19 08:34:46', 'rejeitado', 4);

/* Despejando dados para a tabela `versoes` */
INSERT INTO `versoes` (`versao_id`, `artigo_id`, `numero_versao`, `data_criacao`, `nota_edicao`) VALUES
(1, 1, 1, '2026-05-19 08:34:46', 'Criação do Artigo Revolução Russa'),
(2, 1, 2, '2026-05-19 08:34:46', 'Edição do Artigo Revolução Russa, Adição de Informações'),
(3, 2, 1, '2026-05-19 08:34:46', 'Criação do Artigo Buraco Negro'),
(4, 3, 1, '2026-05-19 08:34:46', 'Criação do Artigo Inteligências Artificiais (AI)'),
(5, 4, 1, '2026-05-19 08:34:46', 'Criação do Artigo História do Teatro');

/* Despejando dados para a tabela `artigobloco`*/
INSERT INTO `artigobloco` (`bloco_id`, `artigo_id`, `bloco_versao_id`, `bloco_tipo`, `bloco_conteudo`, `bloco_ordem_atual`, `bloco_tamanho`) VALUES
(1, 1, 1, 'texto', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 1, 'w-100'),
(2, 1, 1, 'imagem', 'revolucao_russa.jpg', 2, 'w-50'),
(3, 1, 1, 'texto', 'Lorem ipsum dolor sit amet consectetur adipiscing elit.', 3, 'w-50'),
(4, 1, 2, 'texto', 'Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor.', 1, 'w-100'),
(5, 1, 2, 'texto', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.', 2, 'w-100'),
(6, 2, 3, 'texto', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.', 1, 'w-100'),
(7, 2, 3, 'imagem', 'buraco_negro.jpg', 2, 'w-100'),
(8, 3, 4, 'texto', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. ', 1, 'w-100'),
(9, 4, 5, 'texto', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.', 1, 'w-100'),
(10, 4, 5, 'texto', 'Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser necessi far uniform grammatica, pronunciation e plu sommun paroles.', 2, 'w-50'),
(11, 4, 5, 'imagem', 'historia_do_teatro.jpg', 3, 'w-50');

/* Despejando dados para a tabela `artigocategorias` */
INSERT INTO `artigocategorias` (`artigo_id`, `categoria_id`) VALUES
(1, 2),
(2, 4),
(2, 5),
(3, 1),
(4, 3),
(4, 6);

/* Despejando dados para a tabela `comentario` */
INSERT INTO `comentario` (`comentario_id`, `comentario_autor`, `comentario_artigo`, `comentario_conteudo`, `comentario_data_criacao`) VALUES
(1, 3, 1, 'Ótimo artigo!', '2026-05-19 08:34:46'),
(2, 4, 3, 'Recomendo para iniciantes que estão começando a entender a tecnologia.', '2026-05-19 08:34:46'),
(3, 1, 2, 'Estou aprendendo muitas coisas novas sobre Astrologia, e este artigo me faz querer estudar ainda mais sobre o espaço!', '2026-05-19 08:34:46');

/* Despejando dados para a tabela `usuarioartigoscurtidos` */
INSERT INTO `usuarioartigoscurtidos` (`usuario_id`, `artigo_id`) VALUES
(1, 2),
(1, 4),
(2, 3),
(2, 4),
(3, 2),
(3, 3),
(4, 1),
(4, 4);

/* Despejando dados para a tabela `usuarioartigossalvos` */
INSERT INTO `usuarioartigossalvos` (`usuario_id`, `artigo_id`) VALUES
(3, 2),
(3, 3),
(4, 1);

/* Despejando dados para a tabela `usuarioseguidores` */
INSERT INTO `usuarioseguidores` (`seguidor_usuario_id`, `usuario_seguindo_id`, `data_seguimento`) VALUES
(1, 3, '2026-05-19 08:34:46'),
(2, 3, '2026-05-19 08:34:46'),
(3, 1, '2026-05-19 08:34:46'),
(4, 1, '2026-05-19 08:34:46');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
