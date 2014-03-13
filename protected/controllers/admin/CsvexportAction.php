<?php

/**
 * CsvexportAction génère un fichier csv à parir de la recherche
 *
 * @author vdemaure
 */
class CsvexportAction extends CAction { 

    /**
     * Fonction exectutée lors de l'appel de l'action cvsexport 
     */
    public function run() {

        if(Yii::app()->session['searchtype'] != 'admin'){
            Yii::app()->user->setFlash('error', "Merci d'utiliser l'exporation CSV depuis les résultats de la recherche admin uniquement.");
            return;
        }
         
        // Sauvegarde de l'état de l'affichage : 
        $affichage = Yii::app()->session['search']->admin_affichage;
        // Récupération du Criteria avec Journal comme modèle.
        Yii::app()->session['search']->admin_affichage = 'journal';
        $criteria = Yii::app()->session['search']->admin_criteria;
        // Restauration de l'état d'affichage
        Yii::app()->session['search']->admin_affichage = $affichage;


        // 
        $criteria->select = 't.perunilid';


        // Récupération des perunilid
        $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
        $command = $builder->createFindCommand('journal', $criteria);
        $perunilids = $command->queryColumn();
        $perunilids_comma_separated = implode(",", $perunilids);


        $command = Yii::app()->db->createCommand();
        /**
         * Chaque colonne de la requête SQL formera une colonne du fichier
         * CSV. La nom défini avec "AS" sera le titre de la colone dans le
         * ficher CSV 
         */
        $command->select(array(
               "j.perunilid         AS journal-perunilid", 
               "j.titre             AS journal-titre", 
               "j.soustitre         AS journal-soustitre", 
               "j.titre_abrege      AS journal-titre_abrege", 
               "j.titre_variante    AS journal-titre_variante", 
               "j.faitsuitea        AS journal-faitsuitea", 
               "j.devient           AS journal-devient", 
               "j.issn              AS journal-issn", 
               "j.issnl             AS journal-issnl", 
               "j.nlmid             AS journal-nlmid", 
               "j.reroid            AS journal-reroid", 
               "j.doi               AS journal-doi", 
               "j.coden             AS journal-coden", 
               "j.urn               AS journal-urn", 
               "j.publiunil         AS journal-publiunil",
               "j.url_rss           AS journal-url_rss", 
               "j.commentaire_pub   AS journal-commentaire_pub", 
               "j.parution_terminee AS journal-parution_terminee", 
               "j.openaccess        AS journal-openaccess",
               "jc.stamp            AS journal-creation",
               "jm.stamp            AS journal-modification",

               "a.perunilid          AS abonnement-perunilid", 
               "a.abonnement_id      AS abonnement-abonnement_id", 
               "a.titreexclu         AS abonnement-titreexclu", 
               "a.package            AS abonnement-package", 
               "a.no_abo             AS abonnement-no_abo", 
               "a.url_site           AS abonnement-url_site", 
               "a.acces_elec_gratuit AS abonnement-acces_elec_gratuit", 
               "a.acces_elec_unil    AS abonnement-acces_elec_unil", 
               "a.acces_elec_chuv    AS abonnement-acces_elec_chuv", 
               "a.embargo_mois       AS abonnement-embargo_mois", 
               "a.acces_user         AS abonnement-acces_user", 
               "a.acces_pwd          AS abonnement-acces_pwd", 
               "a.etatcoll           AS abonnement-etatcoll", 
               "a.etatcoll_deba      AS abonnement-etatcoll_deba", 
               "a.etatcoll_debv      AS abonnement-etatcoll_debv", 
               "a.etatcoll_debf      AS abonnement-etatcoll_debf", 
               "a.etatcoll_fina      AS abonnement-etatcoll_fina", 
               "a.etatcoll_finv      AS abonnement-etatcoll_finv", 
               "a.etatcoll_finf      AS abonnement-etatcoll_finf", 
               "a.cote               AS abonnement-cote", 
               "a.editeur_code       AS abonnement-editeur_code", 
               "a.editeur_sujet      AS abonnement-editeur_sujet", 
               "a.commentaire_pro    AS abonnement-commentaire_pro", 
               "a.commentaire_pub    AS abonnement-commentaire_pub",
               "a.plateforme         AS abonnement-plateforme",
               "plt.plateforme       AS plateforme-plateforme",
               "a.editeur            AS abonnement-editeur",
               "ed.editeur           AS editeur-editeur", 
               "a.histabo            AS abonnement-histabo",
               "ha.histabo           AS histabo-histabo",
               "a.statutabo          AS abonnement-statutabo",
               "sa.statutabo         AS statutabo-statutabo",
               "a.localisation       AS abonnement-localisation",
               "loc.localisation     AS localisation-localisation",
               "a.gestion            AS abonnement-gestion",
               "gest.gestion         AS gestion-gestion",
               "a.format             AS abonnement-format",
               "frm.format           AS format-format",
               "a.support            AS abonnement-support",
               "sprt.support         AS support-support",
               "a.licence            AS abonnement-licence",
               "lic.licence          AS licence-licence",
               "ac.stamp             AS abonnement-creation",
               "am.stamp             AS abonnement-modification"
        ));
        
        $command->from("journal j");
        $command->join("abonnement a", "j.perunilid = a.perunilid");
 
        $command->leftJoin("plateforme plt", "a.plateforme = plt.plateforme_id");
        $command->leftJoin("editeur ed", "a.editeur    = ed.editeur_id");
        $command->leftJoin("histabo ha", "a.histabo    = ha.histabo_id");
        $command->leftJoin("statutabo sa", "a.statutabo  = sa.statutabo_id");
        $command->leftJoin("localisation loc", "a.localisation = loc.localisation_id");
        $command->leftJoin("gestion gest", "a.gestion   = gest.gestion_id");
        $command->leftJoin("format frm", "a.format    = frm.format_id");
        $command->leftJoin("support sprt", "a.support   = sprt.support_id");
        $command->leftJoin("licence lic", "a.licence   = lic.licence_id");
        
        $command->leftJoin("modifications jm", "j.modification = jm.id");
        $command->leftJoin("modifications jc", "j.creation     = jc.id");
      
        $command->leftJoin("modifications am", "a.modification = am.id");
        $command->leftJoin("modifications ac", "a.creation     = ac.id");

        $command->where("j.perunilid in ($perunilids_comma_separated)");


        // Génération du fichier CSV
        // Extension ECSVExport : http://www.yiiframework.com/extension/csvexport
        Yii::import('ext.ECSVExport');
        $csv = new ECSVExport($command);
        $csv->setDelimiter(";");

        /**
         * Génération du fichier à la volée 
         */
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Perunil2CSV-' . date('YmdHi') . '.csv"');
        echo $csv->toCSV();
    }

}

?>
