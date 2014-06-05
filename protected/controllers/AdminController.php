<?php

class AdminController extends Controller {

    public $support = 0;
    public $last = null; // Dernier formulaire reçu.
// Type de champs
    public $textfields = array(
        'package',
        'no_abo',
        'etatcoll',
        'cote',
        'editeur_sujet',
        'acces_user',
        'acces_pwd',
        'url_site',
        'editeur_code',
        'commentaire_pro',
        'commentaire_pub');
    public $abolinks = array(
        'plateforme',
        'editeur',
        'histabo',
        'statutabo',
        'localisation',
        'gestion',
        'format',
        'support',
        'licence');

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
//            'csvexport' => 'application.controllers.admin.CsvexportAction',
//            'csvimport' => 'application.controllers.admin.CsvimportAction',
//            'csvimportprocess' => 'application.controllers.admin.CsvimportprocessAction',
                //'upload'           => 'application.controllers.admin.UploadAction',
        );
    }

    /**
     * Page d'accueil de l'administration.
     */
    public function actionIndex() {
        $this->render('index');
    }

    /**
     * Traite le formulaire de modification par lot, affiche la confirmation et applique les modifications.
     * 
     * @return null
     */
    public function actionBatchprocessing() {

        $add_text = isset($_POST['add_text']) && $_POST['add_text'] == false ? false : true;
        $stage = isset($_POST['stage']) ? $_POST['stage'] : "1-form";

        switch ($stage) {

            case '1-form':
// Nettoyage des variables de session
                if (isset(Yii::app()->session['updt']))
                    unset(Yii::app()->session['updt']);
// Etape 1 : le formulaire n'a pas été rempli
                break;


            case '2-preview':
// Etape 2 : le formulaire est rempli, affichage de la confirmation
                $a = $_POST['Abonnement'];
                $updt = array();

// Traitement des boolean    
                foreach (array('titreexclu', 'acces_elec_unil', 'acces_elec_chuv', 'acces_elec_gratuit') as $boolinput) {
                    if (isset($a[$boolinput]) && ($a[$boolinput] == '1' || $a[$boolinput] == '0' )) {

                        $updt[$boolinput] = $a[$boolinput];
                    }
                }

// Traitement des nombres
                foreach (array('embargo_mois', 'etatcoll_deba', 'etatcoll_debv', 'etatcoll_debf', 'etatcoll_fina', 'etatcoll_finv', 'etatcoll_finf') as $num) {
                    if (isset($a[$num]) && ctype_digit($a[$num])) {
                        $updt[$num] = $a[$num];
                    }
                }

// Vérification des liens
                foreach ($this->abolinks as $link) {
                    if (isset($a[$link]) && ctype_digit($a[$link])) {
                        $class = ucfirst($link);
// Si le lien existe, on le met à jour
                        if ($class::model()->findByPk($a[$link])) {
                            $updt[$link] = $a[$link];
                        }
                    }
// Si l'entrée est NULL, on supprime le lien
                    if (isset($a[$link]) && trim($a[$link]) == 'NULL') {
                        $updt[$link] = "NULL";
                    }
                }

// Traitement des textes
                $text_to_update = false;
                foreach ($this->textfields as $txt) {
                    if (isset($a[$txt]) && trim($a[$txt]) != "") {
                        $updt[$txt] = $a[$txt];
                        $text_to_update = true;
                    }
                }

// Si aucun changement
                if (count($updt) < 1) {
                    Yii::app()->user->setFlash('notice', "Il n'y a aucun changement à appliquer sur ce lot.");
                    $stage = '1-form';
                }

//Affichage des changements
                Yii::app()->session['updt'] = $updt;
                $rp['updt'] = $updt;
                break;


            case '3-done':
// Etape 3 : la confirmation a été validée, application des changements
// Etape possible seulement si Yii::app()->session['updt'] est défini. 
// Sinon on affiche un message d'erreur et on retourne à l'étape 1
                if (!isset(Yii::app()->session['updt']) && !is_array(Yii::app()->session['updt'])) {
                    Yii::app()->user->setFlash('error', "Impossible de traiter ce lot, merci de recommencer votre requête.");
                    $stage = '1-form';
                    break;
                }

// Traitement des confirmations utilisateur
                $oldupdt = Yii::app()->session['updt'];
                $updt = array();
// récupération des champs séléctionnés
                foreach ($_POST as $key => $checked) {
                    if (isset($oldupdt[$key])) {
// Cette entrée de post est un attibut de la table Abonnement
// On ne conserve les données que si elles ont été validées par l'utilisateur.
                        if ($checked) {
                            $updt[$key] = $oldupdt[$key];
                        }
                    }
                }
// Mise à jour des données de session
                Yii::app()->session['updt'] = $updt;

// Application des mise à jours
                $update_results = array(
                    true => array(), // update réussis
                    false => array()); // update échoués
                $nbr_rows = 0; // nombre de lignes mises à jour
//Passage au mode abonnement
                Yii::app()->session['search']->setAdmin_affichage('abonnement');
// Mise à jour de tous les éléments du lot
                foreach (Abonnement::model()->findAll(Yii::app()->session['search']->admin_criteria) as $abo) {

                    $updt_local = $updt;
                    foreach ($this->textfields as $textfield) {
                        if (isset($updt_local[$textfield])) {
// Si le texte doit être ajouter et non remplacé
                            if ($add_text) {
                                $updt_local[$textfield] = $abo->$textfield . " " . $updt[$textfield];
                            }
// Si le mot clé NULL est dans le champs, il doit être vidé.
                            if ($updt[$textfield] == 'NULL') {
                                $updt_local[$textfield] = "";
                            }
                        }
                    }

// Application de la mise à jour

                    foreach ($updt_local as $field => $value) {
                        $abo->$field = $value;
                    }

                    $result = $abo->save();

//$result = Abonnement::model()->updateByPk($abo->abonnement_id, $updt_local);
// Collecte des statistiques
                    $update_results[$result][] = $abo->abonnement_id;
                    $nbr_rows++;
                    unset($updt_local);
                }
                if (count($update_results[false]) == 0) {
                    Yii::app()->user->setFlash('success', "<strong>La modification par lot a réussi.</strong><br/>" .
                            "Modification de $nbr_rows lignes sur un total de " . Yii::app()->session['totalItemCount'] . ".");
                } else {
                    if (count($update_results[true]) == 0) {
                        Yii::app()->user->setFlash('error', "<strong>La modification par lot a échoué, aucune ligne n'a été modifiée.</strong><br/>" .
                                "Modification de $nbr_rows lignes sur un total de " . Yii::app()->session['totalItemCount'] . ".");
                    } else {
                        Yii::app()->user->setFlash('notice', "<strong>La modification par lot n'a pu être appliquée que partiellement.</strong><br/>" .
                                "Modification de $nbr_rows lignes sur un total de " . Yii::app()->session['totalItemCount'] . ".");
                    }
                }
                $rp['updt'] = Yii::app()->session['updt'];
                $rp['update_results'] = $update_results;
// Suppression de la liste des update pour éviter une nouvelle mise à jour accidentelle
                if (isset(Yii::app()->session['updt']))
                    unset(Yii::app()->session['updt']);
                break;

            default: //unknown option
                throw new CException("L'action '$stage' n'existe pas. Impossible de procéder à la modification par lot.", 1);
                break;
        }

        $rp['add_text'] = $add_text;
        $rp['stage'] = $stage;
        $this->render('batchprocessing', $rp);
    }

    /**
     * Affichage de la liste d'abonnement destiné à la modification par lot
     * 
     * @return null
     */
    public function actionGridViewDialog() {
        if (Yii::app()->session['search']->admin_affichage != 'abonnement') {
            Yii::app()->session['search']->admin_affichage = 'abonnement';
        }
        $this->layout = 'dialog';
        $this->render('gridviewdialog');
    }

    public function actionAboduplicate($perunilid, $aboid) {

        $originalAbo = Abonnement::model()->findByPk($aboid);
        $copiedAbo = $originalAbo->copy();
        if (!$copiedAbo) { // echec
            Yii::app()->user->setFlash('error', "La duplication de l'abonnement $aboid a échoué.");
            $url = Yii::app()->createUrl('/admin/aboedit/perunilid/' . $perunilid . '/aboid/' . $aboid);
        } else { //succès
            Yii::app()->user->setFlash('success', "La duplication de l'abonnement $aboid a réussi. Un nouvel abonnement (numéro $copiedAbo->abonnement_id) à été créé.");
            $url = Yii::app()->createUrl('/admin/aboedit/perunilid/' . $perunilid . '/aboid/' . $copiedAbo->abonnement_id);
        }
        $this->redirect($url);
    }

    public function actionJrnduplicate($perunilid) {
        $originalJrn = Journal::model()->findByPk($perunilid);
        $copiedJrn = $originalJrn->copy();
        if (!$copiedJrn) { // echec
            Yii::app()->user->setFlash('error', "La duplication du journal $perunilid a échoué.");
            $url = Yii::app()->createUrl('/admin/peredit/perunilid/' . $perunilid);
        } else { //succès
            Yii::app()->user->setFlash('success', "La duplication du journal $perunilid a réussi. Un nouveau journal (numéro $copiedJrn->perunilid) à été créé.");
            $url = Yii::app()->createUrl('/admin/peredit/perunilid/' . $copiedJrn->perunilid);
        }
        $this->redirect($url);
    }

    /**
     * Fusionne les journaux selon les information de $_POST :
     * - Liste des perunilid des journaux à fusionner.
     * - Perunilid du journal qui sera conserver.
     * La fonction renvoie l'utilisateur à la page précdente, en affichant avec
     * le Flash le résultat de la fusion.
     *
     * @return null
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
                throw new Exception(
                "La liste des journaux à fusionner comporte moins de 2 " .
                "éléments. Impossible de réaliser la fusion."
                );
            }
// Vérifier que le maître est dans la liste des ID
            if (!isset($_POST['maitre']) || !in_array($_POST['maitre'], $_POST['perunilid'])) {
                throw new Exception(
                "Pour réaliser la fusion, il est nécessaire de définir " .
                "le journal qui sera la notice modèle, auquel les " .
                "abonnements seront attachés."
                );
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

    public function actionMesmodifications($days = 1) {

        if (($days < 1) || ($days > 30)) {
            $days = 1;
        }

        $userid = Yii::app()->user->getState('id');
        $today = date('Y-m-d H:i:s');
        $yesterday = date('Y-m-d H:i:s', time() - 60 * 60 * 24 * $days);

        $criteria = new CDbCriteria();
        $criteria->addCondition("stamp < '$today'");
        $criteria->addCondition("stamp > '$yesterday'");
        $criteria->addCondition("user_id = $userid");
        $criteria->order = 'stamp DESC';

        $dataProvider = new CActiveDataProvider('Modifications', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));

        $this->render('mesmodifications', array('dataProvider' => $dataProvider,
            'searchtitle' => "Vos modifications et créations durant " .
            ($days == 1 ? "les dernières 24 heures." : "les $days derniers jours.")));
    }

    public function actionUrlDetail($model, $id) {
        if ($model == 'Journal') {
            $this->redirect($this->createUrl("site/detail/" . $id));
        }
        if ($model == 'Abonnement') {
            $abo = Abonnement::model()->findByPk($id);
            if ($abo) {
                $this->redirect($this->createUrl("site/detail/$abo->perunilid#$id"));
            }
        }
// Aucune redirection valable
        Yii::app()->user->setFlash('error', "Impossible de vous rediriger vers les détail du $model n° $id");
        $this->redirect($this->createUrl("site/index"));
    }

    /**
     * Affiche le formulaire d'étidion du journal ainsi que les abonnement liés.
     * 
     * @param int $perunilid id du journal à éditer. Si aucun id n'est fourni, il
     *                       l'édition d'un nouveau journal est proposée.
     *                       
     * @return null
     */
    public function actionPeredit($perunilid = null) {

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
                    } else {
// Si le sujet existe dans la liste des nouveaux sujets
//    Supprimer le sujet de liste des nouveaux sujets
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
                    } else {
// Si le sujet existe dans la liste des nouveaux sujets
//    Supprimer le sujet de liste des nouveaux sujets
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
                    $str = "Le périodique «" . CHtml::link($model->titre, array("site/detail/" . $model->perunilid)) . "» a bien été enregistré.";
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
            if (isset($_POST['Abonnement']['perunilid']) && $_POST['Abonnement']['perunilid'] != "") {
                if (!Journal::model()->findByPk($_POST['Abonnement']['perunilid'])) {
                    Yii::app()->user->setFlash('error', "Le perunilid ({$_POST['Abonnement']['perunilid']}) n'est pas valable. " .
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

        // Ajout du script jquery Select2 pour charger les select avec Ajax
        $this->addSelect2();
        
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
        if (count($jrn->abonnements) > 0) {
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

    public function actionSearch() {

        $this->activate_session_search_component();


        if (isset(Yii::app()->session['search']->admin_query_tab)) {
            $this->last = Yii::app()->session['search']->admin_query_tab;
        }

        // Ajout du script jquery Select2 pour charger les select avec Ajax
        $this->addSelect2();

        $this->render('search2');
    }

    function actionEditorSelect() {

        $ret = null;

        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $limit = filter_input(INPUT_GET, 'page_limit', FILTER_VALIDATE_INT);
        if (empty($limit)) {
            $limit = 10;
        }

        if (!empty($term)) {
            $q = new CDbCriteria(array(
                'condition' => "editeur LIKE :ed",
                'params' => array(':ed' => "%$term%"),
                'limit' => $limit,
                    ));
            $models = Editeur::model()->findAll($q);
            if (count($models) > 0) {
                $result = array();
                foreach ($models as $ed) {
                    $result[] = array(
                        'text' => $ed->editeur,
                        'id' => $ed->editeur_id,
                    );
                }

                /* this is the return for a multiple results needed by select2
                 * Your results in select2 options needs to be data.result
                 */
                $ret['results'] = $result;
            }
        } elseif (!empty($id)) {
            $ed = Editeur::model()->findByPk($id);
            if (!empty($ed)) {
                /* this is the return for a single result needed by select2 for initSelection */
                $ret = array(
                    'text' => $ed->editeur,
                    'id' => $ed->editeur_id,
                );
            }
        }

        if (empty($ret)) {
            $ret = array(
                'text' => "Entrez un critère de recherche...",
                'id' => 0,
            );
        }

        echo CJSON::encode($ret);
    }

    public function actionSearchResults() {

        Yii::app()->session['searchtype'] = 'admin';
        $this->activate_session_search_component();

        $search_done = (isset($_GET['perunilidcrit1']) && isset($_GET['embargocrit']) && count($_GET) > 3);

        if ($search_done) {
            Yii::app()->session['search']->admin_query_tab = $_GET;
            $support = filter_input(INPUT_GET, 'support', FILTER_VALIDATE_BOOLEAN);
            Yii::app()->session['search']->support = $support !== NULL ? $support : '0';
        }

        if (isset(Yii::app()->session['search']->admin_query_tab)) {
            $search_done = true;
        }

        $this->render('/site/searchResults', array('search_done' => $search_done, 'searchtype' => 'admin'));

// $this->render('search', $render_params);        
    }

    public function actionSetaffichage($affichage) {
        if ($affichage == 'journal') {
            Yii::app()->session['search']->admin_affichage = 'journal';
        } else {
            Yii::app()->session['search']->admin_affichage = 'abonnement';
        }
//$this->redirect($this->createUrl('admin/search'));
        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionSearchclean() {
        unset(Yii::app()->session['search']);
        $this->redirect($this->createUrl("admin/search"));
    }

    public function actionAddSmallListEntry($type, $id) {
// Récupération de la classe de la liste
        $model = new $type();
        $colname = strtolower($type);
        if (!$model) {
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
        else {
            $this->renderPartial(
                    "_addSmallListEntryForm", array(
                'model' => $model,
                'id' => $id,
                    )
            );
        }
    }

    public function actionRefreshselect($type) {
// Récupération de la classe de la liste
        $model = new $type();
        if (!$model) {
            throw new CException("Impossible d'instancier un objet de la calsse $type");
        }

        $this->renderPartial(
                "_refreshselect", array(
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

    public function actionCsvimportcancel() {
        unset(Yii::app()->session['modif']);
        unset(Yii::app()->session['ajout']);
        Yii::app()->user->setFlash('success', "L'imporation du fichier à été annulée");
        $this->redirect("csvimport");
//$this->render('csvimport', array('model' => new CsvImportForm()));
    }

    private function addSelect2 (){
        // Ajout du script jquery Select2 pour charger les select avec Ajax
        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/js/select2-3.4.8/select2.js');
        $cs->registerCssFile($baseUrl . '//js/select2-3.4.8/select2.css');
    }
}
