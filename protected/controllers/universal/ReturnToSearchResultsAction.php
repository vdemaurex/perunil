<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Return2searchResultsAction
 *
 * @author vdemaure
 */
class ReturnToSearchResultsAction extends CAction {

    public function run() {
        $controller = $this->getController();
        // Vérifier l'existance du SearchComponent en mémoire
        if (isset(Yii::app()->session['search']) && Yii::app()->session['search'] instanceof SearchComponent) {
            // Un recherche existe, on affiche à nouveau la page des résultats.
            //$controller->render('/site/searchResults', array('search_done' => true, 'searchtype' => Yii::app()->session['searchtype']));
            switch (Yii::app()->session['searchtype']) {
                case 'adv':
                    $url = $controller->createUrl("site/advSearchResults");
                    break;

                case 'admin':
                    $url = $controller->createUrl("admin/searchResults");
                    break;

                default: // simple
                    $url = $controller->createUrl("site/simpleSearchResults");
                    break;
            }
        } else {
            // Aucune donnée sur une ancienne recherche, retrour à la recherche simple
            $url = $this->createUrl("site/simpleSearch");
        }
        
        $controller->redirect($url);
    }

}
