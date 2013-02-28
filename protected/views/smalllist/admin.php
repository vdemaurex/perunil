<?php
$col = strtolower(Yii::app()->session['smalllist']);
$this->breadcrumbs=array(
	Yii::app()->session['smalllist']=>array('index'),
	'Gestion',
);

$this->menu=array(
	array('label'=>'Ajouter un nouvel élément', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('$col-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?=Yii::app()->session['smalllist']?></h1>


Vous pouvez utiliser un opérateur de comparaison (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) au début de votre recherche pour spécifier la façon dont la comparaison doit être faite.


<?php /*echo CHtml::link('Recherche avancée','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
*/?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>$col.'-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		$col.'_id',
		$col,
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
