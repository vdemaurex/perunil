<?php

//ini_set("max_execution_time", "120");
ini_set("memory_limit", "2400M");

class PU1toPU2Command extends CConsoleCommand {

    public function getHelp() {
        $out = "Effectue les tâches de migrations pour transformer la base PU1 en PU2. La base PU1 doit comporter le champs \n\n";
        return $out . parent::getHelp();
    }

    public function run($args) {


        echo "****************************************************************\n";
        echo "**       MIGRATION DE LA BASE PERUNIL 1 À PERUNIL 2           **\n";
        echo "****************************************************************\n\n";

        print "Le script va réinitialiser la base. Tapez ENTER pour continuer.";
        fgets(STDIN);

        echo "*******                 TACHES PREALABLES                *******\n\n";

        echo ">>>>  RECONSTRUCTION DE LA BASE PU2 \n\n";
        $this->rebulidPU2db();

        echo ">>>>  INSERTION DES VALEURS DANS LES TABLES CONSTANTES \n";
        $this->populateConstTables();

        echo ">>>>  REMPLISSAGE DES TABLES LISTES ISSUES D'UN SELECT DISTINCT \n";
        $this->populateListTables();

        echo ">>>> SI LE CHAMP MODIFICATION EST NULL : VALEUR CRÉATION\n";
        $this->completeModifIfNull();

        echo ">>>> SI LE CHAMP PERUNIL-FUSION = 0 : VALEUR PERUNILID\n";
        $this->replacePuIdFuZero();


        echo "******* PARCOURS DE L'ENSEMBLE DES PERUNILID-FUSION      *******\n";
        // Liste des perunilid-fusion
        $sql = "SELECT DISTINCT `perunilid_fusion` FROM `journals_fusion` ORDER BY `perunilid_fusion` ASC;";
        $puidfs2D = Yii::app()->dbpu1->createCommand($sql)->queryAll();
        $puidfs = array_map('current', $puidfs2D);
        unset($puidfs2D);
        // Pour tous les perunilid-fusion, classé par perunilid
//        foreach ($this->debugset as $idfusion) {
        foreach ($puidfs as $i => $idfusion) {
            //for ($idfusion = self::PuIdMin; $idfusion <= self::PuIdMax; $idfusion++) {
            echo "   > $idfusion \n";


            //      Récupérer les lignes, classée par dernière modification
//            $sql = "SELECT * FROM `journals_fusion` WHERE `perunilid_fusion` = $idfusion ORDER BY `datemodif` DESC;";
            $sql = "SELECT * FROM `journals_fusion` WHERE `perunilid_fusion` = $idfusion ORDER BY `etatcolldeba` DESC;";
            $rows = Yii::app()->dbpu1->createCommand($sql)->queryAll();


            if (!empty($rows)) { // Au moins une ligne à traiter
                echo "      - contient " . count($rows) . " lignes\n";

                // Avec la ligne contenant le plus haut perunilid, créer une entrée journal
                $journal = $this->buildJournal($rows);

                // Pour toutes les lignes, créer un abonnement à associer au journal
                foreach ($rows as $row) {
                    $this->bulidAbonnement($journal, $row);
                }
            } else { // Aucune ligne à traiter
                echo "      - ne contient aucune ligne \n";
            }


            echo "   >----------------------------------------------------\n\n";
        }

        echo ">>>> PRÉPARATION ET LIAISON DES SUJETS\n";
        $this->prepareJournalSujet();
        $this->linkJournalSujet();


        echo ">>>> LIAISON DES CORECOLLECTION\n";
        $this->linkBiUMCorecollection();
    }

    /* -------------------------------------------------------------------------
     * 
     *  TRAITEMENT DES JOURNAUX
     * 
     * -------------------------------------------------------------------------
     */

    protected function buildJournal($rows) {
        $rowNo = null; // ligne sélectionéée pour la notice
        // Les lignes sont calassée par deba
        foreach ($rows as $i => $candidate) {
            $etatcolldeba = intval($candidate['etatcolldeba']);
            
            // Vrai si le mot supplément n'est pas dans le text
            $pas_un_supplement = stripos($candidate['titre'], 'Suppl') === false;
            
            if ($etatcolldeba < 9999 && $etatcolldeba > 0 && $pas_un_supplement) {
                $rowNo = $i;

                break;
            }
        }

        // Aucune ligne trouvée, on choisi d'après la date de modif

        if ($rowNo == null) {
            $rowNo = 0;
            foreach ($rows as $i => $candidate) {
                $tref = strtotime($rows[$rowNo]['datemodif']);
                $tcdt = strtotime($candidate['datemodif']);
                if ($tcdt > $tref &&
                        stripos($candidate['titre'], 'Supplement') === false && stripos($candidate['titre'], 'Suppl.') === false) {
                    $rowNo = $i;
                }
            }
        }

        // Sélection du journal le plus récent

        $row = $rows[$rowNo];
        //$titleRowNo = $this->findBestTitleJournalRow($rows, $rowNo);

        $jrn = new Journal();
        $jrn->perunilid = $row['perunilid_fusion'];
        $jrn->titre = $row['titre']; //$rows[$titleRowNo]['titre'];
        $jrn->soustitre = $row['soustitre']; //$rows[$titleRowNo]['soustitre'];

        $jrn->titre_abrege = $row['titreabrege'];
        $jrn->titre_variante = $row['variantetitre'];
        $jrn->faitsuitea = $row['faitsuitea'];
        $jrn->devient = $row['devient'];
        $jrn->issn = $row['issn'];
        $jrn->issnl = $row['issnl'];
        $jrn->nlmid = $row['nlmid'];
        $jrn->reroid = $row['reroid'];
        $jrn->doi = $row['doi'];
        //$jrn->coden = $row['coden'];
        $jrn->urn = $row['urn'];
        $jrn->publiunil = $this->findPubliUnil($rows, $rowNo);
        $jrn->url_rss = $row['rss'];
        $jrn->commentaire_pub = $this->findLastNonEmpty($rows, 'commentairepub');
        $jrn->parution_terminee = 0;
        $jrn->openaccess = $row['openaccess'];
        $jrn->DEPRECATED_historique = $row['historique'];

        if ($jrn->save(false)) {
            echo "   > :-) journal $jrn->perunilid enregistré \n";
        } else {
            echo "   > ERREUR lors de l'enregistrement du journal {$row['perunilid_fusion']} \n";
        }

        echo "      - Mise à jour de la création \n";
        $this->setModification($row, $jrn, 'Création');
        echo "      - Mise à jour de la dernière modification \n";
        $this->setModification($row, $jrn, 'Modification');

        return $jrn;
    }

    /**
     * Sélectionne la notice journal la plus récente qui contient à la fois un titre et un 
     * sous
     * @param array $rows
     * @return int Numéro de la ligne qui contient les information pertinantes
     */
    protected function findBestTitleJournalRow($rows, $rowNo) {

        // La si une notice contient un titre et un soustitre, on la conserve de préférence
        foreach ($rows as $i => $row) {
            $titre = trim($row['titre']);
            $soustitre = trim($row['soustitre']);
            if (!empty($titre) && !empty($soustitre)) {
                return $i;
            }
        }
        // La ligne la plus récente est sélectionnée par défaut
        return $rowNo;
    }

    protected function findPubliUnil($rows, $rowNo) {
        foreach ($rows as $row) {
            if ($row['publiunil'] == 1) {
                return 1;
            }
        }
        return $rowNo;
    }

    /* -------------------------------------------------------------------------
     * 
     *  TRAITEMENT DES ABONNEMENTS
     * 
     * -------------------------------------------------------------------------
     */

    protected function bulidAbonnement(Journal $journal, array $row) {

        $abo = new Abonnement();

        $abo->titreexclu = $row['titreexclu'];
        $abo->package = $row['package'];
        $abo->no_abo = $row['idediteur'];
        if (!empty($row['coden'])) {
            $abo->no_abo .= "| reroholdid:" . $row['coden'];
        }
        $abo->url_site = $row['url'];
        $abo->acces_elec_gratuit = $row['acceseleclibre'];
        $abo->acces_elec_unil = $row['acceselecunil'];
        $abo->acces_elec_chuv = $row['acceselecchuv'];
        $abo->embargo_mois = $row['embargo'];
        $abo->acces_user = $row['user'];
        $abo->acces_pwd = $row['pwd'];
        $abo->commentaire_etatcoll = $this->cometatcoll($journal, $row);
        $abo->etatcoll = $row['etatcoll'];
        $abo->etatcoll_deba = $row['etatcolldeba'];
        $abo->etatcoll_debv = $row['etatcolldebv'];
        $abo->etatcoll_debf = $row['etatcolldebf'];
        $abo->etatcoll_fina = $row['etatcollfina'];
        $abo->etatcoll_finv = $row['etatcollfinv'];
        $abo->etatcoll_finf = $row['etatcollfinf'];
        $abo->cote = $row['cote'];
        $abo->editeur_code = $row['codeediteur'];
        $abo->editeur_sujet = $row['keywords'];
        $abo->commentaire_pro = $row['commentairepro'];
        $abo->commentaire_pub = $row['commentairepub'];
        $abo->perunilid = $journal->perunilid;
        $abo->perunilid_old = $row['perunilid'];
        $abo->statutabo = $row['statutabo'];

        $abo->plateforme = $this->findConstTableId($row['plateforme'], 'plateforme');
        $abo->editeur = $this->findConstTableId($row['editeur'], 'editeur');
        $abo->histabo = $this->findConstTableId($row['historiqueabo'], 'histabo');
        $abo->support = $this->findConstTableId($row['support'], 'support');
        // La localisation n'a du sens que pour les journaux papier.
        if ($abo->support == 2) {
            $abo->localisation = $this->findConstTableId($row['localisation'], 'localisation');
        }
        // La gestion n'a de sens que pour les titre électroniques
        if ($abo->support == 1) {
            $abo->gestion = $this->findConstTableId($row['gestion'], 'gestion');
        }
        $abo->format = $this->findConstTableId($row['format'], 'format');
        $abo->licence = $this->findConstTableId($row['licence'], 'licence');

        // Remplacé par perunilid_old : Ajout des anciennes donnée du journal dans le commentaire pro
        //$abo->commentaire_pro .= $this->addPU1Data($row);

        if ($abo->save(false)) {
            echo "   > :-) abonnement $abo->abonnement_id enregistré \n";
        } else {
            echo "   > ERREUR lors de l'enregistrement d'un abonnement du journal $journal->perunilid \n";
        }

        echo "   > Mise à jour de la création \n";
        $this->setModification($row, $abo, 'Création');
        echo "   > Mise à jour de la dernière modification \n";
        $this->setModification($row, $abo, 'Modification');
    }

    protected function cometatcoll($jrn, $row) {

        $titre_selectionne = $jrn->titre;
        $titre_abo_pu1 = $row['titre'];

        // Comparaison    
        similar_text($titre_selectionne, $titre_abo_pu1, $percent);

        if ($percent < 85) {
            return $titre_abo_pu1;
        } else {
            return null;
        }
    }

    /* -------------------------------------------------------------------------
     * 
     *  FONCTION D'AIDE GÉNÉRIQUES
     * 
     * -------------------------------------------------------------------------
     */

    protected function addPU1Data($row) {
        $txt = " || DONNEES PERUNIL 1 : ";
        $data = array(
            "Perunilid : " => $row['perunilid'],
            "Titre : " => $row['titre'],
            "Sous titre : " => $row['soustitre'],
            "Titre abrégé : " => $row['titreabrege'],
            "Variante de titre : " => $row['variantetitre'],
            "Fait suite à : " => $row['faitsuitea'],
            "Devient : " => $row['devient'],
            "issn : " => $row['issn'],
            "issnl : " => $row['issnl'],
            "nlmid : " => $row['nlmid'],
            "reroid : " => $row['reroid'],
            "doi : " => $row['doi'],
            "urn : " => $row['urn'],
            "publiunil : " => $row['publiunil'],
            "Openaccess : " => $row['openaccess'],
            "RSS : " => $row['rss'],
            "Commentaire pub : '" => $row['commentairepub'],
            "Historique : '" => $row['historique']
        );

        foreach ($data as $label => $value) {
            $txt .= $this->PU1DataLine($label, $value);
        }

        return $txt . " || ";
    }

    protected function PU1DataLine($label, $value) {
        $value = trim($value);
        if (!empty($value)) {
            return $label . $value . "; ";
        } else {
            return null;
        }
    }

    protected function findLastNonEmpty($rows, $field) {

        // On renvoie le champ non vide le plus récent
        foreach ($rows as $row) {
            $value = trim($row[$field]);
            if (!empty($value)) {
                return $row[$field];
            }
        }

        // Ce champ est vide pour toutes les lignes
        return "";
    }

    protected function setModification($row, $model, $action) {
        $signature = "";
        $stamp = "";
        if ($action == "Création") {
            $signature = $row['signaturecreation'];
            $stamp = $row['datecreation'];
        } elseif ($action == "Modification") {
            $signature = $row['signaturemodif'];
            $stamp = $row['datemodif'];
        } else {
            throw new Exception("$action n'est pas admis comme action de modification.");
        }

        $criteria = new CDbCriteria;
        $criteria->select = 'utilisateur_id';  // selectionne seulement la colonne 'title'
        $criteria->addSearchCondition('pseudo', $signature);
        $user = Utilisateur::model()->find($criteria);



        $modif = new Modifications();
        $modif->action = $action;
        $modif->model = get_class($model);
        $modif->field = 'Inconnu';
        $modif->stamp = $stamp;
        $modif->user_id = empty($user) ? NULL : $user->utilisateur_id;
        $modif->model_id = $model->primaryKey;


        if ($modif->save(false)) {
            echo "      - $action enregistrée \n";
        } else {
            echo "      - ERREUR lors de l'enregistrement de la $action du journal {$row['perunilid_fusion']} enregistré \n";
        }


        // Création du lien vers la dernière modification
        if ($action == "Création") {
            $model->creation = $modif->id;
        } elseif ($action == "Modification") {
            $model->modification = $modif->id;
        } else {
            throw new Exception("$action n'est pas admis comme action de modification.");
        }
        $model->save(false);
    }

    protected function findConstTableId($value, $field) {
        // Si la valeur est vide, on ne fait rien
        $value = trim($value);
        if (empty($value)) {
            return null;
        }
        $class = ucfirst($field);

        $criteria = new CDbCriteria;
        $criteria->condition = $field . '=:field';
        $criteria->params = array(':field' => $value);
        //$criteria->addSearchCondition($field, $value);
        $model = $class::model()->find($criteria);

        if (!empty($model)) {
            return $model->primaryKey;
        } else {
            return null;
        }
    }

    /* -------------------------------------------------------------------------
     * 
     *  PRÉPARATION DE LA BAmost linkSE DE DONNÉES PERUNIL 2
     * 
     * -------------------------------------------------------------------------
     */

    protected function replacePuIdFuZero() {

        $sql = <<<EOT
UPDATE `perunil_journals`.`journals_fusion`
SET
    `perunilid_fusion` = IF(`perunilid_fusion` = 0, `perunilid`, `perunilid_fusion`)
WHERE 1
EOT;
        $this->executeSQL($sql, 'dbpu1');
    }

    protected function rebulidPU2db() {

        // Supprime toutes les table de la base PU2
        $tables = Yii::app()->db->schema->getTableNames();
        foreach ($tables as $table) {
            Yii::app()->db->createCommand()->dropTable($table);
        }


        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `histabo` (
  `histabo_id` SMALLINT NOT NULL AUTO_INCREMENT,
  `histabo`	varchar(200),
   CONSTRAINT pk_histabo PRIMARY KEY (histabo_id)
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `statutabo` (
  `statutabo_id`      SMALLINT NOT NULL,
  `statutabo`         varchar(200),
   CONSTRAINT pk_statutabo PRIMARY KEY (statutabo_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;


        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `localisation` (
  `localisation_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `localisation`	varchar(200) NOT NULL,
   CONSTRAINT pk_localisation PRIMARY KEY (localisation_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;


        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `gestion` (
  `gestion_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `gestion`	varchar(200) NOT NULL,
   CONSTRAINT pk_gestion PRIMARY KEY (gestion_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;



        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `format` (
  `format_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `format`	varchar(200) NOT NULL,
   CONSTRAINT pk_format PRIMARY KEY (format_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;


        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `support` (
  `support_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `support`	varchar(200) NOT NULL,
   CONSTRAINT pk_support PRIMARY KEY (support_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `plateforme` (
  `plateforme_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `plateforme`	varchar(200) NOT NULL,
   CONSTRAINT pk_plateforme PRIMARY KEY (plateforme_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;


        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `licence` (
  `licence_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `licence`	varchar(200) NOT NULL,
   CONSTRAINT pk_licence PRIMARY KEY (licence_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `editeur` (
  `editeur_id` 	SMALLINT NOT NULL AUTO_INCREMENT,
  `editeur`	varchar(200) NOT NULL,
   CONSTRAINT pk_editeur PRIMARY KEY (editeur_id)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
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
EOT;


        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `sujet` (
  `sujet_id` SMALLINT    NOT NULL AUTO_INCREMENT,
  `code`     varchar(4)  NOT NULL UNIQUE,
  `nom_en`   varchar(50) DEFAULT NULL,
  `nom_fr`   varchar(50) NOT NULL,
  `stm`      BOOLEAN     DEFAULT FALSE,
  `shs`      BOOLEAN     DEFAULT FALSE,
  PRIMARY KEY (`sujet_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `biblio` (
  `biblio_id`      SMALLINT    NOT NULL AUTO_INCREMENT,
  `biblio`            varchar(4)  NOT NULL UNIQUE,
  PRIMARY KEY (`biblio_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `corecollection` (
`perunilid` BIGINT unsigned,
`biblio_id` SMALLINT NOT NULL,
FOREIGN KEY (`perunilid`) REFERENCES journal(`perunilid`),
FOREIGN KEY (`biblio_id`) REFERENCES biblio(`biblio_id`),
PRIMARY KEY (`perunilid`, `biblio_id`)
)ENGINE = MYISAM DEFAULT CHARSET = utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
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
EOT;

        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `journal_sujet` (
  `perunilid` BIGINT unsigned,
  `sujet_id`  SMALLINT NOT NULL,
  FOREIGN KEY (`perunilid`) REFERENCES journal(`perunilid`),
  FOREIGN KEY (`sujet_id`)  REFERENCES sujet(`sujet_id`),
  PRIMARY KEY (`perunilid`, `sujet_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;

        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `corecollection` (
  `perunilid` BIGINT unsigned,
  `biblio_id`  SMALLINT NOT NULL,
  FOREIGN KEY (`perunilid`) REFERENCES journal(`perunilid`),
  FOREIGN KEY (`biblio_id`)  REFERENCES biblio(`biblio_id`),
  PRIMARY KEY (`perunilid`, `biblio_id`)
)ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE 'utf8_unicode_ci';
EOT;


        $sql[] = <<<EOT
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
EOT;


        $sql[] = <<<EOT
CREATE TABLE IF NOT EXISTS `abonnement` (
  `abonnement_id`        SERIAL,
  `titreexclu`           BOOLEAN       DEFAULT FALSE NOT NULL,
  `package`              varchar(250)  DEFAULT NULL,
  `no_abo`               varchar(50)   DEFAULT NULL,
  `url_site`             varchar(2083) DEFAULT NULL,
  `acces_elec_gratuit`   BOOLEAN       DEFAULT FALSE,
  `acces_elec_unil`      BOOLEAN       DEFAULT FALSE,
  `acces_elec_chuv`      BOOLEAN       DEFAULT FALSE,
  `embargo_mois`         TINYINT       DEFAULT NULL COMMENT 'Chiffre donné en mois',
  `acces_user`           varchar(50)   DEFAULT NULL,
  `acces_pwd`            varchar(50)   DEFAULT NULL,
  `commentaire_etatcoll` varchar(500)  DEFAULT NULL,
  `etatcoll`             varchar(250)  DEFAULT NULL,
  `etatcoll_deba`        MEDIUMINT     DEFAULT NULL,
  `etatcoll_debv`        MEDIUMINT     DEFAULT NULL,
  `etatcoll_debf`        MEDIUMINT     DEFAULT NULL,
  `etatcoll_fina`        MEDIUMINT     DEFAULT NULL,
  `etatcoll_finv`        MEDIUMINT     DEFAULT NULL,
  `etatcoll_finf`        MEDIUMINT     DEFAULT NULL,
  `cote`                 varchar(250)  DEFAULT NULL,
  `editeur_code`         varchar(100)  DEFAULT NULL COMMENT 'Code de la revue chez l\'éditeur',
  `editeur_sujet`        varchar(250)  DEFAULT NULL COMMENT 'Sujet chez l\'éditeur, anc. "keywords"',
  `commentaire_pro`      varchar(500)  DEFAULT NULL,
  `commentaire_pub`      varchar(500)  DEFAULT NULL,
  `perunilid`            BIGINT unsigned,            -- FK journal
  `perunilid_old`        BIGINT unsigned,
  `plateforme`           SMALLINT,                   -- FK plateforme
  `editeur`              SMALLINT      DEFAULT NULL, -- FK table éditeur
  `histabo`	         SMALLINT      DEFAULT NULL, -- FK table abo_hist
  `statutabo`            SMALLINT      DEFAULT 0 NOT NULL , -- FK table abo_statut
  `localisation`         SMALLINT      DEFAULT NULL, -- FK
  `gestion`	         SMALLINT      DEFAULT NULL, -- FK
  `format`	         SMALLINT      DEFAULT NULL, -- FK
  `support`	         SMALLINT      DEFAULT NULL, -- FK
  `licence`	         SMALLINT      DEFAULT NULL, -- FK
  `creation`             bigint(20)    DEFAULT NULL,
  `modification`         bigint(20)    DEFAULT NULL,
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
EOT;

        $this->executeSQL($sql);
    }

    protected function populateConstTables() {


        $sql[] = <<<EOT
INSERT INTO `statutabo` (`statutabo_id`, `statutabo`) VALUES
	(0, 'Terminé'),
	(1, 'Actif'),
	(2, 'En test'),
	(3, 'Perdu'),
	(4, 'Problème d\'accès'),
	(5, 'Gestion provisoire');
EOT;

        $sql[] = <<<EOT
INSERT INTO `support` (`support_id`, `support`) VALUES
('1', 'electronique'),
('2', 'papier');
EOT;
        $sql[] = <<<EOT
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
EOT;

        $sql[] = <<<EOT
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
EOT;
        $this->executeSQL($sql);
    }

    protected function populateListTables() {

        $sql[] = <<<EOT
INSERT INTO `histabo` (`histabo`)
SELECT DISTINCT `perunil_journals`.`journals_fusion`.historiqueabo
FROM `perunil_journals`.`journals_fusion`
WHERE `perunil_journals`.`journals_fusion`.historiqueabo <> ""
AND `perunil_journals`.`journals_fusion`.historiqueabo IS NOT NULL;
EOT;

        $sql[] = <<<EOT
INSERT INTO `localisation` (`localisation`)
SELECT DISTINCT `perunil_journals`.`journals_fusion`.localisation
FROM `perunil_journals`.`journals_fusion`
WHERE `perunil_journals`.`journals_fusion`.localisation <> ""
AND `perunil_journals`.`journals_fusion`.localisation IS NOT NULL;
EOT;

        $sql[] = <<<EOT
INSERT INTO `gestion` (`gestion`)
SELECT DISTINCT `perunil_journals`.`journals_fusion`.`gestion`
FROM `perunil_journals`.`journals_fusion`
WHERE `perunil_journals`.`journals_fusion`.`gestion` <> ""
AND `perunil_journals`.`journals_fusion`.`gestion` IS NOT NULL;
EOT;

        $sql[] = <<<EOT
INSERT INTO `format` (`format`)
SELECT DISTINCT `perunil_journals`.`journals_fusion`.`format`
FROM `perunil_journals`.`journals_fusion`
WHERE `perunil_journals`.`journals_fusion`.`format` <> ""
AND `perunil_journals`.`journals_fusion`.`format` IS NOT NULL;
EOT;

        $sql[] = <<<EOT
INSERT INTO `plateforme` (`plateforme`)
SELECT DISTINCT `perunil_journals`.`journals_fusion`.plateforme
FROM `perunil_journals`.`journals_fusion`
WHERE `perunil_journals`.`journals_fusion`.plateforme <> ""
AND `perunil_journals`.`journals_fusion`.plateforme IS NOT NULL;
EOT;

        $sql[] = <<<EOT
INSERT INTO `licence` (`licence`)
SELECT DISTINCT `perunil_journals`.`journals_fusion`.licence
FROM `perunil_journals`.`journals_fusion`
WHERE `perunil_journals`.`journals_fusion`.licence <> ""
AND `perunil_journals`.`journals_fusion`.licence IS NOT NULL;
EOT;

        $sql[] = <<<EOT
INSERT INTO `editeur`
(editeur)
select distinct editeur
from `perunil_journals`.`journals_fusion`;
EOT;

        $sql[] = <<<EOT
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
EOT;

        $this->executeSQL($sql);
    }

    protected function linkJournalSujet() {


        $sql = <<<EOT
INSERT INTO `journal_sujet`
(perunilid,sujet_id)
SELECT DISTINCT
journal.perunilid, `perunil_journals`.journals_sujets.sujetsid
FROM journal, `perunil_journals`.journals_sujets, sujet
WHERE `perunil_journals`.journals_sujets.perunilid_fusion = journal.perunilid
AND `perunil_journals`.journals_sujets.sujetsid = sujet.sujet_id;
EOT;

        $this->executeSQL($sql);
    }

    protected function linkBiUMCorecollection() {


        $sql = <<<EOT
INSERT INTO `corecollection`
(perunilid, biblio_id)
SELECT DISTINCT
`perunil_journals`.`journals_fusion`.perunilid_fusion, 6
FROM `perunil_journals`.`journals_fusion`
WHERE `perunil_journals`.`journals_fusion`.corecollection = 1;
EOT;

        $this->executeSQL($sql);
    }

    protected function executeSQL($sql, $db = 'db') {
        $affectedRows = 0;
        if (!is_array($sql)) {
            $sql = array($sql);
        }

        foreach ($sql as $cmd) {
            $affectedRows = Yii::app()->$db->createCommand($cmd)->execute();
        }

        echo ">>>> $affectedRows lignes traitées\n\n";
    }

    /* -------------------------------------------------------------------------
     * 
     *  PRÉPARATION DE LA BASE DE DONNÉES PERUNIL 1
     * 
     * -------------------------------------------------------------------------
     */

    protected function completeModifIfNull() {

        $sql = <<<EOT
UPDATE `perunil_journals`.`journals_fusion`
SET
    `datemodif` = IF(`datemodif` IS NULL, `datecreation`, `datemodif`)
WHERE 1
EOT;
        $this->executeSQL($sql, 'dbpu1');
    }

    protected function prepareJournalSujet() {
        $testColumnQuery = <<<EOT
SELECT * 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'perunil_journals' 
AND TABLE_NAME = 'journals_sujets' 
AND COLUMN_NAME = 'perunilid_fusion'
EOT;
        if (!Yii::app()->dbpu1->createCommand($testColumnQuery)->execute()) {

            $sql[] = <<<EOT
ALTER TABLE `perunil_journals`.`journals_sujets` ADD `perunilid_fusion` BIGINT;
EOT;
        }
        $sql[] = <<<EOT
UPDATE `perunil_journals`.`journals_sujets`, `perunil_journals`.`journals_fusion`
SET  `perunil_journals`.`journals_sujets`.`perunilid_fusion` = `perunil_journals`.`journals_fusion`.`perunilid_fusion`
WHERE `perunil_journals`.`journals_fusion`.`perunilid` =  `perunil_journals`.`journals_sujets`.`perunilid`;
EOT;

        $this->executeSQL($sql, 'dbpu1');
    }

}
