<?php

class SiteController extends Controller {

    public $support;
    public $field;

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * Action d'entrée dans PérUnil : la recherche simple. 
     */
    public function actionIndex() {
        $this->activate_session_search_component();

        // Affichage du formulaire de recherche et év. des résultats.
        $this->render('simpleSearch');
    }

    public function actionSimpleSearchResults() {
        Yii::app()->session['searchtype'] = 'simple';
        $this->activate_session_search_component();

        $search_done = isset($_GET['q']) && trim($_GET['q']) != "";

        // Si une nouvelle recherche a été effectuée
        if ($search_done) {
            Yii::app()->session['search']->support = isset($_GET['support']) ? $_GET['support'] : '0';
            Yii::app()->session['search']->search_type = isset($_GET['field']) ? $_GET['field'] : SearchComponent::TWORDS;
            Yii::app()->session['search']->maxresults = isset($_GET['maxresults']) ? $_GET['maxresults'] : '-1'; // infini par défaut
            Yii::app()->session['search']->depotlegal = filter_input(INPUT_GET,'depotlegal',FILTER_VALIDATE_BOOLEAN);//isset($_GET['depotlegal']);
            Yii::app()->session['search']->simple_query_str = $_GET['q'];
        }
        // Si une recherche a été sauvegardée
        if (isset(Yii::app()->session['search']->simple_query_str)) {
            $search_done = true;
        }

        // Affichage des résultats.
        $this->render('searchResults', array('search_done' => $search_done, 'searchtype' => 'simple'));
    }

    public function actionSimpleclean() {
        unset(Yii::app()->session['depotlegal']);
        unset(Yii::app()->session['search']);
        $this->redirect($this->createUrl("site/index"));
    }

    /**
     * Recherche avancée 
     */
    public function actionAdvSearchResults() {

        Yii::app()->session['searchtype'] = 'adv';
        $this->activate_session_search_component();

        $search_done = isset($_GET['advsearch']) && trim($_GET['advsearch']) == "advsearch";

        // Si une nouvelle recherche a été effectuée
        if ($search_done) {
            Yii::app()->session['search']->support = isset($_GET['support']) ? $_GET['support'] : '0';
            Yii::app()->session['search']->depotlegal = isset($_GET['depotlegal']);
            Yii::app()->session['search']->search_type = isset($_GET['field']) ? $_GET['field'] : SearchComponent::TWORDS;
            Yii::app()->session['search']->adv_query_tab = $_GET;
        }

        // Si une recherche a été sauvegardée
        if (isset(Yii::app()->session['search']->adv_query_tab)) {
            $search_done = true;
        }
        // affichage de la recherche avancée.
        //$render_params['advsearch'] = true;  
        // Affichage des résultats.
        $this->render('searchResults', array('search_done' => $search_done, 'searchtype' => 'adv'));
    }

    public function actionAdvSearch() {
        $this->activate_session_search_component();
        $this->render('advSearch');
    }

    public function actionAdvclean() {
        unset(Yii::app()->session['search']);
        $this->redirect($this->createUrl("site/advSearch"));
    }

    public function actionSujet() {
        $this->render('sujet');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(CController::createUrl('/admin/index'));
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionDetail($id, $activeTab = false, $dialogue = false) {
        $model = Journal::model()->findByPk($id);

        if ($dialogue) {
            // la vue en détail est appelée depuis un popup javascript
            //$this->layout = "dialogue";
            $this->layout = false;

            $this->render('detail', array(
                'model' => $model,
                'activeTab' => $activeTab,
                'dialogue' => true,
            ));
        } else {
            // Affichage normal de la vue en détail
            $this->render('detail', array(
                'model' => $model,
                'activeTab' => $activeTab,
            ));
        }
    }

    function actionAutocompleteDepotLegalStatus(){
        $depotlegal = filter_input(INPUT_GET, 'depotlegal', FILTER_VALIDATE_BOOLEAN);
        
        // Mise à jour du dépot legal par ajax.
        if (!empty($depotlegal)){
            Yii::app()->session['depotlegal'] = $depotlegal;
            return;
        }
    }
    
    function actionAutocomplete() {
        $withDepotLegal = false;
 
        if (!empty(Yii::app()->session['depotlegal'])){
            $withDepotLegal = Yii::app()->session['depotlegal'];
        }
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        if (!empty($term)) {
            $models = Journal::model()->searchTitleWord($term,$withDepotLegal);
            
//            // Si on a aucun résultat, on effectue une recherche avec le searchComponent
          if (count($models) < 5) {
              $models2 = Journal::model()->searchTitleWord($term, $withDepotLegal, SearchComponent::TWORDS);
              $models = array_merge($models,$models2);
            }
            $result = array();
            foreach ($models as $m) {
                $result[] = array(
                    'label' => $m->titre,
                    //'value' => $m->attribute_for_input_field,
                    'id' => $m->perunilid,
                        //'field' => $m->attribute_for_another_field,
                );
            }
            echo CJSON::encode($result);
        }


        //if (Yii::app()->request->isAjaxRequest && isset($_GET['term'])) {
//        if (isset($_GET['term'])) {
//            $term = $_GET['term'];
//            $models = Journal::model()->findAll(array(
//                'select' => 'titre', //,perunilid',
//                'condition' => "titre LIKE '$term%'",
//                'order' => "titre",
//                'distinct' => true,
//                'limit' => 10,
//                    ));
//            // Si on a aucun résultat, on cherche avec le mot au milieu
//            if (!count($models)) {
//                $models = Journal::model()->findAll(array(
//                    'select' => 'titre', //,perunilid',
//                    'condition' => "titre LIKE '%$term%'",
//                    'order' => "titre",
//                    'distinct' => true,
//                    'limit' => 10,
//                        ));
//            }
//            $result = array();
//            foreach ($models as $m)
//                $result[] = array(
//                    'label' => $m->titre,
//                    //'value' => $m->attribute_for_input_field,
//                    'id' => $m->perunilid,
//                        //'field' => $m->attribute_for_another_field,
//                );
//
//            echo CJSON::encode($result);
//        }
    }

    //
// Function pour nettoyer les critères de recherche (mots vides, ponctuation...)
//
    private function clean_search($original) {
        $var = trim($original);
        $var = " " . $var . " ";
        $var = str_ireplace(",", "", $var);
        $var = str_ireplace(". ", " ", $var);
        $var = str_ireplace(": ", " ", $var);
        $var = str_ireplace(":", " ", $var);
        $var = str_ireplace("-", " ", $var);
        $var = str_ireplace(";", "", $var);
        $var = str_ireplace(" (the) ", " ", $var);
        $var = str_ireplace(" the ", " ", $var);
        $var = str_ireplace(" [the] ", " ", $var);
        $var = str_ireplace(" of ", " ", $var);
        $var = str_ireplace(" de ", " ", $var);
        $var = str_ireplace(" du ", " ", $var);
        $var = str_ireplace(" le ", " ", $var);
        $var = str_ireplace(" les ", " ", $var);
        $var = str_ireplace(" des ", " ", $var);
        $var = str_ireplace(" l'", " ", $var);
        $var = str_ireplace(" la ", " ", $var);
        $var = str_ireplace(" los ", " ", $var);
        $var = str_ireplace(" el ", " ", $var);
        $var = str_ireplace(" and ", " ", $var);
        $var = str_ireplace(" (and) ", " ", $var);
        $var = str_ireplace(" [and] ", " ", $var);
        $var = str_ireplace(" et ", " ", $var);
        $var = str_ireplace(" (et) ", " ", $var);
        $var = str_ireplace(" [et] ", " ", $var);
        $var = str_ireplace(" y ", " ", $var);
        $var = str_ireplace(" und ", " ", $var);
        $var = str_ireplace(" der ", " ", $var);
        $var = str_ireplace(" die ", " ", $var);
        $var = str_ireplace(" das ", " ", $var);
        $var = str_ireplace(" fur ", " ", $var);
        $var = str_ireplace(" für ", " ", $var);
        $var = str_ireplace(" & ", " ", $var);
        $var = str_ireplace(" (&) ", " ", $var);
        $var = str_ireplace(" [&] ", " ", $var);
        $var = str_ireplace(" &amp ", " ", $var);

        // Si l'application de clean_search à été destructive, on revient
        // à la donnée initiale.
        if (($var == "") || ($var == " ")) {
            $var = $original;
        }
        return trim($var);
    }

}
