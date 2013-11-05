<?php

$table = Yii::app()->session['smalllist'];
$col = strtolower($table);
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

<?php


$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>$col.'-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                array(
                    'name'=>$col.'_id',
                    //'value'=>"'{$col}_id'",
                    'htmlOptions'=>array('width'=>'100'),
                ),
                array(
                    'name'=>"$col",
                    //'value'=>"'$col'",
                    //'htmlOptions'=>array('width'=>'40'),
                ),
		array(
                    'name'=>"slcount",
                    'header'=>'Nbr utilisations',
                    //'value'=>$col.'->totaluse',
                    //'value'=>"'totaluse'",
                    'htmlOptions'=>array('width'=>'100'),
                    'filter'=>"",
                ),
		array(
			'class'=>'CButtonColumn',
                    'htmlOptions'=>array('width'=>'75'),
		),
	),
)); 


        echo CHtml::button('Ajouter un nouvel élément', array(
            'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(array('smalllist/create')) . '"',
            'class' => "btn btn-default"));

?>
