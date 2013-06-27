<?php

/**
 * This is the model class for table "localisation".
 *
 * The followings are the available columns in table 'localisation':
 * @property integer $localisation_id
 * @property string $localisation
 *
 * The followings are the available model relations:
 * @property Abonnement[] $abonnements
 */
class Localisation extends CSmalllistActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Localisation the static model class
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
		return 'localisation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('localisation', 'required'),
			array('localisation', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('localisation_id, localisation', 'safe', 'on'=>'search'),
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
			'abonnements' => array(self::HAS_MANY, 'Abonnement', 'localisation'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'localisation_id' => 'Localisation',
			'localisation' => 'Localisation',
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

		$criteria->compare('localisation_id',$this->localisation_id);
		$criteria->compare('localisation',$this->localisation,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}