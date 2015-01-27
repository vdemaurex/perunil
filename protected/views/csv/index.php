<?php
/**
 * Affichage du formulaire d'imporation du fichier CSV.
 */
/* @var $this CsvController */

$this->breadcrumbs = array(
    'Csv',
);
?>
<h1>Importation d'un fichier CSV - Etape 1</h1>

<div class="panel panel-default" style="width: 705px; margin:auto;">
    <div class="panel-heading"><h4>Sélectionnez le fichier à importer</h4></div>
    <div class="panel-body">
        <div class="form">
            <?php echo CHtml::beginForm($this->createUrl("csv/import"), 'post', array('enctype' => 'multipart/form-data')); ?>

            <?php echo CHtml::errorSummary($model); ?>

            <div class="form-group">
                <?php echo CHtml::activeLabel($model, 'fichier'); ?>
                <?php echo CHtml::activeFileField($model, 'fichier'); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::activeLabel($model, 'delimiter'); ?>
                <?php
                echo CHtml::activeDropDownList(
                        $model, 'delimiter', array(
                    'pointvirgule' => "; (Point-virgule)",
                    'virgule' => ", (Virgule)",
                    'tabulation' => " (Tabulation)",                    
                        )
                );
                ?>
            </div>
            <br/>
            <div class="form-group">
                <?php echo CHtml::submitButton("Téléverser le fichier", array('class' => "btn btn-primary")); ?>
            </div>

            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
    <div class="panel-footer">
        <h4>Ajouter ou modifier des données PerUNIL avec un fichier CSV</h4>
        <ol>
            <li>Effectuer une recherche puis exportez le résultat de cette recherche au format CSV.</li>
            <li>Ouvrez le fichier dans Excel.
            <li>Importez le fichier modifié à l'aide du formulaire ci-dessus.</li>
        </ol>
        
        Consultez <a href="https://github.com/vdemaure/perunil/wiki/Exportation-et-importation-CSV" target="_blank">le mode d'emploi complet</a> pour l'imporation csv pour plus de détails.
    </div>
</div>
