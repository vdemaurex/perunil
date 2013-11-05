<?php

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
            //if (Yii::app()->session['search']->support != 2)
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
            if (Yii::app()->session['search']->support != 1)
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

//            $dp = new CActiveDataProvider('Journal', array(
//                    'criteria' => array('condition' => Yii::app()->session['search']->simple_sql_query))
//                    );

            
            
            
            
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

?>
