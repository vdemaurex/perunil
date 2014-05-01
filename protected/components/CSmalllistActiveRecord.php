<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSmalllistActiveRecord
 *
 * @author vdemaure
 */
class CSmalllistActiveRecord extends CActiveRecord {

    protected $slcount;
    private $slcount_table_id;
    private $slcount_sql;

    private function getCount_sql() {
        $abo_table = Abonnement::model()->tableName();
        $tbl = $this->tableName();

        $this->slcount_table_id = $this->getAttribute($tbl . "_id");

        $this->slcount_sql = "SELECT COUNT(*) " .
                "FROM $abo_table ";
        if ($this->slcount_table_id) {
            $this->slcount_sql .=
                    "WHERE $tbl = $this->slcount_table_id " .
                    "GROUP BY $tbl";
        } else {
            $this->slcount_sql .= "WHERE $tbl = " . $tbl . "_id ";
        }
    }

    protected function getCount_sql_subquery() {
        if (!isset($this->slcount_sql))
            $this->getCount_sql();
        return "($this->slcount_sql)";
    }

    protected function getCount() {
        if (!$this->slcount_table_id) {
            $this->slcount = 0;
        }
        if (!isset($this->slcount)) {

            if (!isset($this->slcount_sql))
                $this->getCount_sql();

            $command = Yii::app()->db->createCommand($this->slcount_sql);

            $this->slcount = $command->queryScalar();
            if ($this->slcount === FALSE) {
                $this->slcount = 0;
            }
        }
        return $this->slcount;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        $tbl = $this->tableName();
        $tbl_id = $tbl . "_id";
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array($tbl, 'required'),
            array($tbl, 'length', 'max' => 200),
            array('slcount', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array("$tbl_id, $tbl, slcount", 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        $tbl = $this->tableName();

        return array(
            'abonnements' => array(self::HAS_MANY, 'Abonnement', $tbl),
            // Relation de calcul
            'slcount' => array(self::STAT, 'Abonnement', $tbl),
        );
    }

    public function search() {

        $tbl = $this->tableName();
        $tbl_id = $tbl . "_id";

        $criteria = new CDbCriteria;

        $countsql = $this->getCount_sql_subquery();

        // select
        $criteria->select = array(
            '*',
            $countsql . " as slcount",
        );

        // where
        $criteria->compare($tbl_id, $this->$tbl_id);
        $criteria->compare($tbl, $this->$tbl, true);
        $criteria->compare($countsql, $this->slcount);



        $sort = new CSort;
        $sort->defaultOrder = $tbl . ' ASC';
        $sort->attributes = array(
            $tbl => $tbl,
            $tbl_id => $tbl_id,
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
    }

    /**
     * Après une modification des tables de constantes, on vide le cache.
     */
    public function afterSave() {
        parent::afterSave();

        Yii::app()->cache->flush();
    }

}


