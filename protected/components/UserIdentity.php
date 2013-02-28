<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 *
	public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}*/
        
        public function authenticate()
	{
		$usr=  Utilisateur::model()->find('LOWER(pseudo)=?', array(strtolower($this->username)));
                if ($usr === null) // l'utilisateur n'existe pas
                        $this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$usr->checkPwd($this->password)){ // le mot de passe est invalide
                    $this->errorCode=self::ERROR_PASSWORD_INVALID;
                }
                else{ // l'authentification a réussi
                    $this->setState('id',     $usr->utilisateur_id );
                    $this->setState('status', $usr->status);
                    $this->setState('nom',    $usr->nom);
                    
                    $this->errorCode=  self::ERROR_NONE;
                    $this->username    = $usr->pseudo;
                }
                return $this->errorCode == self::ERROR_NONE;
	}

}