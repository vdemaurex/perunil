<?php

/**
 * This is the model class for table "format".
 *
 * The followings are the available columns in table 'format':
 * @property integer $format_id
 * @property string $format
 *
 * The followings are the available model relations:
 * @property Abonnement[] $abonnements
 */
class Format extends CSmalllistActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Format the static model class
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
		return 'format';
	}

	/**
	 * @return array validation rules for model attributes.
	 *
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('format', 'required'),
			array('format', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('format_id, format', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 *
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'abonnements' => array(self::HAS_MANY, 'Abonnement', 'format'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 *
	public function attributeLabels()
	{
		return array(
			'format_id' => 'Format',
			'format' => 'Format',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 *
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('format_id',$this->format_id);
		$criteria->compare('format',$this->format,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}*/
}