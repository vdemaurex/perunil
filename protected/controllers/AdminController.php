<?php

class AdminController extends Controller {

    public $support = 0;
    public $last = null; // Dernier formulaire reçu.

    //public $field;

    public function filters() {
        return array(
            'accessControl',
        );
    }

    /**
     * Règles d'accès : seul les utilisateurs authentifiés peuvent se connecter.
     * @return type 
     */
    public function accessRules() {
        return array(
            array('deny', // deny anything else
                'users' => array('?'),
            ),
        );
    }
    
    /**
     * Listes les actions qui sont définies sous forme de classe
     * @return array
     */
    public function actions() {
        return array(
            'csvexport'        => 'application.controllers.admin.CsvexportAction',
            'csvimport'        => 'application.controllers.admin.CsvimportAction',
            'csvimportprocess' => 'application.controllers.admin.CsvimportprocessAction',
            'upload'           => 'application.controllers.admin.UploadAction',
        );      
    }

    /**
     * Page d'accueil de l'administration. 
     */
    public function actionIndex() {
        $this->render('index');
    }


    /**
     * Fusionne les journaux selon les information de $_POST :
     * - Liste des perunilid des journaux à fusionner.
     * - Perunilid du journal qui sera conserver.
     * La fonction renvoie l'utilisateur à la page précdente, en affichant avec
     * le Flash le résultat de la fusion.
     */
    public function actionFusion() {
        // Définition de l'URL de retour.
        if (!isset($_POST['REQUEST_URI'])) {
            $returnUrl = Yii::app()->user->returnUrl;
        } else {
            $returnUrl = $_POST['REQUEST_URI'];
        }

        // Vérifications de base de la liste d'id et du maître
        try {
            // Vérifier que la liste des ID est > 1
            if (!isset($_POST['perunilid']) || count($_POST['perunilid']) < 2) {
                throw new Exception("La liste des journaux à fusionner comporte moins de 2 " .
                        "éléments. Impossible de réaliser la fusion.");
            }
            // Vérifier que le maître est dans la liste des ID
            if (!isset($_POST['maitre']) || !in_array($_POST['maitre'], $_POST['perunilid'])) {
                throw new Exception("Pour réaliser la fusion, il est nécessaire de définir " .
                        "le journal qui sera la notice modèle, auquel les " .
                        "abonnements seront attachés.");
            }
        } catch (Exception $exc) {
            Yii::app()->user->setFlash('error', "<h3>La fusion des journaux a échoué. </h3>" . $exc->getMessage());
            $this->redirect($returnUrl);
        }


        $listID = $_POST['perunilid'];
        $maitre = $_POST['maitre'];
        // Début de la fusion
        try {
            // Suppression du maître de listeID
            unset($listID[$maitre]);
            $maitre_jrn = Journal::model()->findByPk($maitre);
            if (!$maitre_jrn) {
                throw new Exception("le perunilid $perunilid est invalide.");
            }

            foreach ($listID as $perunilid) {

                $jrn = Journal::model()->findByPk($perunilid);
                if (!$jrn) {
                    throw new Exception("le perunilid $perunilid est invalide.");
                }
                // Association des abonnement au maître
                foreach ($jrn->abonnements as $abo) {
                    $abo->perunilid = $maitre_jrn->perunilid;
                    if (!$abo->save(false)) {
                        throw new Exception("Impossible d'enregistrer l'abonnement id $abo->abonnement_id.");
                    }
                }
                // Suppression du journal
                //if (!$jrn->delete()) {
                //    throw new CException("Impossible de supprimer le journal id $jrn->perunilid.");
                //}
            }
        } catch (Exception $exc) {
            Yii::app()->user->setFlash('error', $exc->getMessage() . "<br/>" . $exc->getTraceAsString());
        }
        Yii::app()->user->setFlash('success', "Les journaux ont bien été fusionnés.");
        $this->redirect($returnUrl);
    }

    /**
     * Affiche le formulaire d'étidion du journal ainsi que les abonnement liés.
     * @param int $perunilid id du journal à éditer. Si aucun id n'est fourni, il 
     *                       l'édition d'un nouveau journal est proposée. 
     */
    public function actionPeredit($perunilid = NULL) {

        //$this->layout = 'rightSidebar';
        if (isset($perunilid)) {
            $model = Journal::model()->findByPk($perunilid);
        }
        if (!isset($model)) {
            $model = new Journal;
        }

        // Soumission du formulaire pour sauvegarde.
        if (isset($_POST['Journal'])) {
            $model->attributes = $_POST['Journal'];
            if ($model->validate()) {
                // Le formulaire est valide
                // 
                // GESTION DES SUJETS
                // 
                // Ne conserver que les sujet avec un nombre
                $nouvsujets = array_filter($_POST['Journal']['sujet']);
                // Pour chacun des sujet du journal :
                foreach ($model->sujets as $sujet) {
                    // Si le sujet n'existe pas dans la liste des nouveaux sujets
                    //  Supprimer ce sujet de journal
                    $key = array_search($sujet->sujet_id, $nouvsujets);
                    if ($key === false) {
                        $sujet->delete();
                    }
                    // Si le sujet existe dans la liste des nouveaux sujets
                    //    Supprimer le sujet de liste des nouveaux sujets
                    else {
                        unset($nouvsujets[$key]);
                    }
                }
                // Pour tous les sujets restant dans la liste des nouveaux sujets
                //  Ajouter ces sujet à journal
                foreach ($nouvsujets as $sujet_id) {
                    $js = new JournalSujet();
                    $js->perunilid = $model->perunilid;
                    $js->sujet_id = $sujet_id;
                    $js->save();
                }
                // 
                // GESTION DES CORE COLLECTION
                // 
                // Ne conserver que les corecollection avec un nombre
                $nouvcc = array_filter($_POST['Journal']['corecollection']);
                // Pour chacun des sujet du journal :
                foreach ($model->corecollection as $cc) {
                    // Si le sujet n'existe pas dans la liste des nouveaux sujets
                    //  Supprimer ce sujet de journal
                    $key = array_search($cc->biblio_id, $nouvcc);
                    if ($key === false) {
                        $cc->delete();
                    }
                    // Si le sujet existe dans la liste des nouveaux sujets
                    //    Supprimer le sujet de liste des nouveaux sujets
                    else {
                        unset($nouvcc[$key]);
                    }
                }
                // Pour tous les sujets restant dans la liste des nouveaux sujets
                //  Ajouter ces sujet à journal
                foreach ($nouvcc as $biblio_id) {
                    $cc = new Corecollection();
                    $cc->perunilid = $model->perunilid;
                    $cc->biblio_id = $biblio_id;
                    $cc->save();
                }

                // Enregistrement des changements des attributs du journal.
                if ($model->save()) {
                    $model->refresh();
                    // $jrn = Journal::model()->findByPk($model->perunilid);
                    $str = "Le périodique «". CHtml::link($model->titre, array("site/detail/".$model->perunilid )) ."» a bien été enregistré.";
                    Yii::app()->user->setFlash('success', $str);
                } else { // L'enregistrement à échoué.
                    Yii::app()->user->setFlash('error', "Le périodique «{$_POST['Journal']['titre']}» n'a pas été enregistré.");
                }
            } else { // La validation n'a pas passé
                Yii::app()->user->setFlash('notice', "Le formulaire contient des erreurs");
            }
        }

        $this->render('peredit', array('model' => $model));
    }

    /**
     * Edition d'un abonement d'un journal.
     * @param type $perunilid ID du journal
     * @param type $aboid ID de l'abonnement à éditer. Si NULL, nouvel abonnement.
     * @throws CException Levée si l'id du journal est invalide.
     */
    public function actionAboedit($perunilid, $aboid = NULL) {
        //$this->layout = 'rightSidebar';
        $jrn = Journal::model()->findByPk($perunilid);
        if (!isset($perunilid) || !isset($jrn)) {
            throw new CException("L'ajout d'un abonnement ne peut se faire que sur périodique existant (perunilid = $perunilid )");
        }

        if (isset($aboid)) {
            $abo = Abonnement::model()->findByPk($aboid);
        } else {
            $abo = new Abonnement();
            $abo->perunilid = $perunilid;
        }

        // Soumission du formulaire pour sauvegarde.
        if (isset($_POST['Abonnement'])) {

            $abo->attributes = $_POST['Abonnement'];
            if (isset($_POST['Abonnement']['perunilid']) &&  $_POST['Abonnement']['perunilid'] != ""){
                if(!Journal::model()->findByPk($_POST['Abonnement']['perunilid'])){
                    Yii::app()->user->setFlash('error', 
                            "Le perunilid ({$_POST['Abonnement']['perunilid']}) n'est pas valable. ".
                            "L'ancienne valeur ($perunilid) a été restaurée.");
                    $abo->perunilid = $perunilid;  
                }
            }
            if ($abo->validate()) {
                // Le formulaire est valide
                if ($abo->save()) {
                    $abo->refresh();
                    Yii::app()->user->setFlash('success', "L'abonnement n° {$abo->abonnement_id} a bien été enregistré.");
                } else { // L'enregistrement à échoué.
                    Yii::app()->user->setFlash('error', "L'abonnement n° {$_POST['Abonnement']['abonnement_id']}» n'a pas été enregistré.");
                }
            } else { // La validation n'a pas passé
                Yii::app()->user->setFlash('notice', "Le formulaire contient des erreurs");
            }
        }

        $this->render('aboedit', array('jrn' => $jrn, 'model' => $abo));
    }

    public function actionAbodelete($perunilid, $aboid) {
        $abo = Abonnement::model()->findByPk($aboid);
        if (!isset($aboid) || !isset($abo)) {
            throw new CException("Impossible de supprimer l'abonnement (id = $aboid ) car il n'existe pas.");
        }
        if (!$abo->delete()) {
            Yii::app()->user->setFlash('error', "La suppression de l'abonnement (id = $aboid ) a echoué.");
            $this->redirect(CController::createUrl('/admin/aboedit/perunilid/' . $perunilid . '/aboid/' . $aboid));
        } else {
            Yii::app()->user->setFlash('success', "L'abonnement (id = $aboid ) a bien été supprimé.");
            $this->redirect(CController::createUrl('/admin/peredit/perunilid/' . $perunilid));
        }
    }

    public function actionJrndelete($perunilid) {
        $jrn = Journal::model()->findByPk($perunilid);
        if (!isset($perunilid) || !isset($jrn)) {
            throw new CException("Impossible de supprimer le journal (id = $perunilid ) car il n'existe pas.");
        }
        if (count($jrn->abonnements)>0){
            Yii::app()->user->setFlash('error', "La suppression du journal (id = $perunilid ) est impossible car il possède des abonnements.");
            return;
        }
        if (!$jrn->delete()) {
            Yii::app()->user->setFlash('error', "La suppression du journal (id = $perunilid ) a echoué.");
            $this->redirect(CController::createUrl('/admin/peredit/perunilid/' . $perunilid));
        } else {
            Yii::app()->user->setFlash('success', "Le journal (id = $perunilid ) a bien été supprimé.");
            $this->redirect(CController::createUrl('/admin/index'));
        }
    }

    /**
     * Recherche administrative 
     */
    public function actionSearch() {
        // Détermine le type d'affichage : par journal ou par abonnement
        $affichage = 'abonnement';
        if (isset(Yii::app()->session['affichage'])){
            if (Yii::app()->session['affichage'] == 'journal'){
                $affichage = 'journal';
            }
        }
        // Mise à jour de l'option d'affichage
        Yii::app()->session['affichage'] = $affichage;
        
        $this->last = null;
        $render_params = array();
        $render_params['search_done'] = count($_GET);

        if ($render_params['search_done']) {
            
            $search = new SearchComponent();
            if ($affichage == 'journal'){
                $render_params['dataProvider'] = $search->adminSearch($_GET);
            } else{ 
                // Affichage par abonnement
                $render_params['dataProvider'] = $search->aboadminSearch($_GET);
            }
            
            // si la requête ne donne aucun résultat, on affiche un avertissement
            if (!isset($render_params['dataProvider'])) {
                $render_params['search_done'] = FALSE;
                Yii::app()->user->setFlash('notice', "Votre requête n'a retourné aucun résultat.<br/>Recherche administrateur : " .
                        $search->getQuerySummary());
            } else {
                // Si la requête a produit un résultat, on affiche le total et
                // un résumé de la query.
                Yii::app()->user->setFlash('success', "Votre requête a retourné " .
                        $render_params['dataProvider']->totalItemCount .
                        " résultat(s).<br/>Recherche administrateur : " .
                        $search->getQuerySummary());
            }
            $this->last = $_GET;
        }
        $this->render('search', $render_params);
    }


    public function actionAddSmallListEntry($type,$id) {
        // Récupération de la classe de la liste
        $model=new $type();
        $colname = strtolower($type);
        if (!$model){
            throw new CException("Impossible d'instancier un objet de la calsse $type");
        }
        
        // Si le formulaire a été remplis, on procède à l'ajout.
        if (isset($_POST[$type])) {
            $newvalue = $_POST[$type][$colname];
            $model->{$colname} = $newvalue;
            $model->save();
            
            $result[] = array(
	       //         'label' => "Ca marche! ",
	                'value' => $newvalue,
	                'id' => $id,
	                //'field' => $m->attribute_for_another_field,
	            );
	 
	    echo CJSON::encode($result);
            yii::app()->end();
        }
        // Affichage du formulaire
        else{
            $this->renderPartial(
                    "_addSmallListEntryForm", 
                    array(
                        'model' => $model,
                        'id'    => $id,
                        )
                    );
        }
    }
    
    public function actionRefreshselect($type) {
        // Récupération de la classe de la liste
        $model=new $type();
        if (!$model){
            throw new CException("Impossible d'instancier un objet de la calsse $type");
        }
        
         $this->renderPartial(
                    "_refreshselect", 
                    array(
                        'model' => $model,
                        )
                    );
    }


    /**
     * Gestion des utilisateurs
     */
    public function actionUsers() {
        //TODO : Implémenter la gestion des utilisateurs.
        $this->render('users');
    }

    /**
     * Recherche et consultation des modification 
     */
    public function actionModifications() {
        //TODO : Implémenter la consultation des modifications
        $this->render('modifications');
    }


    public function actionCsvimportcancel(){
        unset(Yii::app()->session['modif']);
        unset(Yii::app()->session['ajout']);
        Yii::app()->user->setFlash('success', "L'imporation du fichier à été annulée");
        $this->render('csvimport', array('model' => new CsvImportForm()));
    }

    /**
     * 1. Upload du fichier CSV 
     * 2. Analyse du fichier pour en vérifier la conformité
     */
    /*public function actionAjaxupload(){
        
        //
        // Imporation du fichier
        //
        Yii::import("ext.EAjaxUpload.qqFileUploader");

        $folder = 'upload/'; // folder for uploaded files
        $allowedExtensions = array("csv"); //array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 2 * 1024 * 1024; // maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        
        
        //
        // Si le résulat est valable
        //
        if ($result['success']){
            $row = 1;
            if (($handle = fopen($folder . $result['filename'], "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    echo "<p> $num champs à la ligne $row: <br /></p>\n";
                    $row++;
                    for ($c=0; $c < $num; $c++) {
                        echo $data[$c] . "<br />\n";
                    }
                }
                fclose($handle);
            }
        }
        
        //
        // Renvoi des résulat
        //
        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        echo $result; // it's array
    }*/
    
}