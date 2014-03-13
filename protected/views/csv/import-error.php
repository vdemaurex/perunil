<?php
/**
 * L'importation du fichier CSV à échoué, affichage des erreurs
 */
/* @var $this CsvController */
/* @var $this->parser CSVParser */

$this->breadcrumbs=array(
	'Csv'=>array('/csv'),
	"Erreur(s) lors de l'imporation CSV",
);
?>
<h1>Importation d'un fichier CSV - Etape 2</h1>

<h2>Résultat de l'importation</h2>

<div class="alert alert-danger">
    <strong>L'importation du fichier CSV à échoué.</strong>
    <ul>
    <?php    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    ?>
    </ul>
    
</div>
<span class="glyphicon glyphicon-arrow-right"></span> <?php echo CHtml::link("Retour au formulaire d'importation",array('csv/index')); ?>