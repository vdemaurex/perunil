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
    foreach (Sujet::model()->findAll(array('condition' => $condition, 'order' => 'nom_fr')) as $s) {
        echo "<li>";
        echo CHtml::link($s->nom_fr,array(
            'site/adv',
            'advsearch'=> "advsearch",
            'sujet' => $s->sujet_id,
            ));
        echo "</li>\n";
    }
    echo "</ul>\n";
    echo "</td>";
}
?>
        </tr>
</table>