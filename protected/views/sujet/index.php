<?php
$this->breadcrumbs=array(
	'Sujets',
);

$this->menu=array(
	array('label'=>'Ajouter un sujet', 'url'=>array('create')),
	array('label'=>'Gérer les sujets', 'url'=>array('admin')),
);
?>

<h1>Sujets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
