<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sujet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nom_en'); ?>
		<?php echo $form->textField($model,'nom_en',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'nom_en'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nom_fr'); ?>
		<?php echo $form->textField($model,'nom_fr',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'nom_fr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stm'); ?>
		<?php echo $form->textField($model,'stm'); ?>
		<?php echo $form->error($model,'stm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shs'); ?>
		<?php echo $form->textField($model,'shs'); ?>
		<?php echo $form->error($model,'shs'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Enregister un nouveau sujet' : 'Enregistrer les modifications'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->