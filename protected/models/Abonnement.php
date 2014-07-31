<?php

/**
 * This is the model class for table "abonnement".
 *
 * The followings are the available columns in table 'abonnement':
 * @property string $abonnement_id
 * @property integer $titreexclu
 * @property string $package
 * @property string $no_abo
 * @property string $url_site
 * @property integer $acces_elec_gratuit
 * @property integer $acces_elec_unil
 * @property integer $acces_elec_chuv
 * @property integer $embargo_mois
 * @property string $acces_user
 * @property string $acces_pwd
 * @property string $etatcoll
 * @property integer $etatcoll_deba
 * @property integer $etatcoll_debv
 * @property integer $etatcoll_debf
 * @property integer $etatcoll_fina
 * @property integer $etatcoll_finv
 * @property integer $etatcoll_finf
 * @property string $cote
 * @property string $editeur_code
 * @property string $editeur_sujet
 * @property string $commentaire_pro
 * @property string $commentaire_pub
 * @property string $perunilid
 * @property integer $plateforme
 * @property integer $editeur
 * @property integer $histabo
 * @property integer $statutabo
 * @property integer $localisation
 * @property integer $gestion
 * @property integer $format
 * @property integer $support
 * @property integer $licence
 *
 * The followings are the available model relations:
 * @property Editeur $editeur0
 * @property Histabo $histabo0
 * @property Statutabo $statutabo0
 * @property Localisation $localisation0
 * @property Gestion $gestion0
 * @property Format $format0
 * @property Support $support0
 * @property Licence $licence0
 * @property Journal $perunil
 * @property Plateforme $plateforme0
 */
class Abonnement extends ModifModel {

    public $journal_titre;
    private $modif_tmp;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Abonnement the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'abonnement';
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
            array('titreexclu, acces_elec_gratuit, acces_elec_unil, acces_elec_chuv, embargo_mois, etatcoll_deba, etatcoll_debv, etatcoll_debf, etatcoll_fina, etatcoll_finv, etatcoll_finf, plateforme, editeur, histabo, statutabo, localisation, gestion, format, support, licence', 'numerical', 'integerOnly' => true),
            array('package,commentaire_etatcoll, etatcoll, cote, editeur_sujet', 'length', 'max' => 250),
            array('no_abo, acces_user, acces_pwd', 'length', 'max' => 50),
            array('url_site', 'length', 'max' => 2083),
            array('editeur_code', 'length', 'max' => 100),
            array('commentaire_pro, commentaire_pub', 'length', 'max' => 500),
            array('perunilid', 'length', 'max' => 20),
            array('titre, issn,perunilid, journal_titre', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('journal_titre, abonnement_id, titreexclu, package, no_abo, url_site, acces_elec_gratuit, acces_elec_unil, acces_elec_chuv, embargo_mois, acces_user, acces_pwd,commentaire_etatcoll, etatcoll, etatcoll_deba, etatcoll_debv, etatcoll_debf, etatcoll_fina, etatcoll_finv, etatcoll_finf, cote, editeur_code, editeur_sujet, commentaire_pro, commentaire_pub, perunilid, plateforme, editeur, histabo, statutabo, localisation, gestion, format, support, licence', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {

        $relations = array(
            // Générées automatiquements
            'editeur0' => array(self::BELONGS_TO, 'Editeur', 'editeur'),
            'histabo0' => array(self::BELONGS_TO, 'Histabo', 'histabo'),
            'statutabo0' => array(self::BELONGS_TO, 'Statutabo', 'statutabo'),
            'localisation0' => array(self::BELONGS_TO, 'Localisation', 'localisation'),
            'gestion0' => array(self::BELONGS_TO, 'Gestion', 'gestion'),
            'format0' => array(self::BELONGS_TO, 'Format', 'format'),
            'support0' => array(self::BELONGS_TO, 'Support', 'support'),
            'licence0' => array(self::BELONGS_TO, 'Licence', 'licence'),
            'plateforme0' => array(self::BELONGS_TO, 'Plateforme', 'plateforme'),
            // Modifiées
            'jrn' => array(self::BELONGS_TO, 'Journal', 'perunilid'),
        );
        
        return array_merge($relations, parent::relations());
    }

    public function getId() {
        return $this->abonnement_id;
    }

    public function getPapier() {
        return (isset($this->support0) && $this->support0->support == "papier");
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'abonnement_id' => 'Abonnement id',
            'titreexclu' => 'Titreexclu',
            'package' => 'Nom du package',
            'no_abo' => 'Numéro d\'abonnement',
            'url_site' => 'Url Site',
            'acces_elec_gratuit' => 'Acces Elec Gratuit',
            'acces_elec_unil' => 'Acces Elec Unil',
            'acces_elec_chuv' => 'Acces Elec Chuv',
            'embargo_mois' => 'Embargo',
            'acces_user' => 'Acces User',
            'acces_pwd' => 'Acces Pwd',
            'commentaire_etatcoll' => "Commentaire de l'état de la collection",
            'etatcoll' => 'Etat de la collection',
            'etatcoll_deba' => 'Année de début',
            'etatcoll_debv' => 'Permier volume',
            'etatcoll_debf' => 'Permier numéro',
            'etatcoll_fina' => 'Année de fin',
            'etatcoll_finv' => 'Dernier volume',
            'etatcoll_finf' => 'Dernier numéro',
            'cote' => 'Cote',
            'editeur_code' => 'Editeur Code',
            'editeur_sujet' => 'Editeur Sujet',
            'commentaire_pro' => 'Commentaire Pro',
            'commentaire_pub' => 'Commentaire Pub',
            'perunilid' => 'Perunilid',
            'plateforme' => 'Plateforme',
            'editeur' => 'Editeur',
            'histabo' => 'Histabo',
            'statutabo' => 'Statutabo',
            'localisation' => 'Localisation',
            'gestion' => 'Gestion',
            'format' => 'Format',
            'support' => 'Support',
            'licence' => 'Licence',
        );
    }

    public static function compare($abo1, $abo2) {

        if ($abo1->support != $abo2->support) {
            return $abo1->support < $abo2->support ? -1 : 1; //asc
            //return $abo1->support < $abo2->support ? 1 : -1; //desc
        }

        return 0; // renvoie 0 si les objets sont egaux.
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;


        $criteria->compare('t.titreexclu', $this->titreexclu);
        $criteria->compare('t.package', $this->package, true);
        $criteria->compare('t.no_abo', $this->no_abo, true);
        $criteria->compare('t.url_site', $this->url_site, true);
        $criteria->compare('t.acces_elec_gratuit', $this->acces_elec_gratuit);
        $criteria->compare('t.acces_elec_unil', $this->acces_elec_unil);
        $criteria->compare('t.acces_elec_chuv', $this->acces_elec_chuv);
        $criteria->compare('t.embargo_mois', $this->embargo_mois);
        $criteria->compare('t.acces_user', $this->acces_user, true);
        $criteria->compare('t.acces_pwd', $this->acces_pwd, true);
        $criteria->compare('t.etatcoll', $this->etatcoll, true);
        $criteria->compare('t.etatcoll_deba', $this->etatcoll_deba);
        $criteria->compare('t.etatcoll_debv', $this->etatcoll_debv);
        $criteria->compare('t.etatcoll_debf', $this->etatcoll_debf);
        $criteria->compare('t.etatcoll_fina', $this->etatcoll_fina);
        $criteria->compare('t.etatcoll_finv', $this->etatcoll_finv);
        $criteria->compare('t.etatcoll_finf', $this->etatcoll_finf);
        $criteria->compare('t.cote', $this->cote, true);
        $criteria->compare('t.editeur_code', $this->editeur_code, true);
        $criteria->compare('t.editeur_sujet', $this->editeur_sujet, true);
        $criteria->compare('t.commentaire_pro', $this->commentaire_pro, true);
        $criteria->compare('t.commentaire_pub', $this->commentaire_pub, true);
        $criteria->compare('t.perunilid', $this->perunilid);
        $criteria->compare('t.plateforme', $this->plateforme);
        $criteria->compare('t.editeur', $this->editeur);
        $criteria->compare('t.histabo', $this->histabo);
        $criteria->compare('t.statutabo', $this->statutabo);
        $criteria->compare('t.localisation', $this->localisation);
        $criteria->compare('t.gestion', $this->gestion);
        $criteria->compare('t.format', $this->format);
        $criteria->compare('t.support', $this->support);
        $criteria->compare('t.licence', $this->licence);



        $criteria->together = true;
        $criteria->compare('t.abonnement_id', $this->abonnement_id, true);
        $criteria->with = array('jrn');
        $criteria->compare('jrn.titre', $this->journal_titre, true);
        //$criteria->compare('titre',$this->titre,true);
        //$criteria->compare('issn' ,$this->perunilid,true,"OR");


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function copy($perunilid = null) {
        Yii::log("Duplication de l'abonnement " . $this->abonnement_id, 'info', 'copy' . __CLASS__);
        $new = new Abonnement();
        $data = $this->attributes;
        unset($data['abonnement_id']); //Suppression de l'id, car c'est une nouvelle entrée.
        if ($perunilid) {
            $data['perunilid'] = $perunilid;
        }
        $new->setAttributes($data, false);
        $new->insert();
        if (!$new->abonnement_id) {
            Yii::log("La duplication de l'abonnement a échoué.", 'info', 'copy' . __CLASS__);
            return null;
        }
        return $new;
    }

    public function cached_relation_value($relation_column) {

        // Si la relation n'existe pas
        if (!isset($this->$relation_column) || $this->$relation_column == "") {
            return null;
        }

        // ID du cache : nom de la table + _ + id de l'objet
        $cache_id = $relation_column . "_" . $this->$relation_column;
        $abo_rel = $relation_column . "0";

        $cached_val = Yii::app()->cache->get($cache_id);
        if ($cached_val === FALSE) {
            // La valeur n'a pas été trouvée dans le cache. Elle est regénérée.
            $obj = $this->$abo_rel;
            $cached_val = $obj->$relation_column;
            Yii::app()->cache->set($cache_id, $cached_val);
        }
        // On retourne la valeur dans le cache
        return $cached_val;
    }

    /**
     * Affichage de l'abonnement 
     */
    // TODO : dépalcer dans un composant vue

    const imgstyle = "vertical-align: top;";

    public function htmlImgTag() {
        if (isset($this->support0) && isset($this->support0->support)) {
            //
            // Traitement des support papier
            //
            // Icône
            if ($this->support0->support == "papier") {
                //$src = Yii::app()->baseUrl . "/images/paper.png";
                //return CHtml::image($src, "Papier", array('title' => "Support papier", 'style' => self::imgstyle));
                return '<span class="glyphicon glyphicon-book"></span>&nbsp;';
            }
            //
            // Traitement des support électronique
            //
            else {
                //$src = Yii::app()->baseUrl . "/images/www.png";
                //return CHtml::image($src, "Electronique", array('title' => "Support éléctronique", 'style' => self::imgstyle));
                return '<span class="glyphicon glyphicon-new-window"></span>&nbsp;';
            }
        }
    }

    public function htmlImgTitreExclu() {
        // Icône interdit si l'abonnement est un titre exclu
        if ($this->titreexclu) {
            //$src = Yii::app()->baseUrl . "/images/interdit.png";
            //return CHtml::image($src, "Titre exclu", array('title' => "Titre exclu", 'style' => self::imgstyle));
            return '<span class="glyphicon glyphicon-ban-circle"></span>&nbsp;';
        }
    }

    public function htmlShortDescription() {
        $desc = '<small><dl>';

        if (isset($this->licence0) && isset($this->licence0->licence))
            $desc .= "<dt>Licence</dt><dd>" . $this->licence0->licence . "</dd>";
        if (isset($this->plateforme0) && isset($this->plateforme0->plateforme))
            $desc .= "<dt>Plateforme</dt><dd>" . $this->plateforme0->plateforme . "</dd>";
        if (isset($this->editeur0) && isset($this->editeur0->editeur))
            $desc .= "<dt>Editeur</dt><dd>" . $this->editeur0->editeur . "</dd>";
        if (isset($this->localisation0) && isset($this->localisation0->localisation))
            $desc .= "<dt>Localisation</dt><dd>" . $this->localisation0->localisation . "</dd>";
        if (isset($this->etatcoll) && $this->etatcoll != "")
            $desc .= "<dt>Etatcoll</dt><dd>" . $this->etatcoll . "</dd>";
        $desc .= "</dl></small>";
        return htmlspecialchars($desc);
    }



}
