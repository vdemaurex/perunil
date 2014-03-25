<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModifModel
 *
 * @author vdemaure
 */
class ModifModel extends CActiveRecord {

    public function FieldToString($field) {
        $string = "";
        switch ($field) {
            case 'creation' || 'modification':
                $table = ucfirst($field);
                if (!empty($this->$field)) {
                    $modif = $this->{$field . '_lk'};
                    if ($modif) {
                        // La dernière modification existe bien
                        $string = $modif->stamp . " par " . $modif->utilisateur->pseudo;
                    }
                }
                break;

            default:
                break;
        }
        return $string;
    }

    
        /**
     * @return array relational rules.
     */
    public function relations() {

        return array(
            'modification_lk' => array(self::BELONGS_TO, 'Modifications', 'modification'),
            'creation_lk'     => array(self::BELONGS_TO, 'Modifications', 'creation'),
        );
    }
    
}
