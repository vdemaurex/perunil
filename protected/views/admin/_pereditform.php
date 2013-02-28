<div class="form">

    <?php
    $textfieldstyle = "width : 90%";
    $smalltextfieldstyle = "width : 90%";

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'journal-_pereditform-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <table class="detail-view">
        <tbody>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'titre'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'titre', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'titre'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'soustitre'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'soustitre', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'soustitre'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'titre_abrege'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'titre_abrege', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'titre_abrege'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'titre_variante'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'titre_variante', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'titre_variante'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'faitsuitea'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'faitsuitea', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'faitsuitea'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'devient'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'devient', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'devient'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'publiunil'); ?></th>
                <td colspan="3"><?php echo $form->radioButtonList($model, 'publiunil', array(true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'publiunil'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'openaccess'); ?></th>
                <td colspan="3"><?php echo $form->radioButtonList($model, 'openaccess', array(true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'openaccess'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'parution_terminee'); ?></th>
                <td colspan="3"><?php echo $form->radioButtonList($model, 'parution_terminee', array(true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'parution_terminee'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'issnl'); ?></th>
                <td><?php echo $form->textField($model, 'issnl', array('style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'issnl'); ?></td>
                <th><?php echo $form->labelEx($model, 'issn'); ?></th>
                <td><?php echo $form->textField($model, 'issn', array('style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'issn'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'reroid'); ?></th>
                <td><?php echo $form->textField($model, 'reroid', array('style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'reroid'); ?></td>
                <th><?php echo $form->labelEx($model, 'nlmid'); ?></th>
                <td><?php echo $form->textField($model, 'nlmid', array('style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'nlmid'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'coden'); ?></th>
                <td><?php echo $form->textField($model, 'coden', array('style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'coden'); ?></td>
                <th><?php echo $form->labelEx($model, 'doi'); ?></th>
                <td><?php echo $form->textField($model, 'doi', array('style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'doi'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'urn'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'urn', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'urn'); ?></td>
            </tr>

            <tr class="even">
                <th><?php echo $form->labelEx($model, 'url_rss'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'url_rss', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'url_rss'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'commentaire_pub'); ?></th>
                <td colspan="3"><?php echo $form->textArea($model, 'commentaire_pub', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'commentaire_pub'); ?></td>
            </tr>
            <tr class="even">
                <th>Sujets</th>
                <td id="tdsujets" colspan="3"><?php
    $nbselect = 10;
    $i = 0;
    foreach ($model->sujets as $sujet) {
        $this->widget('SelectWidget', array(
            'model' => Sujet::model(),
            'defaultlabel' => "",
            'selected' => $sujet->sujet_id,
            'select_name' => "Journal[sujet][$i]",
        ));
        echo "<br/>\n";
        $i++;
    }
    ?> 
                    <div><a id="sujetplusln"><img id="sujetplusimg" src="<?= Yii::app()->baseUrl; ?>/images/collapsed.gif"/>Afficher plus de sujets</a></div>
                    <div id="sujetplus" style="display: none;"><?php
                    for (; $i < $nbselect; $i++) {
                        $this->widget('SelectWidget', array(
                            'model' => Sujet::model(),
                            'select_name' => "Journal[sujet][$i]",
                            'selected' => 'all',
                            'defaultlabel' => "",
                        ));
                        echo "<br/>\n";
                    }
    ?></div>
                    <script>

                        var flip = 0;
                        $("#sujetplusln").click(function () {
                            $("#sujetplus").toggle( flip++ % 2 == 0 );
                            var image = $("#sujetplusimg");
                            if ($(image).attr("src") == "<?= Yii::app()->baseUrl; ?>/images/expanded.gif")
                            $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/collapsed.gif");
                            else
                                $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/expanded.gif");

                        });
                    </script>
                </td>
            </tr>

            <tr class="odd">
                <th>Core collection</th>
                <td id="tdcorecollection" colspan="3"><?php
                        $nbselect = 3;
                        $i = 0;
                        foreach ($model->corecollection as $cc) {
                            $this->widget('SelectWidget', array(
                                'model' => Biblio::model(),
                                'defaultlabel' => "",
                                'selected' => $cc->biblio_id,
                                'select_name' => "Journal[corecollection][$i]",
                            ));
                            echo "&nbsp;\n";
                            $i++;
                        }
                        // Ajout des select vides
                        for (; $i < $nbselect; $i++) {
                            $this->widget('SelectWidget', array(
                                'model' => Biblio::model(),
                                'select_name' => "Journal[corecollection][$i]",
                                'selected' => 'all',
                                'defaultlabel' => "",
                            ));
                            echo "&nbsp;\n";
                        }
    ?>

                </td>
            </tr>

            <tr class="even">
                <th style="text-align: center;" colspan="4"><?php
                    if ($model->getIsNewRecord()) {
                        echo CHtml::submitButton('Enregister le nouveau periodique');
                    } else {
                        echo CHtml::submitButton('Enregister le periodique');
                    }
                    // Suppression du périodique si ce n'est pas un périodique non enregistré
                    // et si il ne possède plus d'abonnement.
                    if (!$model->getIsNewRecord()) {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        if (count($model->abonnements)>0) {
                            // Il y encore des abonnements suppression impossible.
                            echo CHtml::link(
                                    'Supprimer ce journal', '#', array('confirm' => 'Impossible de supprimer ce journal car celui-ci possède des abonnements.')
                            );
                            
                        } else { 
                            echo CHtml::link(
                                    'Supprimer ce journal', CController::createUrl('/admin/jrndelete/perunilid/' . $model->perunilid), array('confirm' => 'Êtes vous sûr de vouloir définitvement supprimer ce journal ?')
                            );
                        }
                    }
    ?>
                    </th>
            </tr>
    </table>



                    <?php
                    //si il possède des anciennes données FileMaker, on propose de les afficher.
                    if ((isset($model->DEPRECATED_sujetsfm) && $model->DEPRECATED_sujetsfm != '') ||
                            (isset($model->DEPRECATED_fmid) && $model->DEPRECATED_fmid != '') ||
                            (isset($model->DEPRECARED_historique) && $model->DEPRECARED_historique != '')):
                        ?>
        <div class="span-23 prepend-top"><a id="fm"><img id="fmimg" src="<?= Yii::app()->baseUrl; ?>/images/collapsed.gif"/>Afficher les anciennes données de la base FileMaker</a></div>
        <div class="clear"><br/></div>
        <div id="fmdata" style="display: none;">
            <table class="detail-view">
        <?php if (isset($model->DEPRECATED_sujetsfm) && $model->DEPRECATED_sujetsfm != '') : ?>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'DEPRECATED_sujetsfm'); ?></th>
                        <td><?php echo $form->textField($model, 'DEPRECATED_sujetsfm', array('style' => $textfieldstyle, 'disabled' => 'true')); ?><?php echo $form->error($model, 'DEPRECATED_sujetsfm'); ?></td>
                    </tr>
    <?php
    endif; //DEPRECATED_sujetsfm
    if (isset($model->DEPRECATED_fmid) && $model->DEPRECATED_fmid != '') :
        ?>            
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'DEPRECATED_fmid'); ?></th>
                        <td><?php echo $form->textField($model, 'DEPRECATED_fmid', array('style' => $textfieldstyle, 'disabled' => 'true')); ?><?php echo $form->error($model, 'DEPRECATED_fmid'); ?></td>
                    </tr>
                <?php
                endif; //DEPRECATED_fmid
                if (isset($model->DEPRECARED_historique) && $model->DEPRECARED_historique != '') :
                    ?>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'DEPRECARED_historique'); ?></th>
                        <td><?php echo $form->textArea($model, 'DEPRECARED_historique', array('style' => $textfieldstyle, 'disabled' => 'true')); ?><?php echo $form->error($model, 'DEPRECARED_historique'); ?></td>
                    </tr>
                <?php endif; //DEPRECARED_historique  ?>
            </table>
        </div>
        <script>

            var flip = 0;
            $("#fm").click(function () {
                $("#fmdata").toggle( flip++ % 2 == 0 );
                var image = $("#fmimg");
                if ($(image).attr("src") == "<?= Yii::app()->baseUrl; ?>/images/expanded.gif")
                $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/collapsed.gif");
                else
                    $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/expanded.gif");

            });
        </script>



    <?php
endif;
$this->endWidget();
?>


</div><!-- form -->
