<?php $this->pageTitle = Yii::app()->name . " - " . $model->titre; ?>

<p><?php echo CHtml::link("<< Retour", Yii::app()->createUrl('mobile/index')) ?></p>
<h3><?= $model->titre ?></h3>

<?php
$fields = array(
    'perunilid',
    'titre',
    'soustitre',
    'titre_abrege',
    'titre_variante',
    'faitsuitea',
    'devient',
    array('name' => 'commentaire_pub', 'label' => 'Remarques'),
);
// Affichage des informations
$label;
$value;

foreach ($fields as $key => $field) {
    if (is_array($field)) {
        $label = $field['label'];
        $value = $model->$field['name'];
    } else {
        $label = $field;
        $value = $model->$field;
    }


    if ($value != NULL && trim($value) != "") {
        echo "<p><strong>$label</strong> : $value</p>";
    }
    $label = null;
    $value = null;
}

echo '<ul class="ui-listview" data-role="listview">';
foreach ($model->activeabos as $abo) {
    ?>
    <li class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c" data-theme="c" data-iconpos="right" data-icon="arrow-r" data-wrapperels="div" data-iconshadow="true" data-shadow="false" data-corners="false">
        <div class="ui-btn-inner ui-li">
            <div class="ui-btn-text">
                <?php
                if ($abo->support == 2) {
                    if (isset($abo->localisation0)) {
                        echo '<h3 class="ui-li-heading">Périodique papier : ' . $abo->localisation0->localisation . '</h3>';
                        echo '<p class="ui-li-desc">' . $abo->etatcoll . '</p>';
                    }
                
                } else {
                    if (isset($abo->plateforme0) && isset($abo->plateforme0->plateforme))
                        $linktitle = $abo->plateforme0->plateforme;
                    elseif (isset($abo->licence0) && isset($abo->licence0->licence))
                        $linktitle = $abo->licence0->licence;
                    

                    echo '<a class="ui-link-inherit" href="' . $abo->url_site . '">';
                    echo '<h3 class="ui-li-heading">' . $linktitle . '</h3>';
                    echo '<p class="ui-li-desc">' . $abo->etatcoll . '</p>';
                    echo '</a>';
                }
                ?>

            </div>
            <span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span>
        </div>
    </li>

    <?php
}
echo '</ul>';
?>		