<?php
function r($controller, $name, $comparaisonterm = null, $replacmenttext = null){
    $print = false;
    if (isset($controller->last) && isset($controller->last[$name])){
        $print = true;
        if (isset($comparaisonterm)){
            $print = $controller->last[$name] == $comparaisonterm;
        }
    }
    if($print){
        if(isset($replacmenttext))
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

<h1>Recherche administrateur</h1>
<?php echo CHtml::beginForm($this->createUrl('admin/search'), 'get', array("id" => "adminsearchform"));?>
<table class="detail-view">
    <tbody>
        <tr class="odd">
            <td></td>
            <td><?=  CHtml::submitButton("Chercher");?>
                &nbsp;&nbsp;<?=CHtml::resetButton("Vider le formulaire");?></td>
        </tr>
        <tr class="odd" style="background-color : #E8F8EC;">
            <td><b>Tous les champs de la table journal</b></td><td class="odd"><input type="text" value="<?=r($this,'all');?>" size="60" name="all"></td>
        </tr>
        <tr class="even">
            <td ><b>PerunilID</b></td><td class="odd"><select name="perunilidcrit1">
                    <option value="equal" <?=r($this,'perunilidcrit1',"equal" ,'selected');?>>=</option>
                    <option value="before"<?=r($this,'perunilidcrit1',"before",'selected');?>>&lt;</option>
                    <option value="after" <?=r($this,'perunilidcrit1',"after" ,'selected');?>>&gt;</option>
                </select>&nbsp;&nbsp;
                <input type="text" value="<?=r($this,'perunilid1');?>" size="10" name="perunilid1">
                &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="perunilidcrit2">
                    <option value="equal" <?=r($this,'perunilidcrit2',"equal" ,'selected');?>>=</option>
                    <option value="before"<?=r($this,'perunilidcrit2',"before",'selected');?>>&lt;</option>
                    <option value="after" <?=r($this,'perunilidcrit2',"after" ,'selected');?>>&gt;</option>
                </select>&nbsp;&nbsp;
                <input type="text" value="<?=r($this,'perunilid2');?>" size="10" name="perunilid2"></td>
        </tr>
 
        <tr class="odd">
            <td><b>Titre</b></td><td class="odd"><input type="text" value="<?=r($this,'titre');?>" size="60" name="titre"></td>
        </tr>
        <tr class="even">
            <td><b>Sous titre</b></td><td><input type="text" value="<?=r($this,'soustitre');?>" size="60" name="soustitre"></td>
        </tr>
        <tr class="odd">
            <td><b>Titre abregé</b></td><td class="odd"><input type="text" value="<?=r($this,'titreabrege');?>" size="60" name="titreabrege"></td>
        </tr>
        <tr class="even">
            <td><b>Variante de titre</b></td><td><input type="text" value="<?=r($this,'variantetitre');?>" size="60" name="variantetitre"></td>
        </tr>
        <tr class="odd">
            <td><b>Fait suite à</b></td><td class="odd"><input type="text" value="<?=r($this,'faitsuitea');?>" size="60" name="faitsuitea"></td>
        </tr>
        <tr class="even">
            <td>
                <b>Devient</b></td><td><input type="text" value="<?=r($this,'devient');?>" size="60" name="devient">
            </td>
        </tr>
        <tr class="odd">
            <td><b>Editeur</b></td>
            <td><input type="text" value="<?=r($this,'editeur_txt');?>" size="60" name="editeur_txt">
            </td>
        </tr>
        <tr class="odd">
            <td><b>Editeur</b>
            </td>
            <td>
                <?php $this->widget('SelectWidget', array('model' => Editeur::model(), 'selected' => r($this,'editeur'))); ?>
            </td></tr>
        <tr class="even">
            <td><b>Code de la revue chez l'éditeur</b></td>
            <td><input type="text" value="<?=r($this,'codeediteur');?>" size="60" name="codeediteur"></td>
        </tr>
        <tr class="odd"><td class="odd"><b>Publication Unil</b></td><td class="odd">
                <input type="radio" value="1" name="publiunil" <?=r($this,'publiunil',"1" ,'checked');?>> Oui  |  
                <input type="radio" value="0" name="publiunil" <?=r($this,'publiunil',"0" ,'checked');?>> Non
            </td></tr>
        <tr class="even"><td><b>Open Access</b></td><td>
                <input type="radio" value="1" name="openaccess" <?=r($this,'openaccess',"1" ,'checked');?>> Oui  |  
                <input type="radio" value="0" name="openaccess" <?=r($this,'openaccess',"0" ,'checked');?>> Non
            </td>
        </tr>
        <tr class="odd">
            <td class="odd"><b>ISSN-L</b></td>
            <td class="odd">
                <input type="text" value="<?=r($this,'issnl');?>" size="10" name="issnl">
                |  <b>ISSNs </b><input type="text" value="<?=r($this,'issn');?>" size="30" name="issn">
            </td>
        </tr>
        <tr class="even">
            <td><b>RERO ID</b></td>
            <td><input type="text" value="<?=r($this,'reroid');?>" size="20" name="reroid">
                |  <b>NLM ID </b><input type="text" value="<?=r($this,'nlmid');?>" size="20" name="nlmid">
            </td>
        </tr>
        <tr class="odd">
            <td><b>CODEN</b></td>
            <td class="odd"><input type="text" value="<?=r($this,'coden');?>" size="10" name="coden">
                |  <b>DOI </b><input type="text" value="<?=r($this,'doi');?>" size="15" name="doi">
                |  <b>URN </b><input type="text" value="<?=r($this,'urn');?>" size="10" name="urn">
            </td>
        </tr>
        <tr class="even">
            <td><b>URL</b></td>
            <td><input type="text" value="<?=r($this,'url');?>" size="60" name="url"></td>
        </tr>
        <tr class="odd">
            <td><b>RSS</b></td>
            <td class="odd"><input type="text" value="<?=r($this,'rss');?>" size="60" name="rss"></td>
        </tr>
        <tr class="even">
            <td><b>Username</b></td>
            <td><input type="text" value="<?=r($this,'user');?>" size="20" name="user">
                |  <b>Password </b><input type="text" value="<?=r($this,'pwd');?>" size="20" name="pwd"></td>
        </tr>
        <tr class="odd">
            <td><b>Abonnement / Licence</b></td>
            <td class="odd">
                <?php $this->widget('SelectWidget', array('model' => Licence::model(), 'selected' => r($this,'licence'))); ?>
            </td>
        </tr>
        <tr class="even">
            <td><b>Statut de l'abonnement</b></td>
            <td>
                <?php $this->widget('SelectWidget', array('model' => Statutabo::model(), 'selected' => r($this,'statutabo'))); ?>
            </td>
        </tr>
        <tr class="odd">
            <td><b>Titre exclu de la licence</b></td>
            <td><input type="radio" value="1" name="titreexclu" <?=r($this,'titreexclu',"1" ,'checked');?>> Oui  |  
                <input type="radio" value="0" name="titreexclu" <?=r($this,'titreexclu',"0" ,'checked');?>> Non
            </td>
        </tr>
        <tr class="odd">
            <td><b>Parution terminée</b></td>
            <td><input type="radio" value="1" name="parution_terminee" <?=r($this,'parution_terminee',"1" ,'checked');?>> Oui  |  
                <input type="radio" value="0" name="parution_terminee" <?=r($this,'parution_terminee',"0" ,'checked');?>> Non
            </td>
        </tr>
        
        <tr class="even">
            <td><b>Core collection</b></td>
            <td><input type="radio" value="1" name="corecollection" <?=r($this,'corecollection',"1" ,'checked');?>> Oui  |  
                <input type="radio" value="0" name="corecollection" <?=r($this,'corecollection',"0" ,'checked');?>> Non
            </td>
        </tr>
        <tr class="odd">
            <td><b>Plateforme</b></td>
            <td>
                <?php $this->widget('SelectWidget', array('model' => Plateforme::model(), 'selected' => r($this,'plateforme'))); ?>
            </td>
        </tr>
        <tr class="even">
            <td><b>Gestion</b></td>
            <td>
                <?php $this->widget('SelectWidget', array('model' => Gestion::model(), 'selected' => r($this,'gestion'))); ?>
            </td>
        </tr>
        <tr class="odd">
            <td><b>Historique de l'abonnement</b></td>
            <td class="odd">
                <?php $this->widget('SelectWidget', array('model' => Histabo::model(), 'selected' => r($this,'histabo'))); ?>
            </td>
        </tr>
        <tr class="even">
            <td><b>Support</b></td>
            <td>
                <?php $this->widget('SelectWidget', array('model' => Support::model(), 'selected' => r($this,'support'))); ?>
            </td></tr>
        <tr class="odd">
            <td><b>Format</b></td>
            <td class="odd">
                <?php $this->widget('SelectWidget', array('model' => Format::model(), 'selected' => r($this,'format'))); ?>
            </td>
        </tr>
        <tr class="even">
            <td><b>Accès électronique</b></td>
            <td>
                <input type="checkbox" value="1" name="acces_elec_unil" <?=r($this,'acces_elec_unil',"1" ,'checked');?>> UNIL
                |  <input type="checkbox" value="1" name="acces_elec_chuv" <?=r($this,'acces_elec_chuv',"1" ,'checked');?>> CHUV
                |  <input type="checkbox" value="1" name="acces_elec_gratuit" <?=r($this,'acces_elec_gratuit',"1" ,'checked');?>> Gratuit
            </td>
        </tr>
        <tr class="odd">
            <td><b>Nom du package</b></td>
            <td><input type="text" value="<?=r($this,'package');?>" size="60" name="package"></td>
        </tr>
        <tr class="even">
            <td><b>No d'abonnement</b></td>
            <td><input type="text" value="<?=r($this,'no_abo');?>" size="60" name="no_abo"></td>
        </tr>
        <tr class="odd"><td><b>Etat de collection</b></td><td><input type="text" value="<?=r($this,'etatcoll');?>" size="60" name="etatcoll"></td></tr>
        <tr class="even">
            <td><b>Embargo</b></td>
            <td><select name="embargocrit">
                    <option value="equal" <?=r($this,'embargocrit',"equal" ,'selected');?>>=</option>
                    <option value="before"<?=r($this,'embargocrit',"before",'selected');?>>&lt;</option>
                    <option value="after" <?=r($this,'embargocrit',"after" ,'selected');?>>&gt;</option>
                </select>&nbsp;&nbsp;
                <input type="text" value="<?=r($this,'embargo');?>" size="10" name="embargo"> (nombre de mois)
            </td>
        </tr>
        <tr class="odd">
            <td><b>Début de la collection</b></td>
            <td>Année <input type="text" value="<?=r($this,'etatcolldeba');?>" size="5" name="etatcolldeba">
                | Volume <input type="text" value="<?=r($this,'etatcolldebv');?>" size="5" name="etatcolldebv">
                | Numéro <input type="text" value="<?=r($this,'etatcolldebf');?>" size="5" name="etatcolldebf">
            </td>
        </tr>
        <tr class="even">
            <td><b>Fin de la collection</b></td>
            <td>Année <input type="text" value="<?=r($this,'etatcollfina');?>" size="5" name="etatcollfina">
                | Volume <input type="text" value="<?=r($this,'etatcollfinv');?>" size="5" name="etatcollfinv">
                | Numéro <input type="text" value="<?=r($this,'etatcollfinf');?>" size="5" name="etatcollfinf">
            </td>
        </tr>
        <tr class="odd">
            <td><b>Localisation</b></td>
            <td>
                <?php $this->widget('SelectWidget', array('model' => Localisation::model(), 'selected' => r($this,'localisation'))); ?>
            </td>
        </tr>
        <tr class="even">
            <td><b>Cote (papier)</b></td>
            <td><input type="text" value="<?=r($this,'cote');?>" size="60" name="cote"></td>
        </tr>
        <tr class="odd">
            <td><b>Commentaire professionnel</b></td>
            <td><input type="text" value="<?=r($this,'commentairepro');?>" size="60" name="commentairepro"></td>
        </tr>
        <tr class="even">
            <td><b>Commentaire publique</b></td>
            <td><input type="text" value="<?=r($this,'commentairepub');?>" size="60" name="commentairepub"></td>
        </tr>

        <tr class="even">
            <td><b>Thème</b></td>
            <td>
                <?php $this->widget('SelectWidget', array('model' => Sujet::model(), 'selected' => r($this,'sujet'))); ?>
            </td>
        </tr>
        <tr class="odd">
            <td><b>Sujets importés de FM</b></td>
            <td><input type="text" value="<?=r($this,'sujetsfm');?>" size="60" name="sujetsfm"></td>
        </tr>
        <tr class="even">
            <td><b>No de fiche sur FM</b></td>
            <td><input type="text" value="<?=r($this,'fmid');?>" size="60" name="fmid"></td>
        </tr>
        
        <tr class="odd">
            <td><b>Signature de création</b></td>
            <td>
                <?php
                $this->widget('SelectWidget', array(
                    'model' => Utilisateur::model(),
                    'select_name' => "signaturecreation",
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
                    'select_name' => "signaturemodification",
                    'column' => "pseudo",
                    'selected' => r($this, 'signaturemodification')));
                ?>
            </td>
        </tr>
        <tr class="odd">
            <td><b>Date de création</b></td>
            <td><select name="datecreationcrit1">
                    <option value="after"  <?=r($this,'datecreationcrit1',"after" ,'selected');?>>&gt;</option>        
                    <option value="before" <?=r($this,'datecreationcrit1',"before" ,'selected');?>>&lt;</option>
                   /<option value="equal"  <?=r($this,'datecreationcrit1',"equal" ,'selected');?>>=</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker1" value="<?=r($this,'datecreation1');?>" size="10" name="datecreation1">
                &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="datecreationcrit2">
                    <option value="before" <?=r($this,'datecreationcrit2',"before" ,'selected');?>>&lt;</option>
                    <option value="after"  <?=r($this,'datecreationcrit2',"after" ,'selected');?>>&gt;</option>
                    <option value="equal"  <?=r($this,'datecreationcrit2',"equal" ,'selected');?>>=</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker2" value="<?=r($this,'datecreation2');?>" size="10" name="datecreation2"></td>
        </tr>
        <tr class="even">
            <td><b>Date de modification</b></td>
            <td><select name="datemodifcrit1">
                    <option value="after"  <?=r($this,'datemodifcrit1',"after" ,'selected');?>>&gt;</option>
                    <option value="before" <?=r($this,'datemodifcrit1',"before" ,'selected');?>>&lt;</option>
                    <option value="equal"  <?=r($this,'datemodifcrit1',"equal" ,'selected');?>>=</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker3" value="<?=r($this,'datemodif1');?>" size="10" name="datemodif1">
                &nbsp;&nbsp;Et&nbsp;&nbsp;<select name="datemodifcrit2">
                    <option value="before" <?=r($this,'datemodifcrit2',"before" ,'selected');?>>&lt;</option>
                    <option value="after"  <?=r($this,'datemodifcrit2',"after" ,'selected');?>>&gt;</option>
                    <option value="equal"  <?=r($this,'datemodifcrit2',"equal" ,'selected');?>>=</option>
                </select>&nbsp;&nbsp;
                <input type="text" id="datepicker4" value="<?=r($this,'datemodif2');?>" size="10" name="datemodif2"></td>
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
            <td></td><td><?=  CHtml::submitButton("Chercher");?>
                &nbsp;&nbsp;<?=CHtml::resetButton("Vider le formulaire");?></td>
        </tr>
    </tbody>
</table>
<?php echo CHtml::endForm();?>
