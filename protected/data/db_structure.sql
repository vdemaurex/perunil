-- -----------------------------------------
-- Schéma de la base de donnée PerUnil 2  --
-- -------------------------------------- --
-- Auteur : vincent.demaurex@chuv.ch      --
-- Généré le: Mar 05 Mars 2013 à 09:06    --
-- Version du serveur: 5.5.25a            --
-- Version de PHP: 5.4.4                  --
--------------------------------------------

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- Base de données: `perunil_journals-v2`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

DROP TABLE IF EXISTS `abonnement`;
CREATE TABLE IF NOT EXISTS `abonnement` (
  `abonnement_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titreexclu` tinyint(1) NOT NULL DEFAULT '0',
  `package` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_abo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url_site` varchar(2083) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acces_elec_gratuit` tinyint(1) DEFAULT '0',
  `acces_elec_unil` tinyint(1) DEFAULT '0',
  `acces_elec_chuv` tinyint(1) DEFAULT '0',
  `embargo_mois` tinyint(4) DEFAULT NULL COMMENT 'Chiffre donné en mois',
  `acces_user` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acces_pwd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etatcoll` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `etatcoll_deba` mediumint(9) DEFAULT NULL,
  `etatcoll_debv` mediumint(9) DEFAULT NULL,
  `etatcoll_debf` mediumint(9) DEFAULT NULL,
  `etatcoll_fina` mediumint(9) DEFAULT NULL,
  `etatcoll_finv` mediumint(9) DEFAULT NULL,
  `etatcoll_finf` mediumint(9) DEFAULT NULL,
  `cote` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `editeur_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Code de la revue chez l''éditeur',
  `editeur_sujet` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Sujet chez l''éditeur, anc. "keywords"',
  `commentaire_pro` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commentaire_pub` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `perunilid` bigint(20) unsigned DEFAULT NULL,
  `plateforme` smallint(6) DEFAULT NULL,
  `editeur` smallint(6) DEFAULT NULL,
  `histabo` smallint(6) DEFAULT NULL,
  `statutabo` smallint(6) NOT NULL DEFAULT '0',
  `localisation` smallint(6) DEFAULT NULL,
  `gestion` smallint(6) DEFAULT NULL,
  `format` smallint(6) DEFAULT NULL,
  `support` smallint(6) DEFAULT NULL,
  `licence` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`abonnement_id`),
  UNIQUE KEY `abonnement_id` (`abonnement_id`),
  KEY `fk_editeur` (`editeur`),
  KEY `fk_histabo` (`histabo`),
  KEY `fk_statutabo` (`statutabo`),
  KEY `fk_localisation` (`localisation`),
  KEY `fk_gestion` (`gestion`),
  KEY `fk_format` (`format`),
  KEY `fk_licence` (`licence`),
  KEY `fk_plateforme` (`plateforme`),
  KEY `titreexclu` (`titreexclu`),
  KEY `perunilid` (`perunilid`),
  KEY `support` (`support`),
  KEY `titreexclu_2` (`titreexclu`,`perunilid`,`support`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `biblio`
--

DROP TABLE IF EXISTS `biblio`;
CREATE TABLE IF NOT EXISTS `biblio` (
  `biblio_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `biblio` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`biblio_id`),
  UNIQUE KEY `biblio` (`biblio`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `corecollection`
--

DROP TABLE IF EXISTS `corecollection`;
CREATE TABLE IF NOT EXISTS `corecollection` (
  `perunilid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `biblio_id` smallint(6) NOT NULL,
  PRIMARY KEY (`perunilid`,`biblio_id`),
  KEY `biblio_id` (`biblio_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `editeur`
--

DROP TABLE IF EXISTS `editeur`;
CREATE TABLE IF NOT EXISTS `editeur` (
  `editeur_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `editeur` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`editeur_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `format`
--

DROP TABLE IF EXISTS `format`;
CREATE TABLE IF NOT EXISTS `format` (
  `format_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `format` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`format_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gestion`
--

DROP TABLE IF EXISTS `gestion`;
CREATE TABLE IF NOT EXISTS `gestion` (
  `gestion_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `gestion` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`gestion_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `histabo`
--

DROP TABLE IF EXISTS `histabo`;
CREATE TABLE IF NOT EXISTS `histabo` (
  `histabo_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `histabo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`histabo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journal`
--

DROP TABLE IF EXISTS `journal`;
CREATE TABLE IF NOT EXISTS `journal` (
  `perunilid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `soustitre` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titre_abrege` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `titre_variante` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `faitsuitea` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `devient` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issn` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issnl` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nlmid` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reroid` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doi` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coden` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `urn` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publiunil` tinyint(1) DEFAULT '0',
  `url_rss` varchar(2083) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commentaire_pub` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parution_terminee` tinyint(1) DEFAULT '0',
  `openaccess` tinyint(1) DEFAULT '0',
  `DEPRECATED_sujetsfm` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Ne pas ajouter de données.',
  `DEPRECATED_fmid` int(11) DEFAULT NULL COMMENT 'Ne pas ajouter de données.',
  `DEPRECARED_historique` longtext COLLATE utf8_unicode_ci COMMENT 'Ne pas ajouter de données.',
  PRIMARY KEY (`perunilid`),
  UNIQUE KEY `perunilid` (`perunilid`),
  KEY `titre` (`titre`),
  KEY `soustitre` (`soustitre`),
  KEY `titre_abrege` (`titre_abrege`),
  KEY `titre_variante` (`titre_variante`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `journal_sujet`
--

DROP TABLE IF EXISTS `journal_sujet`;
CREATE TABLE IF NOT EXISTS `journal_sujet` (
  `perunilid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `sujet_id` smallint(6) NOT NULL,
  PRIMARY KEY (`perunilid`,`sujet_id`),
  KEY `sujet_id` (`sujet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `licence`
--

DROP TABLE IF EXISTS `licence`;
CREATE TABLE IF NOT EXISTS `licence` (
  `licence_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `licence` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`licence_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `localisation`
--

DROP TABLE IF EXISTS `localisation`;
CREATE TABLE IF NOT EXISTS `localisation` (
  `localisation_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `localisation` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`localisation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `modifications`
--

DROP TABLE IF EXISTS `modifications`;
CREATE TABLE IF NOT EXISTS `modifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `old_value` text COLLATE utf8_unicode_ci,
  `new_value` text COLLATE utf8_unicode_ci,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stamp` datetime NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plateforme`
--

DROP TABLE IF EXISTS `plateforme`;
CREATE TABLE IF NOT EXISTS `plateforme` (
  `plateforme_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `plateforme` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`plateforme_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `statutabo`
--

DROP TABLE IF EXISTS `statutabo`;
CREATE TABLE IF NOT EXISTS `statutabo` (
  `statutabo_id` smallint(6) NOT NULL,
  `statutabo` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`statutabo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sujet`
--

DROP TABLE IF EXISTS `sujet`;
CREATE TABLE IF NOT EXISTS `sujet` (
  `sujet_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `code` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `nom_en` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_fr` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `stm` tinyint(1) DEFAULT '0',
  `shs` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sujet_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support`
--

DROP TABLE IF EXISTS `support`;
CREATE TABLE IF NOT EXISTS `support` (
  `support_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `support` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`support_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `utilisateur_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pseudo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mot_de_passe` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('Administration','Modification-suppression','Modification','Consultation') COLLATE utf8_unicode_ci NOT NULL,
  `creation_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
