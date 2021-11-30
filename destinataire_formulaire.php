<?php
// === requires =============================
require_once('lib/common.php');
require_once('lib/mysql.php');
require_once('lib/page.php');
require_once('lib/session.php');
require_once('lib/html.php');
require_once('lib/errors.php');

// === Init =================================
$HTML = new HTML("Formulaire - Destinataire");

// ==========================================

$cmd = (isset($_GET['cmd'])) ? $_GET['cmd'] : '';
$destinataire_id = (isset($_POST['destinataires'])) ? $_POST['destinataires'][0] : '';

if (count($_POST['destinataires']) == 1 && $cmd == 'modifier' || $cmd == 'ajouter') 
{
    $uid = $_SESSION['uid'];

    $_SESSION["destinataire_id"] = $destinataire_id;

    $db = new DB();

    $sql = "SELECT `titre`, `nom`, `prenom`, `fonction`, `denomination`, `adresse`, `code_postal`, `localite`, `telephone`, `email`, `commentaire` FROM destinataires WHERE id={$_SESSION["destinataire_id"]};";

    // ----------------------------------------------
    if($cmd == "ajouter")
    {
        $fields = extractFields($sql,"`");

        foreach ($fields as $f) 
        {
            eval("\$$f='';");
        }
    }

    // ----------------------------------------------
    if($cmd == "modifier")
    {
        $destinataire = $db->sql($sql, "ASSOC")[0];
        eval($db->fieldsToVars());
    }

    $HTML->form_('formDestinataire', 'destinataire_modifier.php','POST',["class"=>"formForm"]);
    $HTML->fieldInput('utilisateur_id','utilisateur_id',"hidden",$uid);
    $HTML->fieldSelect('titre', 'titre',['M.'=>'Monsieur','Mme.'=>'Madame'],$titre,["placeholder"=>"Titre","title"=>"Titre"]);
    $HTML->fieldInput('nom', 'nom', 'text', $nom, ["placeholder"=>"Nom","title"=>"Saisissez un nom ."]);
    $HTML->fieldInput('prenom', 'prenom', 'text', $prenom, ["placeholder"=>"Prénom","title"=>"Saisissez un prénom."]);
    $HTML->fieldInput('fonction', 'fonction', 'text', $fonction, ["placeholder"=>"Fonction","title"=>"Saisissez une fonction."]);
    $HTML->fieldInput('denomination', 'denomination', 'text', $denomination, ["placeholder"=>"Dénomination","title"=>"Saisissez une dénomination."]);
    $HTML->fieldTextarea('adresse', 'adresse', $adresse, ["placeholder"=>"Adresse","title"=>"Saisissez une adresse."]);
    $HTML->fieldInput('code_postal', 'code_postal', 'text', $code_postal, ["placeholder"=>"Code postal","title"=>"Saisissez un code postal."]);
    $HTML->fieldInput('localite', 'localite', 'text', $localite, ["placeholder"=>"Localité","title"=>"Saisissez une localité."]);
    $HTML->fieldInput('telephone', 'telephone', 'text', $telephone, ["placeholder"=>"Téléphone","title"=>"Saisissez un téléphone."]);
    $HTML->fieldInput('email', 'email', 'text', $email, ["placeholder"=>"Email","title"=>"Saisissez un email."]);
    $HTML->fieldTextarea('commentaire', 'commentaire', $commentaire, ["placeholder"=>"Commentaire","title"=>"Saisissez un commentaire."]);

    // ----------------------------------------------
    if($cmd == "ajouter")
    {
        $HTML->submit('', 'Valider',["title" => "Valider pour enregistrer votre destinataire.", "formaction"=>"destinataire_ajouter.php"]);
    }

    // ----------------------------------------------
    if($cmd == "modifier")
    {
        $HTML->submit('', 'Valider',["title" =>"Valider pour enregistrer vos modifications.", "formaction"=>"destinataire_modifier.php"]);

        $HTML->submit('', 'Supprimer',["title"=>"Supprimer ce destinataire.","formaction"=>"destinataire_supprimer.php"]);
    }

    // ----------------------------------------------
    $HTML->a('', "{$page->referer}.php", "Retour",["title" => "Retourner à la liste de destinataires.", "formaction"=>"destinataire_liste.php"]);

    $HTML->_form();

    $HTML->output();
}
else
{
    header("Location: destinataire_liste.php");
}