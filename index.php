<?php  
/**
 * Copyright (C) 2014  CHUV Vincent Demaurex

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
 */


// Augmentation de la mémoire et des valeur de base PHP pour l'imporation
// et le traitement de gros fichiers CSV.
ini_set("max_execution_time","120");
ini_set("memory_limit","256M");
ini_set('post_max_size', "10M");
ini_set('upload_max_filesize', "10M");

// A modifier pour mettre à jour la version du Yii framwork
$yii=dirname(__FILE__).'/../lib/yii-1.1.15.022a51/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
