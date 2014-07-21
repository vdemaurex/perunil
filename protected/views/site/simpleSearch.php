<?php $this->pageTitle = Yii::app()->name; ?>

<h2 id="title-simplesearch">Recherche de périodiques disponibles sur les sites UNIL, CHUV et BCUL</h2>
<br/>

<p class="text-center">
<?php

foreach (range('A', 'Z') as $lettre) {
    echo CHtml::link($lettre,array('site/simpleSearchResults',
                                'q'          => $lettre,
                                'field'      => 'tbegin',
                                'support'    => '0',
                                'depotlegal' => '0',
                                'maxresults' => '-1'),
            array("title" => "Liste des périodiques commençant par la lettre $lettre")); 
    echo "&nbsp;&nbsp;";
}
echo '</p>';



// Affichage de la recherche simple 
$this->renderPartial('_simpleSearch');

?>

<div class="clear"><p>&nbsp;</p></div>

<p class="text-center"><?php echo CHtml::link("Accéder à la liste des sujets",array('site/sujet'));?></p>

<?php 
    $this->renderPartial('_logosPlateforme');
?>

