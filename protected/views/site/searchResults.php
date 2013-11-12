<?php $this->pageTitle = Yii::app()->name; 
 switch ($searchtype) {
    case 'adv':
        $titre = "avancée";
        $dp = 'adv_dp';
        $adp = 'adv_adp';
        $count = 'adv_count';
        $search_url = 'site/advSearch';
        $clean_url  = 'site/advclean';
         break;

     case 'admin':
        $titre = "administrateur";
        $dp = 'admin_dp';
        $adp = 'admin_dp';
        $count = 'admin_count';
        $search_url = 'admin/search';
        $clean_url  = 'admin/searchclean';
         break;

    default: // simple
        $titre = "simple";
        $dp = 'simple_dp';
        $adp = 'simple_adp';
        $count = 'simple_sql_query_count';
        $search_url = 'site/index';
        $clean_url  = 'site/simpleclean';
        break;
}

?>
<h2>Résultats de la recherche <?php echo $titre; ?></h2>
<?php
echo  CHtml::htmlButton('<span class="glyphicon glyphicon-backward"> </span> Retour au formulaire de recherche', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl($search_url) . '"',
                        'class'   => "btn btn-default  btn-xs")); 
echo " ";
echo  CHtml::htmlButton('Nouvelle recherche', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl($clean_url) . '"',
                        'class'   => "btn btn-default  btn-xs")); 
?>
<br/>

<?php
// On affiche la zone des résultats uniquement si un recherche existe. 
if ($search_done) {

    // Affichage des résultat pour le publique
    if (Yii::app()->user->isGuest) {     
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => Yii::app()->session['search']->$dp,
            'itemView' => '_view',
            'ajaxUpdate' => false,
            'template' => "{pager}\n{items}\n{pager}",
        ));
    }
    // Affichage des résultat pour les administrateurs
    else {
       // echo CHtml::beginForm(CHtml::normalizeUrl(array('admin/fusion')),'post', array('id' =>'fusionform'));
        $this->renderPartial('/admin/_adminSearchButton');
        
        if (Yii::app()->session['search']->admin_affichage == 'abonnement') {
            // Affichage par abonnement
            $this->renderPartial('/admin/_aboSearchResults', array('dataProvider' => Yii::app()->session['search']->$adp));
        }else{
            // affichage par journaux
            $this->widget('AdminCListView', array(
                'dataProvider' => Yii::app()->session['search']->$dp,
                'itemView' => '/site/_view',
                'ajaxUpdate' => false,
                'template' => "{pager}\n{items}\n{pager}",
            ));
        }
        //echo CHtml::endForm();
    }



    // Affichage des résulats de la recherche
    Yii::app()->session['totalItemCount'] = Yii::app()->session['search']->$count;
    $msg = "";
    if(Yii::app()->session['search']->maxresults > 0 && $searchtype == "simple"){ // Il y a une limitation du nombre de résultats
        $msg = ", limitée à " . Yii::app()->session['search']->maxresults. " résultats,";
    }
    Yii::app()->user->setFlash('success', "Votre requête$msg a retourné " .
            Yii::app()->session['totalItemCount'] .
            " résultat(s).<br/>" .
            Yii::app()->session['search']->getQuerySummary());
} else{ 
    //
    // Si aucune recherche, on affiche les logo des plateformes
    // 
    
    ?>
    <p>Il n'y a aucun résultat à afficher.</p>

    <div class="hidden-xs hidden-sm"> 
        <hr/>
        <h2>Accès direct aux principales plateformes</h2>
        <table class="centpp" cellspacing="10" cellpadding="0" border="0"><tbody><tr><td>

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
    </div>
    <?php
}
?>
