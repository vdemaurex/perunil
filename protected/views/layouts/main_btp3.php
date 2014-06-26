<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />


        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Bootstrap -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet" media="screen" /> 

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
           <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
           <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php
        // Ajout de la librairie javascript jquery
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');

        // ajout de la css de jquery ui
        Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');


        // Autocompletion lors de la recherche
        Yii::app()->clientScript->registerCoreScript('autocomplete');
        ?>

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
        
    </head>

    <body>

        <div class="container" id="page" style="padding-left: 0px; padding-right: 0px;">

            <div id="header">
                <div id="logo">
                    <a href ="<?= Yii::app()->baseUrl; ?>">
                        <img src="<?= Yii::app()->baseUrl; ?>/images/logo_perunil.png"/>
                    </a>
                    <a href="http://www2.unil.ch/ebooks">
                        <button type="button" class="btn btn-default" style="float: right">
                            <span style="font-size: 10pt">Visitez</span><br>
                            <img width="128px" src="<?= Yii::app()->baseUrl; ?>/images/logo_ebooks.png"/><br>
                            <span style="font-size: 8pt">livres électroniques à l'UNIL et au CHUV</span>
                        </button>
                    </a>
                </div>
                

            </div><!-- header -->
            <!-- Navigation publique -->
            
            <nav class="navbar navbar-default navbar-inverse" role="navigation" style="margin-bottom: 0;">
                <!--div id="mainmenu"-->
                
                <div>
                    <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => array(
                            array('label' => 'Recherche simple', 'url' => array('/site/simpleclean')),
                            array('label' => 'Recherche avancée', 'url' => array('/site/advclean')),
                            array('label' => 'Recherche admin', 'url' => array('/admin/searchclean'), 'visible' => !Yii::app()->user->isGuest),
                            array('label' => 'Sujets', 'url' => array('/site/sujet'), 'linkOptions' => array('class' => "hidden-xs")),
                            // array('label' => 'Contact', 'url' => array('/site/contact')),
                            array('label' => '?', 'url' => array('/site/page', 'view' => 'aide')),
                        ),
                        'lastItemCssClass' => 'navbar-right',
                        'htmlOptions' => array('class' => 'nav navbar-nav'),
                    ));

                    $this->widget('zii.widgets.CMenu', array(
                        'items' => array(
//                            array('label' => 'Login',
//                                'url' => array('/site/login'),
//                                'visible' => Yii::app()->user->isGuest),
                            array('label' => '<span class="glyphicon glyphicon-cog"></span>Admin',
                                'url' => array('/admin/index'),
                                'visible' => !Yii::app()->user->isGuest,
                            ),
                            array('label' => 'Actions <b class="caret"></b>',
                                'url' => array('#'),
                                'visible' => !Yii::app()->user->isGuest,
                                'linkOptions' => array('class' => "dropdown-toggle hidden-xs", 'data-toggle' => "dropdown"),
                                'itemOptions' => array('class' => 'dropdown user'),
                                'items' => array(
                                    array('label' => 'Accueil administration', 'url' => array('/admin/index')),
                                    array('label' => 'Recherche admin', 'url' => array('/admin/search')),
                                    array('label' => 'Nouveau périodique', 'url' => array('/admin/peredit')),
                                    array('label' => 'Gérer les sujets', 'url' => array('/sujet/admin')),
                                    array('label' => 'Gérer les listes', 'url' => array('/smalllist')),
                                    array('label' => 'Suivit des modifications', 'url' => array('/admin/modifications')),
                                ),
                            ),
                            array('label' => 'Logout (' . Yii::app()->user->name . ')',
                                'url' => array('/site/logout'),
                                'visible' => !Yii::app()->user->isGuest,
                            ),
                        ),
                        'htmlOptions' => array('class' => 'nav navbar-nav navbar-right'),
                        'submenuHtmlOptions' => array('class' => 'dropdown-menu'),
                        'encodeLabel' => false,
                            )
                    );
                    ?>
                </div>
            </nav>

            <!-- mainmenu -->
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer" class="hidden-xs">
                <div id="vd"> &nbsp; </div>
                <div id="swissu">
                    <a href="http://www.swissuniversity.ch">
                        <img alt="Swiss University" src="<?= Yii::app()->baseUrl; ?>/images/swissuniversity_blue_smush.png" border="0" height="17" width="136"></img>
                    </a>
                </div>
                <div id="logobottom">
                    <a href="http://www.vd.ch">
                        <img border="0" alt="Canton de Vaud" src="<?= Yii::app()->baseUrl; ?>/images/vd_gray.gif"></img>
                    </a>
                    <a class="liens" href="http://www.unil.ch">
                        <img border="0" alt="UNIL" src="<?= Yii::app()->baseUrl; ?>/images/unilogo_noir.png"></img>
                    </a>
                    <a class="liens" href="http://www.bcu-lausanne.ch/">
                        
                        <img border="0" alt="BCU Lausanne" src="<?= Yii::app()->baseUrl; ?>/images/logo_bcu_gris.gif"/>
                    </a>
                    <a class="liens" href="http://www.chuv.ch">
                        <img border="0" alt="CHUV" src="<?= Yii::app()->baseUrl; ?>/images/logo_chuv_transp_bleu.png"></img>
                    </a>
                </div>
                <div id="linksbottom">
                    <?php echo CHtml::mailto('Contact', 'wwwperun@unil.ch'); ?> &nbsp;-&nbsp;
                    <?php echo CHtml::link('Informations légales', 'http://www.unil.ch/central/home/legalinformation.html'); ?> &nbsp;-&nbsp;
                    <?php echo CHtml::link('Impressum', 'http://www.unil.ch/central/home/impressum.html'); ?> &nbsp;-&nbsp;
                    <?php
                    if (Yii::app()->user->isGuest) {
                        echo CHtml::link('Login', array('/site/login'), array('visible' => Yii::app()->user->isGuest));
                    } else {
                        echo CHtml::link('Logout (' . Yii::app()->user->name . ')', array('/site/logout'), array('visible' => !Yii::app()->user->isGuest));
                    }
                    ?> &nbsp;
                </div>
            </div><!-- footer -->

        </div><!-- page -->
        <div id="postaddress"  class="hidden-xs"> 
            Bibliothèque Universitaire de Médecine&nbsp;&nbsp;-&nbsp;
            CHUV BH08 - Bugnon 46&nbsp;-&nbsp;
            CH-1011 Lausanne&nbsp;
        </div>

    </body>
</html>
