<?php
/* @var $this UtilisateurController */
/* @var $model Utilisateur */

$this->breadcrumbs=array(
	'Utilisateurs'=>array('index'),
	'Gestion des utilisateurs',
);

$this->menu=array(
	array('label'=>'List Utilisateur', 'url'=>array('index')),
	array('label'=>'Create Utilisateur', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#utilisateur-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Gestion des utilisateurs</h1>

<div style="margin-bottom: 1em;">
    <?php
    echo CHtml::htmlButton('Créer un nouvel utilisateur', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('utilisateur/create') . '"',
        'class' => "btn btn-primary  btn-sm"));
    
        ?>
</div>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'utilisateur-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'utilisateur_id',
		'nom',
		'email',
		'pseudo',
		//'mot_de_passe',
		'status',	
		//'creation_ip',
		'creation_on',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
