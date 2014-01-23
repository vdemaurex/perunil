<?php
function txtbtn($form,$model,$field) {
    echo $form->textField($model, $field, array('style' => "width : 80%", 'class'=>"form-control")); ?><?php echo $form->error($model, $field);
    echo '&nbsp; ' . CHtml::button('Vider', array('onclick' => 'viderText("#Abonnement_' . $field .'",$(this))', 'class' => "btn btn-default btn-sm"));
}
?>


<h2>Modification par lot</h2>
<p>Le lot en mémoire contient <?php echo Yii::app()->session['totalItemCount']; ?> éléments.
    <?php
    /**
     * Emplacement du contenu du iFrame pour l'affichage de la liste des abonnements du lot.
     */
    $url = $this->createUrl("/admin/gridviewdialog");
    echo CHtml::button(
            'Afficher le contenu du lot', array(
            'onclick' => '$("#gridViewDialog").attr("src","' . $url . '"); $("#cru-dialog").dialog("open");  return false;',
            'class' => "btn btn-primary btn-sm")
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
    $textfieldstyle = "width : 80%";
    $smalltextfieldstyle = "width : 90%";
    $yeartextfield = "width : 30px";


    $form = $this->beginWidget(
            'CActiveForm', array(
        'id' => 'abonnement-_aboeditform-form',
        'enableAjaxValidation' => false,
            ));
    ?>

        <script>
function viderText(id,button)
{
if($(id).val() === "NULL"){
    $(button).val("Vider");
    $(id).val("");
    $(id).removeAttr('readonly');
}
else{
    $(button).val("Ne plus vider");
    $(id).val("NULL");
    $(id).attr('readonly','readonly');
    }       
}
</script>
        
        <?php
        echo $form->errorSummary($model);
        echo CHtml::hiddenField("stage", "2-preview");
        ?>
        <div class="panel panel-default" style="width: 95%; margin:auto;">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th><?php echo $form->labelEx($model, 'titreexclu'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[titreexclu]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'titreexclu'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'acces_elec_unil'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[acces_elec_unil]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'acces_elec_unil'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'acces_elec_chuv'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[acces_elec_chuv]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'acces_elec_chuv'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'acces_elec_gratuit'); ?></th>
                    <td colspan="3"><?php echo CHtml::radioButtonList("Abonnement[acces_elec_gratuit]", "PasDeChangement", array("PasDeChangement" => 'Pas de changement', true => 'Oui', false => 'Non'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?><?php echo $form->error($model, 'acces_elec_gratuit'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'package'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'package'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'no_abo'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'no_abo'); ?></td>
                </tr> 
                <tr>
                    <th><?php echo $form->labelEx($model, 'etatcoll'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'etatcoll'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'embargo_mois'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'embargo_mois'); ?></td>
                </tr>
                <tr >
                    <th>Début de la collection</th>
                    <td colspan="3" class="form-inline">
                        Année <?php echo $form->textField($model, 'etatcoll_deba', array('size' => '4', 'maxlength' => '4', 'class'=>"form-control width80px")); 
                        echo '&nbsp; ' . CHtml::button('Vider', array('onclick' => 'viderText("#Abonnement_etatcoll_deba",$(this))', 'class' => "btn btn-default btn-sm"));
                        echo $form->error($model, 'etatcoll_deba'); ?> | 
                        Volume <?php echo $form->textField($model, 'etatcoll_debv', array('size' => '4', 'maxlength' => '4', 'class'=>"form-control width80px"));  
                        echo '&nbsp; ' . CHtml::button('Vider', array('onclick' => 'viderText("#Abonnement_etatcoll_debv",$(this))', 'class' => "btn btn-default btn-sm")); 
                        echo $form->error($model, 'etatcoll_debv'); ?> | 
                        Numéro <?php echo $form->textField($model, 'etatcoll_debf', array('size' => '4', 'maxlength' => '4', 'class'=>"form-control width80px"));  
                        echo '&nbsp; ' . CHtml::button('Vider', array('onclick' => 'viderText("#Abonnement_etatcoll_debf",$(this))', 'class' => "btn btn-default btn-sm")); 
                        echo $form->error($model, 'etatcoll_debf'); ?>
                    </td>
                </tr>
                <tr>
                    <th>Fin de la collection</th>
                    <td colspan="3" class="form-inline">
                        Année <?php echo $form->textField($model, 'etatcoll_fina', array('size' => '4', 'maxlength' => '4', 'class'=>"form-control width80px")); 
                        echo '&nbsp; ' . CHtml::button('Vider', array('onclick' => 'viderText("#Abonnement_etatcoll_fina",$(this))', 'class' => "btn btn-default btn-sm"));
                        echo $form->error($model, 'etatcoll_fina'); ?> | 
                        Volume <?php echo $form->textField($model, 'etatcoll_finv', array('size' => '4', 'maxlength' => '4', 'class'=>"form-control width80px")); 
                        echo '&nbsp; ' . CHtml::button('Vider', array('onclick' => 'viderText("#Abonnement_etatcoll_finv",$(this))', 'class' => "btn btn-default btn-sm"));
                        echo $form->error($model, 'etatcoll_finv'); ?> | 
                        Numéro <?php echo $form->textField($model, 'etatcoll_finf', array('size' => '4', 'maxlength' => '4', 'class'=>"form-control width80px")); 
                        echo '&nbsp; ' . CHtml::button('Vider', array('onclick' => 'viderText("#Abonnement_etatcoll_finf",$(this))', 'class' => "btn btn-default btn-sm"));
                        echo $form->error($model, 'etatcoll_finf'); ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'plateforme'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Plateforme::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'editeur'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Editeur::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'histabo'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Histabo::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr>  
                <tr>
                    <th><?php echo $form->labelEx($model, 'statutabo'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Statutabo::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'localisation'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Localisation::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr> 
                <tr>
                    <th><?php echo $form->labelEx($model, 'gestion'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Gestion::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'format'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Format::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr>   
                <tr>
                    <th><?php echo $form->labelEx($model, 'support'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Support::model(),
        'frm_classname' => get_class($model)));
    ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'licence'); ?></th>
                    <td colspan="3">
    <?php
    $this->widget('SelectWidget', array(
        'model' => Licence::model(),
        'frm_classname' => get_class($model),
        'showNull' => true));
    ?>
                    </td>
                </tr>  

                <tr>
                    <th><?php echo $form->labelEx($model, 'cote'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'cote'); ?></td>
                </tr> 
                <tr>
                    <th><?php echo $form->labelEx($model, 'editeur_sujet'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'editeur_sujet'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'acces_user'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'acces_user'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'acces_pwd'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'acces_pwd'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'url_site'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'url_site'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'editeur_code'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'editeur_code'); ?></td>
                </tr>  
                <tr>
                    <th><?php echo $form->labelEx($model, 'commentaire_pro'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'commentaire_pro'); ?></td>
                </tr>
                <tr>
                    <th><?php echo $form->labelEx($model, 'commentaire_pub'); ?></th>
                    <td colspan="3" class="form-inline"><?php txtbtn($form,$model,'commentaire_pub'); ?></td>
                </tr> 
                <tr style="background-color : #E8F8EC;">
                    <th><?php echo CHtml::label("Action sur les champs textes (*) :", "add_text"); ?></th>
                    <td colspan="3">
    <?php echo CHtml::radioButtonList("add_text", true, array(true => 'Ajouter le texte à la suite des données existantes.', false => 'Remplacer le contenu par le nouveau texte.'), array('labelOptions' => array('style' => 'display:inline;width:150px;'), 'template' => "{input} {label}", 'separator' => '&nbsp;&nbsp;&nbsp;')); ?>
                    </td>
                </tr> 

                <tr>
                    <th colspan="4" style="vertical-align: middle; text-align: center;">
    <?php echo CHtml::submitButton('Suivant > ', array('class' => "btn btn-primary")); ?>
                    </th>

                </tr>
        </table>
        </div>
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
            if (in_array($key, $this->abolinks) && $value != "NULL") {
                $class = ucfirst($key);
                $link = " - '" . $class::model()->findByPk($value)->$key . "'";
            }
            echo "<li>" . CHtml::checkBox($key, true) . "<strong>$key</strong>";
            if (in_array($key, $this->textfields) && $add_text) {
                echo " <i>(ajout)</i>";
            } else {
                echo " <i>(remplacement)</i>";
            }
            if ($value == "NULL"){
                $value = "Le contenu du champ sera supprimé";
            }
            echo " : $value $link</li>";
        }
        echo "</ul>";


        echo "<p>" . CHtml::submitButton('Appliquer les modifications', array('class' => "btn btn-default btn-sm")) . "</p>";
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
            if (in_array($key, $this->abolinks) && $value != "NULL") {
                $class = ucfirst($key);
                $link = " - '" . $class::model()->findByPk($value)->$key . "'";
            }
            echo "<li><strong>$key</strong>";
            if (in_array($key, $this->textfields) && $add_text) {
                echo " <i>(ajout)</i>";
            } else {
                echo " <i>(remplacement)</i>";
            }
            if ($value == "NULL"){
                $value = "Le contenu du champ a été supprimé";
            }
            echo " : $value $link</li>";
        }
        echo "</ul>"; 
    
endif;