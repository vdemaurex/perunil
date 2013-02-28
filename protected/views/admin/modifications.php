<?php

function r($controller, $name, $comparaisonterm = null, $replacmenttext = null) {
    $print = false;
    if (isset($controller->last) && isset($controller->last[$name])) {
        $print = true;
        if (isset($comparaisonterm)) {
            $print = $controller->last[$name] == $comparaisonterm;
        }
    }
    if ($print) {
        if (isset($replacmenttext))
            return $replacmenttext;
        else
            return $controller->last[$name];
    }
}
?>
<script>
     
    /* Swiss-French initialisation for the jQuery UI date picker plugin. */
    /* Written Martin Voelkle (martin.voelkle@e-tc.ch). */
    jQuery(function($){
        $.datepicker.regional['fr-CH'] = {
            closeText: 'Fermer',
            prevText: '&#x3C;Préc',
            nextText: 'Suiv&#x3E;',
            currentText: 'Courant',
            monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
                'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
            monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
                'Jul','Aoû','Sep','Oct','Nov','Déc'],
            dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
            dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
            dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
            weekHeader: 'Sm',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['fr-CH']);
    });

    $(function() {
        $( "#datepicker1" ).datepicker( $.datepicker.regional[ "fr-CH" ] );
        $( "#datepicker2" ).datepicker( $.datepicker.regional[ "fr-CH" ] );
        $( "#datepicker3" ).datepicker( $.datepicker.regional[ "fr-CH" ] );
        $( "#datepicker4" ).datepicker( $.datepicker.regional[ "fr-CH" ] );
    });
</script>
<h1>Recherche et consultation des modifications</h1>
<?php echo CHtml::beginForm(); ?>
<table class="detail-view">
    <tbody>
        <tr class="odd">
            <td><b>Signature de création</b></td>
            <td>
                <?php
                $this->widget('SelectWidget', array(
                    'model' => Utilisateur::model(),
                    'column' => "pseudo",
                    'selected' => r($this, 'signaturecreation')));
                ?>
            </td>
        </tr>
        <tr class="even">
            <td><b>Signature de modification</b></td>
            <td>
                <?php
                $this->widget('SelectWidget', array(
                    'model' => Utilisateur::model(),
                    'column' => "pseudo",
                    'selected' => r($this, 'signaturecreation')));
                ?>
            </td>
        </tr>
        <tr class="odd">
            <td><b>Date de création</b></td>
            <td><select name="datecreationcrit1">
                    <option value="equal">=</option>
                    <option value="before">&lt;</option>
                    <option value="after">&gt;</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker1" value="" size="10" name="datecreation1">
                &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="datecreationcrit2">
                    <option value="equal">=</option>
                    <option value="before">&lt;</option>
                    <option value="after">&gt;</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker2" value="" size="10" name="datecreation2"></td>
        </tr>
        <tr class="even">
            <td><b>Date de modification</b></td>
            <td><select name="datemodifcrit1">
                    <option value="equal">=</option>
                    <option value="before">&lt;</option>
                    <option value="after">&gt;</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker3" value="" size="10" name="datemodif1">
                &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="datemodifcrit2">
                    <option value="equal">=</option>
                    <option value="before">&lt;</option>
                    <option value="after">&gt;</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker4" value="" size="10" name="datemodif2"></td>
        </tr>
        <tr class="odd">
            <td><b>Historique de modifications</b></td>
            <td><input type="text" value="" size="60" name="historique"></td>
        </tr>
        <tr class="even">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="odd">
            <td></td><td><input type="submit" value="Chercher">
                &nbsp;&nbsp;<input type="reset" value="Annuler"></td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::endForm(); ?>