<h3>Mes modifications durant les 24 dernières heures</h3>

<?php

            $columns[] = 'user_id';
//            $columns[] = array(
//                'name' => 'user_id',
//                'value' => 'Utilisateur::model()->findAllByPk($data->user_id)->pseudo',//'$data->utilisateur==null ? "" : $data->utilisateur->pseudo',
//                'header' => 'Utilisateur',
//            );
            $columns[] = 'action';
            $columns[] = 'model';
            $columns[] = 'model_id';
            $columns[] = 'field';
            $columns[] = 'old_value';
            $columns[] = 'new_value';
            $columns[] = 'stamp';

//            $columns[] = array(
//                'class' => 'CButtonColumn',
//                'template' => '{view}{update}',
//                'updateButtonUrl' => '$this->grid->controller->createUrl("/admin/aboedit/perunilid/" . $data->perunilid . "/aboid/" . $data->abonnement_id)',
//                //--------------------- Affichage de la fenêtre de détail --------------------------
//                'buttons' => array(
//                    'view' =>
//                    array(
//                        'url' => '$this->grid->controller->createUrl("/site/detail", array("id"=>$data->perunilid, "activeTab" => $data->abonnement_id, "dialogue" => 1))',
//                        'click' => 'function(){$("#cru-frame").attr("src",$(this).attr("href")); $("#cru-dialog").dialog("open");  return false;}',
//                    ),
//                ),
                    //--------------------- fin de l'affichage de la fenêtre de détail --------------------------
//            );


            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'mesmodifications-grid',
                'dataProvider' => $dataProvider,
                'formatter' => new FrFormatter(),
                'filter'=> Modifications::model(),
                'columns' => $columns,
            ));
?>
