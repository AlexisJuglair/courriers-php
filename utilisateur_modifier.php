<?php
// === requires =============================
require_once('lib/common.php');
require_once('lib/mysql.php');
require_once('lib/page.php');
require_once('lib/session.php');
require_once('lib/html.php');
require_once('lib/errors.php');

if($errors->check($page->referer == "utilisateur",32768) && $errors->check($session->check(),32768))
{
    $erreur = 0;
    $flag_mdp = false;

    //vérification que l'identifiant n'est pas vide
    $erreur = ($_POST['identifiant']!= '') ? $erreur + 0 : $erreur + 1;

    //vérification que l'identifiant n'existe pas dans la bdd
    $db = new DB();
    $sql = $db->sql("SELECT COUNT(`identifiant`) FROM `utilisateurs` WHERE `identifiant`=\"{$_POST["identifiant"]}\";");
    $erreur = ($sql[0][0] == 0) ? $erreur + 0 : $erreur + 2;

    // vérification que le mot de passe correspond à celui dans la bdd
    $db = new DB();
    $sql = $db->sql("SELECT `mot_de_passe` FROM `utilisateurs` WHERE `id`={$_SESSION["uid"]}");
    $erreur = ($sql[0][0] == $_POST['mot_de_passe']) ? $erreur + 0 : $erreur + 4;

    // vérification que le nouveau mot de passe et le nouveau mot de passe confirmé ne sont pas vides
    if ($_POST['nouveau_mot_de_passe'] != '' && $_POST['confirmation_mot_de_passe'] != '') 
    {
        // vérification que le nouveau mot de passe est le même que le nouveau mot de passe confirmé
        $erreur = ($_POST['nouveau_mot_de_passe'] == $_POST['confirmation_mot_de_passe'])  ? $erreur + 0 : $erreur + 8;

        $flag_mdp = true;

    }
   
    if ($erreur == 0)
    {
        if ($flag_mdp) 
        {
            $_POST['mot_de_passe'] = $_POST['nouveau_mot_de_passe'];
        }
        unset($_POST['nouveau_mot_de_passe']);
        unset($_POST['confirmation_mot_de_passe']);

        eval(arrayToVars($_POST));

        $db = new DB();
        $set = $db->arrayToSql($_POST);
        $sql = $db->sql("UPDATE `utilisateurs` SET $set WHERE `id`={$_SESSION["uid"]};");
    
        header("Location: liste.php");
    }
    else
    {
        header("Content-Type: text/plain");
        print("$erreur\n");
        print($erreur & 4);
        print("\n");
        if (($erreur & 1) == 1)
            print("L'identifiant est vide\n");
        if (($erreur & 2) == 2)
            print("L'identifiant existe déjà\n");
        if (($erreur & 4) == 4)
            print("Mot de passe actuel inconnu\n");
        if (($erreur & 8) == 8)
            print("Les mots de passe ne correspondent pas\n");
    }
    
}