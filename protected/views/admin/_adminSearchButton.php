<?php

        //
        // Bouton pour les action administrateur sur la liste des résultats.
        //
        
        // Boutons affichés dans tous les cas
        echo '<div class="btn-group">';
        echo CHtml::button('Nouvelle recherche', array(
            'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(array('admin/searchclean')) . '"',
            'class' => "btn btn-default"));

        // Boutons lorsque l'affichage se fait par journal
        if (Yii::app()->session['search']->admin_affichage == 'journal') {
            echo " " . CHtml::button('Affichage par abonnements', array(
                'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/setaffichage', array('affichage' => 'abonnement')) . '"',
                'class' => "btn btn-default"));
        } else {
            // Boutons lorsque l'affichage se fait par abonnements
            echo " " . CHtml::button('Affichage par journal', array(
                'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/setaffichage', array('affichage' => 'journal')) . '"',
                'class' => "btn btn-default"));
            echo " " . CHtml::button('Modification par lot', array(
                'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/batchprocessing') . '"',
                'class' => "btn btn-default"));
        }
        echo " " . CHtml::button('Exporter en CSV', array(
            'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/csvexport') . '"',
            'class' => "btn btn-default"));
        echo " " . CHtml::submitButton(
                'Fusionner les éléments sélectionnés', array('class' => "btn btn-default",
            'form' => "fusionform"));
        echo "</div>";
    
?>
