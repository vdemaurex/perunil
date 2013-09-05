<?php

class SearchComponent extends CComponent {
    /*
     * Constantes pour la recherche simple 
     */

    const TEXACT = 'texact'; // Recherche selon la phrase exacte entrée par l'utilisateur
    const TBEGIN = 'tbegin'; // Recherche selon les premier mots du titre
    const TWORDS = 'twords'; // Recherche de tous les mots indépendamment
    const JRNALL = 'jrnall'; // Recherche dans tous les champs publiques de la table Journal

    /**
     * Requête de la recherche avancée
     * @var array 
     */

    private $adv_query_tab;
    private $adv_criteria;
    private $adv_dp;

    /**
     * Requête de la recherche simple
     * @var type 
     */
    private $simple_query_str;
    private $simple_criteria;
    private $simple_dp;

    /**
     * Requête de la recherche administrateur
     * @var type 
     */
    private $admin_query_tab;
    private $admin_criteria;
    private $admin_affichage;
    private $admin_dp;

    /**
     * $query après les traitements de base
     * @var string 
     */
    private $q;

    /**
     * Option pour la recherche simple. Définie en constante de classe.
     * @var string constante. Par défaut TWORD.
     */
    public $search_type = self::TWORDS;

    /**
     * Résumé de la recherce effectuée.
     * @var string 
     */
    private $q_summary = "";

    /**
     * Si fixé, ajoute ou supprime les abonnement dont le titre est exclu.
     * Par défaut, null : ce critère n'est pas pris en compte dans la recherche.
     * @var int 
     */
    public $titreexclu = null;

    /**
     * Type de support du périodique (0 = tous)
     * @var int 0 par défaut
     */
    public $support = 0;

    /**
     * Nombre de résultats affichés par page.
     * @var int 
     */
    public $pagesize = 30;

    ///////////////////////////////////////////////////////////////////////////
    // Recherche avancée
    ///////////////////////////////////////////////////////////////////////////
    // Méthodes publiques
    //===================

    /**
     * Assigne la requête de recherche avancée
     * @param array $query_tab 
     * [C1][search_type] :  partout, titre, editeur, issn
     * [C1][text]        : texte libre de la recherche
     * [C1][op]          : Opérateur de liaison avec le criètre suivant
     * ...
     * [C3] ! ne contient pas de champ 'op'
     * [support]      : 0 (Tous), 1 (électronique), 2 (papier)
     * [accessunil]   : 0/1
     * [openaccess]   : 0/1
     * [sujet]        : Sujet.sujet_id, defaut all
     * [plateforme]   : Plateforme.plateform_id, defaut all
     * [licence]      : Licence.licence_id, defaut all
     * [statutabo]    : Statutabo.statutabo_id, defaut all
     * [localisation] : Localisation.localisation_id, defaut all
     */
    public function setAdv_query_tab($query_tab) {

        $this->adv_query_tab = $query_tab;
        $this->adv_criteria = $this->advancedSearch();
        //$this->adv_criteria_id = uniqid();
    }

    public function getAdv_query_tab() {
        if (isset($this->adv_query_tab)) {
            return $this->adv_query_tab;
        } else {
            return null;
        }
    }

    public function getAdv_dp() {
        /* if (isset($this->adv_dp)) {
          if ($this->adv_dp_id == $this->adv_criteria_id) {
          return $this->adv_dp;
          }
          }
          // Première recherche ou recherche périmée : nouvelle recherche
          $this->adv_dp_id = $this->adv_criteria_id; */
        $this->adv_dp = new CActiveDataProvider(
                        Journal::model(),
                        array('criteria' => $this->adv_criteria,
                            'pagination' => array('pageSize' => $this->pagesize))
        );
        return $this->adv_dp;
    }

    /* public function setAdv_dp($dp) {
      $this->adv_dp = $dp;
      } */

    // Méthode privées
    //================

    /**
     * Crée le critère de recherche pour la recherche avancée
     * @return \CDbCriteria
     * @throws CException 
     */
    private function advancedSearch() {
        $this->q_summary = "";
        if (!$this->adv_query_tab && !is_array($this->adv_query_tab)) {
            throw new CException("Recherche avancée impossible : requête n'est enregistrée.");
        }
        // TODO vérifier la validité du querytab
        //return $this->simplesearch($querytab['C1']['text']);
        $criteria = new CDbCriteria();
        // Ajout de la jointure avec les abonnements, nécessaire avant d'autres
        // jointures.
        $this->joinAbo($criteria);
        foreach (array('C1', 'C2', 'C3') as $CN) {
            if (!isset($querytab[$CN]))
                continue;
            // nettoyage du champ
            $this->simple_query_str = $querytab[$CN]['text'];
            $this->q = $this->clean_search($this->simple_query_str);
            // si le champ ne contient rien , on abandonne ici.
            if ($this->q == "")
                continue;
            $use_notlike = $querytab[$CN]['op'] == 'NOT';
            switch ($querytab[$CN]['search_type']) {
                case 'issn':
                    $criteria->compare('issn', trim($this->simple_query_str), true, $querytab[$CN]['op']);
                    break;
                case 'titre':
                    //$title_criteria = new CDbCriteria();
                    // $this->titleSearch($title_criteria, $use_notlike);
                    $this->titleSearch($criteria, $use_notlike);
                    //$criteria->mergeWith($title_criteria, $querytab[$CN]['op']);
                    break;
                case 'editeur':
                    $criteria->join .= 'LEFT JOIN `editeur` `editeurs` ON (`abonnements`.`editeur`=`editeurs`.`editeur_id`)';
                    $criteria->addCondition("editeurs.editeur LIKE '%$this->q%'");
                    break;

                default: // partout
                    //$title_criteria = new CDbCriteria();
                    //$this->journalSearch($title_criteria, $use_notlike);
                    $this->journalSearch($criteria, $use_notlike);

                    //$abo_criteria = new CDbCriteria();
                    $like = $use_notlike ? "NOT LIKE" : "LIKE";
                    foreach (explode(" ", $this->q) as $word) {
                        if ($word != "" || $word != "") {
                            $query = "abonnements.package $like '%$word%' OR abonnements.url_site $like '%$word%' " .
                                    "OR abonnements.etatcoll $like '%$word%' OR abonnements.cote $like '%$word%' " .
                                    "OR abonnements.editeur_code $like '%$word%' OR abonnements.editeur_sujet $like '%$word%' " .
                                    "OR abonnements.commentaire_pub $like '%$word%' ";
                            //$criteria->addCondition($abo_criteria, 'AND');
                            $criteria->addCondition($query, 'OR');
                        }
                    }

                    break;
            }
        }

        // Ajout des critère généraux
        if (isset($querytab['accessunil']) && !$querytab['accessunil'])
            $criteria->addCondition("abonnements.acces_elec_unil !=1 && abonnements.acces_elec_chuv !=1");
        if (isset($querytab['openaccess']) && !$querytab['openaccess'])
            $criteria->addCondition("abonnements.acces_elec_gratuit !=1 && openaccess !=1");

        //Critère de gestion
        if (isset($querytab['sujet']) && $querytab['sujet'] != '') {
            $criteria->join .= 'LEFT JOIN `journal_sujet` `js` ON (`js`.`perunilid`=`t`.`perunilid`)';
            $criteria->join .= 'LEFT JOIN `sujet` `s` ON (`s`.`sujet_id`=`js`.`sujet_id`)';
            $criteria->addCondition("s.sujet_id ='{$querytab['sujet']}'");
        }

        if (isset($querytab['plateforme']) && $querytab['plateforme'] != '') {
            $criteria->join .= 'LEFT JOIN `plateforme` `p` ON (`p`.`plateforme_id`=`abonnements`.`plateforme`)';
            $criteria->addCondition("p.plateforme_id ='{$querytab['plateforme']}'");
        }

        if (isset($querytab['licence']) && $querytab['licence'] != '') {
            $criteria->join .= 'LEFT JOIN `licence` `l` ON (`l`.`licence_id`=`abonnements`.`licence`)';
            $criteria->addCondition("l.licence_id ='{$querytab['licence']}'");
        }

        if (isset($querytab['statutabo']) && $querytab['statutabo'] != '') {
            $criteria->join .= 'LEFT JOIN `statutabo` `sa` ON (`sa`.`statutabo_id`=`abonnements`.`statutabo`)';
            $criteria->addCondition("sa.statutabo_id ='{$querytab['statutabo']}'");
        }

        if (isset($querytab['localisation']) && $querytab['localisation'] != '') {
            $criteria->join .= 'LEFT JOIN `localisation` `loc` ON (`loc`.`localisation_id`=`abonnements`.`localisation`)';
            $criteria->addCondition("loc.localisation_id ='{$querytab['localisation']}'");
        }

        return $criteria;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Recherche simple
    ///////////////////////////////////////////////////////////////////////////
    // Méthodes publiques
    //===================


    public function setSimple_query_str($query_str) {

        $this->simple_query_str = $query_str;
        $this->simple_criteria = $this->simplesearch();
        //$this->simple_criteria_id = uniqid();
    }

    public function getSimple_query_str() {
        if (isset($this->simple_query_str)) {
            return $this->simple_query_str;
        } else {
            return null;
        }
    }

    public function getSimple_dp() {
        /* if (isset($this->simple_dp)) {
          if ($this->simple_dp_id == $this->simple_criteria_id) {
          return $this->simple_dp;
          }
          }
          // Première recherche ou recherche périmée : nouvelle recherche
          $this->simple_dp_id = $this->simple_criteria_id; */
        $this->simple_dp = new CActiveDataProvider(
                        Journal::model(),
                        array('criteria' => $this->simple_criteria,
                            'pagination' => array('pageSize' => $this->pagesize))
        );
        return $this->simple_dp;
    }

    /*  public function setSimple_dp($dp) {
      $this->simple_dp = $dp;
      } */

    // Méthode privées
    //================

    /**
     * Effectue un recherche sur une seul critère définit par
     * $support.
     * @param string $query
     * @return CActiveDataProvider 
     */
    private function simplesearch() {
        $this->q_summary = "";
        $this->q = $this->clean_search($this->simple_query_str);
        // Si q ne contient qu'une seule lettre, on cherche TBEGIN
        if (strlen($this->q) == 1)
            $this->search_type = self::TBEGIN;

        $criteria = new CDbCriteria();
        $this->joinAbo($criteria);
        switch ($this->search_type) {
            case self::TBEGIN:
            case self::TEXACT:
            case self::TWORDS:
                $this->titleSearch($criteria);
                break;
            case self::JRNALL:
                $this->journalSearch($criteria);
                break;
            default:
                throw new CException("Ce type de recherche ($this->search_type) n'est pas pris en charge.");
                break;
        }
        return $criteria;
//        return new CActiveDataProvider(Journal::model(), array('criteria' => $criteria, 'pagination' => array(
//                        'pageSize' => $this->pagesize)));
    }

    /**
     * La fonction joinAbo ajoute les critères communs a beaucoup de recherches
     * dans la base PerUNIL : 
     *  - Jointure avec la table abonnement
     *  - Ajouter les titres exclu seulement si l'utilisateur est authentifié
     *  - Ajouter le critère du support désiré
     *  - Trier par titre
     * @param CDbCriteria $criteria Requête en cours de construction, passage par référence  
     */
    private function joinAbo($criteria) {
        $criteria->join .= 'LEFT JOIN `abonnement` `abonnements` ON `abonnements`.`perunilid`=`t`.`perunilid` AND abonnements.perunilid IS NOT NULL ';

        //$criteria->addCondition("abonnements.perunilid IS NOT NULL");
        if (Yii::app()->user->isGuest) {
            // Si l'utilisateur n'est pas authentifié, on ne prend pas en compte 
            // les abonnements de titre exculs
            //$criteria->addCondition("abonnements.titreexclu=0");
            $criteria->join .= " AND abonnements.titreexclu=0 ";
        } else {
            // Si l'utilisateur est authentifié, on recherche si titreexcul n'est
            // pas null 
            if (isset($this->titreexclu)) {
                //$criteria->addCondition("abonnements.titreexclu=$this->titreexclu");
                $criteria->join .= " AND abonnements.titreexclu=$this->titreexclu ";
            }
        }
        if ($this->support > 0) {
            //$criteria->addCondition("abonnements.support=$this->support");
            $criteria->join .= " AND abonnements.support=$this->support ";
        }
        $criteria->order = "titre";
        $criteria->distinct = true;
    }

    /**
     * Recherche dans les champs titre de la table journal : titre, 
     * titre_abrege, titre_variante, soustitre.
     * @param CDbCriteria $criteria Requête en cours de construction, passage par référence
     * @param boolean $not_like false par défaut. Si true, la requête est construite
     *                          avec des "NOT LIKE". 
     */
    private function titleSearch($criteria, $not_like = false) {
        $like = $not_like ? "NOT LIKE" : "LIKE";
        $tokens = array();
        if ($this->search_type == self::TEXACT) {
            $tokens[] = "$this->simple_query_str";
        } elseif ($this->search_type == self::TBEGIN) {
            $tokens[] = "$this->q%";
        } else { // Recherche de chaque mot indépendamment.
            foreach (explode(" ", $this->q) as $word) {
                if ($word != "" || $word != "") {
                    $tokens[] = "%$word%";
                }
            }
        }
        foreach ($tokens as $token) {
            $query = "titre $like '$token' OR titre_abrege $like '$token' " .
                    "OR titre_variante $like '$token' OR soustitre $like '$token'";
            $criteria->addCondition($query, 'AND');
        }
    }

    /**
     * Recherche de chaque mot indépendamment dans tous les champs public 
     * de la table journal.
     * @param CDbCriteria $criteria Requête en cours de construction, passage par référence
     * @param boolean $not_like false par défaut. Si true, la requête est construite
     *                          avec des "NOT LIKE". 
     */
    private function journalSearch($criteria, $not_like = false) {
        $like = $not_like ? "NOT LIKE" : "LIKE";
        foreach (explode(" ", $this->q) as $word) {
            if ($word != "" || $word != "") {
                $query = "t.perunilid $like '%$word%' OR titre $like '%$word%' " .
                        "OR titre_abrege $like '%$word%' OR titre_variante $like '%$word%' " .
                        "OR soustitre $like '%$word%' OR issn $like '%$word%' " .
                        "OR issnl $like '%$word%' OR nlmid $like '%$word%' " .
                        "OR reroid $like '%$word%' OR doi $like '%$word%' " .
                        "OR coden $like '%$word%' OR urn $like '%$word%' " .
                        "OR faitsuitea $like '%$word%' OR devient $like '%$word%' " .
                        "OR url_rss $like '%$word%' ";
                $criteria->addCondition($query, 'AND');
            }
        }
        $this->query_summary("'$this->q' dans tous les champs de la table journal.");
    }

    /* private function aboSearch($criteria, $not_like = false) {
      $like = $not_like ? "NOT LIKE" : "LIKE";
      foreach (explode(" ", $this->q) as $word) {
      if ($word != "" || $word != "") {
      $query = "abonnements.package $like '%$word%' OR abonnements.url_site $like '%$word%' " .
      "OR abonnements.etatcoll $like '%$word%' OR abonnements.cote $like '%$word%' " .
      "OR abonnements.editeur_code $like '%$word%' OR abonnements.editeur_sujet $like '%$word%' " .
      "OR abonnements.commentaire_pub $like '%$word%' ";
      $criteria->addCondition($query, 'AND');
      }
      }
      } */

    public function setSearch_type($search_type) {
        if (defined("self::" . strtoupper($search_type)))
            $this->search_type = $search_type;
        else
            throw new CException("Type de recherche invalide : $search_type.");
    }

    public function getSearch_type() {
        return $this->search_type;
    }

    /**
     * Function pour nettoyer les critères de recherche (mots vides, ponctuation...)
     * @param string $original
     * @return string 
     */
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
        $var = trim($var);
        str_ireplace("*", "%", $var);
        return $var;
    }

    ///////////////////////////////////////////////////////////////////////////
    // Recherche administrateur
    ///////////////////////////////////////////////////////////////////////////
    // Méthodes publiques
    //===================

    public function setAdmin_query_tab($query_tab) {
        $this->admin_query_tab = $query_tab;
        $this->refreshAdminCriteria();
    }

    public function setAdmin_affichage($affichage = 'abonnement'){
             
        if ( $affichage == 'journal'){
                $this->admin_affichage = 'journal';
            
        } else {
            $this->admin_affichage = 'abonnement';
        }
        $this->refreshAdminCriteria();
    }
    
    public function getAdmin_affichage(){
        if (isset($this->admin_affichage) && in_array($this->admin_affichage, array("journal", "abonnement"))){
            return $this->admin_affichage;
        }
        else {
            return 'abonnement';
        }
    }

    private function refreshAdminCriteria(){
        $this->q_summary = "";
         if (isset($this->admin_affichage) && $this->admin_affichage == 'journal'){
                 $this->admin_criteria = $this->adminSearch();
        } else {
            $this->admin_criteria = $this->aboadminSearch();
        }
    }

    public function getAdmin_criteria(){
        return $this->admin_criteria;
    }

    public function getAdmin_query_tab() {
        if (isset($this->admin_query_tab)) {
            return $this->admin_query_tab;
        } else {
            return null;
        }
    }

    public function getAdmin_dp() {
        $affichage = ucfirst($this->getAdmin_affichage());

        $this->admin_dp = new CActiveDataProvider(
                        $affichage::model(),
                        array('criteria' => $this->admin_criteria,
                            'pagination' => array('pageSize' => $this->pagesize))
        );

        return $this->admin_dp;
    }

    /**
     * Recherche selon un ou plusieurs critère du tableau $querytab : 
     *  [perunilidcrit1]	string	"equal"	
     *   [perunilid1]	string	""	
     *   [perunilidcrit2]	string	"equal"	
     *   [perunilid2]	string	""		
     *   [titre]	string	""	
     *   [soustitre]	string	""	
     *   [titreabrege]	string	""	
     *   [variantetitre]	string	""	
     *   [faitsuitea]	string	""	
     *   [devient]	string	""	
     *   [editeur]	string	""	
     *   [codeediteur]	string	""	
     *   [issnl]	string	""	
     *   [issn]	string	""	
     *   [reroid]	string	""	
     *   [nlmid]	string	""	
     *   [coden]	string	""	
     *   [doi]	string	""	
     *   [urn]	string	""	
     *   [url]	string	""	
     *   [rss]	string	""	
     *   [user]	string	""	
     *   [pwd]	string	""	
     *   [licence]	foreign key	
     *   [statutabo]	foreign key	
     *   [plateforme]	foreign key	
     *   [gestion]	foreign key
     *   [histabo]	foreign key
     *   [support]	foreign key
     *   [format]	foreign key
     *   [package]	string	""	
     *   [no_abo]	string	""	
     *   [etatcoll]	string	""	
     * [embargocrit]	string	"equal"
     * [embargo]	string	""
     * [etatcolldeba]	string	""
     * [etatcolldebv]	string	""
     * [etatcolldebf]	string	""
     * [etatcollfina]	string	""
     * [etatcollfinv]	string	""
     * [etatcollfinf]	string	""
     * [localisation]	string	""
     * [cote]	string	""	
     * [commentairepro]	string	""	
     * [commentairepub]	string	""	
     * [sujet]	string	""
     * [sujetsfm]	string	""	
     * [fmid]	string	""	
     * [historique]	string	""	
     * @param array $qt 
     * @return CActiveDataProvider correspondant à la requête. NULL si aucun
     *                             critère n'a été fourni.
     */
    private function adminSearch() {
        $qt = $this->admin_query_tab;
        $limite = 100;
        $ct = array('equal' => '=', 'before' => '<', 'after' => '>');

        $criteria = new CDbCriteria();
        $this->joinAbo($criteria);

        // Jointure de la table sujet si nécessaire
        if (trim($qt['sujet'])) {
            $criteria->join .= 'LEFT JOIN `journal_sujet` `js` ON (`js`.`perunilid`=`t`.`perunilid`)';
            $criteria->join .= 'LEFT JOIN `sujet` `s` ON (`s`.`sujet_id`=`js`.`sujet_id`)';
            $criteria->addCondition("s.sujet_id ='{$qt['sujet']}'");
            $this->query_summary("sujet = " . Sujet::model()->findByPk($qt['sujet'])->nom_fr);
        }

        // Jointure de la table editeur si nécessaire
        if (trim($qt['editeur_txt'])) {
            $criteria->join .= 'LEFT JOIN editeur ed ON (ed.editeur_id=abonnements.editeur)';
        }

        //Recherche par perunilid
        if (trim($qt['perunilid1'])) {
            $criteria->addCondition("t.perunilid " . $ct[$qt['perunilidcrit1']] . " '" . $qt['perunilid1'] . "'");
            $this->query_summary("perunilid " . $ct[$qt['perunilidcrit1']] . " " . $qt['perunilid1']);
            // S'il y a un deuxième perunilid
            if (trim($qt['perunilid2'])) {
                $criteria->addCondition("t.perunilid " . $ct[$qt['perunilidcrit2']] . " '" . $qt['perunilid2'] . "'");
                $this->query_summary("perunilid " . $ct[$qt['perunilidcrit2']] . " " . $qt['perunilid2']);
            }
        }

        // Modifications : Si un champ concernant le modifications est rempli
        // 1. Création d'une requête pour la table modification
        // 2. Liste de Perunilid ou d'abonnement_id comme resultat de la requête
        //    avec une maximum selon $limite
        // 3. la liste d'id est passée à une clause IN dans la requête principale.
        if (trim($qt['signaturecreation'])
                || trim($qt['signaturemodification'])
                || trim($qt['datecreation1'])
                || trim($qt['datemodif1'])) {


            $where_string = "";
            $where_array = array();

            // Préparation de la requête pour des recherche sur la création
            if (trim($qt['datecreation1'])) {
                $phpdate = strtotime(trim($qt['datecreation1']));
                $mysqldate = date('Y-m-d H:i:s', $phpdate);
                $where_string = "stamp " . $ct[$qt['datecreationcrit1']] . " :stamp";
                $where_array[":stamp"] = $mysqldate;
                $this->query_summary("date de création " . $ct[$qt['datecreationcrit1']] . " " . $qt['datecreation1']);

                if (trim($qt['datecreation2'])) {
                    $phpdate = strtotime(trim($qt['datecreation2']));
                    $mysqldate = date('Y-m-d H:i:s', $phpdate);
                    $where_string .= " AND stamp " . $ct[$qt['datecreationcrit2']] . " :stampi";
                    $where_array[":stampi"] = $mysqldate;
                    $this->query_summary(" et " . $ct[$qt['datecreationcrit2']] . " " . $qt['datecreation2']);
                } // datecreation2
            } // datecreation1
            // Recherche d'après le créateur
            if (trim($qt['signaturecreation'])) {
                $s = trim($qt['signaturecreation']);
                if ($where_string != "")
                    $where_string .= " AND ";
                $where_string .= 'user_id = :sc';
                $where_array[':sc'] = $s;
                $this->query_summary("Signature de création = " . Utilisateur::model()->findByPk($s)->pseudo);
            }

            // ---
            // Préparation de la requête pour des recherche sur la modification
            if (trim($qt['datemodif1'])) {
                $phpdate = strtotime(trim($qt['datemodif1']));
                $mysqldate = date('Y-m-d H:i:s', $phpdate);
                if ($where_string != "")
                    $where_string .= " AND ";
                $where_string .= "stamp " . $ct[$qt['datemodifcrit1']] . " :stampii";
                $where_array[":stampii"] = $mysqldate;
                $this->query_summary("date de modification " . $ct[$qt['datemodifcrit1']] . " " . $qt['datemodif1']);


                if (trim($qt['datemodif2'])) {
                    $phpdate = strtotime(trim($qt['datemodif2']));
                    $mysqldate = date('Y-m-d H:i:s', $phpdate);
                    $where_string .= " AND stamp " . $ct[$qt['datemodifcrit2']] . " :stampiii";
                    $where_array[":stampiii"] = $mysqldate;
                    $this->query_summary(" et " . $ct[$qt['datemodifcrit2']] . " " . $qt['datemodif2']);
                } // datemodif2
            } // datemodif1
            // Recherche d'après le modificateur
            if (trim($qt['signaturemodification'])) {
                $s = trim($qt['signaturemodification']);
                if ($where_string != "")
                    $where_string .= " AND ";
                $where_string .= 'user_id = :sm';
                $where_array[':sm'] = $s;
                $this->query_summary("Signature de modification = " . Utilisateur::model()->findByPk($s)->pseudo);
            }

            $ids = array();
            foreach (array('journal', 'abonnement') as $model) {
                $where_string .= " AND action = :act AND model = :model";
                $where_array[':act'] = 'Création';
                $where_array[':model'] = $model;
                $cmd = Yii::app()->db->createCommand()
                        ->selectDistinct('m.model_id')
                        ->from('modifications m')
                        ->where($where_string)
                        //->where('user_id = :sc AND action = :act AND model = :mod', array(':sc' => $s, ':act' => 'Création', ':mod' => 'journal'))
                        ->limit($limite)
                        ->order("stamp DESC");

                $perunilids = $cmd->queryAll(true, $where_array);
                $ids[$model] = join("','", array_map('current', $perunilids));
            }

            // FIXME : ne pas ajouter la condition si $ids[$model] est vide.
            $criteria->addCondition("t.perunilid IN ('{$ids['journal']}') OR abonnements.abonnement_id IN ('{$ids['abonnement']}')");
        } // Modifications
        // Recherche tous les champs
        if (trim($qt['all'])) {
            $this->q = $qt['all'];
            $this->journalSearch($criteria);
        }

        // Recherche de tous les champs au format texte : "LIKE %$term%"
        $textfield = @array(
            't.titre' => explode(" ", $qt['titre']),
            't.soustitre' => explode(" ", $qt['soustitre']),
            't.titre_abrege' => explode(" ", $qt['titreabrege']),
            't.titre_variante' => explode(" ", $qt['variantetitre']),
            't.faitsuitea' => explode(" ", $qt['faitsuitea']),
            't.devient' => explode(" ", $qt['devient']),
            'ed.editeur' => explode(" ", $qt['editeur_txt']),
            't.issnl' => $qt['issnl'],
            't.issn' => $qt['issn'],
            't.reroid' => $qt['reroid'],
            't.nlmid' => $qt['nlmid'],
            't.coden' => $qt['coden'],
            't.doi' => $qt['doi'],
            't.urn' => $qt['urn'],
            't.url_rss' => $qt['rss'],
            'abonnements.url_site' => $qt['url'],
            'abonnements.editeur_code' => $qt['codeediteur'],
            'abonnements.acces_user' => $qt['user'],
            'abonnements.acces_pwd' => $qt['pwd'],
            'abonnements.package' => $qt['package'],
            'abonnements.no_abo' => $qt['no_abo'],
            'abonnements.etatcoll' => $qt['etatcoll'],
            'abonnements.cote' => $qt['cote'],
            'abonnements.commentaire_pro' => $qt['commentairepro'],
            'abonnements.commentaire_pub' => $qt['commentairepub'],
            't.commentaire_pub' => $qt['commentairepub'],
            't.DEPRECATED_sujetsfm' => $qt['sujetsfm'],
            't.DEPRECATED_fmid' => $qt['fmid'],
            't.DEPRECARED_historique' => $sm_name,
            't.DEPRECARED_historique' => $qt['historique'],
        );
        foreach ($textfield as $column => $value) {
            // Pour champs dont on fait un recherche terme à terme
            if (is_array($value) && count($value) > 0) {
                $query = new CDbCriteria();
                foreach ($value as $term) {
                    $term = trim($term);
                    if ($term) {
                        $query->addSearchCondition($column, $term);
                        $this->query_summary("$column LIKE %$term%");
                    }
                }
                $criteria->mergeWith($query);
            }
            // Pour les champs dont la recherche ne porte que sur un seul terme
            else {
                $value = trim($value);
                if ($value) {
                    $criteria->addSearchCondition($column, $value);
                    $this->query_summary("$column LIKE %$value%");
                }
            }
        }

        // Recherche exacte : "= $term"
        $exact_fields = @array(
            't.openaccess' => $qt['openaccess'],
            't.parution_terminee' => $qt['parution_terminee'],
            'abonnements.licence' => $qt['licence'],
            'abonnements.statutabo' => $qt['statutabo'],
            'abonnements.plateforme' => $qt['plateforme'],
            'abonnements.gestion' => $qt['gestion'],
            'abonnements.histabo' => $qt['histabo'],
            'abonnements.support' => $qt['support'],
            'abonnements.format' => $qt['format'],
            'abonnements.editeur' => $qt['editeur'],
            'abonnements.titreexclu' => $qt['titreexclu'],
            'abonnements.localisation' => $qt['localisation'],
            'abonnements.etatcoll_deba' => $qt['etatcolldeba'],
            'abonnements.etatcoll_debv' => $qt['etatcolldebv'],
            'abonnements.etatcoll_debf' => $qt['etatcolldebf'],
            'abonnements.etatcoll_fina' => $qt['etatcollfina'],
            'abonnements.etatcoll_finv' => $qt['etatcollfinv'],
            'abonnements.etatcoll_finf' => $qt['etatcollfinf'],
            'abonnements.acces_elec_gratuit' => $qt['acces_elec_gratuit'],
            'abonnements.acces_elec_chuv' => $qt['acces_elec_chuv'],
            'abonnements.acces_elec_unil' => $qt['acces_elec_unil'],
        );
        foreach ($exact_fields as $column => $value) {
            $value = trim($value);
            if ($value) {
                $criteria->addCondition("$column = '$value'");
                $this->query_summary("$column = $value");
            }
        }

        // Traitement du cas de l'embargo
        if (trim($qt['embargo'])) {
            $criteria->addCondition("abonnements.embargo_mois " . $ct[$qt['embargocrit']] . " '" . $qt['embargo'] . "'");
            $this->query_summary("abonnements.embargo_mois " . $ct[$qt['embargocrit']] . " " . $qt['embargo']);
        }
        return $criteria;
        /*
          // S'il n'y auncun critère, on ne revoie rien.
          if (!$criteria->condition) {
          return NULL;
          } else {
          return new CActiveDataProvider(Journal::model(), array('criteria' => $criteria, 'pagination' => array(
          'pageSize' => $this->pagesize)));
          }
         * 
         */
    }

    private function aboadminSearch() {
        $qt = $this->admin_query_tab;
        $limite = 100;
        $ct = array('equal' => '=', 'before' => '<', 'after' => '>');

        $criteria = new CDbCriteria();
        $criteria->join .= 'LEFT JOIN `journal` `j` ON `j`.`perunilid`=`t`.`perunilid` ';

        // Jointure de la table sujet si nécessaire
        if (trim($qt['sujet'])) {
            $criteria->join .= 'LEFT JOIN `journal_sujet` `js` ON (`js`.`perunilid`=`t`.`perunilid`)';
            $criteria->join .= 'LEFT JOIN `sujet` `s` ON (`s`.`sujet_id`=`js`.`sujet_id`)';
            $criteria->addCondition("s.sujet_id ='{$qt['sujet']}'");
            $this->query_summary("sujet = " . Sujet::model()->findByPk($qt['sujet'])->nom_fr);
        }

        // Jointure de la table editeur si nécessaire
        if (trim($qt['editeur_txt'])) {
            $criteria->join .= 'LEFT JOIN editeur ed ON (ed.editeur_id=t.editeur)';
        }

        //Recherche par perunilid
        if (trim($qt['perunilid1'])) {
            $criteria->addCondition("t.perunilid " . $ct[$qt['perunilidcrit1']] . " '" . $qt['perunilid1'] . "'");
            $this->query_summary("perunilid " . $ct[$qt['perunilidcrit1']] . " " . $qt['perunilid1']);
            // S'il y a un deuxième perunilid
            if (trim($qt['perunilid2'])) {
                $criteria->addCondition("t.perunilid " . $ct[$qt['perunilidcrit2']] . " '" . $qt['perunilid2'] . "'");
                $this->query_summary("perunilid " . $ct[$qt['perunilidcrit2']] . " " . $qt['perunilid2']);
            }
        }

        // Modifications : Si un champ concernant le modifications est rempli
        // 1. Création d'une requête pour la table modification
        // 2. Liste de Perunilid ou d'abonnement_id comme resultat de la requête
        //    avec une maximum selon $limite
        // 3. la liste d'id est passée à une clause IN dans la requête principale.
        if (trim($qt['signaturecreation'])
                || trim($qt['signaturemodification'])
                || trim($qt['datecreation1'])
                || trim($qt['datemodif1'])) {


            $where_string = "";
            $where_array = array();

            // Préparation de la requête pour des recherche sur la création
            if (trim($qt['datecreation1'])) {
                $phpdate = strtotime(trim($qt['datecreation1']));
                $mysqldate = date('Y-m-d H:i:s', $phpdate);
                $where_string = "stamp " . $ct[$qt['datecreationcrit1']] . " :stamp";
                $where_array[":stamp"] = $mysqldate;
                $this->query_summary("date de création " . $ct[$qt['datecreationcrit1']] . " " . $qt['datecreation1']);

                if (trim($qt['datecreation2'])) {
                    $phpdate = strtotime(trim($qt['datecreation2']));
                    $mysqldate = date('Y-m-d H:i:s', $phpdate);
                    $where_string .= " AND stamp " . $ct[$qt['datecreationcrit2']] . " :stampi";
                    $where_array[":stampi"] = $mysqldate;
                    $this->query_summary(" et " . $ct[$qt['datecreationcrit2']] . " " . $qt['datecreation2']);
                } // datecreation2
            } // datecreation1
            // Recherche d'après le créateur
            if (trim($qt['signaturecreation'])) {
                $s = trim($qt['signaturecreation']);
                if ($where_string != "")
                    $where_string .= " AND ";
                $where_string .= ' (user_id = :sc';
                $where_array[':sc'] = $s;
                $where_string .= " AND action = :actc) ";
                $where_array[':actc'] = 'Création';
                $this->query_summary("Signature de création = " . Utilisateur::model()->findByPk($s)->pseudo);
            }

            // ---
            // Préparation de la requête pour des recherche sur la modification
            if (trim($qt['datemodif1'])) {
                $phpdate = strtotime(trim($qt['datemodif1']));
                $mysqldate = date('Y-m-d H:i:s', $phpdate);
                if ($where_string != "")
                    $where_string .= " AND ";
                $where_string .= "stamp " . $ct[$qt['datemodifcrit1']] . " :stampii";
                $where_array[":stampii"] = $mysqldate;
                $this->query_summary("date de modification " . $ct[$qt['datemodifcrit1']] . " " . $qt['datemodif1']);


                if (trim($qt['datemodif2'])) {
                    $phpdate = strtotime(trim($qt['datemodif2']));
                    $mysqldate = date('Y-m-d H:i:s', $phpdate);
                    $where_string .= " AND stamp " . $ct[$qt['datemodifcrit2']] . " :stampiii";
                    $where_array[":stampiii"] = $mysqldate;
                    $this->query_summary(" et " . $ct[$qt['datemodifcrit2']] . " " . $qt['datemodif2']);
                } // datemodif2
            } // datemodif1
            // Recherche d'après le modificateur
            if (trim($qt['signaturemodification'])) {
                $s = trim($qt['signaturemodification']);
                if ($where_string != "")
                    $where_string .= " AND ";
                $where_string .= ' (user_id = :sm';
                $where_array[':sm'] = $s;
                $where_string .= " AND action = :actm) ";
                $where_array[':actm'] = 'Modification';
                $this->query_summary("Signature de modification = " . Utilisateur::model()->findByPk($s)->pseudo);
            }

            $where_string .= " AND model = :model ";
            $where_array[':model'] = 'abonnement';
            $cmd = Yii::app()->db->createCommand()
                    ->selectDistinct('m.model_id')
                    ->from('modifications m')
                    ->where($where_string)
                    ->limit($limite)
                    ->order("stamp DESC");

            $perunilids = $cmd->queryAll(true, $where_array);
            $ids = join("','", array_map('current', $perunilids));
            // Ajout de la liste des ids concerné par la modification
            $criteria->addCondition("t.perunilid IN ('$ids')");
        } // Modifications
        // Recherche tous les champs
        if (trim($qt['all'])) {
            $this->q = $qt['all'];
            $this->journalSearch($criteria);
        }

        // Recherche de tous les champs au format texte : "LIKE %$term%"
        $textfield = @array(
            'j.titre' => explode(" ", $qt['titre']),
            'j.soustitre' => explode(" ", $qt['soustitre']),
            'j.titre_abrege' => explode(" ", $qt['titreabrege']),
            'j.titre_variante' => explode(" ", $qt['variantetitre']),
            'j.faitsuitea' => explode(" ", $qt['faitsuitea']),
            'j.devient' => explode(" ", $qt['devient']),
            'ed.editeur' => explode(" ", $qt['editeur_txt']),
            'j.issnl' => $qt['issnl'],
            'j.issn' => $qt['issn'],
            'j.reroid' => $qt['reroid'],
            'j.nlmid' => $qt['nlmid'],
            'j.coden' => $qt['coden'],
            'j.doi' => $qt['doi'],
            'j.urn' => $qt['urn'],
            'j.url_rss' => $qt['rss'],
            't.url_site' => $qt['url'],
            't.editeur_code' => $qt['codeediteur'],
            't.acces_user' => $qt['user'],
            't.acces_pwd' => $qt['pwd'],
            't.package' => $qt['package'],
            't.no_abo' => $qt['no_abo'],
            't.etatcoll' => $qt['etatcoll'],
            't.cote' => $qt['cote'],
            't.commentaire_pro' => $qt['commentairepro'],
            't.commentaire_pub' => $qt['commentairepub'],
            't.commentaire_pub' => $qt['commentairepub'],
            't.DEPRECATED_sujetsfm' => $qt['sujetsfm'],
            't.DEPRECATED_fmid' => $qt['fmid'],
            //'t.DEPRECARED_historique' => $sm_name,
            't.DEPRECARED_historique' => $qt['historique'],
        );
        foreach ($textfield as $column => $value) {
            // Pour champs dont on fait un recherche terme à terme
            if (is_array($value) && count($value) > 0) {
                $query = new CDbCriteria();
                foreach ($value as $term) {
                    $term = trim($term);
                    if ($term) {
                        $query->addSearchCondition($column, $term);
                        $this->query_summary("$column LIKE %$term%");
                    }
                }
                $criteria->mergeWith($query);
            }
            // Pour les champs dont la recherche ne porte que sur un seul terme
            else {
                $value = trim($value);
                if ($value) {
                    $criteria->addSearchCondition($column, $value);
                    $this->query_summary("$column LIKE %$value%");
                }
            }
        }

        // Recherche exacte : "= $term"
        $exact_fields = @array(
            'j.openaccess' => $qt['openaccess'],
            'j.parution_terminee' => $qt['parution_terminee'],
            't.licence' => $qt['licence'],
            't.statutabo' => $qt['statutabo'],
            't.plateforme' => $qt['plateforme'],
            't.gestion' => $qt['gestion'],
            't.histabo' => $qt['histabo'],
            't.support' => $qt['support'],
            't.format' => $qt['format'],
            't.editeur' => $qt['editeur'],
            't.titreexclu' => $qt['titreexclu'],
            't.localisation' => $qt['localisation'],
            't.etatcoll_deba' => $qt['etatcolldeba'],
            't.etatcoll_debv' => $qt['etatcolldebv'],
            't.etatcoll_debf' => $qt['etatcolldebf'],
            't.etatcoll_fina' => $qt['etatcollfina'],
            't.etatcoll_finv' => $qt['etatcollfinv'],
            't.etatcoll_finf' => $qt['etatcollfinf'],
            't.acces_elec_gratuit' => $qt['acces_elec_gratuit'],
            't.acces_elec_chuv' => $qt['acces_elec_chuv'],
            't.acces_elec_unil' => $qt['acces_elec_unil'],
        );
        foreach ($exact_fields as $column => $value) {
            $value = trim($value);
            if ($value) {
                $criteria->addCondition("$column = '$value'");
                $this->query_summary("$column = $value");
            }
        }

        // Traitement du cas de l'embargo
        if (trim($qt['embargo'])) {
            $criteria->addCondition("t.embargo_mois " . $ct[$qt['embargocrit']] . " '" . $qt['embargo'] . "'");
            $this->query_summary("t.embargo_mois " . $ct[$qt['embargocrit']] . " " . $qt['embargo']);
        }

        return $criteria;
        /*

          // S'il n'y auncun critère, on ne revoie rien.
          if (!$criteria->condition) {
          return NULL;
          } else {
          return new CActiveDataProvider(Abonnement::model(), array('criteria' => $criteria, 'pagination' => array(
          'pageSize' => $this->pagesize)));
          }

         */
    }

    private function query_summary($log) {
        if ($this->q_summary == "") {
            $this->q_summary = $log;
        } else {
            $this->q_summary .= ", " . $log;
        }
    }

    public function getQuerySummary() {
        return $this->q_summary;
    }

}
