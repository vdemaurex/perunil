<?php $this->beginContent('//layouts/main'); ?>



<div class="span-19">
    <div id="content">
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
        }
        ?>
        <b>Sélection du type de liste à modifier :</b>
        <div id="smalllistmenu">
            <?php
            $types = array('Plateforme', 'Editeur', 'Histabo', 'Statutabo', 'Localisation', 'Gestion', 'Format', 'Support', 'Licence');
            $items = array();

            foreach ($types as $t) {
                $items[] = array(
                    'label' => $t,
                    'url' => array('/smalllist/changetype/type/' . $t),
                    'active' => Yii::app()->session['smalllist'] == $t);
            }

            $this->widget('zii.widgets.CMenu', array(
                'items' => $items
            ));
            ?> 
        </div>
        <hr />

        <?php echo $content; ?>
    </div><!-- content -->
</div>
<div class="span-5 last">
    <div id="sidebar">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => 'Operations',
        ));
        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->menu,
            'htmlOptions' => array('class' => 'operations'),
        ));
        $this->endWidget();
        ?>
    </div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>