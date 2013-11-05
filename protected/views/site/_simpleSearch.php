<style>
    .radio {
        display: block;
        margin-bottom: 0px;
        margin-top: 7px;
        min-height: 10px;
        padding-left: 20px;
        vertical-align: middle;
    }
    
    th {
        text-align: left;
        vertical-align: bottom;
    }
</style>
<form method="get" action="<?php echo $this->createUrl('site/simpleSearchResults')?>">
<div class="panel panel-default" style="width: 705px; margin:auto;">
  <div class="panel-heading">Votre recherche</div>
      <div class="panel-body">
        <table class="simplesearch">
            <tr>
                <td>
                    <?php
                    /* <input type="search" size="46" placeholder="search" name="q" value="<?= isset($_GET['q']) ? CHtml::encode($_GET['q']) : ''; ?>" />
                     */

                    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'name' => 'q',
                        'value' => isset(Yii::app()->session['search']->simple_query_str) ? Yii::app()->session['search']->simple_query_str : '',
                        'source' => $this->createUrl('site/autocomplete'),
                        'options' => array(
                            'minLength' => 2,
                        ),
                        'htmlOptions' => array(
                            'size'  => '46',
                            'class' => "form-control input-sm" ,
                        ),
                    ));
                    ?>

                </td>
                <td>
                    <input type="submit" value="Rechercher" class="btn btn-primary" />  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="centpp">
                        <tr>
                            <th>
                                Recherche
                            </th>
                            <td>
                                <div class="radio">
                                    <label>
                                      <input id="twords" type="radio" name="field" value="<?= SearchComponent::TWORDS ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::TWORDS) echo "checked"; ?> >
                                      mots du titre
                                    </label>
                                </div>

                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                      <input id="tbegin" type="radio" name="field" value="<?= SearchComponent::TBEGIN ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::TBEGIN) echo "checked"; ?>>
                                      début du titre
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                      <input id="texact" type="radio" name="field" value="<?= SearchComponent::TEXACT ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::TEXACT) echo "checked"; ?>>
                                      titre exact
                                    </label>
                                </div>

                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                      <input id="tous" type="radio" name="field" value="<?= SearchComponent::JRNALL ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::JRNALL) echo "checked"; ?>>
                                      tous les champs
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Support
                            </th>
                            <td>
                                <div class="radio">
                                    <label>
                                      <input type="radio" name="support" value="0" <?php if (Yii::app()->session['search']->support == '0') echo "checked"; ?>>
                                      tous
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                      <input type="radio" name="support" value="1" <?php if (Yii::app()->session['search']->support == '1') echo "checked"; ?>>
                                      électroniques
                                    </label>
                                </div>

                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                      <input type="radio" name="support" value="2" <?php if (Yii::app()->session['search']->support == '2') echo "checked"; ?>>
                                      imprimés
                                    </label>
                                </div> 
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="2"  style="padding-bottom:10px;">Nombre maximum de résultats</th>
                            <td colspan="3" style="padding-top:10px;">
                                <select name="maxresults" id="maxresults" class="form-control input-sm form-inline" style="width: auto;">
                                    <option value="-1"  <?php if (Yii::app()->session['search']->maxresults == '-1') echo "selected"; ?> >Tous</option>
                                    <option value="50"  <?php if (Yii::app()->session['search']->maxresults == '50') echo "selected"; ?> >50</option>
                                    <option value="100" <?php if (Yii::app()->session['search']->maxresults == '100') echo "selected"; ?> >100</option>
                                    <option value="200" <?php if (Yii::app()->session['search']->maxresults == '200') echo "selected"; ?> >200</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>


            </tr>
        </table>
      </div>
</div>
</form>
<script>
$('#tous').click(function(e){
    $('#maxresults option[value="100"]').prop('selected', true);
});

$('#twords, #tbegin, #texact').click(function(e){
    $('#maxresults option[value="-1"]').prop('selected', true);
});

</script>