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


        /**
         * Chaque colonne de la requête SQL formera une colonne du fichier
         * CSV. La nom défini avec "AS" sera le titre de la colone dans le
         * ficher CSV 
         */
        $sql = "SELECT 
                j.perunilid         AS `journal.perunilid`, 
                j.titre             AS `journal.titre`, 
                j.soustitre         AS `journal.soustitre`, 
                j.titre_abrege      AS `journal.titre_abrege`, 
                j.titre_variante    AS `journal.titre_variante`, 
                j.faitsuitea        AS `journal.faitsuitea`, 
                j.devient           AS `journal.devient`, 
                j.issn              AS `journal.issn`, 
                j.issnl             AS `journal.issnl`, 
                j.nlmid             AS `journal.nlmid`, 
                j.reroid            AS `journal.reroid`, 
                j.doi               AS `journal.doi`, 
                j.coden             AS `journal.coden`, 
                j.urn               AS `journal.urn`, 
                j.publiunil         AS `journal.publiunil`,
                j.url_rss           AS `journal.url_rss`, 
                j.commentaire_pub   AS `journal.commentaire_pub`, 
                j.parution_terminee AS `journal.parution_terminee`, 
                j.openaccess        AS `journal.openaccess`,

                a.abonnement_id      AS `abonnement.abonnement_id`, 
                a.titreexclu         AS `abonnement.titreexclu`, 
                a.package            AS `abonnement.package`, 
                a.no_abo             AS `abonnement.no_abo`, 
                a.url_site           AS `abonnement.url_site`, 
                a.acces_elec_gratuit AS `abonnement.acces_elec_gratuit`, 
                a.acces_elec_unil    AS `abonnement.acces_elec_unil`, 
                a.acces_elec_chuv    AS `abonnement.acces_elec_chuv`, 
                a.embargo_mois       AS `abonnement.embargo_mois`, 
                a.acces_user         AS `abonnement.acces_user`, 
                a.acces_pwd          AS `abonnement.acces_pwd`, 
                a.etatcoll           AS `abonnement.etatcoll`, 
                a.etatcoll_deba      AS `abonnement.etatcoll_deba`, 
                a.etatcoll_debv      AS `abonnement.etatcoll_debv`, 
                a.etatcoll_debf      AS `abonnement.etatcoll_debf`, 
                a.etatcoll_fina      AS `abonnement.etatcoll_fina`, 
                a.etatcoll_finv      AS `abonnement.etatcoll_finv`, 
                a.etatcoll_finf      AS `abonnement.etatcoll_finf`, 
                a.cote               AS `abonnement.cote`, 
                a.editeur_code       AS `abonnement.editeur_code`, 
                a.editeur_sujet      AS `abonnement.editeur_sujet`, 
                a.commentaire_pro    AS `abonnement.commentaire_pro`, 
                a.commentaire_pub    AS `abonnement.commentaire_pub`,

                ed.editeur_id AS `editeur.editeur_id`, 
                ed.editeur    AS `editeur.editeur`, 
        
                plt.plateforme_id AS `plateforme.plateforme_id`,
                plt.plateforme    AS `plateforme.plateforme`, 
        
                ha.histabo_id AS `histabo.histabo_id`,
                ha.histabo    AS `histabo.histabo`,
        
                sa.statutabo_id AS `statutabo.statutabo_id`,
                sa.statutabo    AS `statutabo.statutabo`,
        
                loc.localisation_id AS `localisation.localisation_id`,
                loc.localisation    AS `localisation.localisation`,
        
                gest.gestion_id AS `gestion.gestion_id`,
                gest.gestion    AS `gestion.gestion`,
        
                frm.format_id AS `format.format_id`,
                frm.format    AS `format.format`,
        
                sprt.support_id AS `support.support_id`,
                sprt.support    AS `support.support`,
        
                lic.licence_id AS `licence.licence_id`,
                lic.licence    AS `licence.licence`

                FROM journal j
                LEFT JOIN abonnement a     ON j.perunilid = a.perunilid
                LEFT OUTER JOIN plateforme plt   ON a.plateforme = plt.plateforme_id
                LEFT OUTER JOIN editeur ed       ON a.editeur    = ed.editeur_id
                LEFT OUTER JOIN histabo ha       ON a.histabo    = ha.histabo_id
                LEFT OUTER JOIN statutabo sa     ON a.statutabo  = sa.statutabo_id
                LEFT OUTER JOIN localisation loc ON a.localisation = loc.localisation_id
                LEFT OUTER JOIN gestion gest     ON a.gestion   = gest.gestion_id
                LEFT OUTER JOIN format frm       ON a.format    = frm.format_id
                LEFT OUTER JOIN support sprt     ON a.support   = sprt.support_id
                LEFT OUTER JOIN licence lic      ON a.licence   = lic.licence_id

                WHERE j.perunilid in ($perunilids_comma_separated);";


        /**
         * Création de la commande CDbCommand 
         */
        $command = Yii::app()->db->createCommand($sql);


        // Génération du fichier CSV
        // Extension ECSVExport : http://www.yiiframework.com/extension/csvexport
        Yii::import('ext.ECSVExport');
        $csv = new ECSVExport($command);

        /**
         * Génération du fichier à la volée 
         */
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Perunil2CSV-' . date('YmdHi') . '.csv"');
        echo $csv->toCSV();
    }

}

?>
