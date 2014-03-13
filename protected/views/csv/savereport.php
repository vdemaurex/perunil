<?php
/**
 * L'importation du fichier à réussi. Affichage des résultats.
 */
/* @var $this CsvController */
/* @var $this->parser CSVParser */
/* @var $row CSVRow */

$this->breadcrumbs=array(
	'Csv'=>array('/csv'),
	'Importation du fichier CSV',
);
?>
<h1>Importation d'un fichier CSV - Etape 5</h1>

<h2>Résultat de l'importation</h2>
<table class="table">
    <tr>
        <th>#</th>
        <th>Journal</th>
        <th>Action</th>
        <th>Abonnement ID</th>
    </tr>
<?php
foreach ($this->parser->getSavedRows() as $no => $row) {
    ?>
<tr>
    <td>
        <?php echo $no ?>
    </td>
    <td>
        <?php echo CHtml::link($row->getJrnTitle(), Yii::app()->createUrl('/site/detail/' . $row->getPerunilid()), array('target' => '_blank', 'title' => "Ouvrir les détails dans une nouvelle fenêtre."));?>
    </td>
    <td>
        <?php
        if ($row->getState() == CSVRow::CREATE_SAVED){
            echo "Nouvel Abonnement";
        }
        else {
            echo "Modification";
        }
        ?>
    </td>
    <td>
        <?php
            echo CHtml::link($row->getAboid(), 
                    Yii::app()->createUrl('admin/aboedit/perunilid/' . $row->getPerunilid() . '/aboid/' . $row->getAboid()), 
                    array('target' => '_blank', 'title' => "Ouvrir les détails dans une nouvelle fenêtre."));
        ?>
    </td>
</tr>
<?php
}
?>
</table>