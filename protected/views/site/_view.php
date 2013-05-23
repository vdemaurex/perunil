<?php
// Recherche des abonnement disponibles
if (Yii::app()->user->isGuest) {
    $abos = $data->activeabos;
} else {
    $abos = $data->abonnements;
}

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

// On affiche pas les titre qui ne possèdent aucun abonnement actif.
$nbabo = count($abos);
if ($nbabo == 0 && Yii::app()->user->isGuest) {
    return;
}

?>
<div class="view">
    <table class="journal">

        <?php
        //if ($i == 0) : // première ligne uniquement
        // Affichage de la cellule de titre et de sous-titre
        ?>

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
        //endif; // première ligne uniquement
        foreach ($abos as $i => $abo) {
            ?>

            <td class="support" >
                <?php
                echo $abo->htmlImgTag();
                echo $abo->htmlImgTitreExclu();
                /*
                // Support : icône papier ou www
                if (isset($abo->support0) && isset($abo->support0->support)) {
                    if ($abo->support0->support == "papier") {
                        $src = Yii::app()->baseUrl . "/images/paper.png";
                        echo CHtml::image($src, "Papier", array('title' => "Support papier"));
                    } else {
                        $src = Yii::app()->baseUrl . "/images/www.png";
                        echo CHtml::image($src, "Electronique", array('title' => "Support éléctronique"));
                    }
                }
                // Icône interdit si l'abonnement est un titre exclu
                if ($abo->titreexclu) {
                    $src = Yii::app()->baseUrl . "/images/interdit.png";
                    echo CHtml::image($src, "Titre exclu", array('title' => "Titre exclu"));
                }*/
                ?>
            </td>
            <td class="localisation">
                <?php
                if (isset($abo->support0) && $abo->support0->support == "papier") {
                    if (isset($abo->localisation0))
                        echo CHtml::encode($abo->localisation0->localisation);
                } else {
                    // Affichage du lien
                    $this->widget('AboUrlWidget', array('abo' => $abo, 'jrn' => $data));
                }
                ?>
            </td>
            <td class="etacoll">
                <?
                // Etat de la collection
                echo CHtml::encode($abo->etatcoll);
                ?>
            </td>


            <?php
            if ($i == 0) : // première ligne uniquement
                // Si l'utilisateur est administrateur : 
                if ($i == 0 && !Yii::app()->user->isGuest) {
                    ?>
                    <td rowspan="<?= $nbabo ?>" width="80px" style="text-align: right;">
                        <?php
                        echo CHtml::link("Editer", array('admin/peredit/perunilid/' . $data->perunilid));

                        echo "<br />\n";

                        // Fusion
                        echo "<small>" . CHtml::label("Fusion", $data->perunilid) . "</small>";
                        echo CHTml::checkBox("perunilid[$data->perunilid]", false, array('value' => $data->perunilid));


                        // Modèle pour la fusion
                        echo "<br /><small>" . CHtml::label("Modèle", 'fusion' . $data->perunilid) . "</small>";
                        echo CHtml::radioButton('maitre', false, array('value' => $data->perunilid));
                    }
                    ?>
                </td>
            <?php endif; // première ligne uniquement 
            ?>

            </tr>
        <?php } //foreach    ?>
    </table>





    <?php /* echo '<div class="span-20"><h3>' . CHtml::encode($data->titre) . '</h3></div>';


      echo '<div class="span-22 last">';
      if ($data->soustitre != "")
      echo CHtml::encode($data->soustitre) . '</br>';
      if ($data->titre_abrege != "")
      echo CHtml::encode($data->titre_abrege) . '</br>';
      if ($data->titre_variante != "")
      echo CHtml::encode($data->titre_variante) . '</br>';
      echo '</div>';
      ?>


      <?php
      echo '<div class="span-20 last">';



      echo '</div>';
      echo '<div class="clear"></div>';
      ?>

      <div class="boitelienjournal">
      <table border="0">
      <tr>
      <th width="120px">Format</th>
      <th width="320px">Lien / Emplacement</th>
      <th width="360px">Etat de la collection</th>
      </tr>
      <?php foreach ($abos as $abo) {
      ?>
      <tr>
      <td>
      <?php if (isset($abo->support0) && isset($abo->support0->support)){
      if ($abo->support0->support == "papier"){
      $src=Yii::app()->baseUrl . "/images/paper.png";
      echo CHtml::image($src, "Papier", array('title'=>"Support papier"));
      }else{
      $src=Yii::app()->baseUrl . "/images/www.png";
      echo CHtml::image($src, "Electronique", array('title'=>"Support éléctronique"));
      }

      } //echo CHtml::encode(ucfirst($abo->support0->support)); ?>
      </td>
      <td>
      <?php
      $url = $abo->url_site;
      $short_url = preg_replace('/(?<=^.{22}).{4,}(?=.{20}$)/', '...', $url);
      if (isset($abo->support0) && $abo->support0->support == "papier") {
      if (isset($abo->localisation0))
      echo CHtml::encode($abo->localisation0->localisation);
      } else {
      echo CHtml::link(CHtml::encode($short_url), $url, array('target' => '_blank'));
      }
      ?>
      </td>
      <td>
      <?= CHtml::encode($abo->etatcoll); ?>
      </td>
      </tr>
      <?php } ?>
      </table>
      </div>
      </div>

      <div class="boiteoutiljournal">
      <?php
      // Détail
      $src = Yii::app()->baseUrl . "/images/detail.png";
      echo CHtml::link(CHtml::image($src, "Voir la notice complète", array('title' => "Voir la notice complète")), array('site/detail', 'id' => $data->perunilid));
      echo "<br />\n";

      //
      // Eléments pour la fusion si l'utilisateur est authentifié.
      //
      if (!Yii::app()->user->isGuest) {
      // Editer
      $src = Yii::app()->baseUrl . "/images/edit.png";
      echo "<br />" . CHtml::link(CHtml::image($src, "Editer la notice", array('title' => "Editer la notice")), array('admin/peredit/perunilid/' . $data->perunilid));
      echo "<br />\n";

      // Fusion
      echo "<small>" . CHtml::label("Fusion", $data->perunilid) . "</small>";
      echo CHTml::checkBox("perunilid[$data->perunilid]", false, array('value' => $data->perunilid));


      // Modèle pour la fusion
      echo "<br /><small>" . CHtml::label("Modèle", 'fusion' . $data->perunilid) . "</small>";
      echo CHtml::radioButton('maitre', false, array('value' => $data->perunilid));
      }
      ?>
      </div>
      <div class="clear"></div>
     * */ ?> 
</div>
