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
usort($abos, array("Abonnement", "compare"));


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
            <div class="col-md-6">

<?php
echo "<strong>";
echo ucfirst($data->titre);

echo CHtml::link(
        ' <span class="glyphicon glyphicon-search"></span>', array('site/detail',
    'id' => $data->perunilid), array('title' => "Cliquez pour afficher les détails")
);
echo "</strong>";
?>
            </div>
                <?php
                // Commandes d'administration
                if (!Yii::app()->user->isGuest) {
                    echo '<div class="col-md-3 pull-right">';
                    echo " " . CHtml::button('Editer', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/peredit/', array('perunilid' => $data->perunilid)) . '"',
                        'class' => "btn btn-primary btn-xs"));
                    // echo CHtml::link("Editer", array('admin/peredit/perunilid/' . $data->perunilid));

                    echo " | ";

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
                }
                ?>   

        </div>
        <div class="row">
            <div class="col-md-6">
<?php
$txthd = '';
if ($data->soustitre):
    ?>

                    <!-- Sous-titre et autres alternatives -->
                    <small>
    <?php
    if ($data->soustitre != "") {
        if (strlen($data->soustitre) >= 80) {
            echo CHtml::encode(substr($data->soustitre, 0, 80) . " ... ");
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
                    foreach ($data->sujets as $s) {
                        $txtsj .= CHtml::link($s->nom_fr, array(
                                    'site/advSearchResults',
                                    'advsearch' => "advsearch",
                                    'accessunil' => '1',
                                    'openaccess' => '1',
                                    'sujet' => $s->sujet_id,
                                ));
                        $txtsj .= ", ";
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
                <div class="col-md-3">
    <?php
    echo $abo->htmlImgTag();
    echo $abo->htmlImgTitreExclu();
    ?>

                    <?php
                    $this->widget('AboUrlWidget', array('abo' => $abo, 'jrn' => $data));
                    ?>
                </div>
                <div class="col-md-3">
                    <?
                    // Etat de la collection
                    echo CHtml::encode($abo->etatcoll);
                    ?>
                </div>

                <div class="col-md-4">
                    <?php
                    if ($abo->papier) {
                        if (isset($abo->localisation0)) {
                            echo CHtml::encode($abo->localisation0->localisation);
                            if (isset($abo->cote) && $abo->cote != "") {
                                echo " <small>[cote : {$abo->cote}]</small>";
                            }
                        }
                    } else { // électronique
                        if (isset($abo->plateforme0) && !empty($abo->plateforme0->plateforme)) {
                            echo $abo->plateforme0->plateforme;
                        }
                        if (!empty($data->openaccess)) {
                            ?>
                            <img  style="float:right" src="<?= Yii::app()->baseUrl; ?>/images/open-access-logo_16.png"/>
                            <?php
                        }
                    }
                    ?>
                </div>


            </td>
            </tr>
                <?php } //foreach     ?>
    </table>
</div>
