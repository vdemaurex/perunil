<h2>Modification par lot</h2>
<p>Le lot en mémoire contient <?php echo Yii::app()->session['totalItemCount']; ?> éléments.
    <?php
    /**
     * Emplacement du contenu du iFrame pour l'affichage de la liste des abonnements du lot.
     */
    $url = $this->createUrl("/admin/gridviewdialog");
    echo CHtml::button(
            'Afficher le contenu du lot', array(
        'onclick' => '$("#gridViewDialog").attr("src","' . $url . '"); $("#cru-dialog").dialog("open");  return false;')
    );




//--------------------- fenêtre de visualisation du lot --------------------------
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'cru-dialog',
        'options' => array(
            'title' => "Contenu du lot",
            'autoOpen' => false,
            'modal' => false,
            'width' => 750,
            'height' => 750,
        ),
    ));
    ?>
    <iframe id="gridViewDialog" width="100%" height="100%"></iframe>
</p>
<?php
$this->endWidget();
//--------------------- fin de la fenêtre de visualisation du lot -------------------------
// ----------------------- Affichage du formulaire ----------------------------------------
if ($stage == "1-form") :
    ?>
    <div class="form">


    <?php
    $model = new Abonnement();
    $textfieldstyle = "width : 90%";
    $smalltextfieldstyle = "width : 90%";
    $yeartextfield = "width : 30px";


    $form = $this->beginWidget(
            'CActiveForm', array(
        'id' => 'abonnement-_aboeditform-form',
        'enableAjaxValidation' => false,
            ));
    ?>

        <?php
        echo $form->errorSummary($model);
        echo CHtml::hiddenField("stage", "2-preview");
        ?>

        <table class="detail-view">
            <tbody>
                <tr class="odd">
                    <th><?php echo $form->labelEx($model, 'titreexclu'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[titreexclu]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'titreexclu'); ?></td>
                </tr>
                <tr class="even">
                    <th><?php echo $form->labelEx($model, 'acces_elec_unil'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[acces_elec_unil]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'acces_elec_unil'); ?></td>
                </tr>
                <tr class="odd">
                    <th><?php echo $form->labelEx($model, 'acces_elec_chuv'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[acces_elec_chuv]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'acces_elec_chuv'); ?></td>
                </tr>
                <tr class="even">
                    <th><?php echo $form->labelEx($model, 'acces_elec_gratuit'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[acces_elec_gratuit]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'acces_elec_gratuit'); ?></td>
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
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>
                <tr class="even">
                    <th><?php echo $form->labelEx($model, 'editeur'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Editeur::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>
                <tr class="odd">
                    <th><?php echo $form->labelEx($model, 'histabo'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Histabo::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>  
                <tr class="even">
                    <th><?php echo $form->labelEx($model, 'statutabo'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Statutabo::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>
                <tr class="odd">
                    <th><?php echo $form->labelEx($model, 'localisation'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Localisation::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr> 
                <tr class="even">
                    <th><?php echo $form->labelEx($model, 'gestion'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Gestion::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>
                <tr class="odd">
                    <th><?php echo $form->labelEx($model, 'format'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Format::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>   
                <tr class="even">
                    <th><?php echo $form->labelEx($model, 'support'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Support::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>
                <tr class="odd">
                    <th><?php echo $form->labelEx($model, 'licence'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Licence::model(),
        'frm_classname' => get_class($model)));
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
                <tr class="odd" style="background-color : #E8F8EC;">
                    <th><?php echo CHtml::label("Action sur les champs textes (*) :", "add_text"); ?></th>
                    <td colspan="3">
    <?php echo CHtml::radioButtonList("add_text", true, array(true => 'Ajouter le texte à la suite des données existantes.', false => 'Remplacer le contenu par le nouveau texte.'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?>
                    </td>
                </tr> 

                <tr class="even">
                    <th colspan="4" style="vertical-align: middle; text-align: center;">
    <?php echo CHtml::submitButton('Suivant > '); ?>
                    </th>

                </tr>
        </table>
    <?php $this->endWidget(); ?>

    </div><!-- form -->
        <?php
    elseif ($stage == "2-preview") :
// ----------------------- Affichage de la prévisualisation -------------------------------
        echo CHtml::form();
        echo CHtml::hiddenField("stage", "3-done");
        echo CHtml::hiddenField("add_text", $add_text);
        if (count($updt) == 1) {
            echo "<p>Liste de la modification qui sera appliquée au(x) ";
        } else {
            echo "<p>Liste des modifications qui seront appliquées au(x) ";
        }
        echo Yii::app()->session['totalItemCount'] . " élément(s) du lot.</p>";


        echo "<ul>";
        foreach ($updt as $key => $value) {
            // S'il s'agit d'une relation, on affiche le nom
            $link = "";
            if (in_array($key, $this->abolinks)) {
                $class = ucfirst($key);
                $link = " - '" . $class::model()->findByPk($value)->$key . "'";
            }
            echo "<li>" . CHtml::checkBox($key, true) . "<strong>$key</strong>";
            if (in_array($key, $this->textfields) && $add_text) {
                echo " <i>(ajout)</i>";
            } else {
                echo " <i>(remplacement)</i>";
            }

            echo " : $value $link</li>";
        }
        echo "</ul>";


        echo "<p>" . CHtml::submitButton('Appliquer les modifications') . "</p>";
        echo CHtml::endForm(); 
        
        
        
        
        elseif ($stage == "3-done") :
// ------------- Affichage des résultats de la modification par lot -----------------------
        foreach (array(true, false) as $bool) {
            if (count($update_results[$bool]) > 0) {
                if ($bool) {
                    $verbe = "réussi";
                } else {
                    $verbe = "échoué";
                }
                echo "<p>La modification a $verbe pour " . count($update_results[$bool]) . " abonnements.</p>";
                //echo "<br/><pre>";
                //echo wordwrap(implode(", ", $update_results[$bool]), 110, "<br />", true);
                //echo "<br/>";
            }
        }

        echo "<h3>Liste des modification appliquées</h3>";

        echo "<ul>";
        foreach ($updt as $key => $value) {
            // S'il s'agit d'une relation, on affiche le nom
            $link = "";
            if (in_array($key, $this->abolinks)) {
                $class = ucfirst($key);
                $link = " - '" . $class::model()->findByPk($value)->$key . "'";
            }
            echo "<li><strong>$key</strong>";
            if (in_array($key, $this->textfields) && $add_text) {
                echo " <i>(ajout)</i>";
            } else {
                echo " <i>(remplacement)</i>";
            }

            echo " : $value $link</li>";
        }
        echo "</ul>"; 
    
endif;