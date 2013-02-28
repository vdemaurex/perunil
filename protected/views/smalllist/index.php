<?php
$this->breadcrumbs=array(
	Yii::app()->session['smalllist'],
);

$this->menu=array(
	array('label'=>'Ajouter un élément', 'url'=>array('create')),
	array('label'=>'Gérer ' . Yii::app()->session['smalllist'], 'url'=>array('admin')),
);
?>

<h1><?=Yii::app()->session['smalllist']?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
