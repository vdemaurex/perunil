<?php

$column = strtolower(get_class($model));



$form = $this->beginWidget('CActiveForm', array(
    'id' => $id . "Form",
    'enableAjaxValidation' => false,
         ));


echo $form->labelEx($model, $column);
echo $form->textField($model, $column, array('style' => "width:100px;"));
echo $form->error($model, $column);



$this->endWidget('CActiveForm');
