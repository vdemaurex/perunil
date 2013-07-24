<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    public $layout = '//layouts/column1';

    /**
     * Si l'utilisateur est connnecté, on affiche sur la droit le menu d'administration. 
     *
    public function init() {
        if (Yii::app()->user->isGuest) {
            $this->layout = '//layouts/column1';
        } else {
            $this->layout = '//layouts/rightSidebar';
        }
    }*/

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    
    
    protected function activate_session_search_component(){
        if(isset(Yii::app()->session['search']) && Yii::app()->session['search'] instanceof SearchComponent ){
            return;
        }
        else{
            Yii::app()->session['search'] = new SearchComponent();
        }
    }
        
}

