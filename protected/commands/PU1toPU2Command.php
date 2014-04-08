<?php

class PU1toPU2Command extends CConsoleCommand {

    public function getHelp() {
        $out = "Effectue les tâches de migrations avancées pour transformer la base PU1 en PU2 \n\n";
        return $out . parent::getHelp();
    }

    public function run($args) {
        
        
    }
    
    public function actionLastModif(){
        // pour tous les journaux
        foreach (Journal::model()->findAll() as $jrn) {
            $criteria=new CDbCriteria();
            $criteria->addCondition("model = 'Journal'");
            $criteria->addCondition("action = 'Modification'");
            $criteria->addCondition("model_id = $jrn->perunilid");
            $criteria->order = "stamp";
            $criteria->limit = 1;
            
            $lastmodif = Modifications::model()->find($criteria);
            
            $jrn->modification = $lastmodif->id;
            $jrn->save();
        } 
        // rechercher s'il existe dans la table migration
        
        
    }

}
