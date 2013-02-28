<?php
$this->breadcrumbs=array(
	'Sujets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Liste des sujets', 'url'=>array('index')),
	array('label'=>'Gérer les sujets', 'url'=>array('admin')),
);
?>

<h1>Create Sujet</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>