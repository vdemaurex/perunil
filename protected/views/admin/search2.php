<?php
$this->breadcrumbs = array(
    'Admin' => array('/admin'),
    'Search',
);

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
    jQuery(function($) {
        $.datepicker.regional['fr-CH'] = {
            closeText: 'Fermer',
            prevText: '&#x3C;Préc',
            nextText: 'Suiv&#x3E;',
            currentText: 'Courant',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
                'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
            weekHeader: 'Sm',
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['fr-CH']);
    });

    $(function() {
        $("#datepicker1").datepicker($.datepicker.regional[ "fr-CH" ]);
        $("#datepicker2").datepicker($.datepicker.regional[ "fr-CH" ]);
        $("#datepicker3").datepicker($.datepicker.regional[ "fr-CH" ]);
        $("#datepicker4").datepicker($.datepicker.regional[ "fr-CH" ]);
    });

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
        <?php if (!empty($this->last['editeur'])) :?>
        $("#editeurSelect").select2("val", "<?php echo $this->last['editeur'];?>");
        <?php    endif;?>
    });

</script>
<style>
    .form-control {
        width: auto;
    }

    #adminformtable  > tbody > tr > td{
        padding-top: 3px;
        padding-bottom: 3px;
    }
</style>

<h1>Recherche admin</h1>
<?php echo CHtml::beginForm($this->createUrl('admin/searchResults'), 'get', array("id" => "adminsearchform")); ?>
<div class="panel panel-default" style="width: 95%; margin:auto;">
    <table id="adminformtable" class="table table-striped">
        <tbody>
            <tr>
                <td></td>
                <td><?= CHtml::submitButton("Chercher", array('class' => "btn btn-primary btn-ms")); ?>
                    &nbsp;&nbsp;
                    <?php
                    echo CHtml::htmlButton('Nouvelle recherche', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/searchclean') . '"',
                        'class' => "btn btn-default  btn-ms"));
                    ?></td>
            </tr>
            <tr style="background-color : #E8F8EC;">
                <td><b>Tous les champs de la table journal</b></td><td ><input type="text" value="<?= r($this, 'all'); ?>" size="60" name="all" class="form-control"></td>
            </tr>
            <tr>
                <td ><b>PerunilID</b></td>
                <td class="form-inline"><select name="perunilidcrit1"  class="form-control">
                        <option value="equal" <?= r($this, 'perunilidcrit1', "equal", 'selected'); ?>>=</option>
                        <option value="before"<?= r($this, 'perunilidcrit1', "before", 'selected'); ?>>&lt;</option>
                        <option value="after" <?= r($this, 'perunilidcrit1', "after", 'selected'); ?>>&gt;</option>
                    </select>&nbsp;&nbsp;
                    <input type="text" value="<?= r($this, 'perunilid1'); ?>" size="10" name="perunilid1"  class="form-control">
                    &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="perunilidcrit2"  class="form-control">
                        <option value="equal" <?= r($this, 'perunilidcrit2', "equal", 'selected'); ?>>=</option>
                        <option value="before"<?= r($this, 'perunilidcrit2', "before", 'selected'); ?>>&lt;</option>
                        <option value="after" <?= r($this, 'perunilidcrit2', "after", 'selected'); ?>>&gt;</option>
                    </select>&nbsp;&nbsp;
                    <input type="text" value="<?= r($this, 'perunilid2'); ?>" size="10" name="perunilid2"  class="form-control"></td>
            </tr>

            <tr>
                <td><b>Titre</b></td><td ><input type="text" value="<?= r($this, 'titre'); ?>" size="60" name="titre" class="form-control"></td>
            </tr>
            <tr>
                <td><b>Sous titre</b></td><td><input type="text" value="<?= r($this, 'soustitre'); ?>" size="60" name="soustitre"  class="form-control"></td>
            </tr>
            <tr >
                <td><b>Titre abregé</b></td><td ><input type="text" value="<?= r($this, 'titreabrege'); ?>" size="60" name="titreabrege" class="form-control"></td>
            </tr>
            <tr>
                <td><b>Variante de titre</b></td><td><input type="text" value="<?= r($this, 'variantetitre'); ?>" size="60" name="variantetitre" class="form-control"></td>
            </tr>
            <tr >
                <td><b>Fait suite à</b></td><td ><input type="text" value="<?= r($this, 'faitsuitea'); ?>" size="60" name="faitsuitea" class="form-control"></td>
            </tr>
            <tr>
                <td>
                    <b>Devient</b></td><td><input type="text" value="<?= r($this, 'devient'); ?>" size="60" name="devient" class="form-control">
                </td>
            </tr>
            <tr >
                <td><b>Editeur</b></td>
                <td><input type="text" value="<?= r($this, 'editeur_txt'); ?>" size="60" name="editeur_txt" class="form-control">
                </td>
            </tr>
            <tr >
                <td><b>Editeur</b>
                </td>
                <td>
                    
                    <input type="hidden" data-placeholder="Sélectionnez un éditeur.." class="input-xlarge" id="editeurSelect" name="editeur" >
                    <?php //$this->widget('SelectWidget', array('select_id' => 'editeurSelect', 'model' => Editeur::model(), 'selected' => r($this, 'editeur'))); ?>
                </td></tr>
            <tr>
                <td><b>Code de la revue chez l'éditeur</b></td>
                <td><input type="text" value="<?= r($this, 'codeediteur'); ?>" size="60" name="codeediteur" class="form-control"></td>
            </tr>
            <tr ><td ><b>Publication Unil</b></td><td >
                    <input type="radio" value="1" name="publiunil" <?= r($this, 'publiunil', "1", 'checked'); ?>> Oui  |  
                    <input type="radio" value="0" name="publiunil" <?= r($this, 'publiunil', "0", 'checked'); ?>> Non  |  
                    <input type="radio" value="" name="publiunil" <?= r($this, 'publiunil', "", 'checked'); ?>> Ignorer
                </td></tr>
            <tr><td><b>Open Access</b></td><td>
                    <input type="radio" value="1" name="openaccess" <?= r($this, 'openaccess', "1", 'checked'); ?>> Oui  |  
                    <input type="radio" value="0" name="openaccess" <?= r($this, 'openaccess', "0", 'checked'); ?>> Non  |  
                    <input type="radio" value="" name="openaccess" <?= r($this, 'openaccess', "", 'checked'); ?>> Ignorer
                </td>
            </tr>
            <tr >
                <td><b>ISSN-L</b></td>
                <td class="form-inline">
                    <input type="text" value="<?= r($this, 'issnl'); ?>" size="10" name="issnl" class="form-control">
                    |  <b>ISSNs </b><input type="text" value="<?= r($this, 'issn'); ?>" size="30" name="issn" class="form-control">
                </td>
            </tr>
            <tr>
                <td><b>RERO ID</b></td>
                <td class="form-inline"><input type="text" value="<?= r($this, 'reroid'); ?>" size="20" name="reroid" class="form-control">
                    |  <b>NLM ID </b><input type="text" value="<?= r($this, 'nlmid'); ?>" size="20" name="nlmid" class="form-control">
                </td>
            </tr>
            <tr>
                <td><b>CODEN</b></td>
                <td class="form-inline"><input type="text" value="<?= r($this, 'coden'); ?>" size="10" name="coden" class="form-control">
                    |  <b>DOI </b><input type="text" value="<?= r($this, 'doi'); ?>" size="15" name="doi" class="form-control">
                    |  <b>URN </b><input type="text" value="<?= r($this, 'urn'); ?>" size="10" name="urn" class="form-control">
                </td>
            </tr>
            <tr>
                <td><b>URL</b></td>
                <td><input type="text" value="<?= r($this, 'url'); ?>" size="60" name="url" class="form-control"></td>
            </tr>
            <tr>
                <td><b>RSS</b></td>
                <td ><input type="text" value="<?= r($this, 'rss'); ?>" size="60" name="rss" class="form-control"></td>
            </tr>
            <tr>
                <td><b>Username</b></td>
                <td class="form-inline"><input type="text" value="<?= r($this, 'user'); ?>" size="20" name="user" class="form-control">
                    |  <b>Password </b><input type="text" value="<?= r($this, 'pwd'); ?>" size="20" name="pwd" class="form-control"></td>
            </tr>
            <tr>
                <td><b>Abonnement / Licence</b></td>
                <td >
                    <?php $this->widget('SelectWidget', array('model' => Licence::model(), 'selected' => r($this, 'licence'))); ?>
                </td>
            </tr>
            <tr>
                <td><b>Statut de l'abonnement</b></td>
                <td>
                    <?php $this->widget('SelectWidget', array('model' => Statutabo::model(), 'selected' => r($this, 'statutabo'))); ?>
                </td>
            </tr>
            <tr>
                <td><b>Titre exclu de la licence</b></td>
                <td><input type="radio" value="1" name="titreexclu" <?= r($this, 'titreexclu', "1", 'checked'); ?>> Oui  |  
                    <input type="radio" value="0" name="titreexclu" <?= r($this, 'titreexclu', "0", 'checked'); ?>> Non  |  
                    <input type="radio" value="" name="titreexclu" <?= r($this, 'titreexclu', "", 'checked'); ?>> Ignorer
                </td>
            </tr>
            <tr >
                <td><b>Parution terminée</b></td>
                <td><input type="radio" value="1" name="parution_terminee" <?= r($this, 'parution_terminee', "1", 'checked'); ?>> Oui  |  
                    <input type="radio" value="0" name="parution_terminee" <?= r($this, 'parution_terminee', "0", 'checked'); ?>> Non  |  
                    <input type="radio" value="" name="parution_terminee" <?= r($this, 'parution_terminee', "", 'checked'); ?>> Ignorer
                </td>
            </tr>

            <tr>
                <td><b>Core collection BiUM</b></td>
                <td><input type="radio" value="VRAI" name="corecollection" <?= r($this, 'corecollection', "VRAI", 'checked'); ?>> Oui  |  
                    <input type="radio" value="FAUX" name="corecollection" <?= r($this, 'corecollection', "FAUX", 'checked'); ?>> Non  |  
                    <input type="radio" value="IGNORER" name="corecollection" <?= r($this, 'corecollection', "IGNORER", 'checked'); ?>> Ignorer
                </td>
            </tr>
            <tr >
                <td><b>Plateforme</b></td>
                <td>
                    <?php $this->widget('SelectWidget', array('model' => Plateforme::model(), 'selected' => r($this, 'plateforme'))); ?>
                </td>
            </tr>
            <tr>
                <td><b>Gestion</b></td>
                <td>
                    <?php $this->widget('SelectWidget', array('model' => Gestion::model(), 'selected' => r($this, 'gestion'))); ?>
                </td>
            </tr>
            <tr >
                <td><b>Historique de l'abonnement</b></td>
                <td >
                    <?php $this->widget('SelectWidget', array('model' => Histabo::model(), 'selected' => r($this, 'histabo'))); ?>
                </td>
            </tr>
            <tr>
                <td><b>Support</b></td>
                <td>
                    <?php $this->widget('SelectWidget', array('model' => Support::model(), 'selected' => r($this, 'support'))); ?>
                </td></tr>
            <tr >
                <td><b>Format</b></td>
                <td >
                    <?php $this->widget('SelectWidget', array('model' => Format::model(), 'selected' => r($this, 'format'))); ?>
                </td>
            </tr>
            <tr>
                <td><b>Accès électronique</b></td>
                <td>
                    <input type="checkbox" value="1" name="acces_elec_unil" <?= r($this, 'acces_elec_unil', "1", 'checked'); ?>> UNIL
                    |  <input type="checkbox" value="1" name="acces_elec_chuv" <?= r($this, 'acces_elec_chuv', "1", 'checked'); ?>> CHUV
                    |  <input type="checkbox" value="1" name="acces_elec_gratuit" <?= r($this, 'acces_elec_gratuit', "1", 'checked'); ?>> Gratuit
                </td>
            </tr>
            <tr >
                <td><b>Nom du package</b></td>
                <td><input type="text" value="<?= r($this, 'package'); ?>" size="60" name="package" class="form-control"></td>
            </tr>
            <tr>
                <td><b>No d'abonnement</b></td>
                <td><input type="text" value="<?= r($this, 'no_abo'); ?>" size="60" name="no_abo" class="form-control"></td>
            </tr>
            <tr ><td><b>Etat de collection</b></td><td><input type="text" value="<?= r($this, 'etatcoll'); ?>" size="60" name="etatcoll" class="form-control"></td></tr>
            <tr>
                <td><b>Embargo</b></td>
                <td class="form-inline"><select name="embargocrit"  class="form-control">
                        <option value="equal" <?= r($this, 'embargocrit', "equal", 'selected'); ?>>=</option>
                        <option value="before"<?= r($this, 'embargocrit', "before", 'selected'); ?>>&lt;</option>
                        <option value="after" <?= r($this, 'embargocrit', "after", 'selected'); ?>>&gt;</option>
                    </select>&nbsp;&nbsp;
                    <input type="text" value="<?= r($this, 'embargo'); ?>" size="10" name="embargo" class="form-control"> (nombre de mois)
                </td>
            </tr>
            <tr >
                <td><b>Début de la collection</b></td>
                <td class="form-inline">Année <input type="text" value="<?= r($this, 'etatcolldeba'); ?>" size="5" name="etatcolldeba" class="form-control">
                    | Volume <input type="text" value="<?= r($this, 'etatcolldebv'); ?>" size="5" name="etatcolldebv" class="form-control">
                    | Numéro <input type="text" value="<?= r($this, 'etatcolldebf'); ?>" size="5" name="etatcolldebf" class="form-control">
                </td>
            </tr>
            <tr>
                <td><b>Fin de la collection</b></td>
                <td class="form-inline">Année <input type="text" value="<?= r($this, 'etatcollfina'); ?>" size="5" name="etatcollfina" class="form-control">
                    | Volume <input type="text" value="<?= r($this, 'etatcollfinv'); ?>" size="5" name="etatcollfinv" class="form-control">
                    | Numéro <input type="text" value="<?= r($this, 'etatcollfinf'); ?>" size="5" name="etatcollfinf" class="form-control">
                </td>
            </tr>
            <tr >
                <td><b>Localisation</b></td>
                <td>
                    <?php $this->widget('SelectWidget', array('model' => Localisation::model(), 'selected' => r($this, 'localisation'))); ?>
                </td>
            </tr>
            <tr>
                <td><b>Cote (papier)</b></td>
                <td><input type="text" value="<?= r($this, 'cote'); ?>" size="60" name="cote" class="form-control"></td>
            </tr>
            <tr >
                <td><b>Commentaire professionnel</b></td>
                <td><input type="text" value="<?= r($this, 'commentairepro'); ?>" size="60" name="commentairepro" class="form-control"></td>
            </tr>
            <tr>
                <td><b>Commentaire publique</b></td>
                <td><input type="text" value="<?= r($this, 'commentairepub'); ?>" size="60" name="commentairepub" class="form-control"></td>
            </tr>

            <tr>
                <td><b>Thème</b></td>
                <td>
                    <?php $this->widget('SelectWidget', array('model' => Sujet::model(), 'selected' => r($this, 'sujet'))); ?>
                </td>
            </tr>
            <tr >
                <td><b>Sujets importés de FM</b></td>
                <td><input type="text" value="<?= r($this, 'sujetsfm'); ?>" size="60" name="sujetsfm" class="form-control"></td>
            </tr>
            <tr>
                <td><b>No de fiche sur FM</b></td>
                <td><input type="text" value="<?= r($this, 'fmid'); ?>" size="60" name="fmid" class="form-control"></td>
            </tr>

            <tr >
                <td><b>Création</b></td>
                <td class="form-inline">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Utilisateur::model(),
                        'select_name' => "signaturecreation",
                        'column' => "pseudo",
                        'selected' => r($this, 'signaturecreation')));
                    ?>
                 <select name="datecreationcrit1"  class="form-control">
                        <option value="after"  <?= r($this, 'datecreationcrit1', "after", 'selected'); ?>>&gt;</option>        
                        <option value="before" <?= r($this, 'datecreationcrit1', "before", 'selected'); ?>>&lt;</option>
                        /<option value="equal"  <?= r($this, 'datecreationcrit1', "equal", 'selected'); ?>>=</option>
                    </select>&nbsp;&nbsp;
                    <input type="text" id="datepicker1" value="<?= r($this, 'datecreation1'); ?>" size="10" name="datecreation1" class="form-control">
                    &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="datecreationcrit2"  class="form-control">
                        <option value="before" <?= r($this, 'datecreationcrit2', "before", 'selected'); ?>>&lt;</option>
                        <option value="after"  <?= r($this, 'datecreationcrit2', "after", 'selected'); ?>>&gt;</option>
                        <option value="equal"  <?= r($this, 'datecreationcrit2', "equal", 'selected'); ?>>=</option>
                    </select>&nbsp;&nbsp;
                    <input type="text" id="datepicker2" value="<?= r($this, 'datecreation2'); ?>" size="10" name="datecreation2" class="form-control"></td>
            </tr>
            <tr>
                <td><b>Modification</b></td>
                <td class="form-inline">
                    <?php
                    $this->widget('SelectWidget', array(
                        'model' => Utilisateur::model(),
                        'select_name' => "signaturemodification",
                        'column' => "pseudo",
                        'selected' => r($this, 'signaturemodification')));
                    ?>
                 <select name="datemodifcrit1"  class="form-control">
                        <option value="after"  <?= r($this, 'datemodifcrit1', "after", 'selected'); ?>>&gt;</option>
                        <option value="before" <?= r($this, 'datemodifcrit1', "before", 'selected'); ?>>&lt;</option>
                        <option value="equal"  <?= r($this, 'datemodifcrit1', "equal", 'selected'); ?>>=</option>
                    </select>&nbsp;&nbsp;
                    <input type="text" id="datepicker3" value="<?= r($this, 'datemodif1'); ?>" size="10" name="datemodif1" class="form-control">
                    &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="datemodifcrit2"  class="form-control">
                        <option value="before" <?= r($this, 'datemodifcrit2', "before", 'selected'); ?>>&lt;</option>
                        <option value="after"  <?= r($this, 'datemodifcrit2', "after", 'selected'); ?>>&gt;</option>
                        <option value="equal"  <?= r($this, 'datemodifcrit2', "equal", 'selected'); ?>>=</option>
                    </select>&nbsp;&nbsp;
                    <input type="text" id="datepicker4" value="<?= r($this, 'datemodif2'); ?>" size="10" name="datemodif2" class="form-control">
                </td>
            </tr>
            <tr >
                <td><b>Historique de modifications</b></td>
                <td><input type="text" value="" size="60" name="historique" class="form-control"></td>
            </tr>

            <tr >
                <td></td><td><?= CHtml::submitButton("Chercher", array('class' => "btn btn-primary")); ?>
                    &nbsp;&nbsp;<?php
                    echo CHtml::htmlButton('Nouvelle recherche', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl('admin/searchclean') . '"',
                        'class' => "btn btn-default  btn-ms"));
                    ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php echo CHtml::endForm(); ?>

