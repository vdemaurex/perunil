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
     * Si true, les périodiques du dépot légal sont inculs dans la recherche.
     * @var bool false par défaut.
     */

    public $depotlegal = false;

    const depotlegal_idlocalisation = '24, 25, 26, 27';
    const BiUM_Corecollection = 6;

    /**
     * Requête de la recherche avancée
     * @var array 
     */
    protected $adv_query_tab;
    protected $adv_sql_command;
    protected $adv_count;
    protected $adv_dp;

    /**
     * Requête de la recherche simple
     * @var type 
     */
    protected $simple_query_str;
    /* @var $simple_sql_cmd CDbCommand */
    protected $simple_sql_cmd;
    protected $simple_sql_query_count;
    protected $simple_dp;

    /**
     * Requête de la recherche admin
     * @var type 
     */
    protected $admin_query_tab;
    protected $admin_criteria;
    protected $admin_affichage;
    protected $admin_count;
    protected $admin_dp;

    /**
     * $query après les traitements de base
     * @var string 
     */
    protected $q;

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
    public $maxresults = -1;

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
//$this->adv_criteria = $this->advancedSearch();
        //$this->advancedSearch(); // => $this->adv_sql_command
    }

    public function getAdv_query_tab() {
        if (isset($this->adv_query_tab)) {
            return $this->adv_query_tab;
        } else {
            return null;
        }
    }

    public function getAdv_dp() {
        if (isset($this->adv_query_tab)) {
            $this->advancedSearch();
            $rawData = $this->adv_sql_command->queryAll();
            $this->adv_count = count($rawData);
            $this->adv_dp = new CArrayDataProvider($rawData, array(
                'keyField' => 'perunilid',
                'pagination' => array(
                    'pageSize' => $this->pagesize,
                ),
            ));
        } else {
            throw new CException("Il n'existe aucune requête en mémoire pour afficher les résultats de la recherche avancée.");
        }

        return $this->adv_dp;
    }

    public function getAdv_adp() {

        if (isset($this->adv_query_tab)) {
            $this->advancedSearch();
            $rawData = $this->adv_sql_command->queryAll();
            $this->adv_count = count($rawData);
            $idlist = array_map('current', $rawData);

            $criteria = new CDbCriteria();
            $criteria->addInCondition('perunilid', $idlist);


            $adp = new CActiveDataProvider('Abonnement', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => $this->pagesize,
                ),
            ));
        } else {
            throw new CException("Il n'existe aucune requête en mémoire pour afficher les résultats de la recherche avancée.");
        }
        return $adp;
    }

    public function getAdv_count() {
        return $this->adv_count;
    }

    /* public function setAdv_dp($dp) {
      $this->adv_dp = $dp;
      } */

// Méthode privées
//================

    /**
     * Crée le critère de recherche pour la recherche avancée
     * @return CDbCriteria
     * @throws CException 
     */
    private function advancedSearch() {
        $this->q_summary = "";

// Vérification de l'existance et de la conformité de la requête.
        if (!$this->adv_query_tab && !is_array($this->adv_query_tab)) {
            throw new CException("Recherche avancée impossible : requête n'est enregistrée.");
        }

        $c = Yii::app()->db->createCommand();

        $c->selectDistinct('j.perunilid');
        $c->from('journal j');


// Jointure des abonnements
// Si public, seulement les journaux qui ont un abonnement
        if (Yii::app()->user->isGuest) {
            $c->join('abonnement a', 'j.perunilid = a.perunilid AND a.titreexclu = 0 ');
        }
// Si admin, tous les journaux, même sans abonnement
        else {
            $c->leftJoin('abonnement a', 'j.perunilid = a.perunilid');
        }



// 1. Jointure en fonction des limitations demandées

        if (isset($this->adv_query_tab['plateforme']) && $this->adv_query_tab['plateforme'] != '') {
            $c->join(
                    'plateforme pl', "a.plateforme = pl.plateforme_id AND pl.plateforme_id = :idpl", array(':idpl' => $this->adv_query_tab['plateforme'])
            );
            $this->query_summary("Plateforme = « " . Plateforme::model()->findByPk($this->adv_query_tab['plateforme'])->plateforme . " »");
        }

        if (isset($this->adv_query_tab['licence']) && $this->adv_query_tab['licence'] != '') {
            $c->join(
                    'licence li', "a.licence = li.licence_id AND li.licence_id = :idli", array(':idli' => $this->adv_query_tab['licence'])
            );
            $this->query_summary("Licence = « " . Licence::model()->findByPk($this->adv_query_tab['licence'])->licence . " »");
        }

        if (isset($this->adv_query_tab['statutabo']) && $this->adv_query_tab['statutabo'] != '') {
            $c->join(
                    'statutabo st', "a.statutabo = st.statutabo_id AND st.statutabo_id = :idst", array(':idst' => $this->adv_query_tab['statutabo'])
            );
            $this->query_summary("Abonnement = « " . Statutabo::model()->findByPk($this->adv_query_tab['statutabo'])->statutabo . " »");
        }

        if (isset($this->adv_query_tab['localisation']) && $this->adv_query_tab['localisation'] != '') {
            $c->join(
                    'localisation lo', "a.localisation = lo.localisation_id AND lo.localisation_id = :idlo", array(':idlo' => $this->adv_query_tab['localisation'])
            );
            $this->query_summary("Localisation = « " . Localisation::model()->findByPk($this->adv_query_tab['localisation'])->localisation . " »");
        }


// Jointure avec la table sujet
        if (isset($this->adv_query_tab['sujet']) && $this->adv_query_tab['sujet'] != '') {
            $c->join(
                    "journal_sujet js", "js.perunilid = j.perunilid");
            $c->join(
                    "sujet s", "s.sujet_id = js.sujet_id AND s.sujet_id = :sid", array(":sid" => $this->adv_query_tab['sujet'])
            );
            $this->query_summary("Sujet = « " . Sujet::model()->findByPk($this->adv_query_tab['sujet'])->nom_fr . " »");
        }

// Pour les critère d'accès, unil-chuv et openaccès, on ne traite que si c'est décoché
        if (!isset($this->adv_query_tab['accessunil']) || !$this->adv_query_tab['accessunil']) {
            $c->andWhere("a.acces_elec_unil !=1 && a.acces_elec_chuv !=1");
            $this->query_summary("sans les abonnements UNIL et CHUV.");
        }
        if (!isset($this->adv_query_tab['openaccess']) || !$this->adv_query_tab['openaccess']) {
            $c->andWhere("a.acces_elec_gratuit !=1 && j.openaccess !=1");
            $this->query_summary("sans les jouraux Openaccess.");
        }



        $Cwhere = "";
        $editorAlreadyJointed = false;
        $plateformeAlreadyJointed = false;
        foreach (array('C1', 'C2', 'C3') as $CN) {
            if (!isset($this->adv_query_tab[$CN]))
                continue;
// nettoyage du champ
            $this->simple_query_str = $this->adv_query_tab[$CN]['text'];
            $this->q = $this->clean_search($this->simple_query_str);
// si le champ ne contient rien , on abandonne ici.
            if ($this->q == "")
                continue;
            else { // Traitement du champ CN
                $like = ' LIKE ';
                switch ($this->adv_query_tab[$CN]['op']) {
                    case 'OR':
                        $Cwhere .= " OR ";
                        break;
                    case 'NOT': // AND ... NOT LIKE...
                        $like = " NOT LIKE ";
                    case 'AND':
                        $Cwhere .= " AND ";
                        break;
                    default:
                        throw new CException("L'opperateur {$this->adv_query_tab[$CN]['op']} n'existe pas dans les option proposées");
                        break;
                }

                switch ($this->adv_query_tab[$CN]['search_type']) {

                    case 'issn':
                        $issn = trim($this->simple_query_str);
// Ajout du - comme 5ème caratère si nécessaire
                        if (strpos($issn, '-') === FALSE) {
                            $issn = substr_replace($issn, '-', 4, 0);
                        }
                        $Cwhere .= " (j.issn $like '%$issn%' OR j.issnl $like '%$issn%') ";
                        $this->query_summary("issn = $this->simple_query_str");
                        break;

                    case 'titre':
                        $Twhere = "";
                        $tokens = array();

                        foreach (explode(" ", $this->q) as $word) {
                            if ($word != "" || $word != "") {
                                $tokens[] = Yii::app()->db->quoteValue("%$word%");
                            }
                        }

                        $cols = array('j.titre', 'j.titre_abrege', 'j.titre_variante', 'j.soustitre', 'j.faitsuitea', 'j.devient', 'a.commentaire_etatcoll');
// Boucle sur toutes les colonnes
                        foreach ($cols as $col) {
                            $Twhere .= " (";
// Boucle sur touts les mots de la recherche
                            foreach ($tokens as $word) {
                                $Twhere .= "$col $like $word AND ";
                            }
// Suppression d'un OR surnuméraire
                            $Twhere = trim($Twhere, "AND ");
                            $Twhere .= " ) OR ";
                        }

// Suppression d'un AND surnuméraire
                        $Twhere = trim($Twhere, " OR ");
// Ajout de la requête des titres à la requête générale

                        $Cwhere .= " ( $Twhere ) ";
                        $this->query_summary("titre : '$this->q'");
                        break;

                    case 'editeur':
                        if (!$editorAlreadyJointed) {
                            $c->leftjoin(
                                    'editeur ed', "a.editeur = ed.editeur_id "//AND ed.editeur LIKE :editeur", array(':editeur' => "%$this->q%")
                            );
                            $editorAlreadyJointed = true;
                        }
                        $this->query_summary("éditeur ou plateforme contenant l'expression : « " . $this->q . " »");

// Recherche dans la plateforme
// Si la plateforme n'as pas encore été jointe, on l'associe.
                        if (!$plateformeAlreadyJointed) {
                            if (!(isset($this->adv_query_tab['plateforme']) && $this->adv_query_tab['plateforme'] != '')) {
                                $c->leftjoin(
                                        'plateforme pl', "a.plateforme = pl.plateforme_id"
                                );
                                $plateformeAlreadyJointed = true;
                            }
                        }
                        $quotedq = Yii::app()->db->quoteValue("%$this->q%");
                        $Cwhere .= " (ed.editeur LIKE $quotedq OR pl.plateforme LIKE $quotedq) ";

                        break;

                    default:
                        throw new CException("Le critère {$this->adv_query_tab[$CN]['search_type']} n'existe pas pour la recherche avancée");
                        break;
                }
            }
        }

// Si une requête à été générée pour les CN, il faut enlever les conjonction surnuméraires
        if ($Cwhere != "") {
            $Cwhere = trim($Cwhere, "OR ");
            $Cwhere = trim($Cwhere, "AND ");
// Ajour de Cwhere à la requête générale
            $c->andWhere($Cwhere);
        }

// Ajout des abonnements du dépot légal
        if ($this->depotlegal) {
            $this->query_summary("avec les périodiques du dépot légal BCU");
        } else {
            $c->andWhere("(a.localisation NOT IN (" . self::depotlegal_idlocalisation . ") OR a.localisation IS NULL)");
        }


        $c->order("j.titre");

        $sql = $c->text;

        $this->adv_sql_command = $c;

//return $criteria;
// Gérération d'une requête count
//$c->select = "SELECT DISTINCT COUNT(*) ";
    }

///////////////////////////////////////////////////////////////////////////
// Recherche simple
///////////////////////////////////////////////////////////////////////////
// Méthodes publiques
//===================


    public function setSimple_query_str($query_str) {
        $this->simple_query_str = $query_str;
    }

    public function getSimple_query_str() {
        if (isset($this->simple_query_str)) {
            return $this->simple_query_str;
        } else {
            return null;
        }
    }

    private function generateSimpleSqlCmd() {
        $query_str = $this->getSimple_query_str();
        if (empty($query_str)) {
            throw new CException("Il n'existe aucune requête en mémoire pour afficher les résultats de la recherche simple.");
        }
        $simpleSearch = new SimpleSearchComponent;
        $this->simple_sql_cmd = $simpleSearch->getSimpleCdbCommand($query_str, $this->search_type);
    }

    public function getSimple_dp() {
        $this->generateSimpleSqlCmd();


        $rawData = $this->simple_sql_cmd->queryAll();
        $this->simple_sql_query_count = count($rawData);
        $this->simple_dp = new CArrayDataProvider($rawData, array(
            'keyField' => 'perunilid',
            //'sort' => array(
            //    'attributes' => array(
            //        'id', 'username', 'email',
            //    ),
            //),
            'pagination' => array(
                'pageSize' => $this->pagesize,
            ),
        ));

        return $this->simple_dp;
    }

    /**
     *  Crée un ActiveDataProvider a partir de la liste des perunilid issus de
     * la requête. Utilisé pour l'affichage par abonnements.
     * @return \CActiveDataProvider
     */
    public function getSimple_adp() {
        $this->generateSimpleSqlCmd();

        $rawData = $this->simple_sql_cmd->queryAll();
        $this->simple_sql_query_count = count($rawData);

        $idlist = array_map('current', $rawData);

        $criteria = new CDbCriteria();
        $criteria->addInCondition('perunilid', $idlist);


        $adp = new CActiveDataProvider('Abonnement', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $this->pagesize,
            ),
        ));

        return $adp;
    }

    public function getSimple_sql_query_count() {
        return $this->simple_sql_query_count;
    }

    public function getSimple_sql_query() {
        return $this->simple_sql_cmd;
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
                $word = Yii::app()->db->quoteValue("%$word%");
                $query = "t.perunilid $like $word OR titre $like $word " .
                        "OR titre_abrege $like $word OR titre_variante $like $word " .
                        "OR soustitre $like $word OR issn $like $word " .
                        "OR issnl $like $word OR nlmid $like $word " .
                        "OR reroid $like $word OR doi $like $word " .
                        "OR coden $like $word OR urn $like $word " .
                        "OR faitsuitea $like $word OR devient $like $word " .
                        "OR url_rss $like $word ";
                $criteria->addCondition($query, 'AND');
            }
        }
        $this->query_summary("'$this->q' dans tous les champs de la table journal.");
    }

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
    protected function clean_search($original) {
        $var = trim($original);

        // Suppression de guillemet et apostrophes
        $var = str_replace('"', " ", $var);
        $var = str_replace("'", " ", $var);
        $var = str_replace("’", " ", $var);
        $var = str_replace("ʼ", " ", $var);
        $var = str_replace("&#39;", " ", $var);

        
        $var = " " . $var . " ";
        $var = str_ireplace(",", "", $var);
        $var = str_ireplace(". ", " ", $var);
        $var = str_ireplace(": ", " ", $var);
        $var = str_ireplace(":", " ", $var);
//$var = str_ireplace("-", " ", $var); // Pour les issn, il ne faut pas retirer le -
        $var = str_ireplace(";", "", $var);
        $var = str_ireplace(" (the) ", " ", $var);
        $var = str_ireplace(" the ", " ", $var);
        $var = str_ireplace(" [the] ", " ", $var);
        $var = str_ireplace(" of ", " ", $var);
        $var = str_ireplace(" de ", " ", $var);
        $var = str_ireplace(" du ", " ", $var);
        $var = str_ireplace(" l ", " ", $var);
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
// Recherche admin
///////////////////////////////////////////////////////////////////////////
// Méthodes publiques
//===================

    public function setAdmin_query_tab($query_tab) {
        $this->admin_query_tab = $query_tab;
    }

    public function setAdmin_affichage($affichage = 'abonnement') {

        if ($affichage == 'journal') {
            $this->admin_affichage = 'journal';
        } else {
            $this->admin_affichage = 'abonnement';
        }
    }

    public function getAdmin_affichage() {
        if (isset($this->admin_affichage) && in_array($this->admin_affichage, array("journal", "abonnement"))) {
            return $this->admin_affichage;
        } else {
            return 'journal';
        }
    }

    public function getAdmin_query_tab() {
        if (isset($this->admin_query_tab)) {
            return $this->admin_query_tab;
        } else {
            return null;
        }
    }
 
   public function getAdmin_adp() {        
        $asc = new AdminSearchComponent($this);
        
        if ($this->getAdmin_affichage() == 'abonnement') {
            $aboIdList = $asc->getAboIdList();
            $this->admin_count = count($aboIdList);
            
            $criteria = new CDbCriteria();
            $criteria->addInCondition('abonnement_id', $aboIdList);
            $criteria->with = array('jrn');  
            $criteria->order = 'jrn.titre'; 



            $adp = new CActiveDataProvider('Abonnement', array(
                'criteria' => $criteria,
                'pagination' => array(
                'pageSize' => $this->pagesize,
                ),
            ));
            
        } else {
            $perunilidList = $asc->getPerunilidList();
            
            $criteria = new CDbCriteria();
            $criteria->addInCondition('perunilid', $perunilidList);
            $criteria->order = "titre";


            $adp = new CActiveDataProvider('Journal', array(
                'criteria' => $criteria,
                'pagination' => array(
                'pageSize' => $this->pagesize,
                ),
            ));
      }
        
        $this->admin_count = $adp->totalItemCount;
        return $adp;
    }
    
    public function getAdmin_count() {
        return $this->admin_count;
    }
    
    public function getAdminIds(){
        $asc = new AdminSearchComponent($this);
        
        if ($this->getAdmin_affichage() == 'abonnement') {
            return $asc->getAboIdList();
             
        } else {
            return $asc->getPerunilidList();            
      }
    }


    public function query_summary($log) {
        if ($this->q_summary == "") {
            $this->q_summary = $log;
        } else {
            $this->q_summary .= ", " . $log;
        }
    }

    public function emptyQuerySummary() {
        $this->q_summary = "";
    }

    public function getQuerySummary() {
        return $this->q_summary;
    }
}
