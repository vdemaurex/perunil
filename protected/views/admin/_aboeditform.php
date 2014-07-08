<script>
    /* Chargement des selects avec Ajax*/
    $(document).ready(function() {
        $("#editeurSelect").select2({
            placeholder: "Rechercher un éditeur",
            width: '516px',
            ajax: {
                url: "<?php echo Yii::app()->request->hostInfo . $this->createUrl('admin/editorSelect') ?>",
                dataType: 'json',
                quietMillis: 100,
                data: function(term, page) {
                    return {
                        term: term, //search term
                        page_limit: 8 // page size
                    };
                },
                results: function(data, page) {
                    return {results: data.results};
                }

            },
            initSelection: function(element, callback) {
                return $.getJSON("<?php echo Yii::app()->request->hostInfo . $this->createUrl('admin/editorSelect') ?>?id=" + (element.val()), null, function(data) {

                    return callback(data);

                });
            }

        });
<?php if (!empty($model->editeur)) :?>
            $("#editeurSelect").select2("val", "<?php echo $model->editeur; ?>");
<?php endif; ?>
    });

</script>
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
            <?php if (!$model->isNewRecord): ?>
                <tr class="even">
                    <th><?php echo $form->labelEx($model, 'perunilid *', array('class' => "control-label")); ?></th>
                    <td colspan="3">
                        <?php
                        echo $form->textField($model, 'perunilid', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'maxlength' => '9'));
                        echo $form->error($model, 'perunilid');
                        echo " [" . CHtml::link("Edition du journal n° $model->perunilid", array('admin/peredit/perunilid/' . $model->perunilid)) . "]";
                        ?>
                    </td>
                </tr>
            <?php endif; //!isNewRecord?>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'titreexclu', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'package', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'package', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'package'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'no_abo', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'no_abo', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'no_abo'); ?></td>
            </tr> 
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'etatcoll', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'etatcoll', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'etatcoll'); ?></td>               
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'embargo_mois', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'embargo_mois', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'size' => '4', 'maxlength' => '4')); ?>(nombres de mois)<?php echo $form->error($model, 'embargo_mois'); ?></td>
            </tr>
            <tr class="odd">
                <th>Début de la collection</th>
                <td colspan="3">
                    Année <?php echo $form->textField($model, 'etatcoll_deba', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_deba'); ?> | 
                    Volume <?php echo $form->textField($model, 'etatcoll_debv', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_debv'); ?> | 
                    Numéro <?php echo $form->textField($model, 'etatcoll_debf', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_debf'); ?>
                </td>
            </tr>
            <tr class="even">
                <th>Fin de la collection</th>
                <td colspan="3">
                    Année <?php echo $form->textField($model, 'etatcoll_fina', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_fina'); ?> | 
                    Volume <?php echo $form->textField($model, 'etatcoll_finv', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_finv'); ?> | 
                    Numéro <?php echo $form->textField($model, 'etatcoll_finf', array('class' => "form-control input-sm", 'style' => "width : 100px; display: inline;", 'size' => '4', 'maxlength' => '4')); ?><?php echo $form->error($model, 'etatcoll_finf'); ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'plateforme', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'editeur', array('class' => "control-label")); ?></th>
                <td colspan="3">
                    <input type="hidden" data-placeholder="Sélectionnez un éditeur.." class="input-xlarge" id="editeurSelect" name="Abonnement[editeur]" >
                    <?php /*
                    $this->widget('SelectWidget', array(
                        'model' => Editeur::model(),
                        'frm_classname' => get_class($model),
                        'selected' => isset($model->editeur) ? $model->editeur : ''));
                    */ ?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'histabo', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'statutabo', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'localisation', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'gestion', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'format', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'support', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'licence', array('class' => "control-label")); ?></th>
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
                <th><?php echo $form->labelEx($model, 'cote', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'cote', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'cote'); ?></td>
            </tr> 
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'editeur_sujet', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'editeur_sujet', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'editeur_sujet'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'acces_user', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'acces_user', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'acces_user'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'acces_pwd', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'acces_pwd', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'acces_pwd'); ?></td>
            </tr>
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'url_site', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'url_site', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'url_site'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'editeur_code', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textField($model, 'editeur_code', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'editeur_code'); ?></td>
            </tr>  
            <tr class="even">
                <th><?php echo $form->labelEx($model, 'commentaire_pro', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textArea($model, 'commentaire_pro', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'commentaire_pro'); ?></td>
            </tr>
            <tr class="odd">
                <th><?php echo $form->labelEx($model, 'commentaire_pub', array('class' => "control-label")); ?></th>
                <td colspan="3"><?php echo $form->textArea($model, 'commentaire_pub', array('class' => "form-control input-sm", 'style' => $textfieldstyle)); ?><?php echo $form->error($model, 'commentaire_pub'); ?></td>
            </tr> 
            <tr class="even">
                <th colspan="4" style="vertical-align: middle; text-align: center;">
                    <?php
                    if ($model->getIsNewRecord()) {
                        echo CHtml::submitButton('Créer le nouvel abonnement', array('class' => "btn btn-primary"));
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo CHtml::button('Annuler', array(
                            'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(Yii::app()->request->urlReferrer) . '"',
                            'class' => "btn btn-default"));
                    } else {
                        echo CHtml::submitButton('Enregister', array('class' => "btn btn-primary"));
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo CHtml::button('Annuler', array(
                            'onclick' => 'js:document.location.href="' . CHtml::normalizeUrl(Yii::app()->request->requestUri) . '"',
                            'class' => "btn btn-default"));
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo CHtml::button('Supprimer', array(
                            'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('/admin/abodelete/perunilid/' . $model->perunilid . '/aboid/' . $model->abonnement_id) . '"',
                            'confirm' => 'Êtes-vous sûr de vouloir définitivement supprimer cet abonnement ?',
                            'class' => "btn btn-danger"));
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo CHtml::button('Dupliquer', array(
                            'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('/admin/aboduplicate/perunilid/' . $model->perunilid . '/aboid/' . $model->abonnement_id) . '"',
                            'confirm' => 'Une copie de cet abonnement sera crée, êtes-vous sûr de vouloir continuer ?',
                            'class' => "btn btn-success"));
                        
                        
                        
                    }
                    ?></strong>
                </th>

            </tr>
    </table>
    <?php $this->endWidget(); ?>

</div><!-- form -->
<script>
    $(document).ajaxComplete(function(event, xhr, settings) {

        $(".log").text("Triggered ajaxComplete handler. The result is " +
                xhr.responseHTML);

    });
</script>