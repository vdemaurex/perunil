<?php

/**
 * This is the model class for table "statutabo".
 *
 * The followings are the available columns in table 'statutabo':
 * @property integer $statutabo_id
 * @property string $statutabo
 *
 * The followings are the available model relations:
 * @property Abonnement[] $abonnements
 */
class Statutabo extends CSmalllistActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Statutabo the static model class
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
		return 'statutabo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('statutabo_id', 'required'),
			array('statutabo_id', 'numerical', 'integerOnly'=>true),
			array('statutabo', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('statutabo_id, statutabo', 'safe', 'on'=>'search'),
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
			'abonnements' => array(self::HAS_MANY, 'Abonnement', 'statutabo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'statutabo_id' => 'ID',
			'statutabo' => 'Statut de l\'abonnement',
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

		$criteria->compare('statutabo_id',$this->statutabo_id);
		$criteria->compare('statutabo',$this->statutabo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}