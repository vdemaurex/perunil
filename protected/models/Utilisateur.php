<?php

/**
 * This is the model class for table "utilisateur".
 *
 * The followings are the available columns in table 'utilisateur':
 * @property integer $utilisateur_id
 * @property string $nom
 * @property string $email
 * @property string $pseudo
 * @property string $mot_de_passe
 * @property string $status
 * @property string $creation_ip
 * @property string $creation_on
 *
 * The followings are the available model relations:
 * @property Modification[] $modifications
 */
class Utilisateur extends CActiveRecord
{

    public $layout = '//layouts/column1';
    // Conserverve la confirmation du mot de passe
    public $repeat_password;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Utilisateur the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'utilisateur';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nom, email, pseudo, mot_de_passe, status, creation_on', 'required'),
            array('nom, email', 'length', 'max' => 255),
            array('email', 'email'),
            array('pseudo', 'length', 'max' => 16),
            array('status', 'length', 'max' => 24),
            array('mot_de_passe, repeat_password', 'required', 'on' => 'insert'),
            array('mot_de_passe, repeat_password', 'length', 'min' => 6, 'max' => 40),
            array('mot_de_passe', 'compare', 'compareAttribute' => 'repeat_password'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('utilisateur_id, nom, email, pseudo, mot_de_passe, status, creation_ip, creation_on', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'modifications' => array(self::HAS_MANY, 'Modification', 'utilisateur_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'utilisateur_id' => 'Utilisateur',
            'nom' => 'Nom',
            'email' => 'Email',
            'pseudo' => 'Pseudo',
            'mot_de_passe' => 'Mot De Passe',
            'status' => 'Status',
            'creation_ip' => 'Creation Ip',
            'creation_on' => 'Creation On',
        );
    }

    public function beforeSave()
    {
        if (!$this->isValidMd5($this->mot_de_passe)) {
            $this->mot_de_passe = md5($this->mot_de_passe);
        }
 
        return parent::beforeSave();
    }
    
    
    public function checkPwd($password)
    {
        return $this->mot_de_passe == md5($password);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('utilisateur_id', $this->utilisateur_id);
        $criteria->compare('nom', $this->nom, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('pseudo', $this->pseudo, true);
        $criteria->compare('mot_de_passe', $this->mot_de_passe, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('creation_ip', $this->creation_ip, true);
        $criteria->compare('creation_on', $this->creation_on, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    private function isValidMd5($md5 = '')
    {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }

}
