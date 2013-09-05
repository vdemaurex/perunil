
<form method="get">
    <table class="simplesearch">
        <tr>
            <td>
                <?php
                /* <input type="search" size="46" placeholder="search" name="q" value="<?= isset($_GET['q']) ? CHtml::encode($_GET['q']) : ''; ?>" />
                 */

                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'q',
                    'value' => isset(Yii::app()->session['search']->simple_query_str) ? CHtml::encode(Yii::app()->session['search']->simple_query_str) : '',
                    'source' => $this->createUrl('site/autocomplete'),
                    'options' => array(
                        'minLength' => 2,
                    ),
                    'htmlOptions' => array(
                        'size' => '46',
                    //    'style' => 'height:20px;',
                    ),
                ));
                ?>

            </td>
            <td>
                <input type="submit" value="Rechercher">  
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <input type="radio" name="field" value="<?= SearchComponent::TWORDS ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::TWORDS) echo "checked"; ?> >mots du titre
                        </td>
                        <td>
                            <input type="radio" name="field" value="<?= SearchComponent::TBEGIN ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::TBEGIN) echo "checked"; ?>>début du titre
                        </td>
                        <td>
                            <input type="radio" name="field" value="<?= SearchComponent::TEXACT ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::TEXACT) echo "checked"; ?>> titre exact
                        </td>
                        <td>
                            <input type="radio" name="field" value="<?= SearchComponent::JRNALL ?>" <?php if (Yii::app()->session['search']->search_type == SearchComponent::JRNALL) echo "checked"; ?>> tous les champs
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="support" value="0" <?php if (Yii::app()->session['search']->support == '0') echo "checked"; ?>>tous
                        </td>
                        <td>
                            <input type="radio" name="support" value="1" <?php if (Yii::app()->session['search']->support == '1') echo "checked"; ?>>électroniques
                        </td>
                        <td>
                            <input type="radio" name="support" value="2" <?php if (Yii::app()->session['search']->support == '2') echo "checked"; ?>>imprimés
                        </td>
                        <td></td>
                    </tr>
                </table>
            </td>


        </tr>
    </table>
</form>