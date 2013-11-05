<?php
$this->breadcrumbs=array(
	'Admin',
);?>

<div class="panel panel-default" style="width: 90%; margin:auto;">
  <div class="panel-heading">
      <h3>Administration de PérUnil</h3>
  </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <a target="_self" href="<?= CController::createUrl('/admin/search')?>" ><img src="<?= Yii::app()->baseUrl; ?>/images/adminsearch-icon.png" height="64" width="64"></a><br>
                <strong>Recherche administrateur</strong>
            </div>
            <div class="col-md-4 text-center">
                <a target="_self" href="<?= CController::createUrl('/admin/peredit')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/addjournal.png" height="64" width="64"></a><br>
                <strong>Nouveau périodique</strong>
            </div>
            <div class="col-md-4 text-center">
                <a target="_self" href="<?= CController::createUrl('/smalllist')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/managesubjects.png" height="64" width="64"></a><br>
                <strong>Gérer les listes</strong>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-4 text-center">
                <a target="_self" href="<?= CController::createUrl('/admin/csvimport')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/importcsv.png" height="64" width="64"></a><br>
                <strong>Importation par lot (CSV)</strong>
            </div>
            <div class="col-md-4 text-center">
                <a target="_self" href="<?= CController::createUrl('/admin/mesmodifications')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/managemodifications.png" height="64" width="64"></a><br>
                <strong>Mes modifications</strong>
            </div>
            <div class="col-md-4 text-center">
                <a target="_self" href="<?= CController::createUrl('/site/logout')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/logout.png" height="64" width="64"></a><br>
                <strong>Déconnexion</strong>
            </div>
        </div>        
    </div>
</div>



<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                </td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                </td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                </td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
             <?/*   </td>
            <td>*/?>&nbsp;</td>
        </tr>
        <tr><td colspan="7"><br/></td></tr>
        <tr>  
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                </td>
            <td>&nbsp;</td><?/*
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/user/admin')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/manageusers.png" height="64" width="64"></a><br>
            <strong>Gestion des utilisateurs</strong></td>
            <td>&nbsp;</td>*/?>
            <td style="text-align: center;" width="65">&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                </td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>