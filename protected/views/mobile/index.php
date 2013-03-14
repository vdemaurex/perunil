<?php
/* @var $this MobileController */ 
?>



<h3>Recherche d'un périodique</h3>
<p>Entrez au moins trois caractère du titre du périodique recherché </p> 

 <div id="search">
<ul id="autocomplete" data-role="listview" data-inset="true" data-filter="true" data-filter-placeholder="Recherche d'un périodique..." data-filter-theme="d"></ul>
</div>

<script>
    $( document ).on( "pageinit", "#mobilepage", function() {
        $( "#autocomplete" ).on( "listviewbeforefilter", function ( e, data ) {
            var $ul = $( this ),
            $input = $( data.input ),
            value = $input.val(),
            html = "";
            $ul.html( "" );
            if ( value && value.length > 2 ) {
                $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
                $ul.listview( "refresh" );
                $.ajax({
                    url: "<?php echo Yii::app()->createUrl('site/autocomplete')?>",
                    dataType: "json",
                    crossDomain: true,
                    data: {
                        term: $input.val()
                    }
                })
                .then( function ( response ) {
                    $.each( response, function ( i, val ) {
                        html += "<li><a href=\"<?php echo Yii::app()->createUrl('mobile/journal')?>?perunilid="+ val.id +"\">" + val.label + "</a></li>";
                    });
                    $ul.html( html );
                    $ul.listview( "refresh" );
                    $ul.trigger( "updatelayout");
                });
            }
        });
    });
</script>
<style>
    .ui-listview-filter-inset {
        margin-top: 0;
    }
</style> 

