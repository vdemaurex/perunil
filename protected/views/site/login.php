<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>



<div class="panel panel-default" style="width: 705px; margin:auto;">
  <div class="panel-heading">
      <h3>Accès à l'administration de PérUnil</h3>
  </div>
    <div class="panel-body">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Les champs marqués d'un astérisque <span class="required">*</span> sont obligatoires.</p>
        
	<p>
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('class'=>"form-control", 'style'=>"width : 80%;")); ?>
		<?php echo $form->error($model,'username'); ?>
	</p>

	<p>
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('class'=>"form-control", 'style'=>"width : 80%;")); ?>
		<?php echo $form->error($model,'password'); ?>
	</p>

	<p>
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</p>

	<p>
		<?php echo CHtml::submitButton('Connexion',array('class'=>"btn btn-primary")); ?>
	</p>

<?php $this->endWidget(); ?>
        
    </div>
</div>