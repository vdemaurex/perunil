<?php

/**
 * This is the model class for table "plateforme".
 *
 * The followings are the available columns in table 'plateforme':
 * @property integer $plateforme_id
 * @property string $plateforme
 *
 * The followings are the available model relations:
 * @property Abonnement[] $abonnements
 */
class Plateforme extends CSmalllistActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Plateforme the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'plateforme';
    }

    /**
     * @return array validation rules for model attributes.
     */
    /*public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('plateforme', 'required'),
            array('plateforme', 'length', 'max' => 200),
            array('slcount', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('plateforme_id, plateforme, slcount', 'safe', 'on' => 'search'),
        );
    }*/

    /**
     * @return array relational rules.
     */
    /*public function relations() {
        return array(
            'abonnements' => array(self::HAS_MANY, 'Abonnement', 'plateforme'),
            // Relation de calcul
            'slcount' => array(self::STAT, 'Abonnement', 'plateforme'),
        );
    }*/

    /**
     * @return array customized attribute labels (name=>label)
     */
    /*public function attributeLabels() {
        return array(
            'plateforme_id' => 'Plateforme Id',
            'plateforme' => 'Plateforme',
        );
    }*/

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    /*public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $countsql = $this->getCount_sql_subquery();

        // select
        $criteria->select = array(
            '*',
            $countsql . " as slcount",
        );

        // where
        $criteria->compare('plateforme_id', $this->plateforme_id);
        $criteria->compare('plateforme', $this->plateforme, true);
        $criteria->compare($countsql, $this->slcount);

        
        
        $sort = new CSort;
        $sort->defaultOrder = 'plateforme ASC';
        $sort->attributes = array(
            'plateforme' => 'plateforme',
            'plateforme_id' => 'plateforme_id',
            'slcount' => array(
               'asc' => 'slcount ASC',
               'desc' => 'slcount DESC',
            )
        );

        $sort->applyOrder($criteria);

        
        
        
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));






        //return new CActiveDataProvider($this, array(
        //            'criteria' => $criteria,
        //        ));
    }*/

}