<?php

//
// Bouton pour les action admin sur la liste des résultats.
//
    //    echo '<br />';
// Boutons affichés dans tous les cas
//echo '<div class="col-md-12">';
//echo CHtml::button('Nouvelle recherche', array(
//    'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(array('admin/searchclean')) . '"',
//    'class' => "btn btn-default"));
// Boutons lorsque l'affichage se fait par journal
if (Yii::app()->session['search']->admin_affichage == 'journal') {
    echo CHtml::button('Affichage par abonnements', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/setaffichage', array('affichage' => 'abonnement')) . '"',
        'class' => "btn btn-info btn-sm margin5pxleft"));
} else {
    // Boutons lorsque l'affichage se fait par abonnements
    echo CHtml::button('Affichage par journal', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/setaffichage', array('affichage' => 'journal')) . '"',
        'class' => "btn btn-info btn-sm margin5pxleft"));
}
// Actions possible uniquement après une recherche admin
if (Yii::app()->session['searchtype'] == 'admin') {
    echo CHtml::button('Modification par lot', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/batchprocessing') . '"',
        'class' => "btn btn-warning btn-sm margin5pxleft"));
    echo CHtml::button('Exporter en CSV', array(
        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('csv/export') . '"',
        'class' => "btn btn-warning btn-sm margin5pxleft"));
}
//        if (Yii::app()->session['search']->admin_affichage == 'journal') {
//        echo " " . CHtml::submitButton(
//                'Fusionner les éléments sélectionnés', array('class' => "btn btn-default btn-sm",
//            'form' => "fusionform"));
//        }

if (Yii::app()->session['search']->admin_affichage == 'journal') {

    echo CHtml::button('Afficher les outils de fusion', array(
        'onclick' => "js:$('.fusion').removeClass('hide');$('#showFusionButton').addClass('hide');$('#hideFusionButton').removeClass('hide');",
        'class' => "btn btn-default btn-sm margin5pxleft",
        'id' => "showFusionButton"));
    echo CHtml::button('Masquer les outils de fusion', array(
        'onclick' => "js:$('.fusion').addClass('hide');$('#hideFusionButton').addClass('hide');$('#showFusionButton').removeClass('hide');",
        'class' => "btn btn-default btn-sm hide margin5pxleft",
        'id' => "hideFusionButton"));

}


//echo "</div><br /><br>";
?>
