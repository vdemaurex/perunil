<?php

class MobileController extends Controller {

    public $layout = 'mobile';

    public function actionIndex() {
        $this->render('index');
    }

    public function actionJournal($perunilid){
        $model = Journal::model()->findByPk($perunilid);
        $this->render('journal', array(
            'model' => $model,
        ));
        
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}