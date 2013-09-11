<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Recherche sur <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php
if (isset($advsearch) && $advsearch) {
    // Recherche avancée
    $dataProviderName = 'adv_dp';
    $this->renderPartial('_advSearch');
} else {
    // Affichage de la recherche simple 
    $dataProviderName = 'simple_dp';
    $this->renderPartial('_simpleSearch');
}
?>

<div class="clear"><p>&nbsp;</p></div>


<?php
// On affiche la zone des résultats uniquement si un recherche existe. 
if ($search_done) {

    // Affichage des résultat pour le publique
    if (Yii::app()->user->isGuest) {
        $widget = 'zii.widgets.CListView';
        $view = '_view';
    }
    // Affichage des résultat pour les administrateurs
    else {
        $widget = 'AdminCListView';
        $view = '/admin/_adminView';

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
    }

    $this->widget($widget, array(
        'dataProvider' => Yii::app()->session['search']->$dataProviderName,
        'itemView' => $view,
        'ajaxUpdate' => true,
        'template' => "{pager}\n{items}\n{pager}",
    ));


    // Affichage des résulats de la recherche
    Yii::app()->session['totalItemCount'] = Yii::app()->session['search']->$dataProviderName->totalItemCount;
    Yii::app()->user->setFlash('success', "Votre requête a retourné " .
            Yii::app()->session['totalItemCount'] .
            " résultat(s).<br/>" .
            Yii::app()->session['search']->getQuerySummary());
} else { // Si aucune recherche, on affiche les logo des plateformes
    ?>
    <hr/>
    <h2>Accès direct aux principales plateformes</h2>
    <table cellspacing="10" cellpadding="0" border="0"><tbody><tr><td>

                    <a target="_blank" href="http://www.sciencedirect.com/"><img width="180" title="Elsevier Science Direct" src="<?= Yii::app()->baseUrl; ?>/images/sciencedirect.png"></a>
                </td><td>
                    <a target="_blank" href="http://www.tandfonline.com/"><img width="60" title="Taylor &amp; Francis Online" src="<?= Yii::app()->baseUrl; ?>/images/tandfonline.jpg"></a>
                </td><td>
                    <a target="_blank" href="http://onlinelibrary.wiley.com/"><img width="150" title="Wiley" src="<?= Yii::app()->baseUrl; ?>/images/wiley.jpg"></a>
                </td><td>
                    <a target="_blank" href="http://www.springer.com/"><img width="160" title="Springer" src="<?= Yii::app()->baseUrl; ?>/images/springer.jpg"></a>
                </td><td>
                    <a target="_blank" href="http://www.jstor.org/"><img width="60" title="JSTOR" src="<?= Yii::app()->baseUrl; ?>/images/jstor.jpg"></a>
                </td><td>
                    <a target="_blank" href="http://www.ingentaconnect.com/"><img width="180" title="Ingenta Connect" src="<?= Yii::app()->baseUrl; ?>/images/ingenta.png"></a>
                </td></tr></tbody></table>
    <?php
}
?>
