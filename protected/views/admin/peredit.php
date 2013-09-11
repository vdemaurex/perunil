<?php
$this->breadcrumbs = array(
    'Admin' => array('/admin'),
    'Peredit',
);

//
// Affichage du titre de la page
//
if ($model->getIsNewRecord()) {
    echo "<h1>Création d'un nouveau périodique</h1>\n";
    echo "<p> <strong>NB:</strong> Vous devez d'abord enregister ce nouveau 
        périodique avant de pouvoir lui ajouter des abonnements.</p>";
} else {
    echo "<h1>$model->titre</h1>\n";
}
?>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#" rel="url1" >Fiche journal</a></li> 
        <?php
        if (isset($model->abonnements)) {
            foreach ($model->abonnements as $abo) {
                // Affchage de l'icone du support
                $abotitle = $abo->htmlImgTag();
                $abotitle .= $abo->htmlImgTitreExclu() . "&nbsp;";

                // Choix du titre de l'onglet
                if (isset($abo->licence0) && isset($abo->licence0->licence)) {
                    $abotitle .= $abo->licence0->licence;
                } elseif (isset($abo->localisation0) && isset($abo->localisation0->localisation)) {
                    $abotitle .= $abo->localisation0->localisation;
                } else { // En dernier recours, on affiche l'id de l'abonnement
                    $abotitle .= "Abonnement n°" . $abo->abonnement_id;
                }

                // Affichage du lien
                $id = "abo". $abo->abonnement_id;
                ?><li><a href="<?= CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid . '/aboid/' . $abo->abonnement_id); ?>"
                       data-content="<?= $abo->htmlShortDescription(); ?>" rel="popover" data-original-title=""
                       data-toggle="popover"  data-placement="bottom" id="<?= $id ?>"><?= $abotitle ?></a></li>
                    <script>
                        $('#<?= $id; ?>').popover({ html : true, trigger: "hover" });
                    </script>
                    <?php
                
            }
        }
        // Si le journal possède des abonnement, on l'affiche

        if (!$model->getIsNewRecord()) {
            echo "<li>" .
            CHtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add16.png", "Ajouter"), CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid), array('title' => "Ajouter un abonnement")) .
                    "</li>";
        }
        ?>
    </ul>



<?
// Affichage du formulaire de création/modification du journal

$this->renderPartial('_pereditform', array('model' => $model));
if (!$model->getIsNewRecord()) {
    $this->widget('application.modules.auditTrail.widgets.portlets.ShowAuditTrail', array('model' => $model,));
}