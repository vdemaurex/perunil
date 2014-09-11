<?php
$this->breadcrumbs=array(
	'Admin'=>array('/admin'),
	'Aboedit',
);

    echo CHtml::htmlButton('<span class="glyphicon glyphicon-backward"> </span> Retour au résultat de la recherche', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl("admin/returnToSearchResults") . '"',
        'class' => "btn btn-default  btn-sm"));
    echo " ";
    echo CHtml::htmlButton('<span class="glyphicon glyphicon-search"></span> Détail', array(
                'onclick' => 'js:document.location.href="' . Yii::app()->createUrl("site/detail", array("id" => $model->perunilid)) . '"',
                'class' => "btn btn-default  btn-sm"));

//
// Affichage du titre de la page
//

echo "<h1>$jrn->titre</h1>";
//
// Affichage du titre de la page
////
//if($model->getIsNewRecord()){
//    echo "<h3>Création d'un nouvel abonnement pour {$jrn->titre}</h3>\n";
//}
// else {
//    echo "<h3>Edition de l'abonnement n° $model->abonnement_id</h3>\n";
//}
?>
  <div class="row">
      <div class=" col-sm-3 col-md-3">
  
    <ul class="nav nav-pills nav-stacked">
        <li><?= CHtml::link(" <strong>Fiche journal</strong> ", CController::createUrl('admin/peredit/perunilid/' . $jrn->perunilid), array('title' => "Editer le journal")) ?></li> 
        <?php
        
       // Si le journal contient des abonnements, on les affiche
        if (isset($jrn->abonnements)) {
            
            foreach ($jrn->AllAbos as $abo) {
                // Affchage de l'icone du support
                $abotitle = $abo->htmlImgTag();
                $abotitle .= $abo->htmlImgTitreExclu(). "&nbsp;";
                
                // Choix du titre de l'onglet
                if(isset($abo->licence0) && isset($abo->licence0->licence)){
                    $abotitle .= htmlspecialchars($abo->licence0->licence);
                }
                elseif (isset($abo->localisation0) && isset($abo->localisation0->localisation)) {
                    $abotitle .= htmlspecialchars($abo->localisation0->localisation);
                }
                else{ // En dernier recours, on affiche l'id de l'abonnement
                    $abotitle .= "Abonnement n°" . $abo->abonnement_id;
                }
                
                // Affichage du lien, définition de l'onglet active
                if ($abo->abonnement_id == $model->abonnement_id){
                    echo '<li class="active">'.CHtml::link($abotitle,'#')."</li>";
                } else {
                    $id = "abo". $abo->abonnement_id;
                    ?><li><a href="<?= CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid . '/aboid/' . $abo->abonnement_id); ?>"
                       data-content="<?= $abo->htmlShortDescription(); ?>" rel="popover" data-original-title=""
                       data-toggle="popover"  data-placement="right" id="<?= $id ?>"><?= $abotitle ?></a></li>
                    <script>
                        $('#<?= $id; ?>').popover({ html : true, trigger: "hover", template: '<div class="popover special-class" style="width:350px;"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>' });
                    </script>
                    <?php
                }
            }
        }
        $addabo = CHtml::image(Yii::app()->baseUrl . "/images/add16.png", "Ajouter") . "<span style='color:darkgreen'> Nouvel abonnement</span>";
        if (!$jrn->getIsNewRecord()) {
            echo "<li>";
            if ($model->getIsNewRecord()){
                echo CHtml::link($addabo, '#',array("class"=>"selected"));
            }else{
                echo CHtml::link($addabo, CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid), array('title' => "Ajouter un abonnement"));
            }
            echo "</li>";
        }
        ?>
    </ul>

<br/>

    </div>
    <div class="col-sm-6 col-md-9">


<?

// Affichage du formulaire de création/modification du journal
$this->renderPartial('_aboeditform', array('model' => $model));

if (!$model->getIsNewRecord()) {
$this->widget( 'application.modules.auditTrail.widgets.portlets.ShowAuditTrail', array( 'model' => $model, ) );
}
?>

        </div>
  </div>
