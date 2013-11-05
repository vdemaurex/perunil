<form role="form">
  

<?php 
$col = strtolower(Yii::app()->session['smalllist']);
$id = "{$col}_id";
$form=$this->beginWidget('CActiveForm', array(
	'id'=>"{$col}-form",
	'enableAjaxValidation'=>false,
)); ?>
	<div class="form-group">
            <label>Id</label>
		<?php echo $form->textField($model,$id,array('size'=>5,'maxlength'=>10, 'disabled'=>'true', 'class'=>"form-control")); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,$col); ?>
		<?php echo $form->textField($model,$col,array('size'=>60,'maxlength'=>200, 'class'=>"form-control")); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Créer' : 'Enregistrer les modifications', array('class' => "btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</form><!-- form -->