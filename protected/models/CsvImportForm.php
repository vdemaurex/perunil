<?php

/**
 * Description of CsvImportForm
 *
 * @author vdemaure
 */
class CsvImportForm extends CFormModel{
    
    public $fichier;
    
    public function rules() {
        return array(
            array(
                'fichier',
                'file',
                'types'      => 'csv',
                'maxSize'    => 1024*1024*1024, // 10 Mb
                'tooLarge'    => "Ce fichier est trop gros. La taille du fichier doit être inférieur à 10 Mo.",
                'allowEmpty' => false,
                )
        );
    }
    
    public function attributeLabels() {
        return array(
            'fichier' => "Sélectionez un fichier",
        );
    }
    
    
}

?>
