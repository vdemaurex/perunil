<?php

/**
 * Effectue la recherche basée sur le formulaire de recherche admin.
 */
class AdminSearchComponent extends SearchComponent
{

    /**
     * Référence au SearchComponent qui a initié la recherche.
     * @var SearchComponent
     */
    private $sc;

    /**
     * La commande SQL élaborée par AdminSearchComponent.
     * @var CDbCommand 
     */
    private $cmd;

    /**
     * Tableau des champs du formuaire de recherche.
     * @var array 
     */
    private $queryTab;

    /**
     * Tableau de corespondance entre les mots clés du formulaire et
     * les signes des opérateur SQL.
     * 
     * @var array 
     */
    private $ct = array('equal' => '=', 'before' => '<', 'after' => '>');

    ///////////////////////////////////////////////////////////////////////////
    // Interface publique
    ///////////////////////////////////////////////////////////////////////////

    /**
     * Crée une instance d'AdminSearchComponent basée sur les données du 
     * SearchComponent passé en paramètre.
     * 
     * @param SearchComponent $sc
     */
    public function __construct(SearchComponent $sc)
    {
        $this->cmd = $cmd = Yii::app()->db->createCommand();
        $this->sc = $sc;
        $this->queryTab = $sc->admin_query_tab;
    }

    /**
     * Renvoie la liste des abonnement_id qui aux critères de recherche
     * stockés dans le admin_query_tab du SearchComponent passé en paramètre
     * du constructeur.
     * 
     * @return array Liste d'abonnement_id
     */
    public function getAboIdList()
    {
        $this->selectAbo();

        return array_map('current', $this->buildCmd()->queryAll());
    }

    /**
     * Renvoie la liste des perunilid qui correspondent aux critères de recherche
     * stockés dans le admin_query_tab du SearchComponent passé en paramètre
     * du constructeur.
     * 
     * @return array Liste de perunilid
     */
    public function getPerunilidList()
    {
        $this->selectJrn();

        return array_map('current', $this->buildCmd()->queryAll());
    }

    ///////////////////////////////////////////////////////////////////////////
    // Fonctions privées
    //////////////////////////////////////////////////////////////////////////

    /**
     * Ajoute à $this-cmd les commandes SELECT et FROM et JOIN pour obtenir
     * une liste d'abonnement_id, basé sur la table Abonnement.
     */
    private function selectAbo()
    {
        $this->sc->emptyQuerySummary();

        $this->cmd->select("a.abonnement_id");
        //$this->cmd->distinct = true;

        $this->cmd->from("abonnement AS a");

        $this->cmd->join('journal AS j', "a.perunilid = j.perunilid");
    }

    /**
     * Ajoute à $this-cmd les commandes SELECT et FROM et JOIN pour obtenir
     * une liste de perunilid, basé sur la table Journal.
     */
    private function selectJrn()
    {
        $this->sc->emptyQuerySummary();
        $this->cmd->select("j.perunilid");
        $this->cmd->distinct = true;
        $this->cmd->from("journal AS j");
        $this->cmd->join('abonnement AS a', "a.perunilid = j.perunilid");
    }

    /**
     * Ajout à $this-cmd les paramètres de WHERE et les JOIN nécessaires en
     * fonction des données présentes dans $this->queryTab.
     * 
     * Nécessite d'exectuer préalablement $this->selectAbo() ou $this->selectJrn().
     * 
     * La liste des sous-fonctions appelées est donnée par la variable $fields.
     * 
     * @return CDbCommand la commande consturite. Equivalant à $this-cmd. 
     */
    private function buildCmd()
    {

        $fields = array(
            "addSujet" => "sujet",
            "addEditeur" => "editeur_txt",
            "searchPerunilid" => "perunilid1",
            "searchCorecollection" => "corecollection",
            "searchAll" => "all",
            "searchEmbargo" => "embargo",
        );

        foreach ($fields as $functionName => $fieldName) {
            if (!empty($this->queryTab[$fieldName])) {
                $this->$functionName();
            }
        }

        // recherche des la dernière modification
		if (trim($this->queryTab['datemodif1'])
			|| trim($this->queryTab['signaturemodification']) 
			|| trim($this->queryTab['datecreation1']) 
			|| trim($this->queryTab['signaturecreation'])) {

            $this->searchModif();
            $this->searchCreation();
        }

        $this->searchLikeTerms();
        $this->searchExactTerm();
        $this->searchExactLink();

        return $this->cmd;
    }

    /**
     * Vérifie que le id du sujet donné dans $this->queryTab['sujet'] est
     * correct. Joint la table journal_sujet selon le id donné.
     */
    private function addSujet()
    {
        // Vérification de la validité du sujet
        $sujet = Sujet::model()->findByPk($this->queryTab['sujet']);
        if ($sujet) {
            $this->cmd->join("journal_sujet AS js", "js.perunilid =j.perunilid AND js.sujet_id =" . $this->queryTab['sujet']);
            $this->sc->query_summary("sujet = " . $sujet->nom_fr);
        }
    }

    /**
     * Joint la table editeur.
     */
    private function addEditeur()
    {
        $this->cmd->join('editeur AS ed', "a.editeur = ed.editeur_id");
    }

    /**
     * Ajoute à la clause WHERE les critère de recherche des quatres champs du
     * formulaire Admin concernant le perunilid :
     * $this->queryTab['perunilidcrit1'] : opérateur 1
     * $this->queryTab['perunilid1']     : champ de recherche du perunilid
     * $this->queryTab['perunilidcrit2'] : opérateur 1
     * $this->queryTab['perunilid2']     : champ de recherche optionel d'un perunilid 
     *                                     pour créer un intervale de recherche.
     */
    private function searchPerunilid()
    {
        //Recherche par perunilid
        $operateur = $this->ct[$this->queryTab['perunilidcrit1']];
        $this->cmd->andWhere("a.perunilid $operateur '{$this->queryTab['perunilid1']}'");
        $this->sc->query_summary("perunilid $operateur  {$this->queryTab['perunilid1']} ");

        // S'il y a un deuxième perunilid
        if (trim($this->queryTab['perunilid2'])) {
            $operateur = $this->ct[$this->queryTab['perunilidcrit2']];
            $this->cmd->andWhere("a.perunilid $operateur '{$this->queryTab['perunilid2']}'");
            $this->sc->query_summary("perunilid $operateur  {$this->queryTab['perunilid2']} ");
        }
    }

    /**
     * Recherche de la case à cocher Corecollection. 
     * Si $this->queryTab['corecollection'] == 'VRAI' : Joint la table corecollection avec comme
     * critère que la bibliothèque corresponde à self::BiUM_Corecollection.
     * Si $this->queryTab['corecollection'] == 'FAUX' : Exclu la bibliothèque self::BiUM_Corecollection
     * des résultat de la recherche
     */
    private function searchCorecollection()
    {
        if ($this->queryTab['corecollection'] == 'VRAI') { // Joindre la corecollection BiUM
            $this->cmd->join("corecollection AS cc", 'j.perunilid = cc.perunilid AND cc.biblio_id = ' . self::BiUM_Corecollection);
            $this->sc->query_summary("avec la core collection BiUM");
        } elseif ($this->queryTab['corecollection'] == 'FAUX') { // Exclure la corecollection BiUM
            $this->cmd->andWhere("j.perunilid NOT IN (SELECT c.perunilid FROM corecollection AS c WHERE c.biblio_id = " . self::BiUM_Corecollection . ")");
            $this->sc->query_summary("sans la core collection BiUM");
        }
    }

    /**
     * Ajoute à la clause WHERE les critère de recherche des deux champs
     * qui définissent le nombre de mois d'embargo.
     */
    private function searchEmbargo()
    {
        $operateur = $this->ct[$this->queryTab['embargocrit']];
        $this->cmd->andWhere("a.embargo_mois $operateur '" . $this->queryTab['embargo'] . "'");
        $this->sc->query_summary("a.embargo_mois $operateur " . $this->queryTab['embargo']);
    }

    /**
     * Recherche de la dernière modification de l'abonnement, selon le champ 
     * abonnement.modification.
     */
    private function searchModif()
    {

		if (strpos($this->cmd->from,"journal")){
			$this->cmd->join("modifications AS m", 'j.modification = m.id');
		}
		else{
			$this->cmd->join("modifications AS m", 'a.modification = m.id');
		}

		// Préparation de la requête pour des recherche sur la modification
		if (trim($this->queryTab['datemodif1'])) {
			$phpdate = strtotime(trim($this->queryTab['datemodif1']));
			$mysqldate = date('Y-m-d H:i:s', $phpdate);
			$operateur = $this->ct[$this->queryTab['datemodifcrit1']];
			$this->cmd->andWhere("m.stamp $operateur :stampii", array(":stampii" => $mysqldate));
			$this->sc->query_summary("date de modification $operateur " . $this->queryTab['datemodif1']);

			// Deuxième date de modification
			if (trim($this->queryTab['datemodif2'])) {
				$phpdate = strtotime(trim($this->queryTab['datemodif2']));
				$mysqldate = date('Y-m-d H:i:s', $phpdate);
				$operateur = $this->ct[$this->queryTab['datemodifcrit2']];
                $this->cmd->andWhere("m.stamp $operateur :stampiii", array(":stampiii" => $mysqldate));
                $this->sc->query_summary(" et $operateur " . $this->queryTab['datemodif2']);
            } // datemodif2
        } // datemodif1
        // Recherche d'après le modificateur
        if (trim($this->queryTab['signaturemodification'])) {
            $s = trim($this->queryTab['signaturemodification']);
            $this->cmd->andWhere('m.user_id = :sm', array(':sm' => $s));
            $this->sc->query_summary("La notice à été modifiée par " . Utilisateur::model()->findByPk($s)->pseudo);
        }
    }

    /**
     * Recherche de la création de l'abonnement selon le champ abonnement.création
     */
    private function searchCreation()
    {
	if (strpos($this->cmd->from,"journal")){
			$this->cmd->join("modifications AS c", 'j.modification = c.id');
		}
        else{
		$this->cmd->join("modifications AS c", 'a.modification = c.id');
	}

        // Préparation de la requête pour des recherche sur la création
        if (trim($this->queryTab['datecreation1'])) {
            $phpdate = strtotime(trim($this->queryTab['datecreation1']));
            $mysqldate = date('Y-m-d H:i:s', $phpdate);
            $operateur = $this->ct[$this->queryTab['datecreationcrit1']];

            $this->cmd->andWhere("c.stamp $operateur :stamp", array(":stamp" => $mysqldate));
            $this->sc->query_summary("date de création $operateur " . $this->queryTab['datecreation1']);

            // Deuxième date de création
            if (trim($this->queryTab['datecreation2'])) {
                $phpdate = strtotime(trim($this->queryTab['datecreation2']));
                $mysqldate = date('Y-m-d H:i:s', $phpdate);
                $operateur = $this->ct[$this->queryTab['datecreationcrit2']];

                $this->cmd->andWhere("c.stamp $operateur :stampi", array(":stampi" => $mysqldate));
                $this->sc->query_summary(" et $operateur " . $this->queryTab['datecreation2']);
            } // datecreation2
        } // datecreation1
        // Recherche d'après le créateur
        if (trim($this->queryTab['signaturecreation'])) {
            $s = trim($this->queryTab['signaturecreation']);

            $this->cmd->andWhere('c.user_id = :sc', array(':sc' => $s));
            $this->sc->query_summary("le créateur de la notice est " . Utilisateur::model()->findByPk($s)->pseudo);
        }
    }

    /**
     * Recherche dans tous les champs, selon la fonction du SimpleSearchComponent.
     * Ajoute la liste de Perunilid ainsi obtenu à la clause WHERE.
     */
    private function searchAll()
    {
        $simpleSearch = new SimpleSearchComponent;
        $simple_sql_cmd = $simpleSearch->getSimpleCdbCommand($this->queryTab['all'], self::JRNALL);
        $perunilids = $simple_sql_cmd->queryAll();
        $ids = join("','", array_map('current', $perunilids));
        // Ajout de la liste des ids concerné par la modification

        $this->cmd->andWhere("j.perunilid IN ('$ids')");
    }

    /**
     * Selon la présence des champs dans $this->querytab, ajout à la clause WHERE des comparaison
     * avec l'opérateur LIKE
     */
    private function searchLikeTerms()
    {
        // Recherche de tous les champs au format texte : "LIKE %$term%"
        $textfield = @array(
            'j.titre' => explode(" ", $this->queryTab['titre']),
            'j.soustitre' => explode(" ", $this->queryTab['soustitre']),
            'j.titre_abrege' => explode(" ", $this->queryTab['titreabrege']),
            'j.titre_variante' => explode(" ", $this->queryTab['variantetitre']),
            'j.faitsuitea' => explode(" ", $this->queryTab['faitsuitea']),
            'j.devient' => explode(" ", $this->queryTab['devient']),
            'ed.editeur' => explode(" ", $this->queryTab['editeur_txt']),
            'j.issnl' => $this->queryTab['issnl'],
            'j.issn' => $this->queryTab['issn'],
            'j.reroid' => $this->queryTab['reroid'],
            'j.nlmid' => $this->queryTab['nlmid'],
            'j.coden' => $this->queryTab['coden'],
            'j.doi' => $this->queryTab['doi'],
            'j.urn' => $this->queryTab['urn'],
            'j.url_rss' => $this->queryTab['rss'],
            'a.url_site' => $this->queryTab['url'],
            'a.editeur_code' => $this->queryTab['codeediteur'],
            'a.acces_user' => $this->queryTab['user'],
            'a.acces_pwd' => $this->queryTab['pwd'],
            'a.package' => $this->queryTab['package'],
            'a.no_abo' => $this->queryTab['no_abo'],
            'a.etatcoll' => $this->queryTab['etatcoll'],
            'a.cote' => $this->queryTab['cote'],
            'a.commentaire_pro' => explode(" ", $this->queryTab['commentairepro']),
            'a.commentaire_pub' => explode(" ", $this->queryTab['commentairepub']),
            'j.commentaire_pub' => explode(" ", $this->queryTab['commentairepub']),
            'a.DEPRECATED_sujetsfm' => $this->queryTab['sujetsfm'],
            'a.DEPRECATED_fmid' => $this->queryTab['fmid'],
            //'j.DEPRECATED_historique' => $sm_name,
            'j.DEPRECATED_historique' => explode(" ", $this->queryTab['historique']),
        );
        foreach ($textfield as $column => $value) {
            // Pour champs dont on fait une recherche terme à terme
            if (is_array($value)) {
                $this->searchWordByWord($column, $value);
            }
            // Pour les champs dont la recherche ne porte que sur un seul terme
            else {
                $this->searchOneWord($column, $value);
            }
        }
    }

    /**
     * Ajout à la clause WHERE d'une recherche de plusieurs termes ($words) avec
     * l'operateur LIKE se rapportant à la $column.
     * 
     * @param string $column Nom de la colonne avec préfixe : Ex. j.titre
     * @param array $words Liste de mots à rechercher
     */
    private function searchWordByWord($column, $words)
    {
        $filter_where = array('AND');
        $filter_where_data = array();

        foreach ($words as $term) {
            $term = trim($term);
            if (!empty($term)) {
                $rand = rand(1000, 9999);
                $filter_where[] = "$column LIKE :term{$rand}";
                $filter_where_data[":term{$rand}"] = "%$term%";
            }
        }
        if (count($filter_where_data) > 0) {
            $this->cmd->andWhere($filter_where, $filter_where_data);
            $this->sc->query_summary(substr($column, 2) . " = " . implode(" ", $words));
        }
    }

    /**
     * Ajout à la clause WHERE d'une recherche d'un seul terme ($words) avec
     * l'operateur LIKE se rapportant à la $column.
     * 
     * @param string $column Nom de la colonne avec préfixe : Ex. j.titre
     * @param string $word mot à rechercher
     */
    private function searchOneWord($column, $word)
    {
        $word = trim($word);
        if (!empty($word)) {
            $rand = rand(1000, 9999);
            $this->cmd->andWhere(" $column LIKE :word$rand", array(":word$rand" => "%$word%"));
            $this->sc->query_summary(substr($column, 2) . " = $word");
        }
    }

    /**
     * Ajout à la clause WHERE d'une recherche d'un seul terme ($words) avec
     * l'operateur = se rapportant à la $column.
     * 
     * @param string $column Nom de la colonne avec préfixe : Ex. j.titre
     * @param string $word mot à rechercher
     * @param boolean $isLink true si le paramètre est le id d'une table liée.
     */
    private function searchExactWord($column, $word, $isLink = false)
    {
        $columnWoPrefix = substr($column, 2);
        $word = trim($word);
        if (!empty($word) || $word==="0") {
            $rand = rand(1000, 9999);
            $this->cmd->andWhere(" $column = :word$rand", array(":word$rand" => $word));
            
            // Récupération du champ de la table liée.
            if ($isLink) {
                try {
                    $classname = ucfirst($columnWoPrefix);
                    $linkClass = $classname::model()->findByPk($word);
                    $linkname = $linkClass->$columnWoPrefix;
                    $this->sc->query_summary("$columnWoPrefix = $linkname");
                    return;
                } catch (Exception $exc) {
                    //echo $exc->getTraceAsString();
                }
            }
            $this->sc->query_summary("$columnWoPrefix = $word");
        }
    }

    /**
     * Selon la présence des champs dans $this->querytab, ajout à la clause WHERE des comparaison
     * avec l'opérateur =
     */
    private function searchExactTerm()
    {
        $exact_fields = @array(
            'j.openaccess' => $this->queryTab['openaccess'],
            'j.parution_terminee' => $this->queryTab['parution_terminee'],
            'j.publiunil' => $this->queryTab['publiunil'],
            'a.titreexclu' => $this->queryTab['titreexclu'],
            'a.etatcoll_deba' => $this->queryTab['etatcolldeba'],
            'a.etatcoll_debv' => $this->queryTab['etatcolldebv'],
            'a.etatcoll_debf' => $this->queryTab['etatcolldebf'],
            'a.etatcoll_fina' => $this->queryTab['etatcollfina'],
            'a.etatcoll_finv' => $this->queryTab['etatcollfinv'],
            'a.etatcoll_finf' => $this->queryTab['etatcollfinf'],
            'a.acces_elec_gratuit' => $this->queryTab['acces_elec_gratuit'],
            'a.acces_elec_chuv' => $this->queryTab['acces_elec_chuv'],
            'a.acces_elec_unil' => $this->queryTab['acces_elec_unil'],
        );
        foreach ($exact_fields as $column => $value) {
            $this->searchExactWord($column, $value);
        }
    }

    /**
     * Selon la présence des champs dans $this->querytab, ajout à la clause WHERE des comparaison
     * avec l'opérateur = et un affichage du nom complet de la table liée dans
     * query_summary.
     */
    private function searchExactLink()
    {
        $exact_fields = @array(
            'a.licence' => $this->queryTab['licence'],
            'a.statutabo' => $this->queryTab['statutabo'],
            'a.plateforme' => $this->queryTab['plateforme'],
            'a.gestion' => $this->queryTab['gestion'],
            'a.histabo' => $this->queryTab['histabo'],
            'a.support' => $this->queryTab['support'],
            'a.format' => $this->queryTab['format'],
            'a.editeur' => $this->queryTab['editeur'],
            'a.localisation' => $this->queryTab['localisation'],
        );
        foreach ($exact_fields as $column => $value) {
            $this->searchExactWord($column, $value, true);
        }
    }

}
