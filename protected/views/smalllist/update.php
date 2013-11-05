<?php
$col = strtolower(Yii::app()->session['smalllist']); 
$col_id = $col . '_id';

$this->breadcrumbs=array(
	Yii::app()->session['smalllist'] =>array('index'),
	$model->$col_id=>array('view','id'=>$model->$col_id),
	'Update',
);

$this->menu=array(
	array('label'=>'Ajouter un élément', 'url'=>array('create')),
	array('label'=>'Détail de l\'élément', 'url'=>array('view', 'id'=>$model->$col_id)),
	array('label'=>'Gérer ' . Yii::app()->session['smalllist'], 'url'=>array('admin')),
);
?>


<div class="panel panel-default" style="width: 95%; margin-right:auto; margin-left:auto; margin-top: 10px;">
    <div class="panel-heading">
        <strong>Modifier</strong>
    </div>
    <div  style="padding: 20px;">
    <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>