-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.24-log
-- Versão do PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Banco de Dados: `truda`
--

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_sys_config`
--

CREATE TABLE IF NOT EXISTS `tbl_sys_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL COMMENT 'invisible',
  `texto_kimonos` longtext NOT NULL COMMENT 'Texto (Home - Nossos Kimonos)',
  `url_video` varchar(255) NOT NULL COMMENT 'URL (youtube ou vimeo) vídeo da home',
  `endereco` longtext NOT NULL COMMENT 'Endereço',
  `email_contato` varchar(255) NOT NULL COMMENT 'E-mail para contato.',
  `telefone` varchar(255) NOT NULL COMMENT 'Telefone',
  `url_facebook` varchar(255) NOT NULL COMMENT 'invisible',
  `url_twitter` varchar(255) NOT NULL COMMENT 'invisible',
  `script_analytics` varchar(255) NOT NULL COMMENT 'Id do Google Analytics (ex : UA-99999999-1)',
  `data_ins` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'invisible',
  `ordem` int(11) NOT NULL COMMENT 'invisible',
  `ativo` enum('Sim','Não') NOT NULL DEFAULT 'Sim' COMMENT 'invisible',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tbl_sys_config`
--

INSERT INTO `tbl_sys_config` (`id`, `nome`, `texto_kimonos`, `url_video`, `endereco`, `email_contato`, `telefone`, `url_facebook`, `url_twitter`, `script_analytics`, `data_ins`, `ordem`, `ativo`) VALUES
(1, 'Configurações', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec feugiat sit amet ipsum non condimentum. Ut sed sem ut nunc sodales dignissim sed sit amet risus. Nam eu bibendum augue. Cras dictum eleifend massa in rutrum.', 'https://www.youtube.com/watch?v=2CId14ldQKk', 'Rua Atílio Giaretta, n° 40<br>\r\n   Bairro: Adelmo Corradine<br>\r\n   Itatiba/SP<br>\r\n   Cep: 13257-584', 'email.de.contato@dominio.com', '(11) 4534.2423', '', '', '', '2012-04-20 17:38:51', 0, 'Sim');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_sys_log_ips`
--

CREATE TABLE IF NOT EXISTS `tbl_sys_log_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` int(11) NOT NULL DEFAULT '0' COMMENT 'Usuário',
  `data` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Data',
  `hora` time NOT NULL DEFAULT '00:00:00' COMMENT 'Hora',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP',
  `logou` enum('Não','Sim') NOT NULL DEFAULT 'Não',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `tbl_sys_log_ips`
--

INSERT INTO `tbl_sys_log_ips` (`id`, `usuario`, `data`, `hora`, `ip`, `logou`) VALUES
(6, 1, '0000-00-00', '16:03:00', '127.0.0.1', ''),
(7, 1, '0000-00-00', '16:03:00', '127.0.0.1', ''),
(8, 1, '0000-00-00', '16:03:00', '127.0.0.1', 'Sim');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_sys_menu`
--

CREATE TABLE IF NOT EXISTS `tbl_sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL COMMENT 'Nome',
  `pagina` varchar(255) NOT NULL COMMENT 'Página',
  `class` varchar(255) NOT NULL COMMENT 'Classe (CSS)',
  `parent` mediumint(9) NOT NULL COMMENT 'Parent',
  `ordem` int(11) NOT NULL COMMENT 'invisible',
  `ativo` enum('Sim','Não') NOT NULL COMMENT 'Menu disponível',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tbl_sys_menu`
--

INSERT INTO `tbl_sys_menu` (`id`, `nome`, `pagina`, `class`, `parent`, `ordem`, `ativo`) VALUES
(1, 'Parent Vazio', '', '', 0, 0, 'Sim');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_sys_newsletter`
--

CREATE TABLE IF NOT EXISTS `tbl_sys_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_sys_seo`
--

CREATE TABLE IF NOT EXISTS `tbl_sys_seo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL COMMENT 'url (ex: /novo-detalhes/1/all-new-cerato/) Sempre iniciado com barra "/"',
  `seo_title` varchar(255) NOT NULL COMMENT 'invisible',
  `seo_keywords` varchar(255) NOT NULL COMMENT 'Meta Tag - SEO : Keywords',
  `seo_description` varchar(255) NOT NULL COMMENT 'Meta Tag - SEO : Description',
  `ordem` int(11) NOT NULL COMMENT 'invisible',
  `ativo` enum('Sim','Não') NOT NULL DEFAULT 'Sim' COMMENT 'invisible',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tbl_sys_seo`
--

INSERT INTO `tbl_sys_seo` (`id`, `nome`, `seo_title`, `seo_keywords`, `seo_description`, `ordem`, `ativo`) VALUES
(1, 'Meta Tags Padrão', '', 'Keywords do site', 'Description do Site', 0, 'Sim');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_sys_usuarios`
--

CREATE TABLE IF NOT EXISTS `tbl_sys_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordem` int(11) NOT NULL DEFAULT '0' COMMENT 'invisible',
  `ativo` enum('Não','Sim','') DEFAULT NULL COMMENT 'Ativo',
  `login` varchar(255) NOT NULL DEFAULT '' COMMENT 'Login',
  `senha` varchar(50) DEFAULT NULL COMMENT 'Senha',
  `nome` varchar(255) NOT NULL DEFAULT '' COMMENT 'Nome',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Email',
  `cadastrado` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Data do Cadastro',
  `datas` mediumtext NOT NULL COMMENT 'Datas de Acessos',
  `ips` mediumtext NOT NULL COMMENT 'IPs de Acessos',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tbl_sys_usuarios`
--

INSERT INTO `tbl_sys_usuarios` (`id`, `ordem`, `ativo`, `login`, `senha`, `nome`, `email`, `cadastrado`, `datas`, `ips`) VALUES
(1, 0, 'Sim', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Admin Master', '', '0000-00-00', '08/08/2014;', '127.0.0.1;');
