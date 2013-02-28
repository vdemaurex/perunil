<?php
$this->breadcrumbs=array(
	'Admin'=>array('/admin'),
	'Aboedit',
);

$this->widget('ext.tooltipster.tooltipster', array('options'=>array('position'=>'bottom')));
//
// Affichage du titre de la page
//
?>
<h1><?=$jrn->titre;?></h1>

<div id="tabs">
    <ul>
        <li><?= CHtml::link(" Fiche journal ", CController::createUrl('admin/peredit/perunilid/' . $jrn->perunilid), array('title' => "Editer la fiche du périodique",'class' => "tooltipster")) ?></li> 
        <?php
        
       // Si le journal contient des abonnements, on les affiche
        if (isset($jrn->abonnements)) {
            
            foreach ($jrn->abonnements as $abo) {
                // Affchage de l'icone du support
                $abotitle = $abo->htmlImgTag();
                $abotitle .= $abo->htmlImgTitreExclu(). "&nbsp;";
                
                // Choix du titre de l'onglet
                if(isset($abo->licence0) && isset($abo->licence0->licence)){
                    $abotitle .= $abo->licence0->licence;
                }
                elseif (isset($abo->localisation0) && isset($abo->localisation0->localisation)) {
                    $abotitle .= $abo->localisation0->localisation;
                }
                else{ // En dernier recours, on affiche l'id de l'abonnement
                    $abotitle .= "Abonnement n°" . $abo->abonnement_id;
                }
                
                // Affichage du lien, class selected si nécessaire
                if ($abo->abonnement_id == $model->abonnement_id){
                    echo "<li>".CHtml::link($abotitle,'#',array("class"=>"selected"))."</li>";
                } else {
                    echo "<li>" . CHtml::link(
                            $abotitle, 
                            CController::createUrl('/admin/aboedit/perunilid/'.$jrn->perunilid .'/aboid/' . $abo->abonnement_id),
                            array('title' => $abo->htmlShortDescription(), 'class' => "tooltipster")
                            ) . "</li>";
                }
            }
        }
        $addabo = CHtml::image(Yii::app()->baseUrl . "/images/add16.png", "Ajouter");
        if (!$jrn->getIsNewRecord()) {
            echo "<li>";
            if ($model->getIsNewRecord()){
                echo CHtml::link($addabo, '#',array("class"=>"selected"));
            }else{
                echo CHtml::link($addabo, CController::createUrl('/admin/aboedit/perunilid/' . $model->perunilid), array('title' => "Ajouter un abonnement",'class' => "tooltipster"));
            }
            echo "</li>";
        }
        ?>
    </ul>
</div>
<br/>

<?

//
// Affichage du titre de la page
//
if($model->getIsNewRecord()){
    echo "<h2>Création d'un nouvel abonnement pour {$jrn->titre}</h2>\n";
}
 else {
    echo "<h2>Edition de l'abonnement n° $model->abonnement_id</h2>\n";
}
/*
//
// Création du menu de gauche
//
$this->beginWidget('system.web.widgets.CClipWidget', array('id'=>'sidebar'));?>
            <h3>Edition du périodique</h3>
            <p><?php echo "<p>". CHtml::link("<< Retour à la notice du journal", CController::createUrl('/site/detail/' . $model->perunilid))?></p>
            <ul>
                <li><strong>Journal</strong>
                    <ul><li ><?= CHtml::link($jrn->titre, CController::createUrl('admin/peredit/perunilid/' . $jrn->perunilid)) ?></li></ul>
                </li>
                <li><strong>Abonnements</strong>
                    <ul>
                       <?php
        // Si le journal contient des abonnements, on les affiche
        if (isset($jrn->abonnements)) {
            foreach ($jrn->abonnements as $abo) {
                if ($abo->support == 2 && isset($abo->localisation0)) {
                    $abotitle = $abo->localisation0->localisation;
                } elseif ($abo->support == 1 && isset($abo->licence0) && isset($abo->licence0->licence)) {
                    $abotitle = $abo->licence0->licence;
                } else {
                    $abotitle = "Abonnement n°" . $abo->abonnement_id;
                }
                if ($abo->abonnement_id == $model->abonnement_id){
                    echo "<li classe=\"active\">$abotitle</li>";
                } else {
                    echo "<li>" . CHtml::link($abotitle, CController::createUrl('/admin/aboedit/perunilid/'.$jrn->perunilid .'/aboid/' . $abo->abonnement_id)) . "</li>";
                }
            }
        }else { //aucun abonnement existant
            echo "<li>Aucun abonnement</li>";
        }
        echo "<li>". CHtml::link("+ abonnement...", CController::createUrl('/admin/aboedit/perunilid/' . $jrn->perunilid)) ."</li>";
        echo "</ul></li></ul>";
            
            

  //      echo CHtml::Button("Ajouter un abonnement", array('id'=>"addabo", 'submit' =>CController::createUrl('/admin/pereditAddAbo/perunilid/' . $jrn->perunilid)));

$this->endWidget(); // CClipWidget
*/


// Affichage du formulaire de création/modification du journal
$this->renderPartial('_aboeditform', array('model' => $model));

if (!$model->getIsNewRecord()) {
$this->widget( 'application.modules.auditTrail.widgets.portlets.ShowAuditTrail', array( 'model' => $model, ) );
}
?>