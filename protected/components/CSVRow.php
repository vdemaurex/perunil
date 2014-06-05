<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSVRow
 *
 * @author vdemaure
 */
class CSVRow extends CComponent {

    /**
     * Etat de la ligne : l'une des constantes de cette classe.
     * @var string 
     */
    private $state;

    /**
     * La ligne fait référence à un abonnement existant. Il sera modifé.
     * Les modifications sont prêtes pour être appliquées.
     */
    const MODIF = 'modifier';

    /**
     * Un nouvel abonnement sera créé. Le journal auquel il doit être associé à 
     * été identifié.
     * Les modifications sont prêtes pour être appliquées.
     */
    const CREATE = 'créer';

    /**
     * Un nouvel abonnement sera créé mais le journal n'a pas pu être déterminé.
     * L'utilisateur doit choisir le journal dans une liste.
     * Les modifications ne peuvent pas être appliquées (levée d'une 
     * exception).
     */
    const SEARCH = 'chercher';

    /**
     * Un nouvel abonnement sera créé mais le journal n'a pas pu être déterminé.
     * La recherche n'a fourni aucun résultat. L'utilisateur doit donner le
     * perunilid ou rejeter la ligne.
     * Les modifications ne peuvent pas être appliquées (levée d'une 
     * exception).
     */
    const UNKNOWN = 'donnerPerunilid';

    /**
     * La ligne ne correspond pas à un abonnement ou elle à été rejetée par
     * l'utilisateur. Elle se sera pas traitée.
     */
    const REJECTED = 'ignorer';

    /**
     * La modification de l'abonnement a correctement été enregistrée.
     */
    const MODIF_SAVED = 'abonnement_Modifié';

    /**
     * La création de l'abonnement a correctement été réalisée.
     */
    const CREATE_SAVED = 'abonnement_Créé';

    private $data;

    /**
     * Pour le cas MODIF, abonnement récupéré depuis la base de données.
     * @var Abonnement 
     */
    private $DBAbo;

    /**
     * Pour le cas CREATE, journal récupére depuis la base de données.
     * @var Journal 
     */
    private $jrn;

    /**
     * Tableau associatif des entrée VALIDES reçues par l'utilisateur. 
     * [nom de la colonne][valeur]
     * Les entrées sont vérifiées.
     * @var array 
     */
    private $validValues;

    /**
     * Tableau associatif des entrée INVALIDES reçues par l'utilisateur. 
     * [nom de la colonne][valeur]
     * @var array 
     */
    private $invalideValues;

    /**
     * Recherche local à cette ligne pour recherche les journaux.
     * @var SearchComponent 
     */
    private $searchComp;
    private $search_journal_issn;
    private $search_journal_issnl;
    private $search_journal_titre;

    /**
     * Phrase détaillant les résultats de la recherche
     * @var string 
     */
    private $search_log;

    /**
     * Numéro de la ligne dans le fichier CSV
     * @var int
     */
    public $noRow;

    /**
     * Référence au parser
     * @var CSVParser 
     */
    private $parser;

    /**
     * Tableau des changements
     * @var array 
     */
    private $changes;

    /**
     * On ne peut faire qu'une fois le traitement.
     * @var boolean 
     */
    private $processDone = false;

    public function __construct($data, $noRow, CSVParser &$parser) {

        $this->noRow = $noRow + 1;
        $this->parser = $parser;

        if (!is_array($data)) {
            return;
        }

        // Correction de l'encodage
        foreach ($data as $column => $value) {
            $data[$column] = Encoding::toUTF8($value);
        }


        // Le champ abonnement_id est rempli et valide
        if (!empty($data['abonnement_id'])) {
            $existing = Abonnement::model()->findByPk($data['abonnement_id']);
            if ($existing) {
                $this->state = self::MODIF;
                $this->DBAbo = $existing;
            }
        }

        // Analyse du champ perunilid
        elseif (!empty($data['perunilid'])) {
            $existing = Journal::model()->findByPk($data['perunilid']);
            if ($existing) {
                $this->state = self::CREATE;
                $this->jrn = $existing;
            }
        }

        // Recherche
        if (empty($this->state)) {

            if (!empty($data['journal-issn']) || !empty($data['journal-issnl'])) {
                $this->state = self::SEARCH;
                $this->search_journal_issn = trim($data['journal-issn']);
                $this->search_journal_issnl = trim($data['journal-issnl']);
            } elseif (!empty($data['journal-titre'])) {
                $this->state = self::SEARCH;
                $this->search_journal_titre = trim($data['journal-titre']);
            } else {
                // Aucune information valide
                $this->state = self::UNKNOWN;
            }
        }


        // Vérification des colonnes

        foreach ($data as $column => $value) {
            if (Abonnement::model()->hasAttribute($column)) {
                $this->validValues[$column] = $value;
            } else {
                $this->invalideValues[$column] = $value;
            }
        }

        // Si aucune colonne n'est valable, la ligne est rejetée
        if (empty($this->validValues)) {
            $this->state = self::REJECTED;
        }
    }

    //
    // I N T E R F A C E    P U B L I Q U E

    //
    
    /**
     * Retourne la liste des journaux issus du résultat de la recherche.
     * Si il n'y a aucun resultat, retourne un tableau vide.
     * 
     * @return array [perunilid][titre]
     */
    public function getSearchResults() {
        if ($this->state == self::SEARCH) {
            // Type de recherche
            if (!empty($this->search_journal_issn) || !empty($this->search_journal_issnl)) {
                return $this->search_issn();
            } elseif (!empty($this->search_journal_titre)) {
                return $this->search_titre();
            }
        }
        // Recherche impossible
        return array();
    }

    public function getState() {
        return $this->state;
    }

    public function getSearchLog() {
        if (empty($this->search_log)) {
            return "La recherche n'a donné aucun résultat";
        }
        return $this->search_log;
    }

    public function getValidValues() {
        if (!isset($this->validValues)) {
            return array();
        }
        return $this->validValues;
    }

    public static function isValidState($state) {
        $validStates = array(
            self::CREATE,
            self::MODIF,
            self::REJECTED,
            self::SEARCH,
            self::UNKNOWN,
            self::CREATE_SAVED,
            self::MODIF_SAVED
        );
        return in_array($state, $validStates);
    }

    public function setRejectedState() {
        $this->setState(self::REJECTED);
    }

    public function setCreateState($jrn) {
        if (!empty($jrn) && is_a($jrn, 'Journal')) {
            $this->jrn = $jrn;
            $this->setState(self::CREATE);
        } else {
            throw new Exception("Le journal n'est pas valide, l'état de la ligne $this->noRow ne peut être modifié");
        }
    }

    public function getChangeArray() {
        $this->process();
        return $this->changes;
    }

    public function getPerunilid() {
        if (!empty($this->jrn)) {
            return $this->jrn->perunilid;
        }
        elseif (!empty ($this->DBAbo)) {
            return $this->DBAbo->perunilid;
        }
        
        // La valeur validValues['perunilid'] peut ne pas être correct
//        elseif (!empty($this->validValues['perunilid'])) {
//            return $this->validValues['perunilid'];
//        } 
        else {
            return null;
        }
    }

    public function getJrnTitle() {
        if (!empty($this->jrn)) {
            return $this->jrn->titre;
        } elseif (!empty($this->validValues['perunilid'])) {
            $jrn = Journal::model()->findByPk($this->validValues['perunilid']);
            return $jrn->titre;
        } else {
            return null;
        }
    }

    public function getAboid() {
        if (!empty($this->DBAbo))
            return $this->DBAbo->abonnement_id;
        else
            return null;
    }

    public function save() {
        $this->process();
        if ($this->state == self::CREATE) {
            $newAbo = new Abonnement();
            $newAbo->setAttributes($this->DBAbo->getAttributes());
            $newAbo->perunilid = $this->getPerunilid();
            $newAbo->save();
            $this->setState(self::CREATE_SAVED);
            $this->DBAbo = $newAbo;
        } elseif ($this->state == self::MODIF) {
            $modifAbo = Abonnement::model()->findByPk($this->DBAbo->abonnement_id);
            $modifAbo->setAttributes($this->DBAbo->getAttributes());
            $modifAbo->perunilid = $this->getPerunilid();
            $this->setState(self::MODIF_SAVED);
            $modifAbo->save();
            $this->DBAbo = $modifAbo;
        }
    }

    //
    // F O N C T I O N S    P R I V E E S
    //
    
    
    protected function setState($state) {
        if (!self::isValidState($state)) {
            throw new Exception("L'état $state n'est pas admis pour une line de la classe CSVRow.");
        }
        $this->parser->uptdateRowCounter($this->state, $state);
        $this->state = $state;
    }

    protected function search_titre() {

        $this->searchComp = new SearchComponent();

        $this->searchComp->search_type = SearchComponent::TWORDS;
        $this->searchComp->maxresults = 10;
        $this->searchComp->simple_query_str = $this->search_journal_titre;

        $this->search_log = "Recherche du titre du journal avec les termes : '$this->search_journal_titre'.";

        return $this->normalizeArray($this->searchComp->simple_dp->rawData);
    }

    private function splitTerms($termsString) {
        $terms = array();
        foreach (explode(" ", $termsString) as $t) {
            if ($t != "" || $t != "") {
                $terms[] = "%$t%";
            }
        }
        return $terms;
    }

    protected function search_issn() {

        $issns = array_merge($this->splitTerms($this->search_journal_issnl), $this->splitTerms($this->search_journal_issn));

        $C1 = !empty($issns[0]) ? $issns[0] : "";
        $C2 = !empty($issns[1]) ? $issns[1] : "";
        $C3 = !empty($issns[2]) ? $issns[2] : "";

        $this->searchComp = new SearchComponent();

        $this->searchComp->search_type = SearchComponent::TWORDS;
        $this->searchComp->maxresults = 10;
        $this->searchComp->adv_query_tab = array(
            "advsearch" => "advsearch",
            "C1" => array("op" => "AND", "search_type" => "issn", "text" => $C1),
            "C2" => array("op" => "AND", "search_type" => "issn", "text" => $C2),
            "C3" => array("op" => "AND", "search_type" => "issn", "text" => $C3),
            "support" => "0",
            "accessunil" => "1",
            "openaccess" => "1",
            "sujet" => "",
            "plateforme" => "",
            "licence" => "",
            "statutabo" => "",
            "localisation" => "",
            "yt0" => "Chercher"
        );

        $this->search_log = "Recherche de l'issn et de l'issnl du journal avec les termes : '";
        $this->search_log .=!empty($this->search_journal_issn) ? $this->search_journal_issn . " " : "";
        $this->search_log .=!empty($this->search_journal_issnl) ? $this->search_journal_issnl : "";

        return $this->normalizeArray($this->searchComp->adv_dp->rawData);
    }

    private function normalizeArray($rawData) {
        $normalizedArray = array();
        foreach ($rawData as $row) {
            $perunilid = $row['perunilid'];
            $titre = Journal::model()->findByPk($perunilid)->titre;
            $normalizedArray[$perunilid] = $titre;
        }
        return $normalizedArray;
    }

    protected function process() {
        // Le traitement ne peut être effectué qu'une seule fois.
        if ($this->processDone) {
            return;
        }

        // Modification d'un abonnement existant
        if ($this->state == self::MODIF) {
            foreach ($this->validValues as $column => $newvalue) {
                // Si il existe un différence
                if (empty($this->DBAbo->$column) || $this->DBAbo->$column !== $newvalue) {
                    // Application des règle des valeurs
                    $newvalue = CSVValuesRules::rule($column, $newvalue, $this->DBAbo->$column, false);
                    // La modification est notée
                    $this->changes[$column] = array($this->DBAbo->$column, $newvalue);
                    // La modification est enregistrée, mais pas sauvegardée
                    $this->DBAbo->$column = $newvalue;
                }
            }
        } elseif ($this->state == self::CREATE) {
            $this->DBAbo = new Abonnement();
            $this->DBAbo->perunilid = $this->jrn->perunilid;
            foreach ($this->validValues as $column => $newvalue) {
                // Application des règle des valeurs
                $newvalue = CSVValuesRules::rule($column, $newvalue);
                $this->changes[$column] = array("", $newvalue);
                $this->DBAbo->$column = $newvalue;
            }
        }
    }

}
