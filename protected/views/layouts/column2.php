<?php $this->beginContent('//layouts/main_btp3'); ?>



<div class="span-19">
    <div id="content">
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
        }
        ?>
        <p>
        <b>Sélection du type de liste à modifier :</b>
        </p>
        <?php
        $types = array('Plateforme', 'Editeur', 'Histabo', 'Statutabo', 'Localisation', 'Gestion', 'Format', 'Support', 'Licence', 'Fournisseur');
        $items = array();

        foreach ($types as $t) {
            $items[] = array(
                'label' => $t,
                'url' => array('/smalllist/changetype/type/' . $t),
                'active' => Yii::app()->session['smalllist'] == $t);
        }

        $this->widget('zii.widgets.CMenu', array(
            'items' => $items,
            'htmlOptions' => array('class' => 'nav nav-tabs'),
        ));

        echo $content;

        ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>