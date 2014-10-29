<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $name;
	public $email;
        public $errortype;
        public $lasturl;
        public $missinglink;
	public $body;
	public $verifyCode;

        public $errorlist = array(
            'BROKENLINK'    => "Lien cassé ou faux",
            'TEXTERROR'     => "Faute d'orthographe ou grammaticale",
            'DATAERROR'     => "Information erronée ou pas à jour",
            'MISSINGLINK'   => "Suggestion de lien manquant",
            'NEWABOREQUEST' => "Suggestion de nouvel abonnement",
            'OTHER'         => "Autre"
        );
        
	/**
	 * Règles de validation
	 */
	public function rules()
	{
		return array(
			array('name, email, errortype, lasturl', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
                        array('body, missinglink', 'safe'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}


	public function attributeLabels()
	{
		return array(
			'verifyCode'     =>'Code de vérification',
                        'name'           =>'Nom',
                        'errortype'      =>"Type d'erreur",
                        'lasturl'        =>"Adresse URL de la page qui contient le problème",
                        'missinglink'   =>"Adresse URL du lien cassé, faux ou manquant",
                        'body'           =>'Commentaires ou précisions',
		);
	}
        
        public function getErrorTypeStr(){
            if (!empty($this->errortype)){
                return $this->errorlist[$this->errortype];
            }
            return "Erreur non spécifiée";
        }
}