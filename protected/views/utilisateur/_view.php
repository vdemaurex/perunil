<?php
/* @var $this UtilisateurController */
/* @var $data Utilisateur */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('utilisateur_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->utilisateur_id), array('view', 'id'=>$data->utilisateur_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nom')); ?>:</b>
	<?php echo CHtml::encode($data->nom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pseudo')); ?>:</b>
	<?php echo CHtml::encode($data->pseudo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mot_de_passe')); ?>:</b>
	<?php echo CHtml::encode($data->mot_de_passe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_ip')); ?>:</b>
	<?php echo CHtml::encode($data->creation_ip); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_on')); ?>:</b>
	<?php echo CHtml::encode($data->creation_on); ?>
	<br />

	*/ ?>

</div>