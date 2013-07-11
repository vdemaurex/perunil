<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Recherche sur <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php
$dataProviderName = 'search_dataprovider';
if (isset($advsearch) && $advsearch) { // Recherche avancée
    $dataProviderName = 'advsearch_dataprovider';
    if (isset($lastadvsearch)) {
        $advsearch_parms = array('last' => $lastadvsearch);
    } else {
        $advsearch_parms = array();
    }
    $this->renderPartial('_advSearch', $advsearch_parms);
} else  { // Affichage de la recherche simple 
    $this->renderPartial('_simpleSearch');
}
?>

<div class="clear"><p>&nbsp;</p></div>


<?
// On affiche la zone des résultats uniquement si un recherche existe. 
if ($search_done) {
    Yii::app()->session['last_search_url'] = $_SERVER['REQUEST_URI'];
    //echo "<p>$dataProvider->totalItemCount périodiques trouvés pour la recherche \"{$_GET['q']}\".</p>";
    
    if(Yii::app()->user->isGuest){
        $widget = 'zii.widgets.CListView';
        $view   = '_view';
    }
    else{ // admin
        $widget = 'AdminCListView';
        $view   = '/admin/_adminView';
    }

        $this->widget($widget,array(
        'dataProvider' => Yii::app()->session[$dataProviderName],
        'itemView' => $view,
        'ajaxUpdate' => true,
        'template'=>"{pager}\n{items}\n{pager}",
    ));

}
else{ // Si aucune recherche, on affiche les logo des plateformes
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
