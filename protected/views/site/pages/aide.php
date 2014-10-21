<?php
$this->pageTitle=Yii::app()->name . ' - Aide';
$this->breadcrumbs=array(
	'Aide',
);
?>

<?php $this->beginWidget('CMarkdown');?>


Aide à l'utilisation de PérUNIL
===============================

Quel est le contenu de PérUunil ?
--------------------------------

PérUnil offre un accès unique, rapide et structuré à environ 60'000 revues 
électroniques accessibles sur le site UNIL-CHUV.

PérUnil signale également la [localisation des collections de revues imprimées dans les 
différentes bibliothèques du site universitaire de Lausanne](http://www.unil.ch/codul/home/menuinst/codul---info---contact/liste-bibliotheques-codul.html#standard_1561).
   
*Attention :* Seuls les titres des revues et journaux sont signalés dans PérUnil. 
Pour connaître les articles publiés dans ces revues, utiliser [les bases de données bibliographiques](http://dbserv1-bcu.unil.ch/dbbcu/cds/menu.php) 
ou l’application [Explore](http://explore.rero.ch/vd).

 


Accès au contenu de PérUnil
---------------------------

Les revues électroniques signalées dans PérUnil sont accessibles sur l'ensemble du campus 
de l'Université de Lausanne (UNIL) et de l'Hôpital académique (CHUV), ainsi que dans les différents sites de la BCUL.
 
Sur les postes fixes, l'accès à ces ressources est automatique, garanti par adresses IP. 
Il n'est donc pas nécessaire de s'authentifier. En revanche, sur les portables et les tablettes, 
la connexion se fait via le wifi et il est nécessaire de s'authentifier comme faisant partie de l'UNIL ou du CHUV. 
Il en est de même pour l'accès à distance.
 
Les accès UNIL et CHUV sont ouverts aux étudiants, aux enseignants, aux chercheurs aussi bien qu'au personnel administratif.

Conseils d’utilisation
----------------------

###Recherche par titre de revue
Il est conseillé de débuter par une recherche simple en indiquant quelques éléments significatifs du titre de la revue. Il n’est pas nécessaire de taper un titre complet.
: ex. *Amer jour scienc*.

Si la recherche produit trop de résultats, ajouter un élément ou bien taper le titre complet

Pour des titres courts, comme Nature, Science, etc. penser à cocher l’option début de titre ou titre exact.

###Recherche par thème
Afficher la liste des sujets  pour sélectionner un thème.

###Recherche par lettre
Cliquer sur une des lettres de l’alphabet pour parcourir la liste de tous les titres commençant par la lettre sélectionnée.

###Recherche avancée
Pour combiner plusieurs critères.

###Affichage des résultats de la recherche

Certaines revues électroniques sont accessibles chez plusieurs fournisseurs, ou disponibles en format imprimé dans plusieurs bibliothèques. Dans chaque cas, vérifier les états de collections pour connaître les années disponibles sur les différents sites ou dans les collections des bibliothèques.


Pour toute demande d’aide, veuillez contacter la bibliothèque la plus proche ou remplir notre [formulaire de contact](http://www2.unil.ch/perunil/feedback.php)

Conditions d'utilisation
-------------------------
L’utilisation des revues électroniques mises à disposition sur PérUnil ont soumises, 
par la loi (droit d’auteur, copyright) et par contrat avec les éditeurs, à des règles d’usage spécifiques, conformes à un usage loyal (fair use).

Ces règles d’usage, sont plus ou moins restrictives selon les éditeurs ou fournisseurs d'information, concernent les aspects suivants. De manière générale :

  *  Limitation des accès aux utilisateurs autorisés : collaborateurs et étudiants de l’UNIL et du CHUV, personnes présentes dans les bibliothèques;
  *  Impression et sauvegarde des documents exclusivement pour un usage personnel ou académique (études, enseignement, recherche) ;
  *  Interdiction du déchargement systématique;
  *  Interdiction de la diffusion des articles à des tiers, que ce soit sous forme imprimée ou électronique (messagerie ou web);
  *  Interdiction de la divulgation des mots de passe aux personnes non autorisées dans le cas où l’accès à la revue nécessite une authentification par mot de passe.

Les utilisateurs sont priés de respecter ces règles de bon usage. Ceci permettra de continuer à bénéficier d'un accès ouvert, sans contraintes administratives. En cas d'usage abusif, les fournisseurs se réservent le droit de couper l'accès à ces ressources


Description des symboles utilisés
---------------------------------
<?php $this->endWidget();?>
<table class="table">
    <tr>
    <th>
        Icône
    </th>
    <th>
        Description
    </th>
    </tr>
    <tr>
        <td>
            <?php echo CHtml::htmlButton('<span class="glyphicon glyphicon-search"></span> Détail', array('class' => "btn btn-default  btn-xs"));?>
        </td>
        <td>
            Détails du titre 
        </td>
    </tr>
    <tr>
        <td>
            <span class="glyphicon glyphicon-book"></span>
        </td>
        <td>
            Périodique papier accessible pour la consultation sur site
        </td>
    </tr>
       <tr>
        <td>
            <span class="glyphicon glyphicon-new-window"></span>
        </td>
        <td>
            Périodique accessible pour la consultation en ligne
        </td>
    </tr>

       <tr>
        <td>
            <img src="<?= Yii::app()->baseUrl; ?>/images/open-access-logo_16.png"/>
        </td>
        <td>
            Journal Open Access : périodique en accès libre en ligne et respectant aux exigences du mouvement d’accès libre à l’information scientifique et technique.
        </td>
    </tr>


       <tr>
        <td>
            <span class="glyphicon glyphicon-ban-circle" style="color:red;"></span>
        </td>
        <td>
            Titre exclu de la licence souscrite par l’UNIL, CHUV et BCUL.
        </td>
    </tr>
           <tr>
        <td>
            <span class="glyphicon glyphicon-lock"></span>
        </td>
        <td>
            Accès au texte intégral protégé par mot de passe. Si vous vous trouvez 
            sur le campus de l'Université de Lausanne (UNIL) ou de l'Hôpital académique (CHUV),
            ainsi que dans les différents sites de la BCUL, les informations nécessaires à 
            la connexion s’affichent en cliquant le lien « Accéder en ligne ».
        </td>
    </tr>
    <tr>
        <td>
            <span class="glyphicon glyphicon-warning-sign" style="color:orange;"></span>
        </td>
        <td>
            Problème d'accès.
        </td>
    </tr>
</table>




<p>Si vous rencontrez des difficultés à utiliser PérUnil, merci de prendre contact
avec la Bibliothèque Universitaire de Médecine (BiUM).</p>

<p><strong><?= CHtml::link("Bibliothèque Universitaire de Médecine", "http://www.bium.ch");?></strong><br/>
Centre Hospitalier Universitaire Vaudois - BH 08<br />
Rue du Bugnon 46 - CH-1011 Lausanne</p>

<p>Tél. ++41 (0)21 314 50 82</p>
<a href="mailto:wwwperun@unil.ch">Envoyer un e-mail</a>
