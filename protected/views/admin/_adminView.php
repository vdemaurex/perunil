<?php
// Recherche des abonnement disponibles
$abos = $data->abonnements;


// Tris selon le support
if ($this->support > 0) { // Un seul support est demandé
    // Suppression des abonnement qui n'ont pas le format demandé
    foreach ($abos as $key => $abo) {
        if (strcmp($this->support, $abo->support) != 0)
            unset($abos[$key]);
    }
}
else { // Classement des abonnement en fonction du support papier ou électronique
    usort($abos, array("Abonnement", "compare"));
}

// nombre d'abonnement actifs
$nbabo = count($abos);

?>
<div class="view">
    <table class="journal">
        <tr>
        <?php
        //if ($i == 0) : // première ligne uniquement
        // Affichage de la cellule de titre et de sous-titre
        ?>

        <td rowspan="<?= $nbabo ?>" width="80px" class="admin">
            <?php
            echo CHtml::link("Editer", array('admin/peredit/perunilid/' . $data->perunilid));

            echo "<br />\n";

            // Fusion
            echo "<small>" . CHtml::label("Fusionner", $data->perunilid) . "</small>";
            echo CHTml::checkBox("perunilid[$data->perunilid]", false, array('value' => $data->perunilid));


            // Modèle pour la fusion
            echo "<br /><small>" . CHtml::label("A conserver", 'fusion' . $data->perunilid) . "</small>";
            echo CHtml::radioButton('maitre', false, array('value' => $data->perunilid));
        ?>
        </td>    
            
        <td class="etiquette" rowspan="<?= $nbabo ?>">
            <?php
// Affichage du titre du journal
            echo '<span class="titre">' . CHtml::link(CHtml::encode($data->titre), array('site/detail', 'id' => $data->perunilid), array('title' => "Cliquez pour afficher les détails"));
// Si le journal est OpenAccess, on l'affiche
            if ($data->openaccess) {
                $src = Yii::app()->baseUrl . "/images/open-access-logo_21.png";
                echo "&nbsp;" . CHtml::image($src, "OpenAccess", array('title' => "Revue Open Access"));
            }
            ?>
            </span>
            <span class="sous-titre">
                <?php
                if ($data->soustitre != "")
                    echo '</br>' . CHtml::encode($data->soustitre);
                if ($data->titre_abrege != "")
                    echo '</br>' . CHtml::encode($data->titre_abrege);
                if ($data->titre_variante != "")
                    echo '</br>' . CHtml::encode($data->titre_variante);
                ?></span>
            <div class="sujets">
                <?php echo $data->sujets2str(); ?>
            </div>
        </td>


        <?php
        foreach ($abos as $i => $abo) {
            if ($i >0) echo "<tr>";
            ?>
            
            <td class="support" >
                <?php
                echo $abo->htmlImgTag();
                echo $abo->htmlImgTitreExclu();
                ?>
            </td>
            <td class="localisation">
                <?php
                
                    $this->widget('AboUrlWidget', array('abo' => $abo, 'jrn' => $data));
                
                echo " <small>[";
                echo CHtml::link("Editer", CController::createUrl('/admin/aboedit/perunilid/' . $data->perunilid . '/aboid/' . $abo->abonnement_id), array('title' => "Editer l'abonnement",));
                echo "]</small>";
           ?></td>
            <td class="etacoll">
                <?
                // Etat de la collection
                echo CHtml::encode($abo->etatcoll);
                ?>
            </td>

            </tr>
        <?php } //foreach   
        if ($nbabo == 0){
            echo "<td><em>Aucun abonnement pour ce journal</em></td>";
        }
        
        
        ?>
        
    </table>



</div>
