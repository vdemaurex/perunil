<?php $this->pageTitle = Yii::app()->name;?>

<h2>Recherche avancée sur <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h2>
<br/>

<?php

// Recherche avancée
$this->renderPartial('_advSearch');

?>

<div class="clear"><p>&nbsp;</p></div>