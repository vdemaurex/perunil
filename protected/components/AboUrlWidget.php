<?php

class AboUrlWidget extends CWidget {

    public $jrn;
    public $abo;
    private $url;
    private $link_text;
    private $link_title = "Cliquez pour accéder au site hébérgeant cette publication";

    /**
     * Vrai si le pérodique est au format papier
     * @var boolean 
     */
    private $papier;
    private $papier_link = "Cliquer pour accéder à la notice du catalogue collectif vaudois RERO.";

    public function init() {
        // Initialisation des variables
        $this->papier = (isset($this->abo->support0) && $this->abo->support0->support == "papier");
        if (!$this->papier) {
            $this->url = $this->abo->url_site;
//            if (isset($this->abo->plateforme0) && $this->abo->plateforme0->plateforme != "") {
//                $this->link_text = "({$this->abo->plateforme0->plateforme})";
//            } else {
//                //$this->link_text = preg_replace('/(?<=^.{22}).{4,}(?=.{20}$)/', '...', $this->url);
//                $this->link_text = "";
//            }
        }
    }

    public function run() {
        // 
        // Traitement des jouraux papier
        //
        
        if ($this->papier) {
            $texte = "Périodique papier";
            if (isset($this->abo->localisation0) && $this->jrn->reroid) {
                $url = "http://opac.rero.ch/get_bib_record.cgi?db=vd&rero_id=" . $this->jrn->reroid;
                echo CHtml::link(
                        $texte . " (lien vers RERO)", $url, array('target' => '_blank', 'title' => $this->papier_link));
            } else {
                echo $texte;
            }
            return;
        }
//        if ($this->papier) {
//            if (isset($this->abo->localisation0)) {
//                // Texte du lien et de la cote
//                $cote = "";
//                $texte = CHtml::encode($this->abo->localisation0->localisation);
//                if (isset($this->abo->cote) && $this->abo->cote != "") {
//                    $cote = " <small>[cote : {$this->abo->cote}]</small>";
//                }
//
//                // Si le reroid existe, on ajoute un lien vers rero
//                if ($this->jrn->reroid) {
//                    $url = "http://opac.rero.ch/get_bib_record.cgi?db=vd&rero_id=" . $this->jrn->reroid;
//                    echo CHtml::link(
//                            $texte, $url, array('target' => '_blank', 'title' => $this->papier_link)) . $cote;
//                } else {
//                    echo $texte . $cote;
//                }
//            } else {
//                echo CHtml::encode("Périodique papier");
//            }
//            return;
//        }
        //
        // Traitement des journaux électronique
        //
        if ((isset($this->abo->acces_user) && $this->abo->acces_user != "") || (isset($this->abo->acces_pwd) && $this->abo->acces_pwd != "")) {
            //$src = Yii::app()->baseUrl . "/images/login_16.png";
            //echo CHtml::image($src, "Login", array('title' => "Protégé par mot de passe")) . "&nbsp;";
            echo '<span class="glyphicon glyphicon-lock"></span>&nbsp;';
            //echo CHtml::link(CHtml::encode($this->link_text), $this->url, array(
            echo CHtml::link(CHtml::encode("Accéder en ligne $this->link_text"), $this->url, array(
                'target' => '_blank',
                'onclick' => '$("#' . $this->abo->abonnement_id . '").dialog("open"); return false;',
                'title' => $this->link_title));
            $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                'id' => $this->abo->abonnement_id,
                // additional javascript options for the dialog plugin
                'options' => array(
                    'title' => 'Revue protégée par mot de passe',
                    'autoOpen' => false,
                    'width' => '450px',
                    'height' => '300',
                    'resizable' => false,
                    'buttons' => array(
                        'Accéder au site' => 'js:function(){open("' . $this->url . '");}',
                        'Annuler' => 'js:function(){$( this ).dialog( "close" );}',),
                ),
            ));
            ?>
            <p>La revue <strong><?= $this->jrn->titre ?></strong> est protégée par un mot de passe. </p>
            <?php
            // Si l'utilisateur est du CHUV ou de l'UNIL
            if ($this->isUNIL() || $this->isCHUV()) {
                // La différenciation CHUV UNIL n'est pas activée
                //if ((isset($this->abo->acces_elec_unil) && ($this->abo->acces_elec_unil && $this->isUNIL())) ||
                //        (isset($this->abo->acces_elec_chuv) && ($this->abo->acces_elec_chuv && $this->isCHUV()))) {
                ?>
                <p>Voici les informations nécessaires à la connexion : 
                <ul>
                    <li><strong>nom d'utilisateur : </strong><?= $this->abo->acces_user ?></li>
                    <li><strong>mot de passe : </strong><?= $this->abo->acces_pwd ?></li>
                </ul>
                </p><?php
            } else { // L'utilisateur n'a pas les droits 
                ?>

                <p>Nous ne fournissons ces informations qu'aux utilisateurs des réseaux CHUV ou UNIL.
                <ul>
                    <li>Pour accéder au réseau UNIL par VPN : <a href="https://crypto.unil.ch">Crypto</a></li>
                    <li>Pour accéder au réseau CHUV par VPN : <a href="https://jupiter.chuv.ch">Jupiter</a></li>
                </ul>
                </p><?php
            }
            $this->endWidget('zii.widgets.jui.CJuiDialog');
        } else {
            // Aucun mot de passe n'est requis
            
            // Si l'état de l'abonnement est à "Problème d'accès"
            if($this->abo->statutabo == 4){
                $this->link_title = "L'accès online à cette publication est momentanément impossible.";
                echo '<span class="glyphicon glyphicon-warning-sign"></span>&nbsp;';
            }
            
            echo CHtml::link(
                    //CHtml::encode($this->link_text), 
                    CHtml::encode("Accéder en ligne $this->link_text"), $this->url, array('target' => '_blank', 'title' => $this->link_title));
        }
    }

    private function isCHUV() {
        $ip_tab = explode('.', $this->getRealIpAddr());
        return $ip_tab[0] == '155' && $ip_tab[1] == '105';
    }

    private function isUNIL() {
        $ip_tab = explode('.', $this->getRealIpAddr());
        return $ip_tab[0] == '130' && $ip_tab[1] == '223';
    }

    private function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}