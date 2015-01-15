<?php
/* @var $this UtilisateurController */
/* @var $model Utilisateur */

$this->breadcrumbs=array(
	'Utilisateurs'=>array('index'),
	'Nouvel utilisateur',
);
?>

<h1>Ajouter un nouvel utilisateur</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>