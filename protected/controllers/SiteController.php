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
        $render_params = array();

        $this->support = isset($_GET['support']) ? $_GET['support'] : '0';
        $this->field = isset($_GET['field']) ? $_GET['field'] : SearchComponent::TWORDS;

        $render_params['search_done'] = isset($_GET['q']) && trim($_GET['q']) != "";

        if ($render_params['search_done']) {
            $search = new SearchComponent();
            $search->search_type = $this->field;
            $search->support = $this->support;

            $render_params['dataProvider'] = $search->simplesearch($_GET['q']);
        }
        $this->render('publicSearch', $render_params);
    }

    /**
     * Recherche avancée 
     */
    public function actionAdv() {
        $render_params = array();

        $this->support = isset($_GET['support']) ? $_GET['support'] : '0';
        $this->field   = isset($_GET['field']) ? $_GET['field'] : SearchComponent::TWORDS;

        $render_params['search_done'] = isset($_GET['advsearch']) && trim($_GET['advsearch']) == "advsearch";

        if ($render_params['search_done']) {
            //TODO : vérification des données post
            $search = new SearchComponent();
            $search->support = $this->support;
            $render_params['dataProvider'] = $search->multisearch($_GET);
            // On renvoie les donnée du formulaire vers l'affichage pour préremplir le formulaire
            $render_params['lastadvsearch'] = $_GET;

        }
        $render_params['advsearch'] = true; // affichage de la recherche avancée.
        $this->render('publicSearch', $render_params);
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

    public function actionDetail($id) {
        $model = Journal::model()->findByPk($id);
        $this->render('detail', array(
            'model' => $model,
        ));
    }
    
    function actionAutocomplete() {
	    //if (Yii::app()->request->isAjaxRequest && isset($_GET['term'])) {
            if (isset($_GET['term'])) {
                $term = $_GET['term'];
	        $models = Journal::model()->findAll(array(
                    'select'   => 'titre',//,perunilid',
                    'condition'=> "titre LIKE '$term%'",
                    'order'    => "titre",
                    'distinct' =>  true,
                    'limit'    =>  10,
                ));
                // Si on a aucun résultat, on cherche avec le mot au milieu
                if (!count($models)){
                    $models = Journal::model()->findAll(array(
                    'select'   => 'titre',//,perunilid',
                    'condition'=> "titre LIKE '%$term%'",
                    'order'    => "titre",
                    'distinct' =>  true,
                    'limit'    =>  10,
                ));
                }
	        $result = array();
	        foreach ($models as $m)
	            $result[] = array(
	                'label' => $m->titre,
	                //'value' => $m->attribute_for_input_field,
	                'id' => $m->perunilid,
	                //'field' => $m->attribute_for_another_field,
	            );
	 
	        echo CJSON::encode($result);
	    }
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