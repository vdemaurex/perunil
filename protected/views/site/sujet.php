<table>
    <tr>
<?php

$lists = array(
    'Sciences humaines' => "shs=1",
    'Sciences biomédicales' => "stm=1");

foreach ($lists as $group => $condition) {?>
    <td style="vertical-align: top;">
    <h2> <?=$group?> </h2>
    <ul><?php
    // index.php/site/advSearchResults?advsearch=advsearch&accessunil=1&openaccess=1&sujet=170
    foreach (Sujet::model()->findAll(array('condition' => $condition, 'order' => 'nom_fr')) as $s) {
        echo "<li>";
        echo CHtml::link($s->nom_fr,array(
            'site/advSearchResults',
            'advsearch' => "advsearch",
            'accessunil'=>'1',
            'openaccess'=>'1',
            'sujet'     => $s->sujet_id,
            ));
        echo "</li>\n";
    }
    echo "</ul>\n";
    echo "</td>";
}
?>
        </tr>
</table>