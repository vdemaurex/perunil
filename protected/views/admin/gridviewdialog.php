<?php
$dataProvider = Yii::app()->session['search']->admin_dp;
//
// affichage par abonnements
//


        // Sélection des colonnes
$columns[] = 'perunilid';
$columns[] = array(
    'name' => 'journal_titre',
    'value' => '$data->jrn->titre',
    'header' => 'Journal',
);
/* array(
  'name' => 'jrn',
  'value' => '$data->jrn->titre',
  'header' => 'Journal',
  ), */
/* array(
  'name' => 'jrn',
  'value' => '$data->jrn->issn',
  'header' => 'ISSN',
  ), */
//'abonnement_id',
//'titreexclu',
$columns[] = 'package';
//'no_abo',
if ($this->last['support'] != 2)
    $columns[] = 'url_site';
/* array(
  'name' => 'acces_elec_gratuit',
  'type' => 'boolean',
  'header' => 'Gratuit',
  ), */
//'acces_elec_unil',
//'acces_elec_chuv',
//'embargo_mois',
//'acces_user',
//'acces_pwd',
$columns[] = 'etatcoll';
//'etatcoll_deba',
//'etatcoll_debv',
//'etatcoll_debf',
//'etatcoll_fina',
//'etatcoll_finv',
//'etatcoll_finf',
if ($this->last['support'] != 1)
    $columns[] = 'cote';
//'editeur_code',
//'editeur_sujet',
$columns[] = 'commentaire_pro';
$columns[] = 'commentaire_pub';
//'plateforme',
$columns[] = array(
    'name' => 'plateforme',
    'value' => '$data->plateforme0==null ? "" : $data->plateforme0->plateforme',
    'header' => 'Plateforme',
);
//'editeur',
$columns[] = array(
    'name' => 'editeur',
    'value' => '$data->editeur0==null ? "" : $data->editeur0->editeur',
    'header' => 'Editeur',
);
//'histabo',
//'statutabo',
//'localisation',
//'gestion',
//'format',
//'support',
//'licence',
$columns[] = array(
    'name' => 'licence',
    'value' => '$data->licence0->licence',
    'header' => 'Licence',
);

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'abonnement-grid',
    'dataProvider' => $dataProvider,
    'formatter' => new FrFormatter(),
    //'filter'=>  Abonnement::model(),
    'columns' => $columns,
));
?>
