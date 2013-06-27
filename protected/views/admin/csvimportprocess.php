<?php

$this->breadcrumbs = array(
    'Admin' => array('/admin'),
    'Csvimportprocess',
);


/* Affichage des résulats
 *  $modif[] = array(
  'table'  => $table,
  'id'     => $row[$table][$tableid],
  'champs' => $column_name,
  'ancienne_valeur' => $obj->$column_name,
  'nouvelle_valeur' => $row[$table][$column_name],
  );
 * 
 *  $ajout[] = array(
  'table'     => $table,
  'attributs' => $row[$table],
  );
 * 
 */
echo "<h1>Fichier CSV, réustats de l'importation</h1>\n";
echo "<ul>";
foreach ($msg as $message) {
    echo "<li>$message</li>";
}
echo "</ul>";