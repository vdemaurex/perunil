<?php

/**
 * This is the model class for table "journal".
 *
 * The followings are the available columns in table 'journal':
 * @property string $perunilid
 * @property string $titre
 * @property string $soustitre
 * @property string $titre_abrege
 * @property string $titre_variante
 * @property string $faitsuitea
 * @property string $devient
 * @property string $issn
 * @property string $issnl
 * @property string $nlmid
 * @property string $reroid
 * @property string $doi
 * @property string $coden
 * @property string $urn
 * @property integer $publiunil
 * @property string $url_rss
 * @property string $commentaire_pub
 * @property integer $parution_terminee
 * @property integer $openaccess
 * @property string $DEPRECATED_sujetsfm
 * @property integer $DEPRECATED_fmid
 * @property string $DEPRECATED_historique
 *
 * The followings are the available model relations:
 * @property Abonnement[] $abonnements
 * @property Biblio[] $biblios
 * @property Sujet[] $sujets
 */
class Journal extends ModifModel {

    public $perunilid;
    public $titre;
    public $soustitre;
    public $titre_abrege;
    public $titre_variante;
    public $issn;
    public $issnl;
    public $nlmid;
    public $reroid;
    public $doi;
    public $coden;
    public $urn;

    const DEPOTLEGALID = '23, 24, 25, 26';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Journal the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'journal';
    }

    public function behaviors() {
        // Pas de suivit avec AuditTrail si on est en ligne de commande.
        if (php_sapi_name() != 'cli'){
            return array('LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior',);
        }
        else{
            return array();
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('titre', 'required'),
            array('publiunil, parution_terminee, openaccess, DEPRECATED_fmid', 'numerical', 'integerOnly' => true),
            array('titre, soustitre, titre_variante, faitsuitea, devient, doi, urn', 'length', 'max' => 250),
            array('titre_abrege', 'length', 'max' => 100),
            array('issn', 'length', 'max' => 120),
            array('issnl', 'length', 'max' => 9),
            array('nlmid', 'length', 'max' => 15),
            array('reroid', 'length', 'max' => 50),
            array('coden', 'length', 'max' => 6),
            array('url_rss', 'length', 'max' => 2083),
            array('commentaire_pub', 'length', 'max' => 500),
            array('DEPRECATED_sujetsfm', 'length', 'max' => 1000),
            array('DEPRECATED_historique', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('perunilid, titre, soustitre, titre_abrege, titre_variante, faitsuitea, devient, issn, issnl, nlmid, reroid, doi, coden, urn, publiunil, url_rss, commentaire_pub, parution_terminee, openaccess, DEPRECATED_sujetsfm, DEPRECATED_fmid, DEPRECATED_historique', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        $relations = array(
            'abonnements' => array(self::HAS_MANY, 'Abonnement', 'perunilid'),
            'activeabos' => array(self::HAS_MANY, 'Abonnement', 'perunilid',
                'condition' => 'titreexclu != 1',
                'order' => 'support'),
            'corecollection' => array(self::MANY_MANY, 'Biblio', 'corecollection(perunilid, biblio_id)'),
            'sujets' => array(self::MANY_MANY, 'Sujet', 'journal_sujet(perunilid, sujet_id)'),
            // Abonnements
            'activeAllAbos'  => array(self::HAS_MANY, 'Abonnement', 'perunilid',
                'condition' => 'titreexclu != 1',
                'order'     => 'support'),
            'activePaperAbos'  => array(self::HAS_MANY, 'Abonnement', 'perunilid',
                'condition' => 'titreexclu != 1 AND support = 2',
                'order'     => 'support'),
            'activeElecAbos'  => array(self::HAS_MANY, 'Abonnement', 'perunilid',
                'condition' => 'titreexclu != 1 AND support = 1',
                'order'     => 'support'),
            'AllAbos'    => array(self::HAS_MANY, 'Abonnement', 'perunilid'),
            'PaperAbos'  => array(self::HAS_MANY, 'Abonnement', 'perunilid',
                'condition' => 'support = 2',
                'order'     => 'support'),
            'ElecAbos'  => array(self::HAS_MANY, 'Abonnement', 'perunilid',
                'condition' => 'support = 1',
                'order'     => 'support'),
        );
        
        return array_merge($relations, parent::relations());
    }

    public function getId() {
        return $this->perunilid;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'perunilid' => 'Perunilid',
            'titre' => 'Titre',
            'soustitre' => 'Soustitre',
            'titre_abrege' => 'Titre Abrege',
            'titre_variante' => 'Titre Variante',
            'faitsuitea' => 'Faitsuitea',
            'devient' => 'Devient',
            'issn' => 'Issn',
            'issnl' => 'Issnl',
            'nlmid' => 'Nlmid',
            'reroid' => 'Reroid',
            'doi' => 'Doi',
            'coden' => 'Coden',
            'urn' => 'Urn',
            'publiunil' => 'Publiunil',
            'url_rss' => 'Url Rss',
            'commentaire_pub' => 'Commentaire Pub',
            'parution_terminee' => 'Parution Terminee',
            'openaccess' => 'Openaccess',
            'DEPRECATED_sujetsfm' => 'Deprecated Sujetsfm',
            'DEPRECATED_fmid' => 'Deprecated Fmid',
            'DEPRECATED_historique' => 'DEPRECATED Historique',
        );
    }

    public function delete() {
        
        // La supression des abonnements doit se faire manuellement.
        if (count($this->abonnements) > 0) {
            $listabos = "Liste des abonnement liés à ce journal : ";
            foreach ($this->abonnements as $abo) {
                $listabos .= $abo->abonnement_id . "; ";
            }
            throw new CDbException("Impossible de supprimer ce journal (perunilid $this->perunilid) car des abonnements lui sont liés. \n<br/>$listabos");
        }
        
        // Supression des sujets liés
        JournalSujet::model()->deleteAll("perunilid=:perunilid", array("perunilid" => $this->perunilid));
        
        // Supression des corecollection liées
        Corecollection::model()->deleteAll("perunilid=:perunilid", array("perunilid" => $this->perunilid));
   
        // Supression du journal
        return parent::delete();
    }

    public function sujets2str($delimiter = ",") {
        $sujet_str = "";
        foreach ($this->sujets as $s) {
            $sujet_str .= CHtml::link($s->nom_fr, array(
                        'site/adv',
                        'advsearch' => "advsearch",
                        'sujet' => $s->sujet_id,
                    )) . "$delimiter ";
        }
        return trim($sujet_str, "$delimiter ");
    }

    public function corecollection2str() {
        $corecollection_str = "";
        // Etablissement de la liste des corecollection
        foreach ($this->corecollection as $s) {
            $corecollection_str .= $s->biblio . ", ";
        }
        return trim($corecollection_str, ", ");
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('perunilid', $this->perunilid, true);
        $criteria->compare('titre', $this->titre, true);
        $criteria->compare('soustitre', $this->soustitre, true);
        $criteria->compare('titre_abrege', $this->titre_abrege, true);
        $criteria->compare('titre_variante', $this->titre_variante, true);
        $criteria->compare('faitsuitea', $this->faitsuitea, true);
        $criteria->compare('devient', $this->devient, true);
        $criteria->compare('issn', $this->issn, true);
        $criteria->compare('issnl', $this->issnl, true);
        $criteria->compare('nlmid', $this->nlmid, true);
        $criteria->compare('reroid', $this->reroid, true);
        $criteria->compare('doi', $this->doi, true);
        $criteria->compare('coden', $this->coden, true);
        $criteria->compare('urn', $this->urn, true);
        $criteria->compare('publiunil', $this->publiunil);
        $criteria->compare('url_rss', $this->url_rss, true);
        $criteria->compare('commentaire_pub', $this->commentaire_pub, true);
        $criteria->compare('parution_terminee', $this->parution_terminee);
        $criteria->compare('openaccess', $this->openaccess);
        $criteria->compare('DEPRECATED_sujetsfm', $this->DEPRECATED_sujetsfm, true);
        $criteria->compare('DEPRECATED_fmid', $this->DEPRECATED_fmid);
        $criteria->compare('DEPRECATED_historique', $this->DEPRECATED_historique, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    

    /**
     * Cherche dans le titre d'abord par titre, puis par mot. Renvoi la liste de journaux.
     * @param string $query mots donnée par l'utilisateur
     * @param boolean $withDepotLegat si vrai, la recherche porte aussi sur le dépot legal
     * @param integer $limite nombre maxi
     * @return array Liste des journaux qui correspondent aux critères de recherche
     */
    public function searchTitleWord($query, $withDepotLegat = false, $searchtype = SearchComponent::TBEGIN, $limite = 10) {
        
        $criteria = new CDbCriteria();
        $criteria->select = "titre";
        $criteria->distinct = true;
        $criteria->alias = "j";
        $criteria->join ="INNER JOIN abonnement AS a ON j.perunilid = a.perunilid ";

        // Suppression des abonnements du dépot légal
        if (!$withDepotLegat) {
            $criteria->join .= "AND (a.localisation NOT IN (". self::DEPOTLEGALID.") OR a.localisation IS NULL)";
        }

        $cols = array('titre');//, 'titre_abrege', 'titre_variante', 'soustitre', 'faitsuitea', 'devient');

        $tokens = array();
        // Par défaut, on cherche le début du titre, sauf si spécifier différement
        if ($searchtype == SearchComponent::TWORDS){
            foreach (explode(" ", $query) as $word) {
                if ($word != "" || $word != "") {
                    $tokens[] = "%$word%";
                }
            }
        }
        else{
            $tokens[] = "$query%";
        }
 
        // Boucle sur toutes les colonnes
        foreach ($cols as $col) {
            // Boucle sur touts les mots de la recherche
            foreach ($tokens as $word) {
                if ($word != "") {
                    $criteria->addCondition("$col LIKE " . Yii::app()->db->quoteValue($word), 'AND');
                }
            }
        }
        $criteria->order = "titre";

        // Nombre maximum de résultats
        $criteria->limit = $limite;
   

        return Journal::model()->findAll($criteria);
    }

    
    
    
    public function copy() {
        Yii::log("Duplication du journal " . $this->perunilid, 'info', 'copy' . __CLASS__);
        $new = new Journal();
        $data = $this->attributes;
        $data['titre'] = $data['titre'] . " - Copie";
        unset($data['perunilid']); //Suppression de l'id, car c'est une nouvelle entrée.
        $new->setAttributes($data, false);
        $new->insert();
        if (!$new->perunilid) {
            Yii::log("La duplication de l'abonnement a échoué.", 'info', 'copy' . __CLASS__);
            return null;
        } else {
            // Traitement des abonnement
            foreach ($this->abonnements as $abo) {
                $abo->copy($new->perunilid);
            }
            // Copie de la liste des sujets
            foreach ($this->sujets as $sujet) {
                $newjs = new JournalSujet();
                $newjs->perunilid = $new->perunilid;
                $newjs->sujet_id = $sujet->sujet_id;
                if (!$newjs->insert()) {
                    throw new CException("Impossible de créer un nouveau sujet lors de la copie du journal $this->perunilid");
                }
            }

            // Copie des corecollection
            foreach ($this->corecollection as $biblio) {
                $corecollection = new Corecollection();
                $corecollection->perunilid = $new->perunilid;
                $corecollection->biblio_id = $biblio->biblio_id;
                if (!$corecollection->insert()) {
                    throw new CException("Impossible de créer un nouveau corecollection lors de la copie du journal $this->perunilid");
                }
            }
        }
        return $new;
    }

}