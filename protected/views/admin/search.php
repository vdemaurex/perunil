<?php
$this->breadcrumbs = array(
    'Admin' => array('/admin'),
    'Search',
);
// Si la recherche à déjà été effectuée, on masque les champs de recherche
if ($search_done){
    ?>
        <p>
        <a id="searchformlink">
            <img id="searchformlinkimg" src="<?= Yii::app()->baseUrl; ?>/images/collapsed.gif"/>Afficher le formulaire de recherche
        </a>
        </p>
        <div id="searchform" style="display: none;">
            <?php $this->renderPartial('_adminSearchForm');?>
        </div>
        <script>
        var flip = 0;
        $("#searchformlink").click(function () {
            $("#searchform").toggle( flip++ % 2 == 0 );
            var image = $("#searchformlinkimg");
            if ($(image).attr("src") == "<?= Yii::app()->baseUrl; ?>/images/expanded.gif")
            $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/collapsed.gif");
            else
                $(image).attr("src", "<?= Yii::app()->baseUrl; ?>/images/expanded.gif");

        });
        </script>
    <?php
    
    
    $this->widget('AdminCListView',array(//JournalListViewWidget', array(
        'dataProvider' => $dataProvider,
        'itemView' => '/site/_view',
        'ajaxUpdate' => true,
        'template'=>"{pager}\n{items}\n{pager}",
    ));
    
}
// affichage du formulaire de recherche
else{
    $this->renderPartial('_adminSearchForm');
}


?>
