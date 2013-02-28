<?php $this->beginContent('//layouts/main'); ?>

<div class="span-6">
    <div id="sidebar">
        <?php echo $this->clips['sidebar']; ?>
    </div><!-- sidebar -->
</div>
<div class="span-18 last">
    <div id="content">
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
        }
        ?>
<?php echo $content; ?>
    </div><!-- content -->


</div>
<?php $this->endContent(); ?>