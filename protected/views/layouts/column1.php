<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
    <?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>
	<?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>