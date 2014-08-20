<?php

/**
 * Description of SimpleSearchComponent
 *
 * @author vdemaure
 */
class SimpleSearchComponent extends SearchComponent {

    private $cmd;
    private $params;
    private $cols = array('titre', 'titre_abrege', 'titre_variante', 'soustitre', 'faitsuitea', 'devient');

    public function __construct() {
        $this->cmd = $cmd = Yii::app()->db->createCommand();
        $this->params = array();
    }

    public function getSimpleCdbCommand($query_str, $search_type) {
        Yii::app()->session['search']->emptyQuerySummary();
        // Si q ne contient qu'une seule lettre, on cherche TBEGIN
        if (strlen($query_str) == 1) {
            $search_type = self::TBEGIN;
        }

        $this->cmdSelectJoin();
        // Création des requêtes.
        switch ($search_type) {
            case self::TBEGIN:
                Yii::app()->session['search']->query_summary("Recherche d'un titre commençant par '$query_str'");
                $this->cmdWhereRaw($query_str . "%", array("titre"));
                break;

            case self::TEXACT:
                Yii::app()->session['search']->query_summary("Recherche d'un titre correspondant exactement à '$query_str'");
                $this->cmdWhereRaw($query_str);
                break;

            case self::TWORDS:
                $cleanedQuery = $this->clean_search($query_str);
                Yii::app()->session['search']->query_summary("Recherche d'un titre contenant au moins un de ces mots : '$cleanedQuery'");
                $this->cmdWhereWord($cleanedQuery);
                break;

            case self::JRNALL:
                $cleanedQuery = $this->clean_search($query_str);
                Yii::app()->session['search']->query_summary("Recherche dans l'ensemble des champs de la base Pérunil des mots '$cleanedQuery'");
                $this->cmdWhereAll($cleanedQuery);
                break;

            default:
                throw new CException("Ce type de recherche ($search_type) n'est pas pris en charge.");
        }

        $this->cmdOrderLimit();
        return $this->cmd;
    }

    private function cmdSelectJoin() {
        $this->cmd->select("j.perunilid");
        $this->cmd->distinct = true;

        $this->cmd->from("journal AS j");

        // Jointure de l'abonnement pour la sélection du support
        // La sélection du dépot legal impose de faire la jointure avec les abonnements dans tous les cas.
        $joinCondition = "j.perunilid = a.perunilid";

        // Si l'utilisateur et invité, les titres exclus ne sont pas sélectionnés
        if (Yii::app()->user->isGuest) {
            $joinCondition .= " AND a.titreexclu = 0";
        }

        // Limitation au support
        if (Yii::app()->session['search']->support > 0) {
            $support = Yii::app()->session['search']->support;
            $joinCondition .= " AND a.support = $support ";
            Yii::app()->session['search']->query_summary(" au format " . Support::model()->findByPk($support)->support);
        }

        // Ajout ou exculsion des abonnements du dépot légal
        if (Yii::app()->session['search']->depotlegal) {
            Yii::app()->session['search']->query_summary("avec les périodiques du dépot légal BCU");
        } else {
            $joinCondition .= " AND (a.localisation NOT IN (" . self::depotlegal_idlocalisation . ") OR a.localisation IS NULL) ";
        }

        $this->cmd->join('abonnement AS a', $joinCondition);
    }

    private function cmdOrderLimit() {
        $this->cmd->order('titre');
        if (Yii::app()->session['search']->maxresults > 0) {
            $this->cmd->limit($this->maxresults);
        }
    }

    private function cmdWhereRaw($str, $cols = null) {
        if (empty($cols)) {
            $cols = $this->cols;
        }
        // Boucle sur toutes les colonnes
        foreach ($cols as $col) {
            if (!empty($str)) {
                $this->cmd->orWhere(" $col LIKE :{$col}word", array(":{$col}word" => $str));
            }
        }
    }

    private function cmdWhereWord($str) {

        $words = explode(" ", $str);
        foreach ($words as $i => $word) {
            if (empty($word)) {
                continue;
            }
            $filter_where = array('OR');
            $filter_where_data = array();
            // Pour chaque colone, on cherche les mots
            foreach ($this->cols as $col) {

                // Avec troncature gauche
//                $filter_where[] = "$col LIKE :{$col}trunk{$i}A OR $col LIKE :{$col}trunk{$i}B";
//                $filter_where_data[":{$col}trunk{$i}A"] = "$word%";
//                $filter_where_data[":{$col}trunk{$i}B"] = "% $word%";
                // Avec recherche en milieu de mot
                $filter_where[] = "$col LIKE :{$col}trunk{$i}";
                $filter_where_data[":{$col}trunk{$i}"] = "%$word%";
            }// Fin boucle mots
            $this->cmd->andWhere($filter_where, $filter_where_data);
        } // Fin boucle colonne
    }

    private function cmdWhereAll($str) {
        // Jointure des tables liées à abonnement
        $this->cmd->leftJoin("plateforme   AS pl", "a.plateforme   = pl.plateforme_id");
        $this->cmd->leftJoin("editeur      AS ed", "a.editeur      = ed.editeur_id");
        $this->cmd->leftJoin("histabo      AS ha", "a.histabo      = ha.histabo_id");
        $this->cmd->leftJoin("statutabo    AS st", "a.statutabo    = st.statutabo_id");
        $this->cmd->leftJoin("localisation AS lo", "a.localisation = lo.localisation_id");
        $this->cmd->leftJoin("gestion      AS ge", "a.gestion      = ge.gestion_id");
        $this->cmd->leftJoin("format       AS fo", "a.format       = fo.format_id");
        $this->cmd->leftJoin("licence      AS li", "a.licence      = li.licence_id");


        // Colonnes de recherche
        $cols = array(
            // Journal
            "j.perunilid",
            "j.titre",
            "j.soustitre",
            "j.titre_abrege",
            "j.titre_variante",
            "j.faitsuitea",
            "j.devient",
            "j.issn",
            "j.issnl",
            "j.nlmid",
            "j.reroid",
            "j.doi",
            "j.coden",
            "j.urn",
            "j.url_rss",
            "j.commentaire_pub",
            // Abonnement
            "a.package",
            "a.no_abo",
            "a.url_site",
            "a.embargo_mois",
            "a.acces_user",
            "a.acces_pwd",
            "a.etatcoll",
            "a.cote",
            "a.editeur_code",
            "a.editeur_sujet",
            "a.commentaire_pub",
            //Tables associées à Abonnement
            "pl.plateforme",
            "ed.editeur",
            "ha.histabo",
            "st.statutabo",
            "lo.localisation",
            "ge.gestion",
            "fo.format",
            "li.licence",
        );

        // Si admin on ajoute quelques champs
        if (!Yii::app()->user->isGuest) {
            $cols[] = "j.DEPRECATED_sujetsfm";
            $cols[] = "j.DEPRECATED_fmid";
            $cols[] = "j.DEPRECATED_historique";
            $cols[] = "a.commentaire_pro";
        }

        // Boucle sur toutes les colonnes
        foreach (explode(" ", $str) as $i => $word) {
            if (empty($word)) {
                continue;
            }
            $filter_where = array('OR');
            $filter_where_data = array();

            // Boucle sur touts les mots de la recherche
            foreach ($cols as $col) {
                $rand = rand (1000 , 9999 );
                $filter_where[] = "$col LIKE :word{$rand}";
                $filter_where_data[":word{$rand}"] = "%$word%";
            }
            $this->cmd->andWhere($filter_where, $filter_where_data);
        }
    }
}

/*
 * ARCHIVES
 */

//    private function cmdWhereWord($str){
//
//            $words = explode(" ", $str);
//            $where = "";
//            foreach ($words as $word) {
//                if (empty($word)) {
//                    continue;
//                }
//                $wq = "";
//
//                // Pour chaque colone, on cherche les mots
//                foreach ($this->cols as $col) {
//                    $cq = ' `' . $col . '` LIKE "' . $word . '%" OR ';
//                    $cq .= ' `' . $col . '` LIKE "% ' . $word . '%"  ';
//
//                    $wq .= " $cq OR ";              
//                }// Fin boucle mots
//                
//                $wq = trim($wq, "OR ");
//                $where .= " ( $wq ) AND";
//            } // Fin boucle colonne
//            
//            // Suppression d'un opérateur surnuméraire
//            $where = trim($where, "AND ");
//            $this->cmd->where($where);
//        }
