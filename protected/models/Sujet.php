<?php

/**
 * This is the model class for table "sujet".
 *
 * The followings are the available columns in table 'sujet':
 * @property integer $sujet_id
 * @property string $code
 * @property string $nom_en
 * @property string $nom_fr
 * @property integer $stm
 * @property integer $shs
 *
 * The followings are the available model relations:
 * @property Journal[] $journals
 */
class Sujet extends CActiveRecord
{
    
            protected $totaluse;

            protected function getTotaluse() {
                if (!$this->sujet_id) {
                    $this->totaluse = 0;
                }
                if (!isset($this->totaluse)) {           
                    $sql = "SELECT COUNT(*) " .
                            "FROM journal_sujet " .
                            "WHERE sujet_id = $this->sujet_id";
                    $command = Yii::app()->db->createCommand($sql);
                    $this->totaluse = $command->queryScalar();
                    if ($this->totaluse === FALSE){
                        $this->totaluse = 0;
                    }
                }
                return $this->totaluse;
            }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sujet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sujet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, nom_fr', 'required'),
			array('stm, shs', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>4),
			array('nom_en, nom_fr', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('sujet_id, code, nom_en, nom_fr, stm, shs', 'safe', 'on'=>'search'),
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
			'journals' => array(self::MANY_MANY, 'Journal', 'journal_sujet(sujet_id, perunilid)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sujet_id' => 'Sujet',
			'code' => 'Code',
			'nom_en' => 'Nom En',
			'nom_fr' => 'Nom Fr',
			'stm' => 'Stm',
			'shs' => 'Shs',
                        'totaluse' => "Nombre d'utilisation",
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('sujet_id',$this->sujet_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('nom_en',$this->nom_en,true);
		$criteria->compare('nom_fr',$this->nom_fr,true);
		$criteria->compare('stm',$this->stm);
		$criteria->compare('shs',$this->shs);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}