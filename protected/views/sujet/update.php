<?php
$this->breadcrumbs=array(
	'Sujets'=>array('index'),
	$model->sujet_id=>array('view','id'=>$model->sujet_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Sujet', 'url'=>array('index')),
	array('label'=>'Create Sujet', 'url'=>array('create')),
	array('label'=>'View Sujet', 'url'=>array('view', 'id'=>$model->sujet_id)),
	array('label'=>'Manage Sujet', 'url'=>array('admin')),
);
?>

<h1>Modification du sujet <?php echo $model->sujet_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>