<div class="form">

<?php 
$col = strtolower(Yii::app()->session['smalllist']);
$form=$this->beginWidget('CActiveForm', array(
	'id'=>"{$col}-form",
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,$col); ?>
		<?php echo $form->textField($model,$col,array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,$col); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Ajouter' : 'Modifier'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->