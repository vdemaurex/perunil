<?php $this->pageTitle = Yii::app()->name . " - " . $model->titre; ?>
<p><?php echo CHtml::link("<< Retour aux résultats de la recherche", Yii::app()->session['last_search_url'])?></p>
<h1><?= $model->titre ?></h1>
<?php
if (!Yii::app()->user->isGuest){
    echo"<p>";
    echo CHtml::link('Editer le journal',array('admin/peredit/perunilid/'. $model->perunilid));
    echo"</p>";
}


$fields = array(
    'perunilid',
    'titre',
    'soustitre',
    'titre_abrege',
    'titre_variante',
    'faitsuitea',
    'devient',
    'issn',
    'issnl',
    'nlmid',
    'reroid',
    'doi',
    'coden',
    'urn',
    array('name' => 'publiunil', 'label' => 'Est une publication UNIL ?', 'type' => 'boolean'),
    array('name' => 'url_rss', 'value' => CHtml::link(CHtml::encode($model->url_rss)), 'type' => 'raw', 'label' => "Flux RSS du périodique"),
    array('name' => 'parution_terminee', 'label' => 'Parution terminée ?', 'type' => 'boolean'),
    array('name' => 'openaccess', 'label' => 'Openaccess (gratuit) ?', 'type' => 'boolean'),
    array('name' => 'commentaire_pub', 'label' => 'Remarques'),
);
foreach ($fields as $key => $field) {
    if (is_array($field)) {
        $name = $field['name'];
    } else {
        $name = $field;
    }
    if ($model->$name == NULL || trim($model->$name) == "") {
        unset($fields[$key]);
    }
}

// Ajout des sujets
$fields[] = array('label'=>'Sujets', 'type'=>'raw', 'value'=>$model->sujets2str());
// Ajout de la liste corecollection
if ($model->corecollection2str() != ""){
    $fields[] = array('label'=>'Core collection', 'type'=>'raw', 'value'=> $model->corecollection2str());
}

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => $fields,
    'formatter' => new FrFormatter(),
));

$tabviewparam = array();
$i = 1;

foreach ($model->activeabos as $abo) {
    if ($abo->support == 2 && isset($abo->localisation0)) {
        $tabtitle = $abo->localisation0->localisation;
    } elseif ($abo->support == 1 && isset($abo->licence0) && isset($abo->licence0->licence)) {
        $tabtitle = $abo->licence0->licence;
    } else {
        $tabtitle = "Abonnement n°" . $abo->abonnement_id;
    }
    $tabviewparam['tabs']['tab' . $i] = array(
        'title' => $tabtitle,
        'view' => '_aboview',
        'data' => array('abo' => $abo, 'jrn' => $model),
    );

    $i++;
}

$this->widget('CTabView', $tabviewparam);


if (!Yii::app()->user->isGuest && !$model->getIsNewRecord()) {
$this->widget( 'application.modules.auditTrail.widgets.portlets.ShowAuditTrail', array( 'model' => $model, ) );
}

?>