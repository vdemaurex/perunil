<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UploadAction
 *
 * @author vdemaure
 */
class UploadAction extends CAction{
    
        public function actionUpload() {
	$uploaddir = Yii::app()->controller->module->path;
	$uploadfile = $uploaddir . basename($_FILES['myfile']['name']);

	$name_array = explode(".", $_FILES['myfile']['name']);
	$type = end($name_array);

	if ($type == "csv") {
	    if (move_uploaded_file($_FILES['myfile']['tmp_name'], $uploadfile)) {
		$importError = 0;
	    } else {
		$importError = 1;
	    }
	} else {
	    $importError = 2;
	}

	// checking file with earlier imports

	$paramsArray = $this->checkOldFile($uploadfile);
	$delimiterFromFile = $paramsArray['delimiter'];
	$textDelimiterFromFile = $paramsArray['textDelimiter'];
	$tableFromFile = $paramsArray['table'];

	// view rendering

	$this->layout = 'clear';
	$this->render('firstResult', array(
	    'error' => $importError,
	    'uploadfile' => $uploadfile,
	    'delimiterFromFile' => $delimiterFromFile,
	    'textDelimiterFromFile' => $textDelimiterFromFile,
	    'tableFromFile' => $tableFromFile,
	));
    }
    
    
}

?>
