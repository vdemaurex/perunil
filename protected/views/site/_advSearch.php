<?php
$fields = array(
    "titre" => "Titre",
    "editeur" => "Editeur",
    "issn" => "ISSN",);

$operators = array("AND" => "ET",
    "OR" => "OU",
    "NOT" => "MAIS PAS",);

$last = Yii::app()->session['search']->adv_query_tab;
?>

<?php
echo CHtml::beginForm($this->createUrl('site/advSearchResults'), 'get', array('role' => "form"));
// NB: Comme les opérateurs servent à joindre la ligne courant à la précédente, ils sont
// décalés d'une ligne.
?>
<?php echo CHtml::hiddenField("advsearch", "advsearch"); ?>
<?php echo CHtml::hiddenField("C1[op]", "AND"); ?>


<div class="panel panel-default" style="width: 705px; margin:auto;">
  <div class="panel-heading">Votre recherche</div>
  <div class="panel-body">

<table class="advsearch">
    <tr>
        <td ><?php echo CHtml::dropDownList("C1[search_type]", isset($last) && isset($last['C1'])  ? $last['C1']['search_type'] : "titre", $fields, array('class' => "form-control")); ?></td>
        <td ><?php echo CHtml::textField(
                'C1[text]', 
                isset($last) && isset($last['C1'])  ? $last['C1']['text'] : "", 
                array('size' => 40, 'maxlength' => 150, 'class' => "form-control advsearchfield")
                ); ?></td>
        <td ><?php echo CHtml::dropDownList("C2[op]", isset($last) && isset($last['C1'])  ? $last['C2']['op'] : "AND", $operators, array('class' => "form-control")); ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::dropDownList("C2[search_type]", isset($last) && isset($last['C2']) ? $last['C2']['search_type'] : "editeur", $fields, array('class' => "form-control")); ?></td>
        <td><?php echo CHtml::textField(
                'C2[text]', 
                isset($last) && isset($last['C2']) ? $last['C2']['text'] : "", 
                array('size' => 40, 'maxlength' => 150, 'class' => "form-control advsearchfield")
                ); ?></td>
        <td><?php echo CHtml::dropDownList("C3[op]", isset($last) && isset($last['C3']) ? $last['C3']['op'] : "AND", $operators, array('class' => "form-control")); ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::dropDownList("C3[search_type]", isset($last) && isset($last['C3']) ? $last['C3']['search_type'] : "issn", $fields, array('class' => "form-control")); ?></td>
        <td><?php echo CHtml::textField(
                'C3[text]', 
                isset($last) && isset($last['C3']) ? $last['C3']['text'] : "", 
                array('size' => 40, 'maxlength' => 150, 'class' => "form-control advsearchfield")
                ); ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><strong>Format</strong></td>
        <td colspan =" 2">
            <input type="radio" name="support" value="0" <?= isset($last) && isset($last['support']) ? $last['support'] == '0' ? "checked" : ""  : "checked" ?>>tous &nbsp;
            <input type="radio" name="support" value="1" <?= isset($last) && isset($last['support']) ? $last['support'] == '1' ? "checked" : ""  : "" ?>>électroniques &nbsp;
            <input type="radio" name="support" value="2" <?= isset($last) && isset($last['support'])  ? $last['support'] == '2' ? "checked" : ""  : "" ?>> imprimés
        </td>
    </tr>
    <tr>
        <td><strong>Accès</strong></td>
        <td colspan =" 2">
            <input type="checkbox" value="1" name="accessunil" <?= isset($last) && isset($last['accessunil']) ? $last['accessunil'] == '1' ? "checked" : ""  : "checked" ?>> abonnements Unil et CHUV &nbsp;
            <input type="checkbox" value="1" name="openaccess" <?= isset($last) && isset($last['openaccess']) ? $last['openaccess'] == '1' ? "checked" : ""  : "checked" ?>> périodiques gratuits ou Open Access &nbsp;
        </td>
    </tr>
    <tr>
        <td><strong>Sujets</strong></td>
        <td colspan =" 2">
            <?php $this->widget('SelectWidget', array('model' => Sujet::model(), 'selected' => isset($last) && isset($last['sujet']) ? $last['sujet'] : 'all')); ?>
        </td>
    </tr>
</table>
  </div>
</div>
<br/>
<div class="panel panel-default" style="width: 705px; margin:auto;">
  <div class="panel-heading">Limiter la recherche à</div>
  <div class="panel-body">
<table class="advsearch">
    <tr>
        <td><strong>Plateforme</strong></td>
        <td><?php $this->widget(
                'SelectWidget', 
                array(
                    'model' => Plateforme::model(), 
                    'selected' => isset($last) && isset($last['plateforme']) ? $last['plateforme'] : 'all')
            ); ?></td>
    </tr>
    <tr>
        <td><strong>Abonnement / Licence</strong></td>
        <td><?php $this->widget(
                'SelectWidget', 
                array(
                    'model' => Licence::model(), 
                    'selected' => isset($last) && isset($last['licence']) ? $last['licence'] : 'all')
            ); ?>
        </td>
    </tr>
        <tr>
        <td><strong>Statut de l'abonnement</strong></td>
        <td><?php $this->widget('SelectWidget', array(
            'model' => Statutabo::model(), 
            'selected' => isset($last) && isset($last['statutabo']) ? $last['statutabo'] : 'all'
            )); ?>
        </td>
    </tr>
    <tr>
        <td><strong>Localisation</strong></td>
        <td><?php $this->widget('SelectWidget', array(
            'model' => Localisation::model(), 
            'selected' => isset($last) && isset($last['localisation']) ? $last['localisation'] : 'all'
            )); ?>
        </td>
    </tr>
</table>
  </div>
</div>
<div class="panel panel-default" style="width: 705px; margin:auto;">
  <div class="panel-body">
      <div style=" margin-left: 220px;">
        <?=CHtml::submitButton("Chercher", array('class' => "btn btn-primary"));?> &nbsp;
        <?=CHtml::button('Vider le formulaire', array(
                            'onclick' => 'js:document.location.href="'. CHtml::normalizeUrl(array('site/advclean')) .'"',
                            'class' => "btn btn-default"));?>
      </div>
  </div>
</div>
<?php echo CHtml::endForm(); ?>



