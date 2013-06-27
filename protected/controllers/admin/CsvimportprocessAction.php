<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CsvimportproccessAction
 *
 * @author vdemaure
 */
class CsvimportprocessAction extends CAction {

    public function run() {
        $modif = array();
        $ajout = array();
        $msg = array();


        // Récupération des variables de session
        if (isset(Yii::app()->session['modif'])) {
            $modif = Yii::app()->session['modif'];
        } else {
            Yii::app()->user->setFlash('notice', "Il n'y a aucune modification à appliquer.");
        }

        if (isset(Yii::app()->session['ajout'])) {
            $ajout = Yii::app()->session['ajout'];
        } else {
            Yii::app()->user->setFlash('notice', "Il n'y a élément à ajouter à la base.");
        }

        /**
         * Traitement de modifications 
         */
        foreach ($modif as $m) {
            $Table = ucfirst($m['table']);
            $obj = $Table::model()->findByPk($m['id']);
            $column = $m['champs'];
            $obj->$column = $m['nouvelle_valeur'];
            if ($obj->save()) {
                $msg[] = "La modification de la table $Table ({$this->idlink($obj, $m['id'])}) a réussi.
             Ancienne valeur :  {$m['ancienne_valeur']}. 
             Nouvelle valeur :  {$m['nouvelle_valeur']}.";
            } else {
                $msg[] = "La modification de la table $Table ({$this->idlink($obj, $m['id'])}) a échoué.";
            }
        }

        /**
         * Traitement des ajouts 
         */
        foreach ($ajout as $a) {
            $Table = ucfirst($a['table']);
            $obj = new $Table;

            if (isset($a['attributs'])) {
                $obj->attributes = $a['attributs'];
            }
            if ($obj->save()) {
                $msg[] = "L'ajout d'une entrée dans la table $Table ({$this->idlink($obj, $m['id'])}) a réussi.";
            } else {
                $msg[] = "L'ajout d'une entrée dans la table $Table (id : {$m['id']}) a échoué.";
            }
        }
        $this->getController()->render('csvimportprocess', array('msg' => $msg));
    }



private

function idlink($obj, $id) {
    $msg = "id : ";
    if ($obj instanceof Journal || $obj instanceof Abonnement) {
        $perunilid = $obj->perunilid;
        $msg .= "<a href=". CHtml::normalizeUrl(array("site/detail/$perunilid")) . ">$id</a>";       
    } else {
        $msg .= $id;
    }
    return $msg;
}
}
?>
