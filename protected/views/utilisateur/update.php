<?php
/* @var $this UtilisateurController */
/* @var $model Utilisateur */

$this->breadcrumbs=array(
	'Utilisateurs'=>array('index'),
	$model->utilisateur_id=>array('view','id'=>$model->utilisateur_id),
	'Update',
);
?>

<h1>Modification de l'utilisateur "<?php echo $model->pseudo; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>