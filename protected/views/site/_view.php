<?php
$support = Yii::app()->session['search']->support;
// Recherche des abonnement disponibles
if (!$data instanceof Journal) {
    $data = Journal::model()->findByPk($data['perunilid']);
}
if (Yii::app()->user->isGuest) {
    if ($support == 1) {
        $abos = $data->activeElecAbos;
    } elseif ($support == 2) {
        $abos = $data->activePaperAbos;
    } else {
        $abos = $data->activeAllAbos;
    }
} else { // Admin : tous les abonnement, même inactifs
    if ($support == 1) {
        $abos = $data->ElecAbos;
    } elseif ($support == 2) {
        $abos = $data->PaperAbos;
    } else {
        $abos = $data->AllAbos;
    }
}

// Classement des abonnement en fonction du support papier ou électronique
//usort($abos, array("Abonnement", "compare"));


// On affiche pas les titre qui ne possèdent aucun abonnement actif.
$nbabo = count($abos);
if ($nbabo == 0 && Yii::app()->user->isGuest) {
    return;
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <!-- Titre du journal !-->
        <div class="row">
            <div style="padding-left: 5px; padding-right: 5px;" <?php if (!Yii::app()->user->isGuest){ echo 'class="col-md-6"';}?>>

                <span class="journal-titre">
                <?php echo ucfirst($data->titre); ?>
            </span>
                
 

<?php 


if (Yii::app()->user->isGuest) {
    echo '<div style="float:right;">';
    echo CHtml::htmlButton('<span class="glyphicon glyphicon-search"></span> Détail', array(
                'onclick' => 'js:document.location.href="' . Yii::app()->createUrl("site/detail", array("id" => $data->perunilid)) . '"',
                'class' => "btn btn-default  btn-xs"));
    echo '</div>';
}
?>
           
                </div>
                <?php
                // Commandes d'administration
                if (!Yii::app()->user->isGuest) {
                    echo '<div class="pull-right" style="padding-left: 5px; padding-right: 5px;">';
                    echo CHtml::htmlButton('<span class="glyphicon glyphicon-search"></span> Détail', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl("site/detail", array("id" => $data->perunilid)) . '"',
                        'class' => "btn btn-default  btn-xs"));
                    echo " " . CHtml::htmlButton('<span class="glyphicon glyphicon-pencil"></span> Editer', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/peredit/', array('perunilid' => $data->perunilid)) . '"',
                        'class' => "btn btn-primary btn-xs"));
                    // echo CHtml::link("Editer", array('admin/peredit/perunilid/' . $data->perunilid));

                    echo "<div class='fusion pull-right hide'> ";

                    // Fusion
                    echo CHTml::checkBox("perunilid[$data->perunilid]", false, array('value' => $data->perunilid));
                    echo "<small>" . CHtml::label("Fusion", $data->perunilid) . "</small>";

                    echo " | ";

                    // Modèle pour la fusion
                    echo CHtml::radioButton('maitre', false, array('value' => $data->perunilid));
                    echo "<small>" . CHtml::label("Modèle", 'fusion' . $data->perunilid) . "</small>";

                    echo " | ";

                    echo " " . CHtml::submitButton(
                            'Fusion !', array('class' => "btn btn-default btn-xs",
                        'form' => "fusionform"));
                    echo '</div>';
                    echo '</div>';
                }
                ?>   

        </div>
        <div class="row" style="padding-left: 5px;">
            <div class="journal-soustitre" <?php if (!Yii::app()->user->isGuest){ echo 'class="col-md-6"';}?>>
<?php
$txthd = '';
if ($data->soustitre):
    ?>

                    <!-- Sous-titre et autres alternatives -->
                    <small>
    <?php
    if ($data->soustitre != "") {
        if (strlen($data->soustitre) >= 90) {
            echo Encoding::truncate($data->soustitre, 90) . " ... ";
        } else {
            echo CHtml::encode($data->soustitre);
        }    
        $txthd = ' | ';
    }
    ?>
                    </small>


                    <?php
                    endif;

                    $txthd .= '<small>';
                    $txtsj = "";
                    $txtft = '</small>';
                    $i = 0;
                    foreach ($data->sujets as $s) {
                        $txtsj .= CHtml::link($s->nom_fr, array(
                                    'site/advSearchResults',
                                    'advsearch' => "advsearch",
                                    'accessunil' => '1',
                                    'openaccess' => '1',
                                    'sujet' => $s->sujet_id,
                                ));
                        $txtsj .= ", ";
                        $i++;
                        if ($i > 8){
                            $txtsj .= "... ";
                            break;
                        }
                    }
                    if (!empty($txtsj))
                        echo $txthd . trim($txtsj, ", ") . $txtft;
                    ?>
            </div>
        </div>
    </div>

    <!-- Table -->
    <table class="table">
<?php
foreach ($abos as $i => $abo) {
    ?>

            <td>
                <div class="col-md-1 hidden-xs hidden-sm">&nbsp;</div>
                <div class="col-md-2">
    <?php
    echo $abo->htmlImgTag();
    echo $abo->htmlImgTitreExclu();
    ?>

                    <?php
                    $this->widget('AboUrlWidget', array('abo' => $abo, 'jrn' => $data));
                    ?>
                </div>
                <div class="col-md-4">
                    <?
                    // Etat de la collection                    
                    if (!empty($abo->commentaire_etatcoll)){
                        echo CHtml::encode($abo->commentaire_etatcoll) . " : ";
                    }
                    echo CHtml::encode($abo->etatcoll);
                    if ($abo->statutabo == 4){
                        echo " [Négociation en cours]";
                    }
                    ?>
                </div>

                <div class="col-md-4">
                    <?php
                    if ($abo->papier) {
                        // Abonnement papier, localisation et eventuelement la cote.
                        if (isset($abo->localisation0)) {
                            echo CHtml::encode($abo->localisation0->localisation);
                            if (isset($abo->cote) && $abo->cote != "") {
                                echo " <small>[cote : {$abo->cote}]</small>";
                            }
                        }
                    } else { // électronique
                        // Si elle des disponible, on affiche la plateforme
                        if (isset($abo->plateforme0) && !empty($abo->plateforme0->plateforme)) {
                            echo $abo->plateforme0->plateforme;
                        }
                        // Sinon, on affiche le nom de domaine où est hébérgé le journal
                        elseif (!empty ($abo->url_site)){
                            echo $abo->getDomaineName();
                        }
                        
                        // Affichage du logo Openaccess
                        if (!empty($data->openaccess)) {
                            ?>
                            <img  style="float:right" src="<?= Yii::app()->baseUrl; ?>/images/open-access-logo_16.png"/>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php    
                if (!Yii::app()->user->isGuest){
                    // L'utilisateur est admin, on affiche les bouton détail et 
                    // modification pour l'abonnement.
                    echo '<div class="col-md-1">';
                    echo " " . CHtml::htmlButton(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            array(
                                'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/aboedit/perunilid/'.$data->perunilid .'/aboid/'.$abo->abonnement_id) . '"',
                                'class' => "btn btn-primary btn-xs")
                            );
                    echo '</div>';
                }
                        
                        
                ?>


            </td>
            </tr>
                <?php } //foreach     ?>
    </table>
</div>
