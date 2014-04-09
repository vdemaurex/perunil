<?php

ini_set("max_execution_time", "120");
ini_set("memory_limit", "2400M");

class PU1toPU2Command extends CConsoleCommand {

    public function getHelp() {
        $out = "Effectue les tâches de migrations avancées pour transformer la base PU1 en PU2 \n\n";
        return $out . parent::getHelp();
    }

    public function run($args) {
        $this->addLastModiforCreat();
    }

    private function addLastModiforCreat($table = 'journal', $action = 'Modification') {
        $this->l("Ajout des $action à la table $table");
        $class = ucfirst($table);
        
        
        // pour tous les journaux
        $property = strtolower($action);
        if ($table == 'journal'){
            $id = 'perunilid';
        }
        else{
            $id = $table. '_id';
        }
        
        $this->l("---------------- Début de la mise à jour ---------------");
        $this->l("|   $table id   |   $action.id   |");
        $this->l("|---------------|----------------|");
//        $i = 10;
        foreach ($class::model()->findAll() as $jrn) {
//            if ($i <= 0) {
//                break;
//            } else {
//                $i--;
//            }
            if (!empty($jrn->$property)) {
                continue;
            }
            $criteria = new CDbCriteria();
            $criteria->addCondition("model = '$table'");
            $criteria->addCondition("action = '$action'");
            $criteria->addCondition("model_id = $jrn->perunilid");
            $criteria->order = "stamp";
            $criteria->limit = 1;

            $lastaction = Modifications::model()->find($criteria);
            
            if (!empty($lastaction)){
            
                $command = Yii::app()->db->createCommand();

                if ($command->update($table, array($property => $lastaction->id), $id.'=:id', array(':id' => $jrn->perunilid))) {
                    $this->l(sprintf("|[%12s] | [%12s] |", $jrn->perunilid, $lastaction->id));
                } else {
                    $this->l(sprintf("|[%12s] | [%12s] | ERROR", $jrn->perunilid, $lastaction->id));
                }
            }

        }
        $this->l("---------------- Fin de la mise à jour ---------------\n");
    }

    private function l($str) {
        echo "\n$str";
    }

}
