<?php
class FrFormatter extends CFormatter {
    
    public $booleanFormat=array('Non','Oui');
    
    public function formatBoolean($value){
            return $value ? Yii::t('app', $this->booleanFormat[1]) : Yii::t('app',$this->booleanFormat[0]);
    }
 
}