<?php
$this->breadcrumbs=array(
	Yii::app()->session['smalllist']=>array('index'),
	'Ajouter un élément',
);

$this->menu=array(
	array('label'=>'Lister ' . Yii::app()->session['smalllist'], 'url'=>array('index')),
	array('label'=>'Gérer ' . Yii::app()->session['smalllist'], 'url'=>array('admin')),
);
?>

<h1>Ajouter un élément à <?= Yii::app()->session['smalllist'];?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>