<?php
// === requires =============================
require_once('lib/common.php');
require_once('lib/mysql.php');
require_once('lib/page.php');
require_once('lib/session.php');
require_once('lib/html.php');
require_once('lib/errors.php');

// === Init =================================
$HTML = new HTML("Courriers - Formulaire");

// ==========================================

$cmd = (isset($_GET['cmd'])) ? $_GET['cmd'] : '';

$courrier_id = (isset($_POST['courriers'])) ? $_POST['courriers'][0] : '';

if (count($_POST['courriers']) == 1 && $cmd == 'modifier' || $cmd == 'ajouter') 
{
    $uid = $_SESSION['uid'];
        
    $_SESSION["courrier_id"] = $courrier_id;

    $db = new DB();

    $sql = "SELECT `objet`, `offre`, `date_envoi`, `date_relance`, `paragraphe1`, `paragraphe2`, `paragraphe3`, `paragraphe4`, `nosref`, `vosref`, `annonce`, `destinataire_id`, `status` FROM courriers WHERE id=$courrier_id;";

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
        $courrier = $db->sql($sql, "ASSOC")[0];
        eval($db->fieldsToVars());
    }

    // ----------------------------------------------
    $sql = "SELECT id, CONCAT(`prenom`,' ',`nom`) AS identite FROM `destinataires` WHERE `utilisateur_id`={$_SESSION['uid']} ORDER BY `prenom` ASC, `nom` ASC;";
    $destinataires = $db->sql($sql);
    $destinataires_select = [];
    foreach ($destinataires as $record) 
    {
        $destinataires_select[$record[0]] = $record[1];
    }

    // ----------------------------------------------
    $sql = "SELECT `id`, `libelle` FROM `_status`;";
    $status_ = $db->sql($sql);
    $status_select = [];
    foreach ($status_ as $record) 
    {
        $status_select[$record[1]] = $record[1];
    }

    $HTML->form_('formUtilisateur', 'modifier.php','POST',["class"=>"formForm"]);
    $HTML->fieldInput('utilisateur_id','utilisateur_id',"hidden",$_SESSION['uid']);
    $HTML->fieldSelect('status', 'status',$status_select,$status,["placeholder"=>"Status","title"=>"Status"]);
    $HTML->fieldTextarea('annonce','annonce',$annonce ,["placeholder"=>"Annonce","title"=>"Annonce"]);
    $HTML->fieldSelect('destinataire_id', 'destinataire_id', $destinataires_select, $courrier["destinataire_id"],["placeholder"=>"Destinataire","title"=>"Destinataire."]);
    $HTML->fieldInput('nosref', 'nosref', 'text', $nosref, ["placeholder"=>"Nos ref??rences","title"=>"Saisissez votre r??f??rence."]);
    $HTML->fieldInput('vosref', 'vosref', 'text', $vosref, ["placeholder"=>"Vos r??f??rences","title"=>"Saisissez la r??f??rence de l'utilisateur."]);
    $HTML->fieldInput('objet', 'objet', 'text', $objet, ["placeholder"=>"Objet","title"=>"Objet du message."]);
    $HTML->fieldInput('offre', 'offre', 'text', $offre, ["placeholder"=>"Offre","title"=>"Num??ro de l'offre."]);
    if($cmd == 'modifier')
    {
        $HTML->fieldInput('date_envoi', 'date_envoi', 'date', $date_envoi, ["placeholder"=>"Date d'envoi","title"=>"Saisissez la date d'envoi'."]);
    }
    $HTML->fieldInput('date_relance', 'date_relance', 'date', $date_relance, ["placeholder"=>"Date de relance pr??vue","title"=>"Saisissez la date de relance pr??vue."]);
    $HTML->fieldTextarea('paragraphe1', 'paragraphe1', $paragraphe1, ["placeholder"=>"Paragraphe 1","title"=>"Saisissez votre premier paragraphe."]);
    $HTML->fieldTextarea('paragraphe2', 'paragraphe2', $paragraphe2, ["placeholder"=>"Paragraphe 2","title"=>"Saisissez votre deuxieme paragraphe."]);
    $HTML->fieldTextarea('paragraphe3', 'paragraphe3', $paragraphe3, ["placeholder"=>"Paragraphe 3","title"=>"Saisissez votre troisieme paragraphe."]);
    $HTML->fieldTextarea('paragraphe4', 'paragraphe4', $paragraphe4, ["placeholder"=>"Paragraphe 4","title"=>"Saisissez votre quatrieme paragraphe."]);

    // ----------------------------------------------
    if($cmd == "ajouter")
    {
        $HTML->submit('', 'Valider',["title" => "Valider vos informations pour vous inscrire.", "formaction"=>"ajouter.php"]);
    }

    // ----------------------------------------------
    if($cmd == "modifier")
    {
        $HTML->submit('', 'Valider',["title" =>"Valider pour enregistrer vos modifications.", "formaction"=>"modifier.php"]);

        $HTML->submit('', 'Supprimer',["title"=>"Supprimer ce courrier.","formaction"=>"supprimer.php"]);
    }

    // ----------------------------------------------
    $HTML->a('', "{$page->referer}.php", "Retour",["title" => "Retourner ?? la page de connexion.", "formaction"=>"liste.php"]);

    $HTML->_form();

    $HTML->output();
}
else
{
    header("Location: liste.php");
}