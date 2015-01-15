<?php
/* @var $this UtilisateurController */
/* @var $model Utilisateur */

$this->breadcrumbs=array(
	'Utilisateurs'=>array('index'),
	$model->utilisateur_id,
);

?>

<h1>Détail de l'utilisateur "<?php echo $model->pseudo; ?>"</h1>

<div style="margin-bottom: 1em;">
    <?php
            echo CHtml::button('Retour à la liste des utlisateurs', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('utilisateur/index') . '"',
        'class' => "btn btn-primary btn-sm margin5pxleft"));
    
    echo CHtml::htmlButton("Modifier l'utilisateur", array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('utilisateur/update/'. $model->utilisateur_id) . '"',
        'class' => "btn btn-primary  btn-sm margin5pxleft"));
        ?>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'utilisateur_id',
		'nom',
		'email',
		'pseudo',
		'status',
		'creation_ip',
		'creation_on',
	),
)); ?>
