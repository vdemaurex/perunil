<?php
$this->pageTitle=Yii::app()->name . ' - Signaler une erreur';
$this->breadcrumbs=array(
	'Signaler une erreur',
);
?>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>
<?php
        echo CHtml::htmlButton('<span class="glyphicon glyphicon-backward"> </span> Retour au résultat de la recherche', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl("site/returnToSearchResults") . '"',
        'class' => "btn btn-default  btn-sm"));


    else: ?>

<h2>
Signaler une erreur ou soumettre une suggestion
</h2>
<p>
Le contenu des pages du site PérUnil et ebooksUnil est sous la responsabilité de
la BCU Lausanne et de la Bibliothèque Universitaire de Médecine. L'équipe qui gère
cette base de données tente par tous les moyens de diffuser une information valide, 
de garder celle-ci à jour et de s'assurer de la qualité du contenu et du bon 
fonctionnement de l'ensemble du site.
</p>
<p>
Il peut malheureusement arriver qu'une erreur, une information erronée ou 
un problème de programmation se glisse dans une page. Nous vous invitons donc 
à nous signaler tout problème à l'aide du formulaire ci-dessous.
</p>
    
<p>
Si vos questions concernent plutôt le site de l'Université de Lausanne, 
ou pour toute autre question n'ayant pas trait à pérUnil spécifiquement, 
veuillez contacter le <a href="http://www.unil.ch/ci/fr/home/menuguid/help-desk.html">Help Desk de l'Unil</a>. 
</p>

<div class="form" style="width:80%; margin: auto;">

<?php $form=$this->beginWidget
        (
            'CActiveForm', 
            array(
                'id'=>'contact-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array
                    (
                        'validateOnSubmit'=>true,
                    ),
            )
        ); 
?>

	<p class="note">Les champs marqué d'un asterisque (<span class="required">*</span>) doivent obligatoirement être remplis.</p>


	<?php echo  $form->errorSummary($model,NULL, NULL, array('class'=> "alert alert-danger"));?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('class' => "form-control")); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('class' => "form-control")); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'errortype'); ?>
		<?php echo $form->dropDownList($model,'errortype', $model->errorlist ,array('class' => "form-control"));  ?>
		<?php echo $form->error($model,'errortype'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'lasturl'); ?>
		<?php echo $form->textField($model,'lasturl',array('class' => "form-control")); ?>
		<?php echo $form->error($model,'lasturl'); ?>
	</div>
        <div class="form-group">
		<?php echo $form->labelEx($model,'missinglink'); ?>
		<?php echo $form->textField($model,'missinglink',array('class' => "form-control")); ?>
		<?php echo $form->error($model,'missinglink'); ?>
	</div>
        
        
	<div class="form-group">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('class' => "form-control")); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode', array('class' => "form-control")); ?>
		</div>
		<div class="hint">Veuillez entrer les lettres comme elles apparaissent dans l'image ci-dessus.
		<br/>Les lettres ne sont pas sensibles à la casse.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton('Envoyer'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>