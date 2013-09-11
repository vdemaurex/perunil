<div class="form">

    <?php
    $textfieldstyle = "width : 90%";
    $smalltextfieldstyle = "width : 90%";

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'journal-_pereditform-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-horizontal',
            'role'  => 'form',
        ),
            ));
    ?>

    <p class="note">Les champs marqué d'une astersisque <span class="required">*</span> sont obligatoires.</p>

    <?php echo $form->errorSummary($model); ?>

            <table class="detail-view">
                <tbody>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'perunilid', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'perunilid', array('class' => "form-control input-sm", 'style' => $textfieldstyle, 'readonly' => true)); ?><?php echo $form->error($model, 'soustitre'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'titre', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'titre', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'titre'); ?></td>
                    </tr>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'soustitre', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'soustitre', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'soustitre'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'titre_abrege', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'titre_abrege', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'titre_abrege'); ?></td>
                    </tr>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'titre_variante', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'titre_variante', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'titre_variante'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'faitsuitea', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'faitsuitea', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'faitsuitea'); ?></td>
                    </tr>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'devient', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'devient', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'devient'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'publiunil', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->radioButtonList($model, 'publiunil', array(true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'publiunil'); ?></td>
                    </tr>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'openaccess', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->radioButtonList($model, 'openaccess', array(true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'openaccess'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'parution_terminee', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->radioButtonList($model, 'parution_terminee', array(true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'parution_terminee'); ?></td>
                    </tr>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'issnl', array('class'=> "control-label")); ?></th>
                        <td><?php echo $form->textField($model, 'issnl', array('class' => "form-control input-sm", 'style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'issnl'); ?></td>
                        <th><?php echo $form->labelEx($model, 'issn', array('class'=> "control-label")); ?></th>
                        <td><?php echo $form->textField($model, 'issn', array('class' => "form-control input-sm", 'style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'issn'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'reroid', array('class'=> "control-label")); ?></th>
                        <td><?php echo $form->textField($model, 'reroid', array('class' => "form-control input-sm", 'style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'reroid'); ?></td>
                        <th><?php echo $form->labelEx($model, 'nlmid', array('class'=> "control-label")); ?></th>
                        <td><?php echo $form->textField($model, 'nlmid', array('class' => "form-control input-sm", 'style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'nlmid'); ?></td>
                    </tr>
                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'coden', array('class'=> "control-label")); ?></th>
                        <td><?php echo $form->textField($model, 'coden', array('class' => "form-control input-sm", 'style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'coden'); ?></td>
                        <th><?php echo $form->labelEx($model, 'doi', array('class'=> "control-label")); ?></th>
                        <td><?php echo $form->textField($model, 'doi', array('class' => "form-control input-sm", 'style' => $smalltextfieldstyle)); ?><?php echo $form->error($model, 'doi'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'urn', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'urn', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'urn'); ?></td>
                    </tr>

                    <tr class="even">
                        <th><?php echo $form->labelEx($model, 'url_rss', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textField($model, 'url_rss', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'url_rss'); ?></td>
                    </tr>
                    <tr class="odd">
                        <th><?php echo $form->labelEx($model, 'commentaire_pub', array('class'=> "control-label")); ?></th>
                        <td colspan="3"><?php echo $form->textArea($model, 'commentaire_pub', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'commentaire_pub'); ?></td>
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
                               echo CHtml::submitButton('Enregister le nouveau periodique', array('class' => "btn btn-primary"));
                            } else {
                                echo CHtml::submitButton('Enregister le periodique', array('class' => "btn btn-primary"));
                            }
                            
                            if (!$model->getIsNewRecord()) {
                                // 
                                // Suppression
                                // 
                                // Suppression seulement si ce n'est pas un périodique non enregistré
                                // et si il ne possède pas d'abonnement.
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                if (count($model->abonnements) > 0) {
                                    // Il y encore des abonnements suppression impossible.
                                     echo CHtml::button('Supprimer ce journal', array(
                                        'confirm' => 'Impossible de supprimer ce journal car celui-ci possède des abonnements.',
                                        'class' => "btn btn-danger"));
                                } else {
                                  echo CHtml::button('Supprimer ce journal', array(
                                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl(('/admin/jrndelete/perunilid/' . $model->perunilid)) . '"',
                                        'confirm' => 'Êtes vous sûr de vouloir définitvement supprimer ce journal ?',
                                        'class' => "btn btn-danger"));
                                }
                                // 
                                // Duplication
                                
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo CHtml::button('Dupliquer ce journal', array(
                                    'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('/admin/jrnduplicate/perunilid/' . $model->perunilid .'"'),
                                    'confirm' => 'Ce journal ainsi que tous ses abonnements vont être copiés, êtes-vous sûr de vouloir continuer ?',
                                    'class' => "btn btn-default"));
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
                                <th><?php echo $form->labelEx($model, 'DEPRECATED_sujetsfm', array('class'=> "control-label")); ?></th>
                                <td><?php echo $form->textField($model, 'DEPRECATED_sujetsfm', array('class' => "form-control input-sm", 'style' => $textfieldstyle, 'disabled' => 'true')); ?><?php echo $form->error($model, 'DEPRECATED_sujetsfm'); ?></td>
                            </tr>
        <?php
    endif; //DEPRECATED_sujetsfm
    if (isset($model->DEPRECATED_fmid) && $model->DEPRECATED_fmid != '') :
        ?>            
                            <tr class="odd">
                                <th><?php echo $form->labelEx($model, 'DEPRECATED_fmid', array('class'=> "control-label")); ?></th>
                                <td><?php echo $form->textField($model, 'DEPRECATED_fmid', array('class' => "form-control input-sm", 'style' => $textfieldstyle, 'disabled' => 'true')); ?><?php echo $form->error($model, 'DEPRECATED_fmid'); ?></td>
                            </tr>
        <?php
    endif; //DEPRECATED_fmid
    if (isset($model->DEPRECARED_historique) && $model->DEPRECARED_historique != '') :
        ?>
                            <tr class="even">
                                <th><?php echo $form->labelEx($model, 'DEPRECARED_historique', array('class'=> "control-label")); ?></th>
                                <td><?php echo $form->textArea($model, 'DEPRECARED_historique', array('class' => "form-control input-sm", 'style' => $textfieldstyle, 'disabled' => 'true')); ?><?php echo $form->error($model, 'DEPRECARED_historique'); ?></td>
                            </tr>
    <?php endif; //DEPRECARED_historique   ?>
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
