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

<h1>Modifier <?php echo Yii::app()->session['smalllist'] . " " . $model->$col_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>