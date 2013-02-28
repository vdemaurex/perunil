<?php
$col = strtolower(Yii::app()->session['smalllist']); 
$col_id = $col . '_id';

$this->breadcrumbs=array(
	Yii::app()->session['smalllist']=>array('index'),
	$model->$col_id,
);

$this->menu=array(
	array('label'=>'Ajouter un élément', 'url'=>array('create')),
	array('label'=>'Mettre à jour l\'élément', 'url'=>array('update', 'id'=>$model->$col_id)),
	array('label'=>'Supprimer l\'élément', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->$col_id),'confirm'=>'Êtes-vous sûr de vouloir supprimer cet élément.')),
	array('label'=>'Retour à la liste', 'url'=>array('admin')),
);
?>

<h1>Détail de <?php echo 'Gérer ' . Yii::app()->session['smalllist'] . " #". $model->$col_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		$col_id,
		$col,
	),
)); ?>
