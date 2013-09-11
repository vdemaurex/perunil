<?php
$this->breadcrumbs = array(
    'Admin' => array('/admin'),
    'Search',
);
// Si la recherche à déjà été effectuée, on masque les champs de recherche
if ($search_done) {
    $dataProvider = Yii::app()->session['search']->admin_dp;

    // si la requête ne donne aucun résultat, on affiche un avertissement
    if (!isset($dataProvider)) {
        Yii::app()->session['totalItemCount'] = 0;
        Yii::app()->user->setFlash('notice', "Votre requête n'a retourné aucun résultat.<br/>Recherche administrateur : " .
                Yii::app()->session['search']->getQuerySummary());
        $search_done = FALSE;
    } else {
        // Si la requête a produit un résultat, on affiche le total et
        // un résumé de la query.
        Yii::app()->session['totalItemCount'] = $dataProvider->totalItemCount;
        Yii::app()->user->setFlash('success', "Votre requête a retourné " .
                Yii::app()->session['totalItemCount'] .
                " résultat(s).<br/>Recherche administrateur : " .
                Yii::app()->session['search']->getQuerySummary());
                
    }
}

// Si la recherche n'a retrouné aucun résultat, l'état de $search_done peut avoir changé
if ($search_done) {
    ?>
    <p>
        <a id="searchformlink">
            <img id="searchformlinkimg" src="<?= Yii::app()->baseUrl; ?>/images/collapsed.gif"/>Afficher le formulaire de recherche
        </a>
    </p>
    <div id="searchform" style="display: none;">
        <?php $this->renderPartial('_adminSearchForm'); ?>
    </div>
    <script>
        var flip = 0;
        $("#searchformlink").click(function () {
            $("#searchform").toggle( flip++ % 2 == 0 );
            var image = $("#searchformlinkimg");
            if ($(image).attr("src") == "<?= Yii::app()->baseUrl; ?>/images/expanded.gif")
            $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/collapsed.gif");
            else
                $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/expanded.gif");

        });
    </script>
    

    
        <?php
        // Choix du type d'affichage
        if (Yii::app()->session['search']->admin_affichage == 'journal') {
            // affichage par journaux
            $this->widget('AdminCListView', array(//JournalListViewWidget', array(
                'dataProvider' => $dataProvider,
                'itemView' => '/site/_view',
                'ajaxUpdate' => true,
                'template' => "{pager}\n{items}\n{pager}",
            ));
        } else {
            //
            // affichage par abonnements
            //


        // Sélection des colonnes
            $columns[] = 'perunilid';
            $columns[] = array(
                'name' => 'journal_titre',
                'value' => '$data->jrn->titre',
                'header' => 'Journal',
            );
            /* array(
              'name' => 'jrn',
              'value' => '$data->jrn->titre',
              'header' => 'Journal',
              ), */
            /* array(
              'name' => 'jrn',
              'value' => '$data->jrn->issn',
              'header' => 'ISSN',
              ), */
            //'abonnement_id',
            //'titreexclu',
            $columns[] = 'package';
            //'no_abo',
            //if ($this->last['support'] != 2)
            //    $columns[] = 'url_site';
            /* array(
              'name' => 'acces_elec_gratuit',
              'type' => 'boolean',
              'header' => 'Gratuit',
              ), */
            //'acces_elec_unil',
            //'acces_elec_chuv',
            //'embargo_mois',
            //'acces_user',
            //'acces_pwd',
            $columns[] = 'etatcoll';
            //'etatcoll_deba',
            //'etatcoll_debv',
            //'etatcoll_debf',
            //'etatcoll_fina',
            //'etatcoll_finv',
            //'etatcoll_finf',
            if ($this->last['support'] != 1)
                $columns[] = 'cote';
            //'editeur_code',
            //'editeur_sujet',
            //$columns[] = 'commentaire_pro';
            $columns[] = 'commentaire_pub';
            //'plateforme',
            $columns[] = array(
                'name' => 'plateforme',
                'value' => '$data->plateforme0==null ? "" : $data->plateforme0->plateforme',
                'header' => 'Plateforme',
            );
            //'editeur',
            $columns[] = array(
                'name' => 'editeur',
                'value' => '$data->editeur0==null ? "" : $data->editeur0->editeur',
                'header' => 'Editeur',
            );
            //'histabo',
            //'statutabo',
            //'localisation',
            //'gestion',
            //'format',
            //'support',
            //'licence',
            $columns[] = array(
                'name' => 'licence',
                'value' => '$data->licence0->licence',
                'header' => 'Licence',
            );
            $columns[] = array(
                'class' => 'CButtonColumn',
                'template' => '{view}{update}',
                'updateButtonUrl' => '$this->grid->controller->createUrl("/admin/aboedit/perunilid/" . $data->perunilid . "/aboid/" . $data->abonnement_id)',
                //--------------------- Affichage de la fenêtre de détail --------------------------
                'buttons' => array(
                    'view' =>
                    array(
                        'url' => '$this->grid->controller->createUrl("/site/detail", array("id"=>$data->perunilid, "activeTab" => $data->abonnement_id, "dialogue" => 1))',
                        'click' => 'function(){$("#cru-frame").attr("src",$(this).attr("href")); $("#cru-dialog").dialog("open");  return false;}',
                    ),
                ),
                    //--------------------- fin de l'affichage de la fenêtre de détail --------------------------
            );

            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'abonnement-grid',
                'dataProvider' => $dataProvider,
                'formatter' => new FrFormatter(),
                //'filter'=>  Abonnement::model(),
                'columns' => $columns,
            ));

            //--------------------- fenêtre de détail --------------------------
            // add the (closed) dialog for the iframe
            $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                'id' => 'cru-dialog',
                'options' => array(
                    'title' => "Détail du journal et de l'abonnement",
                    'autoOpen' => false,
                    'modal' => false,
                    'width' => 750,
                    'height' => 750,
                ),
            ));
            ?>
        <iframe id="cru-frame" width="100%" height="100%"></iframe>
        <?php
        $this->endWidget();
//--------------------- fin de la fenêtre de détail --------------------------
    } // Fin affichage des abonnement
}
// Aucune recherche n'a encore été effectuée
// affichage du formulaire de recherche
else {
    $this->renderPartial('_adminSearchForm');
}
?>
