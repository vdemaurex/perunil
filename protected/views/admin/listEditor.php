<?php

$selectData = Yii::app()->cache->get('editeur');
if ($selectData === false) {
    // Régénère $selectData car il ne se trouve pas dans le cache
    $selectData = Editeur::model()->findAll(array('order' => 'editeur'));
    // Sauvegarde pour une utlisation utlérieure
    Yii::app()->cache->set('editeur', $selectData);
}
echo '<pre>';
foreach ($selectData as $m) {
    echo "$m->editeur_id \t $m->editeur \n";
}
echo '</pre>';
