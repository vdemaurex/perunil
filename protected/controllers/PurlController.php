<?php

/**
 * Description of PurlController
 *
 * @author vdemaure
 */
class PurlController extends Controller {

   
    public function actionPerunilid($id) {
        if (intval($id)>0){
            // recherche du id parmis les perunilid actuels
            $jrn = Journal::model()->findByPk($id);
            if (is_object($jrn)){
                $this->redirect($this->createUrl("site/detail/" . $jrn->perunilid));
                return;
            }
            // recherche du id parmis les perunilid anciens
            $abo = Abonnement::model()->findByAttributes(array('perunilid_old'=>$id));
            if (is_object($abo)){
                $this->redirect($this->createUrl("site/detail/" . $abo->perunilid));
                return;
            }
        }
        Yii::app()->user->setFlash('error', "Impossible de trouver le périodique correspondant au perunilid : '$id'. Essayez de recherche votre périodique à l'aide du formulaire ci-dessous.");
        $this->redirect($this->createUrl("site/index"));
    }

}
