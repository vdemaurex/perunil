<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'PérUNIL',
    'language' => 'fr',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.auditTrail.models.AuditTrail',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

//        'gii' => array(
//            'class' => 'system.gii.GiiModule',
//            'password' => 'jvbcdb',
//            // If removed, Gii defaults to localhost only. Edit carefully to taste.
//            'ipFilters' => array('127.0.0.1', '::1'),
//        ),
        'auditTrail' => array(
            'userClass' => 'Utilisateur', // the class name for the user object
            'userIdColumn' => 'utilisateur_id', // the column name of the primary key for the user
            'userNameColumn' => 'pseudo', // the column name of the primary key for the user
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<action:(search)>' => 'site/simpleSearchResults',
            ),
        ),
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
          // uncomment the following to use a MySQL database
         */
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=perunil_journals-v2',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'jvbcdb',
            'charset' => 'utf8',
            //'schemaCachingDuration' => 3600,
            //'enableParamLogging'=>true

        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
//                'web' => array(
//                    'class' => 'CWebLogRoute',
//                    'levels' => 'trace, info, error, warning, application',
//                    'categories' => 'system.db.*, application',
//                    'showInFireBug' => true //firebug only - turn off otherwise
//                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    'class' => 'CEmailLogRoute',
                    'levels' => 'error',
                    'emails' => 'vincent.demaurex@chuv.ch',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'cache' => array(
            'class' => 'CDbCache',
        //'connectionID' => 'db',
        ),
    ),
    // Paramètres accessibles partout dans l'application
    // en utilisant Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        //'adminEmail' => 'wwwperun@unil.ch',
        'adminEmail' => 'Mathilde.Panes@chuv.ch',
        'productiondate' => '31.01.2015',
    ),
);
