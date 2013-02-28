<div class="view">
<?php $col = strtolower(Yii::app()->session['smalllist']); 
$col_id = $col . '_id';
?>
	<b><?php echo CHtml::encode($data->getAttributeLabel($col.'_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->$col_id), array('view', 'id'=>$data->$col_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel($col)); ?>:</b>
	<?php echo CHtml::encode($data->$col); ?>
	<br />


</div>