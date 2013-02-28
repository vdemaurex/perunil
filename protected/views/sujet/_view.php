<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('sujet_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->sujet_id), array('view', 'id'=>$data->sujet_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nom_en')); ?>:</b>
	<?php echo CHtml::encode($data->nom_en); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nom_fr')); ?>:</b>
	<?php echo CHtml::encode($data->nom_fr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stm')); ?>:</b>
	<?php echo CHtml::encode($data->stm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shs')); ?>:</b>
	<?php echo CHtml::encode($data->shs); ?>
	<br />


</div>