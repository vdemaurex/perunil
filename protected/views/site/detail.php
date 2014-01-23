<?php $this->pageTitle = Yii::app()->name . " - " . $model->titre; 

if (!isset($dialogue)){
    $dialogue = false;
}

if (!$dialogue) {
    echo"<p>";
    echo  CHtml::htmlButton('<span class="glyphicon glyphicon-backward"> </span> Retour aux résultats de la recherche', array(
                        'onclick' => 'history.go(-1);return false;',
                        'class'   => "btn btn-default  btn-xs")); 
   
    if (!Yii::app()->user->isGuest) {
        //echo"<p>";
        //echo CHtml::link('Editer le journal', array('admin/peredit/perunilid/' . $model->perunilid));
        echo  " " . CHtml::htmlButton('Editer le journal', array(
                        'onclick' => 'js:document.location.href="' . Yii::app()->createUrl("admin/peredit", array('perunilid' => $model->perunilid)) . '"',
                        'class'   => "btn btn-primary  btn-xs")); 
        //echo"</p>";
    }
     ?></p>
    <h1><?= $model->titre ?></h1>
    <?php
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

// Suppression des champs qui ne contiennent aucune information
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
$fields[] = array('label' => 'Sujets', 'type' => 'raw', 'value' => $model->sujets2str());
// Ajout de la liste corecollection
if ($model->corecollection2str() != "") {
    $fields[] = array('label' => 'Core collection', 'type' => 'raw', 'value' => $model->corecollection2str());
}

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => $fields,
    'formatter' => new FrFormatter(),
));

$tabviewparam = array();
$i = 1;

if (Yii::app()->user->isGuest){
    $abos = $model->activeabos;
}else{
    // Admin, inculre les abo exculs
    $abos = $model->AllAbos;
}

foreach ($abos as $abo) {
    // On affiche seulement l'abonnement désiré
    if ($activeTab) {
        if ($activeTab != $abo->abonnement_id)
            continue;
    }
    if ($abo->support == 2 && isset($abo->localisation0)) {
        $tabtitle = $abo->localisation0->localisation;
    } elseif ($abo->support == 1 && isset($abo->licence0) && isset($abo->licence0->licence)) {
        $tabtitle = $abo->licence0->licence;
    } else {
        $tabtitle = "Abonnement n°" . $abo->abonnement_id;
    }
    $tabviewparam['tabs'][$abo->abonnement_id] = array(
        'title' => $tabtitle,
        'view' => '_aboview',
        'data' => array('abo' => $abo, 'jrn' => $model),
    );


    $i++;
}

$this->widget('CTabView', $tabviewparam);


if (!Yii::app()->user->isGuest && !$model->getIsNewRecord() && !$dialogue) {
    $this->widget('application.modules.auditTrail.widgets.portlets.ShowAuditTrail', array('model' => $model,));
}
?>