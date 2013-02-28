<div class="wide form">

<?php 
$col = strtolower(Yii::app()->session['smalllist']);
$form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo CHtml::label("ID", "{$col}_id");?>
		<?php echo $form->textField($model,"{$col}_id"); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,$col); ?>
		<?php echo $form->textField($model,$col,array('size'=>50,'maxlength'=>200)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Rechercher'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->