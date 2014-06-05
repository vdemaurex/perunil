<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    // application components
    'components' => array(
        /* Connexion à la base PU2 */
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=perunil_journals-v2',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'jvbcdb',
            'charset' => 'utf8',
            'schemaCachingDuration' => 3600,
        ),
        /* Connexion à la base PU1 */
        'dbpu1' => array(
            'connectionString' => 'mysql:host=localhost;dbname=perunil_journals',
            'username'         => 'root',
            'password'         => 'jvbcdb',
            'charset'          => 'utf8',
            'emulatePrepare'   => true,
            'class'            => 'CDbConnection'
        ),
    ),
);
