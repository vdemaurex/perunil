<?php

class BatchProcessingAction extends CAction
{
    /**
     * Tableau des parmetres qui seront transmis à la fonction render.
     * @var array 
     */
    private $rp = array();
    
    /**
     * Si vrai la valeur des champs text donné par l'utilisateur est ajouté au
     * contenu existant. 
     * Si faux,le contenu des champs text est remplacé par la valeur fournie
     * par l'utilisateur.
     * @var boolean 
     */
    private $addText;

    /**
     * Etape du traitement par lot.
     * @var string
     */
    private $stage;


    public function run()
    {
        $this->addText = isset($_POST['add_text']) && $_POST['add_text'] == false ? false : true;
        $this->stage = isset($_POST['stage']) ? $_POST['stage'] : "1-form";

        switch ($this->stage) {

            case '1-form': // Etape 1 : le formulaire n'a pas été rempli
                $this->showForm();
                break;


            case '2-preview': // Etape 2 : le formulaire est rempli, affichage de la confirmation
                $this->preview();
                break;


            case '3-done': // Etape 3 : la confirmation a été validée, application des changements
                $this->applyChanges();
                break;

            default: // Valeur inconnue
                throw new CException("L'action '$this->stage' n'existe pas. Impossible de procéder à la modification par lot.", 1);
                break;
        }

        $this->rp['add_text'] = $this->addText;
        $this->rp['stage'] = $this->stage;
        $this->controller->render('batchprocessing', $this->rp);
    }

    /**
     * Etape 1 : le formulaire n'a pas été rempli
     */
    private function showForm()
    {
        // Nettoyage des variables de session
        if (isset(Yii::app()->session['updt'])) {
            unset(Yii::app()->session['updt']);
        }
    }

    /**
     * Etape 2 : le formulaire est rempli, affichage de la confirmation
     */
    private function preview()
    {
        $a = $_POST['Abonnement'];
        $updt = array();

        // Traitement des boolean    
        foreach (array('titreexclu', 'acces_elec_unil', 'acces_elec_chuv', 'acces_elec_gratuit') as $boolinput) {
            if (isset($a[$boolinput]) && ($a[$boolinput] == '1' || $a[$boolinput] == '0' )) {
                $updt[$boolinput] = $a[$boolinput];
            }
        }

        // Traitement des nombres
        foreach (array('embargo_mois', 'etatcoll_deba', 'etatcoll_debv', 'etatcoll_debf', 'etatcoll_fina', 'etatcoll_finv', 'etatcoll_finf') as $num) {
            if (isset($a[$num]) && ctype_digit($a[$num])) {
                $updt[$num] = $a[$num];
            }
        }

        // Vérification des liens
        foreach ($this->controller->abolinks as $link) {
            if (isset($a[$link]) && ctype_digit($a[$link])) {
                $class = ucfirst($link);
                // Si le lien existe, on le met à jour
                if ($class::model()->findByPk($a[$link])) {
                    $updt[$link] = $a[$link];
                }
            }


            // Si l'entrée est NULL, on supprime le lien
            if (isset($a[$link]) && trim($a[$link]) == 'NULL') {
                $updt[$link] = "NULL";
            }
        }

        // Traitement des textes
        $text_to_update = false;
        foreach ($this->controller->textfields as $txt) {
            if (isset($a[$txt]) && trim($a[$txt]) != "") {
                $updt[$txt] = $a[$txt];
                $text_to_update = true;
            }
        }

        // Si aucun changement
        if (count($updt) < 1) {
            Yii::app()->user->setFlash('notice', "Il n'y a aucun changement à appliquer sur ce lot.");
            $this->stage = '1-form';
        }

        //Affichage des changements
        Yii::app()->session['updt'] = $updt;
        $this->rp['updt'] = $updt;
    }

    
    /**
     * Etape 3 : la confirmation a été validée, application des changements
     */
    private function applyChanges()
    {
        // Etape possible seulement si Yii::app()->session['updt'] est défini. 
        // Sinon on affiche un message d'erreur et on retourne à l'étape 1
        if (!isset(Yii::app()->session['updt']) && !is_array(Yii::app()->session['updt'])) {
            Yii::app()->user->setFlash('error', "Impossible de traiter ce lot, merci de recommencer votre requête.");
            $this->stage = '1-form';
            return;
        }

        // Traitement des confirmations utilisateur
        $oldupdt = Yii::app()->session['updt'];
        $updt = array();
        // récupération des champs séléctionnés
        foreach ($_POST as $key => $checked) {
            if (isset($oldupdt[$key])) {
            // Cette entrée de post est un attibut de la table Abonnement
            // On ne conserve les données que si elles ont été validées par l'utilisateur.
                if ($checked) {
                    $updt[$key] = $oldupdt[$key];
                }
            }
        }
        // Mise à jour des données de session
        Yii::app()->session['updt'] = $updt;

        // Application des mise à jours
        $update_results = array(
            true => array(), // update réussis
            false => array()); // update échoués
        $nbr_rows = 0; // nombre de lignes mises à jour
        
        //Passage au mode abonnement
        Yii::app()->session['search']->setAdmin_affichage('abonnement');
        // Mise à jour de tous les éléments du lot
        
        foreach (Yii::app()->session['search']->getAdminIds() as $abonnement_id) {
            $abo = Abonnement::model()->findByPk($abonnement_id);
            
            $updt_local = $updt;
            foreach ($this->controller->textfields as $textfield) {
                if (isset($updt_local[$textfield])) {
                    // Si le texte doit être ajouter et non remplacé
                    if ($this->addText) {
                        $updt_local[$textfield] = $abo->$textfield . " " . $updt[$textfield];
                    }
                    // Si le mot clé NULL est dans le champs, il doit être vidé.
                    if ($updt[$textfield] == 'NULL') {
                        $updt_local[$textfield] = "";
                    }
                }
            }

            // Application de la mise à jour
            foreach ($updt_local as $field => $value) {
                $abo->$field = $value;
            }
            $result = $abo->save();

            // Collecte des statistiques
            $update_results[$result][] = $abonnement_id;
            $nbr_rows++;
            unset($updt_local);
        }
        
        if (count($update_results[false]) == 0) {
            Yii::app()->user->setFlash('success', "<strong>La modification par lot a réussi.</strong><br/>" .
                    "Modification de $nbr_rows lignes sur un total de " . Yii::app()->session['totalItemCount'] . ".");
        } else {
            if (count($update_results[true]) == 0) {
                Yii::app()->user->setFlash('error', "<strong>La modification par lot a échoué, aucune ligne n'a été modifiée.</strong><br/>" .
                        "Modification de $nbr_rows lignes sur un total de " . Yii::app()->session['totalItemCount'] . ".");
            } else {
                Yii::app()->user->setFlash('notice', "<strong>La modification par lot n'a pu être appliquée que partiellement.</strong><br/>" .
                        "Modification de $nbr_rows lignes sur un total de " . Yii::app()->session['totalItemCount'] . ".");
            }
        }
        
        $this->rp['updt'] = Yii::app()->session['updt'];
        $this->rp['update_results'] = $update_results;
        
        // Suppression de la liste des update pour éviter une nouvelle mise à jour accidentelle
        if (isset(Yii::app()->session['updt']))
            unset(Yii::app()->session['updt']);
    }
}
