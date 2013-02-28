<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'sujet_id'); ?>
		<?php echo $form->textField($model,'sujet_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nom_en'); ?>
		<?php echo $form->textField($model,'nom_en',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nom_fr'); ?>
		<?php echo $form->textField($model,'nom_fr',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stm'); ?>
		<?php echo $form->textField($model,'stm'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shs'); ?>
		<?php echo $form->textField($model,'shs'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->