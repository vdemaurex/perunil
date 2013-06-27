<?php
$this->breadcrumbs = array(
    'Admin' => array('/admin'),
    'Csvimport',
);


/* Affichage des résulats
 *  $modif[] = array(
  'table'  => $table,
  'id'     => $row[$table][$tableid],
  'champs' => $column_name,
  'ancienne_valeur' => $obj->$column_name,
  'nouvelle_valeur' => $row[$table][$column_name],
  );
 * 
 *  $ajout[] = array(
  'table'     => $table,
  'attributs' => $row[$table],
  );
 * 
 */
   echo "<h1>Importation d'un fichier CSV</h1>\n";



if (isset($showresults) && $showresults) {
    Yii::app()->user->setFlash('success', "Lecture du fichier $filename réussie.");

    if (isset($modif) && count($modif) > 0) {
        echo "<h2>Liste des modifications</h2>";
        $modifdataProvider = new CArrayDataProvider(
                        $modif, array(
                    'keys' => array('table', 'id', 'champs', 'ancienne_valeur', 'nouvelle_valeur'),
                    'sort' => array(
                        'attributes' => array('table', 'id', 'champs', 'ancienne_valeur', 'nouvelle_valeur'),
                        'defaultOrder' => array('table' => false),
                    ),
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                ));


        $this->widget('zii.widgets.grid.CGridView', array('dataProvider' => $modifdataProvider,));
    } else {
        echo "<p>Aucune modification à afficher</p>";
    }


    if (isset($ajout) && count($ajout) > 0) {
        echo "<h2>Liste des nouvelle entrées</h2>";
        $flatAjout = array();
        foreach ($ajout as $key => $row) {
            $flatAjout[] = array(
                'table' => $row['table'],
                'attributs' => implode(",", $row['attributs']),
            );
        }
        $ajoutdataProvider = new CArrayDataProvider(
                        $flatAjout, array(
                    'keys' => array('table'),
                    'sort' => array(
                        'attributes' => array('table'),
                        'defaultOrder' => array('table' => false),
                    ),
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                ));


        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $ajoutdataProvider,
                )
        );
    } else {
        echo "<p>Aucun ajout à afficher</p>";
    }


    echo CHtml::button('Appliquer les modifications', array(
        'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(array('admin/csvimportprocess')) . '"'));


    echo CHtml::button('Annuler les modifications', array(
        'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(array('admin/csvimportcancel')) . '"'));
} 


else { // Pas de résultats à afficher
    ?>
    <p>Sélectionnez le fichier à importer puis cliquez sur "Téléverser le fichier".</p>
    <div class="form">
    <?php echo CHtml::beginForm("", 'post', array('enctype' => 'multipart/form-data')); ?>

    <?php echo CHtml::errorSummary($model); ?>

        <div class="row">
        <?php echo CHtml::activeLabel($model, 'fichier'); ?>
        <?php echo CHtml::activeFileField($model, 'fichier'); ?>
        </div>

        <div class="row submit">
            <?php echo CHtml::submitButton("Téléverser le fichier"); ?>
        </div>

            <?php echo CHtml::endForm(); ?>
    </div>


        <?php
    }
    ?>

