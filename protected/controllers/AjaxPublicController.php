<?php

/**
 * Regroupe les fonction ajax de la partie publique
 *
 * @author vdemaure
 */
class AjaxPublicController extends Controller {

    /**
     * L'action par défaut renvoie à la page d'accueil.
     */
    public function actionIndex() {
        $this->redirect($this->createUrl("site/index"));
    }

    /**
     *  Fixe la valeur de depotlegal, si vrai, il doit être inclu.
     * @return type
     */
    function actionAutocompleteDepotLegalStatus() {
        $depotlegal = filter_input(INPUT_GET, 'depotlegal', FILTER_VALIDATE_BOOLEAN);

        // Mise à jour du dépot legal par ajax.
        if (!empty($depotlegal)) {
            Yii::app()->session['depotlegal'] = $depotlegal;
            return;
        }
    }

    function actionAutocomplete() {
        $withDepotLegal = false;

        if (!empty(Yii::app()->session['depotlegal'])) {
            $withDepotLegal = Yii::app()->session['depotlegal'];
        }
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        if (!empty($term)) {
            $results = $this->searchTitleWordBegining($term);
        }
        if (count($results) == 0) {
            $results = $this->searchTitleWordBeginingAllTerms($term);
        }
        echo CJSON::encode($results);
    }

    private function sqlQueryStart() {
        $sql = 'SELECT DISTINCT j.`perunilid` AS id, j.`titre` AS label FROM journal AS j ';

        if (Yii::app()->user->isGuest) {
            $sql .= " INNER JOIN abonnement AS a ON j.perunilid = a.perunilid AND a.titreexclu = 0 ";
        } else {
            $sql .= " LEFT JOIN abonnement AS a ON j.perunilid = a.perunilid ";
        }

        $sql .= ' WHERE ';

        if (empty(Yii::app()->session['depotlegal']) || !Yii::app()->session['depotlegal']) {
            $sql .= " (a.localisation NOT IN (" . SearchComponent::depotlegal_idlocalisation . ") OR a.localisation IS NULL) AND ";
        }

        return $sql;
    }

    private function searchTitleWordBegining($term) {
        $sql = $this->sqlQueryStart();
        $sql .= ' `titre` REGEXP "[[:<:]]' . addslashes(quotemeta($term)) . '" LIMIT 8';
        $cmd = Yii::app()->db->createCommand($sql);
        //$cmd->bindParam(":term", "^$term*");
        return $cmd->queryAll();
    }

    private function searchTitleWordBeginingAllTerms($term) {

        $tokens = array();
        foreach (explode(" ", $term) as $word) {
            if ($word != "" || $word != "") {
                // $tokens[] = "[[:<:]]$word"; //Recherche en début de mots
                $tokens[] = "%$word%"; //Recherche en début de mots
            }
        }
        if (count($tokens) == 0) {
            return array();
        }


        $sql = $this->sqlQueryStart();

        $OR = '';
        foreach (array('titre', 'soustitre') as $column) {
            $AND = '';
            $sql .= $OR . ' (';
            foreach ($tokens as $word) {
                $sql .= $AND . ' `' . $column . '` LIKE "' . $word . '" ';
                $AND = ' AND ';
            }
            $sql .= ') ';
            $OR = ' OR ';
        }
        $sql .= ' LIMIT 8;';

        $cmd = Yii::app()->db->createCommand($sql);
        //$cmd->bindParam(":term", "^$term*");
        return $cmd->queryAll();
    }

}
