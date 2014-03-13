<?php

//
// Bouton pour les action admin sur la liste des résultats.
//
        echo '<br />';
// Boutons affichés dans tous les cas
echo '<div class="btn-group">';
//echo CHtml::button('Nouvelle recherche', array(
//    'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(array('admin/searchclean')) . '"',
//    'class' => "btn btn-default"));
// Boutons lorsque l'affichage se fait par journal
if (Yii::app()->session['search']->admin_affichage == 'journal') {
    echo " " . CHtml::button('Affichage par abonnements', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/setaffichage', array('affichage' => 'abonnement')) . '"',
        'class' => "btn btn-default btn-sm"));
} else {
    // Boutons lorsque l'affichage se fait par abonnements
    echo " " . CHtml::button('Affichage par journal', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/setaffichage', array('affichage' => 'journal')) . '"',
        'class' => "btn btn-default btn-sm"));
}
// Actions possible uniquement après une recherche admin
if (Yii::app()->session['searchtype'] == 'admin') {
    echo " " . CHtml::button('Modification par lot', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/batchprocessing') . '"',
        'class' => "btn btn-default btn-sm"));
    echo " " . CHtml::button('Exporter en CSV', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('csv/export') . '"',
        'class' => "btn btn-default btn-sm"));
}
//        if (Yii::app()->session['search']->admin_affichage == 'journal') {
//        echo " " . CHtml::submitButton(
//                'Fusionner les éléments sélectionnés', array('class' => "btn btn-default btn-sm",
//            'form' => "fusionform"));
//        }
echo "</div><br /><br>";
?>
