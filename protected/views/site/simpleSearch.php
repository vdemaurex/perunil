<?php $this->pageTitle = Yii::app()->name; 
$isadv = isset($advsearch) && $advsearch; ?>

<h2>Recherche sur <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h2>
<br/>

<?php

// Affichage de la recherche simple 
$this->renderPartial('_simpleSearch');

?>

<div class="clear"><p>&nbsp;</p></div>


<div class="hidden-xs hidden-sm"> 
    <hr/>
    <h2>Accès direct aux principales plateformes</h2>
    <table class="centpp" cellspacing="10" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td>
                    <a target="_blank" href="http://www.sciencedirect.com/"><img width="180" title="Elsevier Science Direct" src="<?= Yii::app()->baseUrl; ?>/images/sciencedirect.png"></a>
                </td>
                <td>
                    <a target="_blank" href="http://www.tandfonline.com/"><img width="60" title="Taylor &amp; Francis Online" src="<?= Yii::app()->baseUrl; ?>/images/tandfonline.jpg"></a>
                </td>
                <td>
                    <a target="_blank" href="http://onlinelibrary.wiley.com/"><img width="150" title="Wiley" src="<?= Yii::app()->baseUrl; ?>/images/wiley.jpg"></a>
                </td>
                <td>
                    <a target="_blank" href="http://www.springer.com/"><img width="160" title="Springer" src="<?= Yii::app()->baseUrl; ?>/images/springer.jpg"></a>
                </td>
                <td>
                    <a target="_blank" href="http://www.jstor.org/"><img width="60" title="JSTOR" src="<?= Yii::app()->baseUrl; ?>/images/jstor.jpg"></a>
                </td>
                <td>
                    <a target="_blank" href="http://www.ingentaconnect.com/"><img width="180" title="Ingenta Connect" src="<?= Yii::app()->baseUrl; ?>/images/ingenta.png"></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

