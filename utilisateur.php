<?php
// === requires =============================
require_once('lib/common.php');
require_once('lib/mysql.php');
require_once('lib/page.php');
require_once('lib/session.php');
require_once('lib/html.php');
require_once('lib/errors.php');

// === Init =================================
$HTML = new HTML("Utilisateur");

// ==========================================

if ($errors->check(($page->referer == "index" || $page->referer == "liste" || $page->referer == "destinataire_liste"), 32768))
{
    $db = new DB();

    $id_utilisateur = ($page->referer == "index") ? '' : $_SESSION['uid'];

    $sql = "SELECT titre,nom,prenom,telephone,email,adresse,code_postal,localite,identifiant FROM utilisateurs WHERE id={$id_utilisateur};";

    // ----------------------------------------------
    if($page->referer == "index")
    {
        $fields = extractFields($sql,"`");

        foreach ($fields as $f) 
        {
            eval("\$$f='';");
        }
    }

    // ----------------------------------------------
    if($page->referer == "liste" || $page->referer == "destinataire_liste")
    {
        $utilisateur = $db->sql($sql, "ASSOC")[0];
        eval($db->fieldsToVars());
    }

    $HTML->form_('formUtilisateur', 'connecter.php','POST',["class"=>"formForm"]);

    $HTML->fieldSelect('titre', 'titre', ["Mme"=>"Madame", "Melle"=>"Mademoiselle", "M."=>"Monsieur"], $titre,["placeholder"=>"Titre"]);
    $HTML->fieldInput('prenom', 'prenom', 'text', $prenom, ["placeholder"=>"Prénom","title"=>"Votre prénom."]);
    $HTML->fieldInput('nom', 'nom', 'text', $nom, ["placeholder"=>"NOM","title"=>"Votre NOM de famille."]);
    $HTML->fieldInput('telephone', 'telephone', 'text', $telephone, ["placeholder"=>"Téléphone","title"=>"Votre numéro de téléphone."]);
    $HTML->fieldInput('email', 'email', 'text', $email, ["placeholder"=>"E-mail","title"=>"Votre adresse électronique."]);
    $HTML->fieldTextarea('adresse', 'adresse', htmlentities($adresse), [ "placeholder" => "Adresse", "title"=>'Votre adresse postale.'] );
    $HTML->fieldInput('code_postal', 'code_postal', 'text', $code_postal, ["placeholder"=>"Code postal","title"=>"Le code postal de votre commune."]);
    $HTML->fieldInput('localite', 'localite', 'text', $localite, ["placeholder"=>"Localité","title"=>"Le nom de votre localité."]);
    $HTML->fieldInput('identifiant', 'identifiant', 'text', $identifiant, ["placeholder"=>"Identifiant","title"=>"Votre identifiant de connexion."]);
    if($page->referer == "index")
    {
        $HTML->fieldInput('mot_de_passe ', 'mot_de_passe', 'password', '', ["placeholder"=>"Mot de passe ","title"=>"Votre mot de passe."]);
    }
    if($page->referer == "liste" || $page->referer == "liste")
    {
        $HTML->fieldInput('mot_de_passe ', 'mot_de_passe', 'password', '', ["placeholder"=>"Mot de passe actuel","title"=>"Votre mot de passe actuel."]);
        $HTML->fieldInput('nouveau_mot_de_passe  ', 'nouveau_mot_de_passe', 'password', '', ["placeholder"=>"Nouveau mot de passe","title"=>"Votre nouveau mot de passe."]);
    }
    $HTML->fieldInput('confirmation_mot_de_passe ', 'confirmation_mot_de_passe', 'password', '', ["placeholder"=>"Confirmation mot de passe","title"=>"Confirmez votre nouveau mot de passe."]);

    // ----------------------------------------------
    if (($page->referer == 'liste') || ($page->referer == 'destinataire_liste') && ($errors->check($session->check(), 32768))) {
        $HTML->submit('', 'Valider', ["Valider vos informations pour les modifier.", "formaction"=>"utilisateur_modifier.php"]);
        $HTML->space();
        $HTML->a('', "{$page->referer}.php", "Retour", "Retourner à la page de connexion.");
        $HTML->space();
        $HTML->a('','utilisateur_desinscription.php','Se désinscrire',['title'=>"Se désinscrire", "onclick"=>"return confirm('Êtes-vous sûr de vouloir vous désinscrire ?')"]);
        $HTML->_form();
        $HTML->output();
    } else {
        $HTML->submit('', 'Valider', ["Valider vos informations pour vous inscrire.", "formaction"=>"sinscrire.php"]);
        $HTML->a('', "{$page->referer}.php", "Retour", "Retourner à la page de connexion.");
        $HTML->_form();
        $HTML->output();
    }
}
