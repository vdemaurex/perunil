
<form method="get">
    <table class="simplesearch">
        <tr>
            <td>
                <?php
                /* <input type="search" size="46" placeholder="search" name="q" value="<?= isset($_GET['q']) ? CHtml::encode($_GET['q']) : ''; ?>" />
                 */

                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'q',
                    'value' => isset(Yii::app()->session['search_query']) ? CHtml::encode(Yii::app()->session['search_query']) : '',
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
                            <input type="radio" name="field" value="<?= SearchComponent::TWORDS ?>" <? if ($this->field == SearchComponent::TWORDS) echo "checked"; ?> >mots du titre
                        </td>
                        <td>
                            <input type="radio" name="field" value="<?= SearchComponent::TBEGIN ?>" <? if ($this->field == SearchComponent::TBEGIN) echo "checked"; ?>>début du titre
                        </td>
                        <td>
                            <input type="radio" name="field" value="<?= SearchComponent::TEXACT ?>" <? if ($this->field == SearchComponent::TEXACT) echo "checked"; ?>> titre exact
                        </td>
                        <td>
                            <input type="radio" name="field" value="<?= SearchComponent::JRNALL ?>" <? if ($this->field == SearchComponent::JRNALL) echo "checked"; ?>> tous les champs
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="radio" name="support" value="0" <? if ($this->support == '0') echo "checked"; ?>>tous
                        </td>
                        <td>
                            <input type="radio" name="support" value="1" <? if ($this->support == '1') echo "checked"; ?>>électroniques
                        </td>
                        <td>
                            <input type="radio" name="support" value="2" <? if ($this->support == '2') echo "checked"; ?>> imprimés
                        </td>
                        <td></td>
                    </tr>
                </table>
            </td>


        </tr>
    </table>
</form>