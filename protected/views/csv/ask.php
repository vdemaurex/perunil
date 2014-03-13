<?php
/**
 * Choix de l'utilisateur concernant le journal
 */
/* @var $this CsvController */
/* @var $this->parser CSVParser */
/* @var $row CsvRow */

$this->breadcrumbs=array(
	'Csv'=>array('/csv'),
	'Choix du journal',
);

$search_results = $row->getSearchResults();
?>

<h1>Importation d'un fichier CSV - Etape 3</h1>

<div class="panel panel-info">
    <div class="panel-heading">
    <strong>Création d'un nouvel abonnement</strong><br /> Sur cette page vous devez indiquer à quel journal ratacher ce nouvel abonnement.
</div>
<div class="panel-body"> 
    

  <table class="table">
      <tr>
          <th width='90px'>N° de ligne</th>
          <th>Valeurs de la ligne CSV</th>
      </tr>
      <tr>
          <td><?php echo $row->noRow;?></td>
          <td>
             <?php
             foreach ($row->getValidValues() as $column => $value) {
                 if (!empty($value)){
                 echo "<span style='margin-right: 1em; white-space: nowrap;'><em >$column : </em>$value;</span> ";
                 }
             }
             
             ?>
          </td>
      </tr>

  </table>
</div>
</div>

<hr />

<div class="panel panel-default">
    <div class="panel-heading"><strong>Sélection du journal dans une liste<strong></div>
  <div class="panel-body">
      <p><em><?php echo $row->getSearchLog();?> :</em></p>

        <?php
        // 1. Affichage du choix des journaux 
        $radioData = array();
        foreach ($search_results as $perunilid => $title) {
            $url = Yii::app()->createUrl('/admin/peredit/perunilid/' . $perunilid);
            $radioData[$perunilid] = $title . ", n° " . CHtml::link($perunilid,$url, array('target'=>'_blank', 'title'=> "Ouvrir l'édition du journal dans une nouvelle fenêtre"));
        }

        $radioData['SPECIFIED'] = '<hr>Indiquez le perunilid :';


        ?>
        <style>
            label {
                display: inline-block;
                font-weight: normal;
                margin-bottom: 0px;
            }
        </style>
        <div class="form" style="margin-left : 1em;">

            <?php // 1. Affichage du formulaire pour la sélection journal
            echo CHtml::beginForm($this->createUrl('csv/answer'));  ?>
            <?php echo CHtml::hiddenField('formno',  1); 
              echo CHtml::radioButtonList(
                            'perunilid',
                            'SPECIFIED',
                            $radioData
                            //array('template'=>'{input} {label}')
                        );
             echo CHtml::textField('specified_perunilid'); ?>
            
            <div class="row submit" style="margin-top : 1em;">
                <?php echo CHtml::submitButton('Choisir le journal sélectionné',array('class'=>"btn btn-primary ")); ?>
                <?php echo CHtml::endForm(); ?>
                <?php // 3. Passer la ligne en mode REJECTED
                    echo CHtml::beginForm($this->createUrl('csv/answer'),'post',array('style'=>"display:inline;"));  
                    echo CHtml::hiddenField('formno',  3); 
                    echo CHtml::hiddenField('state',  CSVRow::REJECTED); 
                     echo CHtml::submitButton('Ne pas traiter cette ligne',array('class'=>"btn btn-warning ")); 
                     echo CHtml::endForm(); ?>
            </div>

        
                        
        </div><!-- form -->
  </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Création d'un nouveau journal <strong></div>
        <div class="panel-body">
            <div class="form" style="margin-left : 1em;" >
            <?php // 2. Affichage du formulaire pour le nouveau journal
            echo CHtml::beginForm($this->createUrl('csv/answer'));  ?>

                <?php echo CHtml::hiddenField('formno',  2); ?>
                <div class="row">
                    <?php echo CHtml::label("Titre du journal : ",'journalTitle'); ?>
                    <?php echo CHtml::textField('journalTitle'); ?>
                </div>

                <div class="row">
                    <?php echo CHtml::label("ISSN du journal : ",'journalIssn'); ?>
                    <?php echo CHtml::textField('journalIssn'); ?>
                </div>

                <div class="row">
                    <?php echo CHtml::label("ISSNL du journal : ",'journalIssnl'); ?>
                    <?php echo CHtml::textField('journalIssnl'); ?>
                </div>

                <div class="row submit" style="margin-top : 1em;">
                    <?php echo CHtml::submitButton('Enregistrer un nouveau journal',array('class'=>"btn btn-success ")); ?>
                </div>
        
            <?php echo CHtml::endForm(); ?>
            </div><!-- form -->
  </div>
</div>