<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSVValuesRules
 *
 * @author vdemaure
 */
class CSVValuesRules extends CComponent {

    public static function rule($column, $newValue, $oldValue = null, $newRecord = true) {
        if (method_exists('CSVValuesRules', $column)) {
            return self::$column($newValue, $oldValue, $newRecord);
        } else {
            return $newValue;
        }
    }

    protected static function support($newValue, $oldValue, $newRecord) {
        if ($newValue == 1 || $newValue == 2) {
            return $newValue;
        }
        // Valeur incorrecte ou null
        if ($newRecord) {
            return 1; // électronique
        } else {
            return $oldValue;
        }
    }

}
