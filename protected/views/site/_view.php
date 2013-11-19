<?php
$support = Yii::app()->session['search']->support;
// Recherche des abonnement disponibles
if (!$data instanceof Journal){
    $data = Journal::model()->findByPk($data['perunilid']);
}
if (Yii::app()->user->isGuest) {
    if ($support == 1){
        $abos = $data->activeElecAbos;
    }
    elseif ($support == 2){
        $abos = $data->activePaperAbos;
    }else{
        $abos = $data->activeAllAbos;
    }
    
} else { // Admin : tous les abonnement, même inactifs
        if ($support == 1){
        $abos = $data->ElecAbos;
        }
        elseif ($support == 2){
            $abos = $data->PaperAbos;
        }else{
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
        <strong>
            <?php
            echo ucfirst($data->titre);

            echo CHtml::link(
                    ' <span class="glyphicon glyphicon-search"></span>', array('site/detail',
                'id' => $data->perunilid), array('title' => "Cliquez pour afficher les détails")
            );
            ?>
        </strong>
            </div>
        <?php
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
        <?php if ($data->soustitre): ?>
       <div class="row">
           <div class="col-md-6">
        <!-- Sous-titre et autres alternatives -->
        <small>
            <?php
            if ($data->soustitre != ""){
                echo CHtml::encode($data->soustitre);
            }
            ?>
        </small>
           </div>
       </div>
        <?php endif; ?>
    </div>

    <!-- Table -->
    <table class="table">
<?php
foreach ($abos as $i => $abo) {
    ?>

            <td>
                <div class="col-md-1 hidden-xs hidden-sm">&nbsp;</div>
                <div class="col-md-4">
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
                echo CHtml::encode($abo->etatcoll);
                ?>
                </div>


                    </td>
            </tr>
        <?php } //foreach     ?>
    </table>
</div>
