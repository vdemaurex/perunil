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

    protected $totaluse;

    protected function getTotaluse() {
        $tbl = $this->tableName();
        $id = $this->getAttribute($tbl."_id");
        if (!$id) {
            $this->totaluse = 0;
        }
        if (!isset($this->totaluse)) {           
            $sql = "SELECT COUNT(*) " .
                    "FROM abonnement " .
                    "WHERE $tbl = $id " .
                    "GROUP BY $tbl";
            $command = Yii::app()->db->createCommand($sql);
            $this->totaluse = $command->queryScalar();
            if ($this->totaluse === FALSE){
                $this->totaluse = 0;
            }
        }
        return $this->totaluse;
    }

}

?>
