<?php
/**
 * Affichage du récapitulatif
 */
/* @var $this CsvController */
/* @var $this->parser CSVParser */
/* @var $row CSVRow */

$this->breadcrumbs = array(
    'Csv' => array('/csv'),
    'Récapitulatif',
);
?>

<ul>
    <?php
    foreach ($proceededRows as $no => $row) {
        ?>
        <li><strong><?php echo $no; ?> : <?php echo $row->getState(); ?> abonnement   

                <?php
                if ($row->getPerunilid()) {
                    echo " pour le journal ";
                    echo CHtml::link($row->getPerunilid(), Yii::app()->createUrl('/site/detail/' . $row->getPerunilid()), array('target' => '_blank', 'title' => "Ouvrir les détails dans une nouvelle fenêtre."));
                }
                ?>

            </strong>
            <ul>
                <?php
                foreach ($row->getChangeArray() as $column => $values) {
                    if ($row->getState() == CSVRow::MODIF) {
                        if ($values[0] == "") {
                            $values[0] = '""';
                        }
                        if ($values[1] == "") {
                            $values[1] = '""';
                        }
                        echo "<li><em>$column : </em> <span style='color: orange;'> $values[0]</span><strong> =></strong> <span style='color: blue;'>$values[1]</span>";
                    } else {
                        if (!empty($values[1])) {
                            echo "<li><em>$column : </em> <span style='color: green;'>  $values[1]</span>";
                        }
                    }
                }
                    ?>
                </ul>
            </li>
            <?php
    }
    ?>
</ul>

<?php 
echo CHtml::button('Executer cette importation', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('csv/execImport') . '"',
        'class' => "btn btn-primary"));
echo " ";
echo CHtml::button('Interrompre le processus', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('csv/index') . '"',
        'class' => "btn btn-warning"));
