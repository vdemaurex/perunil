<?php
/* @var $this UtilisateurController */
/* @var $model Utilisateur */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'utilisateur-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="panel panel-default" style="width: 705px; margin:auto;">
    <div class="panel-heading">Ajouter un nouvel utilisateur</div>
    <div class="panel-body">

        <table class="advsearch">
            <tr>
                <td ><?php echo $form->labelEx($model,'nom'); ?></td>
                <td ><?php echo $form->textField($model,'nom',array('size'=>60,'maxlength'=>255)); ?><?php echo "<br>". $form->error($model,'nom'); ?></td>
            </tr>
            <tr>
                <td ><?php echo $form->labelEx($model,'email'); ?></td>
                <td ><?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?><?php echo "<br>".$form->error($model,'email'); ?></td>
            </tr>
           <tr>
                <td ><?php echo $form->labelEx($model,'pseudo'); ?></td>
                <td ><?php echo $form->textField($model,'pseudo',array('size'=>60,'maxlength'=>255)); ?><?php echo "<br>".$form->error($model,'pseudo'); ?></td>
            </tr>
            <? if (!$model->isNewRecord) : ?>
            <tr>
                <td ><?php echo CHtml::label("Modififier le mot de passe", 'Utilisateur[modifmdp]'); ?></td>
                <td ><?php echo CHtml::checkBox('Utilisateur[modifmdp]', false)?></td>
            </tr>
            <?             endif;?>
            <tr>
                <td ><?php echo CHtml::label("Nouveau mot de passe", 'Utilisateur[mot_de_passe]'); ?></td>
                <td ><?php echo $form->passwordField($model,'mot_de_passe','',array('size'=>20,'maxlength'=>16)); ?><?php echo "<br>". $form->error($model,'mot_de_passe'); ?></td>
            </tr>
            <tr>
                <td ><?php echo CHtml::label("Confirmer le mot de passe", 'Utilisateur[repeat_password]'); ?></td>
                <td ><?php echo $form->passwordField($model, 'repeat_password','',array('size'=>20,'maxlength'=>16)); ?><?php echo "<br>". $form->error($model,'repeat_password'); ?></td>
            </tr>
             <tr>
                <td ><?php echo $form->labelEx($model,'status'); ?></td>
                <td ><?php echo CHtml::dropDownList('Utilisateur[status]', $model->status, 
              array('Administration'=>'Administration','Modification-suppression'=>'Modification-suppression','Modification'=>'Modification','Consultation'=>'Consultation'));?>
		<?php echo "<br>". $form->error($model,'status'); ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="panel panel-default" style="width: 705px; margin:auto;">
    <div class="panel-body">
        <div style=" margin-left: 220px;">
            <? echo CHtml::submitButton($model->isNewRecord ? 'Créer' : 'Enregistrer', array('class' => "btn btn-primary")); ?> &nbsp;
            <?  
            echo CHtml::button('Annuler', array(
                            'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('utilisateur/index') . '"',
                            'class' => "btn btn-default")) ?>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
