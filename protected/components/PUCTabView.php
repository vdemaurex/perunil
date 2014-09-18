<?php

Yii::import('zii.widgets.CListView');

class PUCTabView extends CTabView
{

    /**
     * Renders the header part.
     */
    protected function renderHeader()
    {
        echo "<ul class=\"nav nav-tabs\" role=\"tablist\">\n";
        foreach ($this->tabs as $id => $tab) {
            $title = isset($tab['title']) ? $tab['title'] : 'undefined';
            $active = $id === $this->activeTab ? ' class="active"' : '';
            $url = isset($tab['url']) ? $tab['url'] : "#{$id}";
            echo "<li {$active}><a href=\"{$url}\">{$title}</a></li>\n";
        }
        echo "</ul>\n";
    }

    /**
     * Registers the needed CSS and JavaScript.
     */
    public function registerClientScript()
    {
        $cs = Yii::app()->getClientScript();
        
        $cs->registerCoreScript('yiitab');
        $id=$this->getId();
        $cs->registerScript('Yii.CTabView#'.$id,"jQuery(\"#{$id}\").yiitab();");
        
          $baseUrl = Yii::app()->baseUrl; 
         
         $cs->registerScriptFile($baseUrl.'/js/PUTabs.js');
    }

}
