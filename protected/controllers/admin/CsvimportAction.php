<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Csvimportaction
 *
 * @author vdemaure
 */
class CsvimportAction extends CAction {

    private $tables = array(
        'journal',
        'abonnement',
        'editeur',
        'plateforme',
        'histabo',
        'statutabo',
        'localisation',
        'gestion',
        'format',
        'support',
        'licence'
    );

    public function run() {

        $controller = $this->getController();

        $model = new CsvImportForm;
        $render_data['model'] = $model;
        if (isset($_POST['CsvImportForm'])) {
            $model->attributes = $_POST['CsvImportForm'];
            if ($model->validate()) {
                $csvfile = CUploadedFile::getInstance($model, 'fichier');
//$tempLoc = $csvfile->getTempName();
// Ouverture du fichier
                $f = fopen($csvfile->tempName, 'r');
                if ($f) {

                    /* 1. Vérifier si le fichier contient tout les champs requis
                     * 2. Créer un tableau assiciatif à partir du fichier CSV
                     *    [No ligne][Table][Colonne]
                     * 3. Variables pour le stockage : 
                     *   $jrnabo [perunilid] = [Journal[Abonnements]]
                     *   $usedid [Table][id]
                     *   $csttable [Table][obj]
                     *   
                     * 4. Pour chaque ligne du tableau associatif
                     *      Pour chaque table
                     *           Si l'id existe
                     *               Créer l'objet à partir de la base de donnée
                     *               Si l'id n'existe pas dans la base
                     *                   supprimer l'id, il sera traité comme nouvel élément
                     *               Sinon, l'objet a pu être créé
                     *                   Transmettre à l'objet les champs pour validation
                     *                   S'il y a des modifications
                     *                       ajouter
                     *                   S'il n'y a pas de modification
                     *               Fin Si  
                     *           Si l'id n'existe pas
                     *               Si il existe au moins une donnée dans un champs
                     *                   Créer l'objet et assigner les données
                     *                   Ajouter l'objet au tableau new
                     *               Fin Si
                     *           Fin Si 
                     *      Fin Pour     
                     */


                    // Créer un tableau assiciatif à partir du fichier CSV
                    $data = $this->getAssocArray($f, $model->getDelimiter());
                    $data = $this->tableColumnArray($data);


                    $usedid = array();
                    $modif = array();
                    $ajout = array();


                    foreach ($data as $i => $row) {

                        // Analyse de toutes les tables
                        foreach ($this->tables as $table) {
                            // On ne peut modifier que les tables journal et abonnement
                            if (!in_array($table, array('journal', 'abonnement'))) {
                                continue;
                            }

                            // Le id de la table journal est une exception
                            // sinon c'est table_id
                            $tableid = $table . "_id";
                            if ($table == 'journal') {
                                $tableid = 'perunilid';
                            }

                            // Nom de la table dans la base de donnée
                            $Table = ucfirst($table);

                            //
                            // Récupération du l'objet $table
                            //
                            // Si $table n'a pas déjà été traité, on procède à son analyse 


                            if (!isset($usedid[$table]) || !array_key_exists($row[$table][$tableid], $usedid[$table])) {
                                $obj = $Table::model()->findByPk($row[$table][$tableid]);

                                // Le objet existe dans la base
                                if ($obj) {

                                    // pour chaque propriété, vérifier les différences
                                    foreach ($row[$table] as $column_name => $column_value) {
                                        // Une colonne a été modifiée    
                                        if ($obj->$column_name != $column_value) {
                                            // Le journal est conservé pour modification

                                            $usedid[$table][] = $row[$table][$tableid];

                                            // ajout de la modification a effectuer
                                            $modif[] = array(
                                                'table' => $table,
                                                'id' => $row[$table][$tableid],
                                                'champs' => $column_name,
                                                'ancienne_valeur' => $obj->$column_name,
                                                'nouvelle_valeur' => $row[$table][$column_name],
                                            );
                                        }
                                    }
                                }
                            }
                            // Cet objet n'existe pas dans la base
                            else {
                                // Si tous les attributs de l'entrée sont vides, ne pas en tenir compte
                                if (array_filter($row[$table], function ($v) {
                                            return $v != "";
                                        })) {
                                    // Conserver la nouvelle entrée
                                    $ajout[] = array(
                                        'table' => $table,
                                        'attributs' => $row[$table],
                                    );
                                }
                            }
                        }
                    } // Fin du parcours des lignes


                    fclose($f);
                } else {
                    throw new CException("Impossible d'ouvrir le fichier " . $csvfile->tempName . " en lecture.");
                }
            }


// Enregistrement des tableaux modif et ajout dans la session
            Yii::app()->session['modif'] = $modif;
            Yii::app()->session['ajout'] = $ajout;
            $render_data['modif'] = $modif;
            $render_data['ajout'] = $ajout;
            $render_data['showresults'] = true;
            $render_data['filename'] = $csvfile->name;
        } // Fin traitement du formulaire d'importation


        $controller->render('csvimport', $render_data);
    }

    private function tableColumnArray($assocArray) {
        $tc = array();
        foreach ($assocArray as $i => $row) {
            foreach (array_keys($row) as $key) {
                $splitedkey = explode('-', $key);
                $table = $splitedkey[0];
                $column = $splitedkey[1];
                $tc[$i][$table][$column] = $row[$key];
            }
        }
        return $tc;
    }

    private function showCsvAsHtmlTable($f) {
        echo "<html><body><table>\n\n";
        while (($line = fgetcsv($f)) !== false) {
            echo "<tr>";
            foreach ($line as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "<tr>\n";
        }

        echo "\n</table></body></html>";
    }

    private function getAssocArray($f, $delimiter = ',') {
        // TODO vérifier la conformité des colones du fichier CSV
        $array = $fields = array();
        $i = 0;

        while (($row = fgetcsv($f, 4096, $delimiter)) !== false) {
            if (empty($fields)) {
                $fields = $row;
                continue;
            }
            foreach ($row as $k => $value) {
                $array[$i][$fields[$k]] = $value;
            }
            $i++;
        }
        if (!feof($f)) {
            throw new CException("La lecture du fichier CSV a échoué.");
        }
        return $array;
    }

}

?>
