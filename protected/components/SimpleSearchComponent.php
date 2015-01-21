<?php

/**
 * Description of SimpleSearchComponent
 *
 * @author vdemaure
 */
class SimpleSearchComponent extends SearchComponent {

    /**
     * Commande SQL construite par cette classe, encapsulée dans un objet CDbCommand.
     * @var CDbCommand 
     */
    private $cmd;
    
    /**
     * Liste des colonnes de titre où s'effectue par défaut la recherche.
     * @var array 
     */
    private $cols = array('titre', 'titre_abrege', 'titre_variante', 'soustitre', 'faitsuitea', 'devient', 'a.commentaire_etatcoll');

    public function __construct() {
        $this->cmd = $cmd = Yii::app()->db->createCommand();
    }

    /**
     * Retourne un objet CDbCommand contenant une requête basée sur les paramètres
     * fournis.
     * @param string $query_str Chaîne de caractères contenant les terme à 
     *                          rechercher.
     * @param string $search_type Type de recherche à effectué, basé sur les 
     *                            constantes de la classe SearchComponent.
     * @return CDbCommand
     * @throws CException Levée si $search_type ne correspond à aucun type de
     *                    recherche connu.
     */
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
                $query_str = trim($query_str);
                // Recherche par début de titre, uniquement dans la colonne titre
                Yii::app()->session['search']->query_summary("Recherche d'un titre commençant par '$query_str'");
                $this->cmdWhereRaw($query_str . "%", array("titre"));
                break;

            case self::TEXACT:
                // Recherche par titre exact, uniquement dans la colonne titre
                $query_str = trim($query_str);
                Yii::app()->session['search']->query_summary("Recherche d'un titre correspondant exactement à '$query_str'");
                $this->cmdWhereRaw($query_str, array("titre"));
                break;

            case self::TWORDS:
                // Recherche chaque mot indépendament dans les colones de titres
                $cleanedQuery = $this->clean_search($query_str);
                Yii::app()->session['search']->query_summary("Recherche d'un titre contenant au moins un de ces mots : '$cleanedQuery'");
                $this->cmdWhereWord($cleanedQuery);
                break;

            case self::JRNALL:
                // Recherche chaque mot indépendament dans tous les champs de toutes les tables.
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

    /**
     * Spécifie les clauses SELECT, FROM et JOIN de la requête. Les tables
     * sont jointes selon les critères stockés dans l'objet de session
     * Yii::app()->session['search'], instance de SearchComponent.
     */
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

        // Jointure exculant les journaux sans abonnement pour les visiteur
        if (Yii::app()->user->isGuest) {
            $this->cmd->join('abonnement AS a', $joinCondition);
        }
        // Pour les administrateur, jointure yc des journaux sans abonnement
        else {
            $this->cmd->leftJoin('abonnement AS a', $joinCondition);
        }
    }

    /**
     * Spécifie les clauses ORDER BY et LIMIT de la requête selon les critères 
     * stockés dans l'objet de session Yii::app()->session['search'], 
     * instance de SearchComponent.
     */
    private function cmdOrderLimit() {
        $this->cmd->order('titre');
        if (Yii::app()->session['search']->maxresults > 0) {
            $this->cmd->limit($this->maxresults);
        }
    }

    /**
     * Recherche exact de la chaîne de caractères passée en paramètre. Par
     * défaut la recherche porte sur toutes les colonnnes, sauf si le paramètre
     * cols est fourni.
     * @param string $str Chaine de caractère à recherche. Peut contenir les
     *                    caractères d'échappement de MySQL, ex: '%'.
     * @param array $cols Tableau contenant la liste des colonne où $str doit 
     *                    être recherché. Si non fourni, $this->cols est utilisé.
     */
    private function cmdWhereRaw($str, $cols = null) {
        if (empty($cols)) {
            $cols = $this->cols;
        }
        // Boucle sur toutes les colonnes
        foreach ($cols as $col) {
            if (!empty($str)) {
                $rand = rand (1000 , 9999 );
                $this->cmd->orWhere(" $col LIKE :word$rand", array(":word$rand" => $str));
            }
        }
    }

    /**
     * La chaîne de caractère $str est séparée en mots. Chacun de ces mot est
     * recherché dans toutes les colonnes spécifiées par $this->cols. Les mots
     * sont recherchés selon le schéma %$word%. 
     * La requête ainsi construite est ajoutée à la clause WHERE de $this->cmd.
     * @param string $str Chaîne de caractères dont les mots doivent être
     *                    recherchés indépendament dans les colonnes définies 
     *                    dans $this->cols.
     */
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
                // Avec recherche en milieu de mot
                $rand = rand (1000 , 9999 );
                // un numéro unique est généré pour s'assurer que l'association
                // entre les unités de la requête préformatée et le tableau de
                // paramètres est unique.
                $filter_where[] = "$col LIKE :trunk{$rand}";
                $filter_where_data[":trunk{$rand}"] = "%$word%";
            }// Fin boucle mots
            $this->cmd->andWhere($filter_where, $filter_where_data);
        } // Fin boucle colonne
    }

    /**
     * La chaîne de caractère $str est séparée en mots. Chacun de ces mot est
     * recherché dans toutes les colonnes de toutes les tables de la base pour
     * lesquelles une telle recherche est pertinente.Les mots
     * sont recherchés selon le schéma %$word%. 
     * La requête ainsi construite est ajoutée à la clause WHERE de $this->cmd.
     * @param string $str Chaîne de caractères dont les mots doivent être
     *                    recherchés indépendament dans toutes les colonnes 
     *                    de toutes les tables
     */
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
        $this->cmd->leftJoin("fournisseur  AS four", "a.fournisseur  = four.fournisseur_id");


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
            $cols[] = "four.fournisseur";
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