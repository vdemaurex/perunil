<?php

class AboUrlWidget extends CWidget {

    public $jrn;
    public $abo;
    private $url;
    private $short_url;

    public function init() {
        $this->url = $this->abo->url_site;
        $this->short_url = preg_replace('/(?<=^.{22}).{4,}(?=.{20}$)/', '...', $this->url);
    }

    public function run() {
        if ((isset($this->abo->acces_user) && $this->abo->acces_user != "") || (isset($this->abo->acces_pwd) && $this->abo->acces_pwd != "")) {
            $src = Yii::app()->baseUrl . "/images/login_16.png";
            echo CHtml::image($src, "Login", array('title' => "Protégé par mot de passe")) . "&nbsp;";
            echo CHtml::link(CHtml::encode($this->short_url), $this->url, array(
                'target' => '_blank',
                'onclick' => '$("#' . $this->abo->abonnement_id . '").dialog("open"); return false;',));
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
            if ($this->isUNIL() || $this->isCHUV()){
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

                <p>Nous ne fournissons ces informations qu'aux utilisateurs des réseau CHUV ou UNIL.
                <ul>
                    <li>Pour accéder au réseau UNIL par VPN : <a href="https://crypto.unil.ch">Crypto</a></li>
                    <li>Pour accéder au réseau CHUV par VPN : <a href="https://jupiter.chuv.ch">Jupiter</a></li>
                </ul>
                </p><?php
            }
            $this->endWidget('zii.widgets.jui.CJuiDialog');
        } else {
            // Aucun mot de passe n'est requis
            echo CHtml::link(CHtml::encode($this->short_url), $this->url, array('target' => '_blank'));
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