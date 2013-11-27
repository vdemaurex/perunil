<?php

/**
 * Description of CsvImportForm
 *
 * @author vdemaure
 */
class CsvImportForm extends CFormModel{
    
    public $fichier;
    public $delimiter = ',';
    
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
            'fichier'   => "Sélectionez un fichier",
            'delimiter' => "Spécifiez le séparateur (un seul caractère)"
        );
    }
    
    public function getDelimiter(){
        switch ($this->delimiter) {
            case 'tabulation':
                return '\t';
                break;
            case 'pointvirgule':
                return ';';
                break;
            default:
                return ',';
                break;
        }
        return ',';
    }
    
    
}

?>
