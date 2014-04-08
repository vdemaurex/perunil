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


DROP TABLE IF EXISTS 
`abonnement`, 
`biblio`, 
`corecollection`, 
`editeur`, 
`format`, 
`gestion`, 
`histabo`, 
`journal`, 
`journal_sujet`, 
`licence`, 
`localisation`, 
`modifications`, 
`plateforme`, 
`statutabo`, 
`sujet`, 
`support`, 
`utilisateur`;

-- Tables pour toutes les valeurs listes éditables
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
  `DEPRECATED_historique` LONGTEXT    DEFAULT NULL COMMENT 'Ne pas ajouter de données.',
  CONSTRAINT fk_creation      FOREIGN KEY (modification)   REFERENCES modifications(id),
  CONSTRAINT fk_modification  FOREIGN KEY (modification)   REFERENCES modifications(id),
  PRIMARY KEY (`perunilid`)
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
  CONSTRAINT fk_creation      FOREIGN KEY (modification)   REFERENCES modifications(id),
  CONSTRAINT fk_modification  FOREIGN KEY (modification)   REFERENCES modifications(id),
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


--
-- Migration des données de la base PerUnil
--


-- ----------------------------------------------------------------------------
-- Insertion des tables de "constantes"
-- ----------------------------------------------------------------------------
INSERT INTO `histabo` (`histabo`) VALUES 
	('BCUD'),
	('BCUR'),
	('Biologie'),
	('BiUM'),
	('BPUL'),
	('CDSP'),
	('CURML'),
	('HEPL'),
	('IPA'),
	('IST'),
	('IUHMSP'),
	('IUMSP'),
	('Physiologie');

INSERT INTO `statutabo` (`statutabo_id`, `statutabo`) VALUES 
	(0, 'Terminé'),
	(1, 'Actif'),
	(2, 'En test'),
	(3, 'Perdu'),
	(4, 'Problème d\'accès'),
	(5, 'Gestion provisoire');


INSERT INTO `localisation` (`localisation`) 
SELECT DISTINCT `perunil_journals`.`journals`.localisation
FROM `perunil_journals`.`journals`
WHERE `perunil_journals`.`journals`.localisation <> ""
AND `perunil_journals`.`journals`.localisation IS NOT NULL;


INSERT INTO `gestion` (`gestion`) VALUES
('Abo e-only'),
('Echange'),
('Opt-in title'),
('Print+Online');

INSERT INTO `format` (`format`) VALUES
('Autres sources'),
('Base de données'),
('Dictionnaire'),
('Encyclopédie'),
('Quotidien');


INSERT INTO `support` (`support`) VALUES
('electronique'),
('papier');


INSERT INTO `plateforme` (`plateforme`) 
SELECT DISTINCT `perunil_journals`.`journals`.plateforme
FROM `perunil_journals`.`journals`
WHERE `perunil_journals`.`journals`.plateforme <> ""
AND `perunil_journals`.`journals`.plateforme IS NOT NULL;



INSERT INTO `licence` (`licence`) 
SELECT DISTINCT `perunil_journals`.`journals`.licence
FROM `perunil_journals`.`journals`
WHERE `perunil_journals`.`journals`.licence <> ""
AND `perunil_journals`.`journals`.licence IS NOT NULL;


INSERT INTO `biblio` (`biblio`) VALUES
('BCU'),
('BCUD'),
('BCUR'),
('Biochimie'),
('Biologie'),
('BiUM'),
('BPUL'),
('CDSP'),
('CURML'),
('DPT'),
('IPA'),
('ISDC'),
('IST'),
('IUHMSP'),
('IUMSP'),
('ScTerre');


INSERT INTO `utilisateur`
(nom, email, pseudo, 
mot_de_passe, status, creation_ip, creation_on ) VALUES
('Vincent Demaurex','vincent@demaurex.fr','vdemaure','318d097f10418205b287d50bf9427a3a','Administration','130.223.2.202','2012-06-27 09:42:19'),
('Pablo Iriarte','pablo.iriarte@chuv.ch','piriarte','cc03e747a6afbbcbf8be7668acfebee5','Administration','130.223.2.67','2012-02-09 01:33:13'),
('Natalia Djeddou','nathalia.djeddou@chuv.ch','ndjeddou','9808b9ee740e4732f12eef304367dca9','Modification','130.223.2.67','2012-02-09 01:34:00'),
('Mireille Pochon','mireille.pochon@bcu.unil.ch','mpochon','6002e6a8ca95b3c697c7132fe1148725','Modification','130.223.2.67','2012-02-09 01:33:39'),
('Maika Casse','marie-dominique.garcia@chuv.ch','mdcasse','b024597999b2d39db656c867163a5abc','Modification','130.223.2.67','2012-02-09 01:34:22'),
('Josiane Bonetti','josiane.bonetti@unil.ch','jbonetti','e8b2b43fa47096ed093e0e02ac059e90','Modification','130.223.2.67','2012-02-09 01:33:52'),
('Isabelle de Kaenel','isabelle.de-kaenel@chuv.ch','ikaenel','7b36390113702b7684c378ee8202ac46','Modification','130.223.2.67','2012-02-09 01:34:14'),
('','bdfm@chuv.ch','bium', '', 'Consultation', NULL, '2013-04-11'),
('','undefined@bium.ch','undefined', '', 'Consultation', NULL, '2013-04-11'),
('','fsacco@bium.ch','fsacco', '', 'Consultation', NULL, '2013-04-11'),
('','jtrottman@bium.ch','jtrottman', '', 'Consultation', NULL, '2013-04-11'),
('','cjaques@bium.ch','cjaques', '', 'Consultation', NULL, '2013-04-11'),
('','fkhenoune@bium.ch','fkhenoune', '', 'Consultation', NULL, '2013-04-11'),
('','plavanchy@bium.ch','plavanchy', '', 'Consultation', NULL, '2013-04-11'),
('','bichi@bium.ch','bichi', '', 'Consultation', NULL, '2013-04-11'),
('','mdelessert@bium.ch','mdelessert', '', 'Consultation', NULL, '2013-04-11'),
('','mbertinat@bium.ch','mbertinat', '', 'Consultation', NULL, '2013-04-11'),
('','jfrey@bium.ch','jfrey', '', 'Consultation', NULL, '2013-04-11'),
('','rgomez@bium.ch','rgomez', '', 'Consultation', NULL, '2013-04-11'),
('','giffland@bium.ch','giffland', '', 'Consultation', NULL, '2013-04-11'),
('','pdevaud@bium.ch','pdevaud', '', 'Consultation', NULL, '2013-04-11'),
('','mpfister@bium.ch','mpfister', '', 'Consultation', NULL, '2013-04-11'),
('Administrateur Test Perunil 2', 'vincent.demaurex@chuv.ch', 'atestpu2','b2b0000cd5e3f848a148932cbb1173c4', 'Modification', '130.223.2.82', '2013-07-04 12:18:58'),
('Jolanda Elmers', 'Jolanda.Elmers@chuv.ch', 'jelmers', 'ed1a62b71887ed2fa43da8f5daa7644d', 'Administration', '155.105.7.44', '2014-01-07 13:25:47');


-- ----------------------------------------------------------------------------
-- INSERTIONS BASEES SUR LES REQUETES
-- ----------------------------------------------------------------------------

INSERT INTO `journal`
(perunilid,
titre,
soustitre,
titre_abrege,
titre_variante,
faitsuitea,
devient,
issn,
issnl,
nlmid,
reroid,
doi,
coden,
urn,
publiunil,
url_rss,
commentaire_pub,
openaccess,
DEPRECATED_sujetsfm,
DEPRECATED_fmid,
DEPRECATED_historique)
SELECT 
perunilid,
titre,
soustitre,
titreabrege,
variantetitre,
faitsuitea,
devient,
issn,
issnl,
nlmid,
reroid,
doi,
coden,
urn,
publiunil,
rss,
commentairepub,
openaccess,
sujetsfm,
fmid,
historique
FROM `perunil_journals`.`journals`;
-- ----------------------------------------------------------------------------

INSERT INTO `editeur`
(editeur)
select distinct editeur 
from `perunil_journals`.`journals`;


-- ----------------------------------------------------------------------------

INSERT INTO `sujet`
(sujet_id, code, nom_en, nom_fr, stm, shs)
SELECT 
sujetsid,
sujetscode,
sujetsen,
sujetsfr,
sujetsstm,
sujetsshs
FROM `perunil_journals`.`sujets`;

-- ----------------------------------------------------------------------------
-- # ETAPE INTERMÉDIAIRE POUR LES TABLES LIEES À ABONNEMENT
--   1) CRÉATION D'UNE COLONNE SUPPLÉMENTAIRE DANS LA TABLE JOURNALS
--   2) LA COLONNE EST REMPLIE AVEV LES ID DE LA TABLE CONSTANTE CONCERNEE
--
-- ----------------------------------------------------------------------------
ALTER TABLE `perunil_journals`.`journals` ADD `tmp_plateforme` SMALLINT;

UPDATE `perunil_journals`.`journals`, plateforme
SET  `perunil_journals`.`journals`.tmp_plateforme = plateforme.plateforme_id
WHERE `perunil_journals`.`journals`.plateforme LIKE plateforme.plateforme;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_editeur` SMALLINT;

UPDATE `perunil_journals`.`journals`, editeur
SET  `perunil_journals`.`journals`.tmp_editeur = editeur.editeur_id
WHERE `perunil_journals`.`journals`.editeur LIKE editeur.editeur;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_histabo` SMALLINT;

UPDATE `perunil_journals`.`journals`, histabo
SET  `perunil_journals`.`journals`.tmp_histabo = histabo.histabo_id
WHERE `perunil_journals`.`journals`.historiqueabo LIKE histabo.histabo;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_statutabo` SMALLINT;

UPDATE `perunil_journals`.`journals`, statutabo
SET  `perunil_journals`.`journals`.tmp_statutabo = statutabo.statutabo_id
WHERE `perunil_journals`.`journals`.statutabo LIKE statutabo.statutabo_id;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_localisation` SMALLINT;

UPDATE `perunil_journals`.`journals`, localisation
SET  `perunil_journals`.`journals`.tmp_localisation = localisation.localisation_id
WHERE `perunil_journals`.`journals`.localisation LIKE localisation.localisation;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_gestion` SMALLINT;

UPDATE `perunil_journals`.`journals`, gestion
SET  `perunil_journals`.`journals`.tmp_gestion = gestion.gestion_id
WHERE `perunil_journals`.`journals`.gestion LIKE gestion.gestion;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_format` SMALLINT;

UPDATE `perunil_journals`.`journals`, format
SET  `perunil_journals`.`journals`.tmp_format = format.format_id
WHERE `perunil_journals`.`journals`.format LIKE format.format;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_support` SMALLINT;

UPDATE `perunil_journals`.`journals`, support
SET  `perunil_journals`.`journals`.tmp_support = support.support_id
WHERE `perunil_journals`.`journals`.support LIKE support.support;

-- ----------------------------------------------------------------------------

ALTER TABLE `perunil_journals`.`journals` ADD `tmp_licence` SMALLINT;

UPDATE `perunil_journals`.`journals`, licence
SET  `perunil_journals`.`journals`.tmp_licence = licence.licence_id
WHERE `perunil_journals`.`journals`.licence LIKE licence.licence;


-- ----------------------------------------------------------------------------
-- # PEUPLEMENT DE LA TABLE ABONNEMENT
--   1) POUR CHAQUE LIGNE DE LA TABLE JOURNALS DONT LE TITRE A UNE 
--      CORRESPONDANCE DANS LA TABLE JOURNAL, ON CREE UNE ENTREE DANS LA TABLE
--      ABONNEMENT.
--   2) CETTE ENTREE COMPREND UN SOUS-ENSEMBLE DE LA TABLE JOURNALS ET EST
--   

INSERT INTO `abonnement`
(titreexclu,
package,
url_site,
acces_elec_gratuit,
acces_elec_unil,
acces_elec_chuv,
embargo_mois,
acces_user,
acces_pwd,
etatcoll,
etatcoll_deba,
etatcoll_debv,
etatcoll_debf,
etatcoll_fina,
etatcoll_finv,
etatcoll_finf,
cote,
editeur_sujet,
commentaire_pro,
commentaire_pub,
perunilid,
plateforme,
editeur,
histabo,
statutabo,
localisation,
gestion,
format,
support,
licence
)
SELECT 
journals.titreexclu,
journals.package,
journals.url,
journals.acceseleclibre,
journals.acceselecunil,
journals.acceselecchuv,
journals.embargo,
journals.user,
journals.pwd,
journals.etatcoll,
journals.etatcolldeba,
journals.etatcolldebv,
journals.etatcolldebf,
journals.etatcollfina,
journals.etatcollfinv,
journals.etatcollfinf,
journals.cote,
journals.keywords,
journals.commentairepro,
journals.commentairepub,
journals.perunilid,
journals.tmp_plateforme,
journals.tmp_editeur,
journals.tmp_histabo,
journals.tmp_statutabo,
journals.tmp_localisation,
journals.tmp_gestion,
journals.tmp_format,
journals.tmp_support,
journals.tmp_licence
FROM `perunil_journals`.`journals` journals;

-- ----------------------------------------------------------------------------
-- # PEUPLEMENT DES TABLES INTERMÉDIAIRES POUR LES RELATION MANY-TO-MANY
-- ----------------------------------------------------------------------------


INSERT INTO `journal_sujet`
(perunilid,sujet_id)
SELECT DISTINCT
journal.perunilid, `perunil_journals`.journals_sujets.sujetsid
FROM journal, `perunil_journals`.journals_sujets, sujet
WHERE `perunil_journals`.journals_sujets.perunilid = journal.perunilid
AND `perunil_journals`.journals_sujets.sujetsid = sujet.sujet_id;

-- ----------------------------------------------------------------------------

-- Corecollection est utilisée dans PerUnil 1 uniquement par la Bium, c'est
-- pourquoi toutes les entrées lui sont affectées.
-- Vérifier le numéro de la BiUM : select * from biblio where nom like'BiUM';

INSERT INTO `corecollection`
(perunilid,biblio_id)
SELECT DISTINCT
`perunil_journals`.`journals`.perunilid, 6
FROM `perunil_journals`.`journals`
WHERE `perunil_journals`.`journals`.corecollection = 1;

-- ----------------------------------------------------------------------------

-- Migration des informations de modification

INSERT INTO `perunil_journals-v2`.`modifications`
(old_value, new_value, action, model, field, stamp, user_id, model_id)
SELECT NULL, NULL, 'Modification', 'Journal', 'Inconnu', jrn.datemodif, usr.utilisateur_id, jrn.perunilid
FROM `perunil_journals`.`journals` jrn
LEFT JOIN `perunil_journals-v2`.`utilisateur` usr ON jrn.signaturemodif LIKE usr.pseudo 
WHERE usr.utilisateur_id IS NOT NULL;

-- Migration des informations de création
INSERT INTO `perunil_journals-v2`.`modifications`
(old_value, new_value, action, model, field, stamp, user_id, model_id)
SELECT NULL, NULL, 'Création', 'Journal', 'Inconnu', jrn.datecreation, usr.utilisateur_id, jrn.perunilid
FROM `perunil_journals`.`journals` jrn
LEFT JOIN `perunil_journals-v2`.`utilisateur` usr ON jrn.signaturecreation LIKE usr.pseudo 
WHERE usr.utilisateur_id IS NOT NULL;

-- UPDATE 'perunil_journals-v2'.'journal' jrn
-- SET jrn.modification = (SELECT id 
--                     FROM modifications  m 
--                     WHERE m.model = 'Journal' AND m.action = 'Modification' AND m.model_id = jrn.perunilid 
--                     ORDER BY stamp 
--                     DESC LIMIT 1)
