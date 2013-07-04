<div class="form">


    <?php
    $textfieldstyle = "width : 90%";
    $smalltextfieldstyle = "width : 90%";
    $yeartextfield = "width : 30px";


    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'abonnement-_aboeditform-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Les champs marqué d'un asterisque (<span class="required">*</span>) doivent obligatoirement être remplis.</p>

    <?php echo $form->errorSummary($model); ?>

    <table class="detail-view">
        <tbody>
            <?php  if (!$model->isNewRecord): ?>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'perunilid *'); ?></th>
                <td colspan="3">
                    <?php 
                    echo $form->textField($model, 'perunilid', array('size' => '10', 'maxlength' => '9')); 
                    echo $form->error($model, 'perunilid');
                    echo " [" . CHtml::link("Edition du journal n° $model->perunilid", array('admin/peredit/perunilid/' . $model->perunilid)) . "]";
                ?>
                </td>
            </tr>
            <?php endif; //!isNewRecord?>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'titreexclu'); ?></th>
                <td colspan="3"><?php echo $form->radioButtonList($model, 'titreexclu', array(true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'titreexclu'); ?></td>
            </tr>
            <tr class="even">
                <th>Accès électronique</th>
                <td colspan="3">
                    <?php echo $form->checkbox($model, 'acces_elec_unil'); ?> <label style="display: inline;">UNIL</label><?php echo $form->error($model, 'acces_elec_unil'); ?> | 
                    <?php echo $form->checkbox($model, 'acces_elec_chuv'); ?> <label style="display: inline;">CHUV</label><?php echo $form->error($model, 'acces_elec_chuv'); ?> | 
                    <?php echo $form->checkbox($model, 'acces_elec_gratuit'); ?> <label style="display: inline;">Libre</label><?php echo $form->error($model, 'acces_elec_gratuit'); ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'package'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'package', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'package'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'no_abo'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'no_abo', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'no_abo'); ?></td>
            </tr> 
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'etatcoll'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'etatcoll', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'etatcoll'); ?></td>               
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'embargo_mois'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'embargo_mois', array('size' => '4', 'maxlength' => '4')); ?>(nombres de mois)<?php echo $form->error($model, 'embargo_mois'); ?></td>
            </tr>
            <tr class="odd">
                <th>Début de la collection</th>
                <td colspan="3">
                    Année <?php echo $form->textField($model, 'etatcoll_deba', array('size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_deba'); ?> | 
                    Volume <?php echo $form->textField($model, 'etatcoll_debv', array('size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_debv'); ?> | 
                    Numéro <?php echo $form->textField($model, 'etatcoll_debf', array('size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_debf'); ?>
                </td>
            </tr>
            <tr class="even">
                <th>Fin de la collection</th>
                <td colspan="3">
                    Année <?php echo $form->textField($model, 'etatcoll_fina', array('size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_fina'); ?> | 
                    Volume <?php echo $form->textField($model, 'etatcoll_finv', array('size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_finv'); ?> | 
                    Numéro <?php echo $form->textField($model, 'etatcoll_finf', array('size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_finf'); ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'plateforme'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Plateforme::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->plateforme) ? $model->plateforme : ''));
                    ?>
                </td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'editeur'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Editeur::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->editeur) ? $model->editeur : ''));
                    ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'histabo'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Histabo::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->histabo) ? $model->histabo : ''));
                    ?>
                </td>
            </tr>  
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'statutabo'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Statutabo::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->statutabo) ? $model->statutabo : ''));
                    ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'localisation'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Localisation::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->localisation) ? $model->localisation : ''));
                    ?>
                </td>
            </tr> 
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'gestion'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Gestion::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->gestion) ? $model->gestion : ''));
                    ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'format'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Format::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->format) ? $model->format : ''));
                    ?>
                </td>
            </tr>   
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'support'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Support::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->support) ? $model->support : ''));
                    ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'licence'); ?></th>
                <td colspan="3">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Licence::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->licence) ? $model->licence : ''));
                    ?>
                </td>
            </tr>  

            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'cote'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'cote', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'cote'); ?></td>
            </tr> 
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'editeur_sujet'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'editeur_sujet', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'editeur_sujet'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'acces_user'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'acces_user', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'acces_user'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'acces_pwd'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'acces_pwd', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'acces_pwd'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'url_site'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'url_site', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'url_site'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'editeur_code'); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'editeur_code', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'editeur_code'); ?></td>
            </tr>  
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'commentaire_pro'); ?></th>
                <td colspan="3"><?php echo $form->textArea($model, 'commentaire_pro', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'commentaire_pro'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'commentaire_pub'); ?></th>
                <td colspan="3"><?php echo $form->textArea($model, 'commentaire_pub', array('style' => $textfieldstyle)); ?><?php echo $form->error($model, 'commentaire_pub'); ?></td>
            </tr> 
            <tr class="even">
                <th colspan="4" style="vertical-align: middle; text-align: center;">
                    <?php
                    if ($model->getIsNewRecord()) {
                        echo CHtml::submitButton('Enregister le nouvel abonnement');
                    } else {
                        echo CHtml::submitButton('Enregister l\'abonnement');
                    }

                    if (!$model->getIsNewRecord()) {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo CHtml::link(
                                'Supprimer cet abonnement', CController::createUrl('/admin/abodelete/perunilid/' . $model->perunilid . '/aboid/' . $model->abonnement_id), array('confirm' => 'Êtes vous sûr de vouloir définitvement supprimer cet abonnement ?')
                        );
                    }
                    ?></strong>
                </th>

            </tr>
    </table>
    <?php $this->endWidget(); ?>

</div><!-- form -->
<script>
    $(document).ajaxComplete(function(event, xhr, settings) {

        $( ".log" ).text( "Triggered ajaxComplete handler. The result is " +
            xhr.responseHTML );

    });
</script>