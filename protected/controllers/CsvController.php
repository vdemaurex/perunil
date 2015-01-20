<?php

class CsvController extends Controller {

    private $tables = array(
        'journal',
        'abonnement',
        'editeur',
        'plateforme',
        'histabo',
        'statutabo',
        'localisation',
        'gestion',
        'format',
        'support',
        'licence'
    );

    /**
     *
     * @var CSVParser 
     */
    protected $parser;

    /**
     * Charge le CSVParser stocké dans la session comme objet du controller pour 
     * en facilité l'accès.
     * @param CAction $action
     */
    public function beforeAction($action) {
//        parent::beforeAction($action);
        if (isset(Yii::app()->session['csvparser'])) {
            $this->parser = Yii::app()->session['csvparser'];
        }
        return parent::beforeAction($action);
    }

    /**
     * A la fin de l'exécution du controller, le CSVParser est stocké dans la 
     * session.
     * @param CAction $action
     */
    public function afterAction($action) {

        if (isset($this->parser)) {
            Yii::app()->session['csvparser'] = $this->parser;
        }
        return parent::afterAction($action);
    }

    //////////////////////////////////////////////////////////////////// ETAPE 1
    /**
     * Affiche le formulaire pour importer le fichier CSV.
     * Nettoie les variables de session.
     */
    public function actionIndex() {

        // Nettoyage des variables de session
        if (isset(Yii::app()->session['csvparser'])) {
            unset(Yii::app()->session['csvparser']);
            unset($this->parser);
        }
        // Création et affichage d'un formulaire vierge
        $this->render('index', array('model' => new CsvImportForm()));
    }

    //////////////////////////////////////////////////////////////////// ETAPE 2
    /**
     * Importe le fichier CSV
     *  1. vérifie l'existence du fichier
     *  2. Importe de fichier
     *  3. Crée l'instance CSVPaser
     *  4a. Affiche les erreurs d'imporation.
     *  4b. Redirige vers les questions à l'utilisateur
     */
    public function actionImport() {

        $assocArray = array();
        $errors = array();
        // Si le formulaire d'upload à été correctement rempli
        if (isset($_POST['CsvImportForm'])) {

            $model = new CsvImportForm();
            $model->attributes = $_POST['CsvImportForm'];
            if ($model->validate()) {
                // Ouverture du fichier
                $csvfile = CUploadedFile::getInstance($model, 'fichier');
                $f = fopen($csvfile->tempName, 'r');
                if ($f) {
                    $assocArray = $fields = array();
                    $i = 0;

                    while (($row = fgetcsv($f, 4096, $model->getDelimiter())) !== false) {
                        // Si c'est la première ligne
                        if (empty($fields)) {
                            // Récupération des entêtes.
                            $fields = $row;
                            continue;
                        }
                        // Récupération des données
                        foreach ($row as $k => $value) {
                            $assocArray[$i][$fields[$k]] = $value;
                        }
                        $i++;
                    }
                    if (!feof($f)) {
                        $errors[] = "La lecture du fichier CSV a échoué.";
                    }
                    fclose($f);
                } else {
                    // Impossible d'ouvrir le fichier
                    $errors[] = "Erreur serveur : ouverture du Fichier impossible";
                }//if ($f)
            } else {
                // Echec de la validation
                $errors[] = "Le fichier n'est pas conforme";
            } //if ($model->validate())
        } else {
            // Le formulaire n'a pas été rempli
            $errors[] = "Aucun fichier à téléverser.";
        } // if (isset($_POST['CsvImportForm']))


        if (empty($errors)) {
            // Lecture du fichier s'est passée sans erreur
            // Création du parser
            try {
                Yii::app()->session['csvparser'] = new CSVParser($assocArray);
                $this->parser = Yii::app()->session['csvparser'];
            } catch (Exception $e) {
                // Exception seulement levée par l'accès à la base de données
                $errors[] = $e->getMessage();
            }
        }

        if (empty($errors)) {
            $this->render('import-sucess');
        } else {
            $this->render('import-error', array('errors' => $errors));
        }
    }

    //////////////////////////////////////////////////////////////////// ETAPE 3
    /**
     * Tant que certains livres ne sont pas fixé, on pose des questions à
     * l'utilisateur.
     */
    public function actionAsk() {
        $this->noParserGoToIndex();

        $row = $this->parser->next2search();
        if ($row) {
            // afficher les questions
            $this->render('ask', array('row' => $row));
        } else {
            // affiche la pévisualisation des résultats
            $this->redirect($this->createUrl("csv/preview"));
        }
    }

    public function actionAnswer() {
        $this->noParserGoToIndex();
        // Récupération de la  ligne à traiter.
        $row = $this->parser->next2search();

        $formno = filter_input(INPUT_POST, 'formno', FILTER_VALIDATE_INT);

        switch ($formno) {
            case '1': // Assigner le journal choisi
                $perunilid = filter_input(INPUT_POST, 'perunilid', FILTER_SANITIZE_STRING);
                // Perunilid spécifié
                if ($perunilid == 'SPECIFIED') {
                    $perunilid = filter_input(INPUT_POST, 'specified_perunilid', FILTER_VALIDATE_INT);
                } else {
                    // Perunilid séléctionné dans la liste
                    $perunilid = filter_input(INPUT_POST, 'perunilid', FILTER_VALIDATE_INT);
                }

                // Recherche du journal
                $jrn = Journal::model()->findByPk($perunilid);
                if (!empty($jrn) && is_a($jrn, 'Journal')) {
                    // Le journal est valide. Mise à jour de la ligne.
                    $row->setCreateState($jrn);
                    Yii::app()->user->setFlash('success', "L'assignation du journal N° $perunilid pour la ligne n° $row->noRow à réussi. " .
                            CHtml::button('Editer ce journal dans une nouvelle fenêtre', array('onclick' => 'js:window.open("' . Yii::app()->createUrl('/admin/peredit/perunilid/' . $jrn->perunilid) . '","_blank")',
                                'class' => "btn btn-default btn-xs")));
                } else {
                    Yii::app()->user->setFlash('error', "Une erreur est survenue. Le journal $perunilid n'est pas valide. La ligne n° $row->noRow ne peut pas être traitée.");
                }
                break;



            case '2': // Créer un nouveau journal
                $jrn = new Journal();
                $jrn->titre = filter_input(INPUT_POST, 'journalTitle', FILTER_SANITIZE_STRING);
                $jrn->issn = filter_input(INPUT_POST, 'journalIssn', FILTER_SANITIZE_STRING);
                $jrn->issnl = filter_input(INPUT_POST, 'journalIssnl', FILTER_SANITIZE_STRING);

                if ($jrn->validate()) {
                    if ($jrn->save()) {
                        $row->setCreateState($jrn);
                        Yii::app()->user->setFlash('success', "La création du journal N° $jrn->perunilid et son asignation à la ligne n° $row->noRow à réussi. " .
                                CHtml::button('Editer ce journal dans une nouvelle fenêtre', array('onclick' => 'js:window.open("' . Yii::app()->createUrl('/admin/peredit/perunilid/' . $jrn->perunilid) . '","_blank")',
                                    'class' => "btn btn-default btn-xs")));
                        //  CHtml::link("Editer ce journal dans une nouvelle fenêtre", Yii::app()->createUrl('/admin/peredit/perunilid/' . $jrn->perunilid), array('target' => '_blank')));
                    } else {
                        Yii::app()->user->setFlash('error', "Une erreur est survenue, Impossible d'enregister un nouveau journal");
                    }
                } else {
                    Yii::app()->user->setFlash('error', "Une erreur est survenue, Impossible de créer un nouveau journal, les données fournies ne sont pas correctes.");
                }

                break;



            case '3': // Ne pas traiter la ligne
                $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);

                if ($state == CSVRow::REJECTED) {
                    Yii::app()->user->setFlash('success', "La ligne n° $row->noRow ne sera pas traitée");
                    $row->setRejectedState();
                } else {
                    Yii::app()->user->setFlash('error', "Une erreur est survenue, la ligne n° $row->noRow ne peut pas être passée en mode 'rejetée', car l'état $state n'est pas accepté.");
                }
                break;
            default:
                throw new Exception("Le formulaire n° $formno n'existe pas.");
        }

        // On passe à la question suivante 
        // ou à la même question si une erreur s'est produite.
        //$this->actionAsk();
        $this->redirect($this->createUrl("csv/ask"));
    }

    //////////////////////////////////////////////////////////////////// ETAPE 4
    public function actionPreview() {
        $this->noParserGoToIndex();
        // Préparation des changements, sans les appliquer
        Yii::app()->user->setFlash('success', "Toutes  les lignes ont été traitées. Le fichier est prêt pour l'importation.");
        $this->render('preview', array('proceededRows' => $this->parser->getProceededRows()));
    }

    //////////////////////////////////////////////////////////////////// ETAPE 5
    public function actionExecImport() {
        $this->noParserGoToIndex();

        try {
            $this->parser->doUpdate();
        } catch (Exception $exc) {
            Yii::app()->user->setFlash('error', "Une erreur est survenue lors du processus d'importation : " . $exc->getMessage());
        }
        $this->render('savereport');
    }

    /**
     * Exportation des résultats de la recherche admin au format CSV.
     */
    public function actionExport() {
        if (Yii::app()->session['searchtype'] != 'admin') {
            Yii::app()->user->setFlash('error', "Merci d'utiliser l'exporation CSV depuis les résultats de la recherche admin uniquement.");
            return;
        }

        $ids = Yii::app()->session['search']->getAdminIds();
        $ids_comma_separated = implode(",", $ids);


        $command = Yii::app()->db->createCommand();
        /**
         * Chaque colonne de la requête SQL formera une colonne du fichier
         * CSV. La nom défini avec "AS" sera le titre de la colone dans le
         * ficher CSV 
         */
        $command->select(array(
            "j.titre              AS journal-titre",
            "j.issn               AS journal-issn",
            "j.issnl              AS journal-issnl",
            "j.reroid             AS journal-reroid",
            "a.perunilid          AS perunilid",
            "a.abonnement_id      AS abonnement_id",
            "a.titreexclu         AS titreexclu",
            "a.package            AS package",
            "a.no_abo             AS no_abo",
            "a.url_site           AS url_site",
            "a.acces_elec_gratuit AS acces_elec_gratuit",
            "a.acces_elec_unil    AS acces_elec_unil",
            "a.acces_elec_chuv    AS acces_elec_chuv",
            "a.embargo_mois       AS embargo_mois",
            "a.acces_user         AS acces_user",
            "a.acces_pwd          AS acces_pwd",
            "a.etatcoll           AS etatcoll",
            "a.etatcoll_deba      AS etatcoll_deba",
            "a.etatcoll_debv      AS etatcoll_debv",
            "a.etatcoll_debf      AS etatcoll_debf",
            "a.etatcoll_fina      AS etatcoll_fina",
            "a.etatcoll_finv      AS etatcoll_finv",
            "a.etatcoll_finf      AS etatcoll_finf",
            "a.cote               AS cote",
            "a.editeur_code       AS editeur_code",
            "a.editeur_sujet      AS editeur_sujet",
            "a.commentaire_pro    AS commentaire_pro",
            "a.commentaire_pub    AS commentaire_pub",
            "a.plateforme         AS plateforme",
            "plt.plateforme       AS Nom-plateforme",
            "a.editeur            AS editeur",
            "ed.editeur           AS Nom-editeur",
            "a.histabo            AS histabo",
            "ha.histabo           AS Nom-histabo",
            "a.statutabo          AS statutabo",
            "sa.statutabo         AS Nom-statutabo",
            "a.localisation       AS localisation",
            "loc.localisation     AS Nom-localisation",
            "a.gestion            AS gestion",
            "gest.gestion         AS Nom-gestion",
            "a.format             AS format",
            "frm.format           AS Nom-format",
            "a.support            AS support",
            "sprt.support         AS Nom-support",
            "a.licence            AS licence",
            "lic.licence          AS Nom-licence",
            "ac.stamp             AS creation",
            "am.stamp             AS modification"
        ));

        // Traitement différent selon le type d'affichage actuel
        if (Yii::app()->session['search']->getAdmin_affichage() == 'abonnement') {
            $command->from("abonnement a");
            $command->join("journal j", "j.perunilid = a.perunilid");
            $command->where("a.abonnement_id in ($ids_comma_separated)");
 
        } else {
            $command->from("journal j");
            $command->join("abonnement a", "j.perunilid = a.perunilid");
            $command->where = "j.perunilid in ($ids_comma_separated)";
            // Pour les journaux, il faut encore supprimer les abonnement qui ne sont pas au bon format
            if (Yii::app()->session['search']->support > 0) {
                $command->where .= " AND a.support = " . Yii::app()->session['search']->support;
            }
            
      }
        
        $command->leftJoin("plateforme plt", "a.plateforme = plt.plateforme_id");
        $command->leftJoin("editeur ed", "a.editeur    = ed.editeur_id");
        $command->leftJoin("histabo ha", "a.histabo    = ha.histabo_id");
        $command->leftJoin("statutabo sa", "a.statutabo  = sa.statutabo_id");
        $command->leftJoin("localisation loc", "a.localisation = loc.localisation_id");
        $command->leftJoin("gestion gest", "a.gestion   = gest.gestion_id");
        $command->leftJoin("format frm", "a.format    = frm.format_id");
        $command->leftJoin("support sprt", "a.support   = sprt.support_id");
        $command->leftJoin("licence lic", "a.licence   = lic.licence_id");

        $command->leftJoin("modifications jm", "j.modification = jm.id");
        $command->leftJoin("modifications jc", "j.creation     = jc.id");

        $command->leftJoin("modifications am", "a.modification = am.id");
        $command->leftJoin("modifications ac", "a.creation     = ac.id");


        // Génération du fichier CSV
        // Extension ECSVExport : http://www.yiiframework.com/extension/csvexport
        Yii::import('ext.ECSVExport');
        $csv = new ECSVExport($command);
        $csv->setDelimiter(";");

        /**
         * Génération du fichier à la volée 
         */
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Perunil2CSV-' . date('YmdHi') . '.csv"');
        echo $csv->toCSV();
    }

    private function noParserGoToIndex() {
        if (empty(Yii::app()->session['csvparser'])) {
            Yii::app()->user->setFlash('error', "Aucun fichier CSV en cours d'importation.");
            $this->redirect($this->createUrl("csv/index"));
        }
    }

}
