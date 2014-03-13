<?php
/**
 * L'importation du fichier à réussi. Affichage des résultats.
 */
/* @var $this CsvController */
/* @var $this->parser CSVParser */

$this->breadcrumbs=array(
	'Csv'=>array('/csv'),
	'Importation du fichier CSV',
);
?>
<h1>Importation d'un fichier CSV - Etape 2</h1>

<h2>Résultat de la lecture du fichier</h2>

<div class="alert alert-success">
    <strong>L'importation du fichier CSV a réussi. </strong>
<ul>
    <li>Nombre de ligne(s) dans le fichier : <?php echo $this->parser->getNbrTotalRows(); ?></li>
    <li>Nombre de ligne(s) valide(s) : <?php echo $this->parser->getNbrValidRows(); ?>, dont : </li>
    <ul>
        <li><?php echo $this->parser->getNbrModifRows(); ?> abonnement(s) existant(s) à mettre à jour.</li>
        <li><?php echo $this->parser->getNbrPerunilidRows(); ?> abonnement(s) à créer, dont le journal est déjà identifié par le perunilid.</li>
        <li><?php echo $this->parser->getNbrSeachRows(); ?> abonnement(s) à créer, dont le journal doit être sélectionné dans une liste.</li>
        <li><?php echo $this->parser->getNbrUnknownRows(); ?> abonnement(s) à créer, dont le journal doit être crée ou précisé par perunilid.</li>
    </ul>
    <li>Nombre de ligne(s) rejetée(s) : <?php echo $this->parser->getNbrRejectedRows(); ?></li>
</ul>
</div>


<?php 
echo CHtml::htmlButton('<span class="glyphicon glyphicon-arrow-right"></span> Poursuivre l\'importation', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('csv/ask') . '"',
        'class' => "btn btn-primary"));
echo " ";
echo CHtml::button('Interrompre le processus', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('csv/index') . '"',
        'class' => "btn btn-warning"));
