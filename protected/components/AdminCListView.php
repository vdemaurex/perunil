<?php

Yii::import('zii.widgets.CListView');

class AdminCListView extends CListView{


public function renderItems()
	{
		echo CHtml::openTag($this->itemsTagName,array('class'=>$this->itemsCssClass))."\n";
		$data=$this->dataProvider->getData();
		if(($n=count($data))>0)
		{
			$owner=$this->getOwner();
			$render=$owner instanceof CController ? 'renderPartial' : 'render';
			$j=0;
                        echo CHtml::beginForm(CHtml::normalizeUrl(array('admin/fusion')));
                        echo CHtml::submitButton('Fusionner les éléments sélectionnés');
                        echo CHtml::hiddenField('REQUEST_URI',$_SERVER['REQUEST_URI']);
                        
                        // Exportation CSV
                        // Sauvegarde du CDbCriteria pour un eventuel export
                        Yii::app()->session['criteria'] = $this->dataProvider->criteria;
                        // Affichage du bouton d'exporation
                        echo CHtml::button('Exporter en CSV', array(
                            'onclick' => 'js:document.location.href="'. CHtml::normalizeUrl(array('admin/csvexport')) .'"'));
                        
                        
			foreach($data as $i=>$item)
			{
                            
				$data=$this->viewData;
				$data['index']=$i;
				$data['data']=$item;
				$data['widget']=$this;
				$owner->$render($this->itemView,$data);
				if($j++ < $n-1)
					echo $this->separator;
			}
                        echo CHtml::endForm();
		}
		else
			$this->renderEmptyText();
		echo CHtml::closeTag($this->itemsTagName);
	}
}