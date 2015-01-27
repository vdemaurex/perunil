<?php

/**
 * La classe CSVValuesRules permet de créer des règle de validation pour les
 * différents champs des fichiers CSV importés.
 *
 * @author vdemaure
 */
class CSVValuesRules extends CComponent {

    /**
     * Appelle une fonction de validation donc le nom correspond à $column.
     * Si aucune fonction de ce nom n'existe, on retourne $newValue.
     * 
     * 
     * @param string $column nom de la colonne de la table Abonnement à vérifier.
     * @param string $newValue nouvelle valeur qui sera vérifiée. Si aucune fonction
     * de vérification n'existe, cette valeur est retournée par défaut.
     * @param string $oldValue Ancienne valeur de la table. Elle est renvoyée si la 
     * validation ne passe pas.
     * @param bool $newRecord Si vrai, on traite le cas d'un nouvel abonnement. La fonction 
     * de validatio peut ainsi prévoir une valeur par défaut.
     * @return string Renvoie la valeur issue de la fonction de validation.
     */
    public static function rule($column, $newValue, $oldValue = null, $newRecord = true) {
        // Vider la colonne si NULL
        if ($newValue == 'NULL'){
            return "";
        }
        if (method_exists('CSVValuesRules', $column)) {
            return self::$column($newValue, $oldValue, $newRecord);
        } else {
            return $newValue;
        }
    }

    /**
     * Vérifie que le support corresponde bien au deux valeur possibles.
     */
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
