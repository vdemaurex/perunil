<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />


        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Bootstrap -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet" media="screen" /> 

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
           <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
           <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" />
        <title>Affichage du lot</title>

        <?php
        // Ajout de la librairie javascript jquery
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');

        // ajout de la css de jquery ui
        Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');


        // Autocompletion lors de la recherche
        Yii::app()->clientScript->registerCoreScript('autocomplete');
        ?>

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>

    </head>

    <body>
<?php
// Ne peut fonctionner qu'avec les résultat de la recherche admin.
if(Yii::app()->session['searchtype'] != 'admin'){
    throw new Exception("Merci d'utiliser l'exporation CSV depuis les résultats de la recherche administrateur uniquement.");
}
$dataProvider = Yii::app()->session['search']->admin_adp;
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
    //'htmlOptions' => array('class' => 'table table-striped')
)); 
?>
</body>
</html>