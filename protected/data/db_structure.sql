-- 
-- Structure de la base
-- 
--
-- Base de données: `perunil2`
-- 
-- Auteur : Vincent Demaurex
-- Version
-- -------
-- 08.01.14 : Ajout des champs contenant les dernières modifications pour
--            les tables Journal et Abonnement



-- Tables pour toutes les valeurs liste éditables
-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS `histabo` (
  `histabo_id` SMALLINT NOT NULL AUTO_INCREMENT,
  `histabo`	varchar(200),
   CONSTRAINT pk_histabo PRIMARY KEY (histabo_id)
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';


-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS `statutabo` (
  `statutabo_id`      SMALLINT NOT NULL,
  `statutabo`         varchar(200),
   CONSTRAINT pk_statutabo PRIMARY KEY (statutabo_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';



-- -------------------------------------------------------

CREATE TABLE IF NOT EXISTS `localisation` (
  `localisation_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `localisation`	varchar(200) NOT NULL,
   CONSTRAINT pk_localisation PRIMARY KEY (localisation_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';




-- -------------------------------------------------------

CREATE TABLE IF NOT EXISTS `gestion` (
  `gestion_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `gestion`	varchar(200) NOT NULL,
   CONSTRAINT pk_gestion PRIMARY KEY (gestion_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';




-- -------------------------------------------------------

CREATE TABLE IF NOT EXISTS `format` (
  `format_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `format`	varchar(200) NOT NULL,
   CONSTRAINT pk_format PRIMARY KEY (format_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';




-- -------------------------------------------------------

CREATE TABLE IF NOT EXISTS `support` (
  `support_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `support`	varchar(200) NOT NULL,
   CONSTRAINT pk_support PRIMARY KEY (support_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';






-- -------------------------------------------------------

CREATE TABLE IF NOT EXISTS `plateforme` (
  `plateforme_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `plateforme`	varchar(200) NOT NULL,
   CONSTRAINT pk_plateforme PRIMARY KEY (plateforme_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';




-- -------------------------------------------------------

CREATE TABLE IF NOT EXISTS `licence` (
  `licence_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `licence`	varchar(200) NOT NULL,
   CONSTRAINT pk_licence PRIMARY KEY (licence_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';



-- -------------------------------------------------------

CREATE TABLE IF NOT EXISTS `editeur` (
  `editeur_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `editeur`	varchar(200) NOT NULL,
   CONSTRAINT pk_editeur PRIMARY KEY (editeur_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';



-- --------------------------------------------------------
-- TABLE journal

CREATE TABLE IF NOT EXISTS `journal` (
  `perunilid`           SERIAL,
  `titre`               varchar(250)  NOT NULL,
  `soustitre`           varchar(250)  DEFAULT NULL,
  `titre_abrege`        varchar(100)  DEFAULT NULL,
  `titre_variante`      varchar(250)  DEFAULT NULL,
  `faitsuitea`          varchar(250)  DEFAULT NULL,
  `devient`             varchar(250)  DEFAULT NULL,
  `issn`                varchar(120)  DEFAULT NULL,
  `issnl`               varchar(9)    DEFAULT NULL,
  `nlmid`               varchar(15)   DEFAULT NULL,
  `reroid`              varchar(50)   DEFAULT NULL,
  `doi`                 varchar(250)  DEFAULT NULL,
  `coden`               varchar(6)    DEFAULT NULL,
  `urn`                 varchar(250)  DEFAULT NULL,
  `publiunil`           BOOLEAN       DEFAULT FALSE,
  `url_rss`             varchar(2083) DEFAULT NULL,
  `commentaire_pub`     varchar(500)  DEFAULT NULL,
  `parution_terminee`   BOOLEAN       DEFAULT FALSE,
  `openaccess`          BOOLEAN       DEFAULT FALSE,
  `creation`            bigint(20)    DEFAULT NULL,
  `modification`        bigint(20)    DEFAULT NULL,
  `DEPRECATED_sujetsfm` varchar(1000) DEFAULT NULL COMMENT 'Ne pas ajouter de données.',
  `DEPRECATED_fmid`     INT           DEFAULT NULL COMMENT 'Ne pas ajouter de données.',
  `DEPRECARED_historique` LONGTEXT    DEFAULT NULL COMMENT 'Ne pas ajouter de données.',
  CONSTRAINT pk_perunilid     PRIMARY KEY (perunilid),
  CONSTRAINT fk_creation      FOREIGN KEY (creation)       REFERENCES modifications(id),
  CONSTRAINT fk_modification  FOREIGN KEY (modification)   REFERENCES modifications(id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';


-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `sujet` (
  `sujet_id` SMALLINT    NOT NULL AUTO_INCREMENT,
  `code`     varchar(4)  NOT NULL UNIQUE,
  `nom_en`   varchar(50) DEFAULT NULL,
  `nom_fr`   varchar(50) NOT NULL,
  `stm`      BOOLEAN     DEFAULT FALSE,
  `shs`      BOOLEAN     DEFAULT FALSE,
  PRIMARY KEY (`sujet_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `biblio` (
  `biblio_id`      SMALLINT    NOT NULL AUTO_INCREMENT,
  `biblio`            varchar(4)  NOT NULL UNIQUE,
  PRIMARY KEY (`biblio_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';

CREATE TABLE IF NOT EXISTS `corecollection` (
  `perunilid`  BIGINT unsigned,
  `biblio_id`  SMALLINT NOT NULL,
  FOREIGN KEY (`perunilid`) REFERENCES journal(`perunilid`),
  FOREIGN KEY (`biblio_id`)  REFERENCES biblio(`biblio_id`),
  PRIMARY KEY (`perunilid`, `biblio_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';

-- -------------------------------------------------------
-- CI
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `utilisateur_id`  SMALLINT     NOT NULL AUTO_INCREMENT,
  `nom`             varchar(255) NOT NULL,
  `email`           varchar(255) NOT NULL UNIQUE,
  `pseudo`          varchar(50)  NOT NULL UNIQUE, 
  `mot_de_passe`    varchar(50)  NOT NULL,
  `status`          ENUM("Administration", "Modification-suppression", "Modification", "Consultation") NOT NULL,
  `creation_ip`     varchar(255) DEFAULT NULL,
  `creation_on`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`utilisateur_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';

-- --------------------------------------------------------
-- 
CREATE TABLE IF NOT EXISTS `journal_sujet` (
  `perunilid` BIGINT unsigned,
  `sujet_id`  SMALLINT NOT NULL,
  FOREIGN KEY (`perunilid`) REFERENCES journal(`perunilid`),
  FOREIGN KEY (`sujet_id`)  REFERENCES sujet(`sujet_id`),
  PRIMARY KEY (`perunilid`, `sujet_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';


-- --------------------------------------------------------
-- 
CREATE TABLE IF NOT EXISTS `corecollection` (
  `perunilid` BIGINT unsigned,
  `biblio_id`  SMALLINT NOT NULL,
  FOREIGN KEY (`perunilid`) REFERENCES journal(`perunilid`),
  FOREIGN KEY (`biblio_id`)  REFERENCES biblio(`biblio_id`),
  PRIMARY KEY (`perunilid`, `biblio_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';

-- --------------------------------------------------------

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
  PRIMARY KEY (`id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';


-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS `abonnement` (
  `abonnement_id`      SERIAL,
  `titreexclu`         BOOLEAN       DEFAULT FALSE NOT NULL,
  `package`            varchar(250)  DEFAULT NULL,
  `no_abo`             varchar(50)   DEFAULT NULL,
  `url_site`           varchar(2083) DEFAULT NULL,
  `acces_elec_gratuit` BOOLEAN       DEFAULT FALSE,
  `acces_elec_unil`    BOOLEAN       DEFAULT FALSE,
  `acces_elec_chuv`    BOOLEAN       DEFAULT FALSE,
  `embargo_mois`       TINYINT       DEFAULT NULL COMMENT 'Chiffre donné en mois',
  `acces_user`         varchar(50)   DEFAULT NULL,
  `acces_pwd`          varchar(50)   DEFAULT NULL,
  `etatcoll`           varchar(250)  DEFAULT NULL,
  `etatcoll_deba`      MEDIUMINT     DEFAULT NULL,
  `etatcoll_debv`      MEDIUMINT     DEFAULT NULL,
  `etatcoll_debf`      MEDIUMINT     DEFAULT NULL,
  `etatcoll_fina`      MEDIUMINT     DEFAULT NULL,
  `etatcoll_finv`      MEDIUMINT     DEFAULT NULL,
  `etatcoll_finf`      MEDIUMINT     DEFAULT NULL,
  `cote`               varchar(250)  DEFAULT NULL,
  `editeur_code`       varchar(100)  DEFAULT NULL COMMENT 'Code de la revue chez l\'éditeur',
  `editeur_sujet`      varchar(250)  DEFAULT NULL COMMENT 'Sujet chez l\'éditeur, anc. "keywords"',
  `commentaire_pro`    varchar(500)  DEFAULT NULL,
  `commentaire_pub`    varchar(500)  DEFAULT NULL,
  `perunilid`          BIGINT unsigned,            -- FK journal
  `plateforme`         SMALLINT,                   -- FK plateforme
  `editeur`            SMALLINT      DEFAULT NULL, -- FK table éditeur
  `histabo`	           SMALLINT      DEFAULT NULL, -- FK table abo_hist
  `statutabo`          SMALLINT      DEFAULT 0 NOT NULL , -- FK table abo_statut
  `localisation`       SMALLINT      DEFAULT NULL, -- FK
  `gestion`	           SMALLINT      DEFAULT NULL, -- FK
  `format`	           SMALLINT      DEFAULT NULL, -- FK
  `support`	           SMALLINT      DEFAULT NULL, -- FK
  `licence`	           SMALLINT      DEFAULT NULL, -- FK
  `creation`           bigint(20)    DEFAULT NULL,
  `modification`       bigint(20)    DEFAULT NULL,
  CONSTRAINT fk_creation      FOREIGN KEY (creation)        REFERENCES modifications(id),
  CONSTRAINT fk_modification  FOREIGN KEY (modification)    REFERENCES modifications(id),
  CONSTRAINT fk_editeur       FOREIGN KEY (editeur)         REFERENCES editeur(editeur_id),
  CONSTRAINT fk_histabo       FOREIGN KEY (histabo)         REFERENCES histabo(histabo_id),
  CONSTRAINT fk_statutabo     FOREIGN KEY (statutabo)       REFERENCES statutabo(statutabo_id),
  CONSTRAINT`fk_localisation` FOREIGN KEY (localisation)    REFERENCES localisation(localisation_id),
  CONSTRAINT`fk_gestion`      FOREIGN KEY (gestion)         REFERENCES gestion(gestion_id),
  CONSTRAINT`fk_format`	      FOREIGN KEY (format)          REFERENCES format(format_id),
  CONSTRAINT`fk_support`      FOREIGN KEY (support)         REFERENCES support(support_id),
  CONSTRAINT`fk_licence`      FOREIGN KEY (licence)         REFERENCES licence(licence_id),
  CONSTRAINT`fk_journal`      FOREIGN KEY (perunilid)       REFERENCES journal(perunilid),
  CONSTRAINT`fk_plateforme`   FOREIGN KEY (plateforme)      REFERENCES plateforme(plateforme_id),
  PRIMARY KEY (`abonnement_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
