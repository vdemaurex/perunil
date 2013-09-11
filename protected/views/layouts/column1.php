<?php $this->beginContent('//layouts/main_btp3'); ?>
<div id="content">
    <?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>
	<?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>