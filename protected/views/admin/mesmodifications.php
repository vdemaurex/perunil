<?php
/**
 * Affichage des dernières modifications de l'utilisateur connecté.
 */
/* @var $this CsvController */

$this->breadcrumbs = array(
    'Csv',
);
?>

<h3><?php echo $searchtitle ?></h3>

<div class="btn-group">
    <?php
    echo " " . CHtml::button('24 heures', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/mesmodifications', array('days' => '1')) . '"',
        'class' => "btn btn-default"));

    echo " " . CHtml::button('2 jours', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/mesmodifications', array('days' => '2')) . '"',
        'class' => "btn btn-default"));

    echo " " . CHtml::button('3 jours', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/mesmodifications', array('days' => '3')) . '"',
        'class' => "btn btn-default"));

    echo " " . CHtml::button('5 jours', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/mesmodifications', array('days' => '5')) . '"',
        'class' => "btn btn-default"));

    echo " " . CHtml::button('7 jours', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/mesmodifications', array('days' => '7')) . '"',
        'class' => "btn btn-default"));
    
    echo " " . CHtml::button('14 jours', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/mesmodifications', array('days' => '14')) . '"',
        'class' => "btn btn-default"));
    
        echo " " . CHtml::button('30 jours', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/mesmodifications', array('days' => '30')) . '"',
        'class' => "btn btn-default"));
    ?>
</div>
<p></p>
<div class="panel panel-default">
    <div class="panel-body">
        <?php
        //$columns[] = 'user_id';
        $columns[] = 'action';
        //$columns[] = 'model';
        //$columns[] = 'model_id';
        $columns[] = array(
            'name'=>'model',
            'type'=>'raw',
            'value' => '$data->model . " " . CHtml::link($data->model_id,Yii::app()->createUrl("admin/urlDetail",array("model"=>$data->model, "id"=>$data->model_id)),array("target"=>"_blank"))',
      );
        $columns[] = 'field';
        $columns[] = 'old_value';
        $columns[] = 'new_value';
        $columns[] = 'stamp';


        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'mesmodifications-grid',
            'dataProvider' => $dataProvider,
            'formatter' => new FrFormatter(),
            'filter' => Modifications::model(),
            'columns' => $columns,
        ));
        ?>
    </div>
</div>


