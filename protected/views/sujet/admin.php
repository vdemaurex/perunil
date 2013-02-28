<?php
$this->breadcrumbs=array(
	'Sujets'=>array('index'),
	'Gestion',
);

$this->menu=array(
	//array('label'=>'Liste des sujets', 'url'=>array('index')),
	array('label'=>'Ajouter un sujet', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sujet-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Edition des sujets</h1>

<?php echo CHtml::link('Recherche avancée des sujets','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sujet-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'sujet_id',
		'code',
		'nom_en',
		'nom_fr',
		'stm',
		'shs',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
