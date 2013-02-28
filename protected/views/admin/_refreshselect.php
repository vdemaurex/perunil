<?php

$this->widget('SelectWidget', array(
    'model' => $model,
    'ajax'  => true,
    'frm_classname' => get_class($model),
    'selected' => Yii::app()->db->getLastInsertID()));
?>
