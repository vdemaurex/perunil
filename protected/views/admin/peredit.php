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
<div id="tabs">
    <ul>
        <li><a href="#" rel="url1" class="selected">Fiche journal</a></li> 
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
                    echo "<li>" . CHtml::link(
                            $abotitle, CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid . '/aboid/' . $abo->abonnement_id), array('title' => $abo->htmlShortDescription(), 'class' => "tooltipster")
                    ) . "</li>";
                
            }
        }
        // Si le journal possède des abonnement, on l'affiche
        /* if (isset($model->abonnements)) {
          foreach ($model->abonnements as $abo) {
          if ($abo->support == 2 && isset($abo->localisation0)) {
          $abotitle = $abo->localisation0->localisation;
          } elseif ($abo->support == 1 && isset($abo->licence0) && isset($abo->licence0->licence)) {
          $abotitle = $abo->licence0->licence;
          } else {
          $abotitle = "Abonnement n°" . $abo->abonnement_id;
          }
          ?>
          <li><?php
          $url = '/admin/aboedit/perunilid/' . $model->perunilid . '/aboid/' . $abo->abonnement_id;
          echo CHtml::link($abotitle, CController::createUrl($url));
          ?>
          </li>
          <?php
          }
          } */
        if (!$model->getIsNewRecord()) {
            echo "<li>" .
            CHtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add16.png", "Ajouter"), CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid)) .
            "</li>";
        }
        ?>
    </ul>
</div>


<?
/*
  //
  // Création du menu de gauche
  //
  $this->beginWidget('system.web.widgets.CClipWidget', array('id' => 'sidebar'));
  ?>




  <h3>Edition du périodique</h3>
  <p><?php echo "<p>". CHtml::link("<< Retour à la notice du journal", CController::createUrl('/site/detail/' . $model->perunilid))?></p>

  <ul>
  <li><strong>Journal</strong>
  <ul><li classe="active"><?= $model->titre ?></li></ul>
  </li>
  <li><strong>Abonnements</strong>
  <ul>
  <?php
  // Si le journal contient des abonnements, on les affiche
  if (isset($model->abonnements)) {
  foreach ($model->abonnements as $abo) {
  if ($abo->support == 2 && isset($abo->localisation0)) {
  $abotitle = $abo->localisation0->localisation;
  } elseif ($abo->support == 1 && isset($abo->licence0) && isset($abo->licence0->licence)) {
  $abotitle = $abo->licence0->licence;
  } else {
  $abotitle = "Abonnement n°" . $abo->abonnement_id;
  }
  echo "<li>" . CHtml::link($abotitle, CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid . '/aboid/' . $abo->abonnement_id)) . "</li>";
  }
  } else { //aucun abonnement existant
  echo "<li>Aucun abonnement</li>";
  }
  if (!$model->getIsNewRecord()) {
  echo "<li>" .
  CHtml::link("+ abonnement...", CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid)) .
  "</li>";
  }
  echo "</ul></li></ul>";

  $this->endWidget(); // CClipWidget */
// Affichage du formulaire de création/modification du journal




$this->renderPartial('_pereditform', array('model' => $model));
if (!$model->getIsNewRecord()) {
    $this->widget('application.modules.auditTrail.widgets.portlets.ShowAuditTrail', array('model' => $model,));
}