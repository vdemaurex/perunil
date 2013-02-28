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
 * @property Plateforme $plateforme
 */
class Abonnement extends CActiveRecord {

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
        return array('LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior',);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('titreexclu, acces_elec_gratuit, acces_elec_unil, acces_elec_chuv, embargo_mois, etatcoll_deba, etatcoll_debv, etatcoll_debf, etatcoll_fina, etatcoll_finv, etatcoll_finf, plateforme, editeur, histabo, statutabo, localisation, gestion, format, support, licence', 'numerical', 'integerOnly' => true),
            array('package, etatcoll, cote, editeur_sujet', 'length', 'max' => 250),
            array('no_abo, acces_user, acces_pwd', 'length', 'max' => 50),
            array('url_site', 'length', 'max' => 2083),
            array('editeur_code', 'length', 'max' => 100),
            array('commentaire_pro, commentaire_pub', 'length', 'max' => 500),
            array('perunilid', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('abonnement_id, titreexclu, package, no_abo, url_site, acces_elec_gratuit, acces_elec_unil, acces_elec_chuv, embargo_mois, acces_user, acces_pwd, etatcoll, etatcoll_deba, etatcoll_debv, etatcoll_debf, etatcoll_fina, etatcoll_finv, etatcoll_finf, cote, editeur_code, editeur_sujet, commentaire_pro, commentaire_pub, perunilid, plateforme, editeur, histabo, statutabo, localisation, gestion, format, support, licence', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'editeur0' => array(self::BELONGS_TO, 'Editeur', 'editeur'),
            'histabo0' => array(self::BELONGS_TO, 'Histabo', 'histabo'),
            'statutabo0' => array(self::BELONGS_TO, 'Statutabo', 'statutabo'),
            'localisation0' => array(self::BELONGS_TO, 'Localisation', 'localisation'),
            'gestion0' => array(self::BELONGS_TO, 'Gestion', 'gestion'),
            'format0' => array(self::BELONGS_TO, 'Format', 'format'),
            'support0' => array(self::BELONGS_TO, 'Support', 'support'),
            'licence0' => array(self::BELONGS_TO, 'Licence', 'licence'),
            'perunil' => array(self::BELONGS_TO, 'Journal', 'perunilid'),
            'plateforme' => array(self::BELONGS_TO, 'Plateforme', 'plateforme'),
        );
    }

    public function getId() { 
        return $this->abonnement_id;
        
        }
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            
            'abonnement_id' => 'Abonnement',
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

        $criteria->compare('abonnement_id', $this->abonnement_id, true);
        $criteria->compare('titreexclu', $this->titreexclu);
        $criteria->compare('package', $this->package, true);
        $criteria->compare('no_abo', $this->no_abo, true);
        $criteria->compare('url_site', $this->url_site, true);
        $criteria->compare('acces_elec_gratuit', $this->acces_elec_gratuit);
        $criteria->compare('acces_elec_unil', $this->acces_elec_unil);
        $criteria->compare('acces_elec_chuv', $this->acces_elec_chuv);
        $criteria->compare('embargo_mois', $this->embargo_mois);
        $criteria->compare('acces_user', $this->acces_user, true);
        $criteria->compare('acces_pwd', $this->acces_pwd, true);
        $criteria->compare('etatcoll', $this->etatcoll, true);
        $criteria->compare('etatcoll_deba', $this->etatcoll_deba);
        $criteria->compare('etatcoll_debv', $this->etatcoll_debv);
        $criteria->compare('etatcoll_debf', $this->etatcoll_debf);
        $criteria->compare('etatcoll_fina', $this->etatcoll_fina);
        $criteria->compare('etatcoll_finv', $this->etatcoll_finv);
        $criteria->compare('etatcoll_finf', $this->etatcoll_finf);
        $criteria->compare('cote', $this->cote, true);
        $criteria->compare('editeur_code', $this->editeur_code, true);
        $criteria->compare('editeur_sujet', $this->editeur_sujet, true);
        $criteria->compare('commentaire_pro', $this->commentaire_pro, true);
        $criteria->compare('commentaire_pub', $this->commentaire_pub, true);
        $criteria->compare('perunilid', $this->perunilid, true);
        $criteria->compare('plateforme', $this->plateforme);
        $criteria->compare('editeur', $this->editeur);
        $criteria->compare('histabo', $this->histabo);
        $criteria->compare('statutabo', $this->statutabo);
        $criteria->compare('localisation', $this->localisation);
        $criteria->compare('gestion', $this->gestion);
        $criteria->compare('format', $this->format);
        $criteria->compare('support', $this->support);
        $criteria->compare('licence', $this->licence);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    const imgstyle = "vertical-align: top;";

    public function htmlImgTag() {
        if (isset($this->support0) && isset($this->support0->support)) {
            //
            // Traitement des support papier
            //
            // Icône
            if ($this->support0->support == "papier") {
                $src = Yii::app()->baseUrl . "/images/paper.png";
                return CHtml::image($src, "Papier", array('title' => "Support papier", 'style' => self::imgstyle));
            }
            //
            // Traitement des support électronique
            //
            else {
                $src = Yii::app()->baseUrl . "/images/www.png";
                return CHtml::image($src, "Electronique", array('title' => "Support éléctronique", 'style' => self::imgstyle));
            }
        }
    }

    public function htmlImgTitreExclu() {
        // Icône interdit si l'abonnement est un titre exclu
        if ($this->titreexclu) {
            $src = Yii::app()->baseUrl . "/images/interdit.png";
            return CHtml::image($src, "Titre exclu", array('title' => "Titre exclu", 'style' => self::imgstyle));
        }
    }

    public function htmlShortDescription() {
        $desc = "";
        if (isset($this->licence0) && isset($this->licence0->licence))
            $desc .= "<strong>Licence</strong> : " . $this->licence0->licence . "<br /> ";
        if (isset($this->plateforme) && isset($this->plateforme->plateforme))
            $desc .= "<strong>Plateforme</strong> : " . $this->plateforme->plateforme . "<br /> ";
        if (isset($this->editeur0) && isset($this->editeur0->editeur))
            $desc .= "<strong>Editeur</strong> : " . $this->editeur0->editeur . "<br /> ";
        if (isset($this->localisation0) && isset($this->localisation0->localisation))
            $desc .= "<strong>Localisation</strong> : " . $this->localisation0->localisation . "<br /> ";
        if (isset($this->etatcoll) && $this->etatcoll != "")
            $desc .= "<strong>Etatcoll</strong> : " . $this->etatcoll . "<br /> ";
        return $desc;
    }

}