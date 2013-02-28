<?php
$this->breadcrumbs=array(
	'Admin',
);?>
<h1>Administration de PérUnil</h1>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/admin/search')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/adminsearch-icon.png" height="64" width="64"></a><br>
            <strong>Recherche administrateur</strong></td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/admin/peredit')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/addjournal.png" height="64" width="64"></a><br>
            <strong>Nouveau périodique</strong></td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/sujet/admin')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/managesubjects.png" height="64" width="64"></a><br>
            <strong>Gérer les sujets</strong></td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/admin/modif')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/managemodifications.png" height="64" width="64"></a><br>
            <strong>Suivit des modifications</strong></td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>

<br/>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>  
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/admin/csv')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/importcsv.png" height="64" width="64"></a><br>
            <strong>Importation par lot (CSV)</strong></td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/user/admin')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/manageusers.png" height="64" width="64"></a><br>
            <strong>Gestion des utilisateurs</strong></td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: center;" width="65">
                <a target="_self" href="<?= CController::createUrl('/site/logout')?>"><img src="<?= Yii::app()->baseUrl; ?>/images/logout.png" height="64" width="64"></a><br>
            <strong>Déconnexion</strong></td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>