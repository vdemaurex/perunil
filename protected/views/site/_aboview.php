<?php

/**

 *
 * The followings are the available model relations:
 * @property Editeur $editeur0
 * @property Histabo $histabo0
 * @property Statutabo $statutabo0
 * @property Localisation $localisation0
 * @property Gestion $gestion0
 * @property Format $format0
 * @property Support $support0
 * @property Licence $licence0
 * @property Journal $perunil
 * @property Plateforme $plateforme
 */
//ob_start();
//$this->widget('AboUrlWidget', array('abo' => $abo, 'jrn' => $jrn));
//$url=ob_get_contents();
//ob_end_clean();


$fields = array(
    'abonnement_id',
    //array('name' => 'url_site', 'value' => $url, 'type' => 'raw', 'label' => "URL du périodique"),
    //'url_site',
    'package',
    array(
        'name' => 'url_site',
        'value' => CHtml::link($abo->url_site, $abo->url_site, array('target' => '_blank')), 'type' => 'raw', 'label' => "URL du périodique"
    ),
    'no_abo',
    'etatcoll',
    'cote',
    'editeur_code',
    'editeur_sujet',
    'commentaire_pub',
    @array('name' => 'plateforme', 'value' => $abo->plateforme0->plateforme, 'label' => 'Plateforme'),
    @array('name' => 'editeur', 'value' => $abo->editeur0->editeur, 'label' => 'Editeur'),
    @array('name' => 'statutabo', 'value' => $abo->statutabo0->statutabo, 'label' => 'Statut de l\'abonnement'),
    @array('name' => 'localisation', 'value' => $abo->localisation0->localisation, 'label' => 'Localisation'),
    @array('name' => 'format', 'value' => $abo->format0->format, 'label' => 'Format'),
    @array('name' => 'support', 'value' => $abo->support0->support, 'label' => 'Support'),
    @array('name' => 'licence', 'value' => $abo->licence0->licence, 'label' => 'Licence'),
);


// Ajout des détail spécifiques au support électornique
if ($abo->support == 1) {
    $fields = array_merge(
            $fields, array(
        array('name' => 'acces_elec_gratuit', 'label' => 'Accès électronique gratuit', 'type' => 'boolean'),
        array('name' => 'acces_elec_unil', 'label' => 'Accès depuis l\'UNIL', 'type' => 'boolean'),
        array('name' => 'acces_elec_chuv', 'label' => 'Accès depuis le CHUV', 'type' => 'boolean'),
        'embargo_mois',
    ));
}



// Ajout des détails réservés aux utilisateurs authentifiés
if (!Yii::app()->user->isGuest) {

    $fields[] = array('name' => 'acces_user', 'label' => "Nom d'utilisateur");
    $fields[] = array('name' => 'acces_pwd', 'label' => "Mot de passe");
    $fields[] = 'commentaire_pro';
    $fields[] = array('name' => 'titreexclu', 'label' => 'Titre exclu', 'type' => 'boolean');
    
    if (!empty($abo->perunilid_old)){
    $fields[] = array(
            'name' => 'url_site',
            'value' => CHtml::link(
                    $abo->perunilid_old ." (ouvrir dans Perunil 1)", 
                    "http://www2.unil.ch/perunil/detail.php?id=" . $abo->perunilid_old, 
                    array('target' => '_blank')), 
                    'type' => 'raw', 
                    'label' => "Ancien perunilid (version 1)"
        );
    }
    
    $fields[] = array('name' => 'modification', 'label' => 'Dernière modification', 'value' => $abo->fieldToString('modification'));
    $fields[] = array('name' => 'creation', 'label' => 'Date de création', 'value' => $abo->fieldToString('creation'));
}


// Suppression des champs qui ne contiennent aucune information
foreach ($fields as $key => $field) {
    if (is_array($field)) {
        $name = $field['name'];
    } else {
        $name = $field;
    }
    if (is_string($abo->$name))
        if (trim($abo->$name) == "")
            unset($fields[$key]);
    if ($abo->$name == NULL) {
        unset($fields[$key]);
    }
}

$this->widget('zii.widgets.CDetailView', array(
    'data' => $abo,
    'attributes' => $fields,
    'formatter' => new FrFormatter(),
));
?>
