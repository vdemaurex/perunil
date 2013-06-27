<?php

/**
 * This is the model class for table "histabo".
 *
 * The followings are the available columns in table 'histabo':
 * @property integer $histabo_id
 * @property string $histabo
 *
 * The followings are the available model relations:
 * @property Abonnement[] $abonnements
 */
class Histabo extends CSmalllistActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Histabo the static model class
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
		return 'histabo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('histabo', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('histabo_id, histabo', 'safe', 'on'=>'search'),
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
			'abonnements' => array(self::HAS_MANY, 'Abonnement', 'histabo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'histabo_id' => 'ID',
			'histabo' => 'Historique de l\'abonnement',
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

		$criteria->compare('histabo_id',$this->histabo_id);
		$criteria->compare('histabo',$this->histabo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}