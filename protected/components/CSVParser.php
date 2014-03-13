<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSVParser
 *
 * @author vdemaure
 */
class CSVParser extends CComponent {

    /**
     * Toutes les lignes du fichier CSV, sauf les lignes d'entête;
     * @var array 
     */
    private $rows;
    private $nbrTotalRows = 0;
    private $nbrRows;

    public function __construct($data) {

        // Initialisation des statistiques
        $this->nbrRows[CSVRow::REJECTED] = 0;
        $this->nbrRows[CSVRow::SEARCH]   = 0;
        $this->nbrRows[CSVRow::MODIF]    = 0;
        $this->nbrRows[CSVRow::CREATE]   = 0;
        $this->nbrRows[CSVRow::UNKNOWN]  = 0;
        
        // Statisitiques de la sauvegarde
        $this->nbrRows[CSVRow::CREATE_SAVED] = 0;
        $this->nbrRows[CSVRow::MODIF_SAVED]  = 0;


        foreach ($data as $no => $rowData) {
            $this->rows[$no] = new CSVRow($rowData, $no, $this);

// Statistiques
            $this->nbrRows[$this->rows[$no]->state] ++;

            $this->nbrTotalRows ++;
        }
    }

//
// Interface publique
//

    public function next2search() {
        foreach ($this->rows as $row) {
            if ($row->getstate() == CSVRow::SEARCH || $row->getState() == CSVRow::UNKNOWN) {
                return $row;
            }
        }

// Aucune line dans la catégorie SEARCH
        return NULL;
    }

    public function getProceededRows() {
        $proceeded = array();
// Pour chaque ligne valide, préparer les modifcations
        foreach ($this->rows as $row) {
            if ($row->getState() == CSVRow::MODIF || $row->getState() == CSVRow::CREATE) {
                $proceeded[$row->noRow] = $row;
            }
        }

        return $proceeded;
    }

    public function getSavedRows() {
        $saved = array();
// Pour chaque ligne valide, préparer les modifcations
        foreach ($this->rows as $row) {
            if ($row->getState() == CSVRow::CREATE_SAVED || $row->getState() == CSVRow::MODIF_SAVED) {
                $saved[$row->noRow] = $row;
            }
        }

        return $saved;
    }
    
    public function doUpdate() {
        // Vérification qu'il ne reste plus de ligne au statut incertain.
        if ($this->nbrRows[CSVRow::SEARCH] > 0 ||
                $this->nbrRows[CSVRow::UNKNOWN] > 0) {
            throw new Exception("Certaine ligne n'ont pas été traitée. Enregistrement impossible.'");
        }

        // Enregistrement des lignes
        foreach ($this->rows as $row) {
            if ($row->getState() == CSVRow::MODIF || $row->getState() == CSVRow::CREATE) {
                $row->save();
            }
        }
        
    }

//
// Accès aux propriétés
//

    public function isValidCSVFile() {
        return $this->getNbrValidRows() > 0;
    }

    public function getNbrTotalRows() {
        return $this->nbrTotalRows;
    }

    public function getNbrValidRows() {
        return $this->nbrTotalRows - $this->nbrRows[CSVRow::REJECTED];
    }

    public function getNbrRejectedRows() {
        return $this->nbrRows[CSVRow::REJECTED];
    }

    public function getNbrPerunilidRows() {
        return $this->nbrRows[CSVRow::CREATE];
    }

    public function getNbrSeachRows() {
        return $this->nbrRows[CSVRow::SEARCH];
    }

    public function getNbrModifRows() {
        return $this->nbrRows[CSVRow::MODIF];
    }

    public function getNbrUnknownRows() {
        return $this->nbrRows[CSVRow::UNKNOWN];
    }

    public function uptdateRowCounter($oldState, $newState) {
// Vérification de $oldState
        if (!CSVRow::isValidState($oldState)) {
            throw new Exception("L'état $oldState n'est pas admis pour une line de la classe CSVRow.");
        }
        if (empty($this->nbrRows[$oldState])) {
            throw new Exception("Impossible de supprimer un ligne du compteur $oldState, celui-ci est vide");
        }

// Vérification de $newState
        if (!CSVRow::isValidState($newState)) {
            throw new Exception("L'état $newState n'est pas admis pour une line de la classe CSVRow.");
        }

// Mise à jour des compteurs
        $this->nbrRows[$oldState] --;
        $this->nbrRows[$newState] ++;
    }

}
