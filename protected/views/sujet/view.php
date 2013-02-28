<?php
$this->breadcrumbs=array(
	'Sujets'=>array('index'),
	$model->sujet_id,
);

$this->menu=array(
	array('label'=>'Gestion des sujets', 'url'=>array('admin')),
	array('label'=>'Ajouter un sujet', 'url'=>array('create')),
	array('label'=>'Modifier', 'url'=>array('update', 'id'=>$model->sujet_id)),
	array('label'=>'Supprimer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->sujet_id),'confirm'=>'Etes-vous sûr de vouloir définitivement supprimer ce sujet ?')),
);
?>

<h1>Détail du sujet n°<?php echo $model->sujet_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'sujet_id',
		'code',
		'nom_en',
		'nom_fr',
		'stm',
		'shs',
	),
)); ?>
