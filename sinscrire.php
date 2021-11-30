<?php
// === requires =============================
require_once('lib/common.php');
require_once('lib/mysql.php');
require_once('lib/page.php');
require_once('lib/session.php');
require_once('lib/html.php');
require_once('lib/errors.php');

if($errors->check($page->referer == "utilisateur",32768))
{
    $erreur = 0;

    //vérification que l'identifiant n'est pas vide
    $erreur = ($_POST['identifiant']!= '') ? $erreur + 0 : $erreur + 1;

    //vérification que l'identifiant n'existe pas dans la bdd
    $db = new DB();
    $sql = $db->sql("SELECT COUNT(`identifiant`) FROM `utilisateurs` WHERE `identifiant`=\"{$_POST["identifiant"]}\";");
    $erreur = ($sql[0][0] == 0) ? $erreur + 0 : $erreur + 2;

    //vérification que le nom n'est pas vide
    $erreur = ($_POST['prenom'] != '') ? $erreur + 0 : $erreur + 4;

    //vérification que le prénom n'est pas vide
    $erreur = ($_POST['nom'] != '') ? $erreur + 0 : $erreur + 8;

    // vérification que le nouveau mot de passe est le même que le nouveau mot de passe confirmé
    $erreur = ($_POST['mot_de_passe'] == $_POST['confirmation_mot_de_passe'])  ? $erreur + 0 : $erreur + 16;

    //vérification que les champs mot de passe ne sont pas vides
    $erreur = ($_POST['mot_de_passe'] != '' || $_POST['confirmation_mot_de_passe'] != '')  ? $erreur + 0 : $erreur + 32;

    if ($erreur == 0)
    {
        unset($_POST['confirmation_mot_de_passe']);

        eval(arrayToVars($_POST));

        $db = new DB();
        $set = $db->arrayToSql($_POST);
        $sql = $db->sql("INSERT INTO `utilisateurs` SET $set;");
    
        header("Location: index.php");
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
            print("Le prénom est vide\n");
        if (($erreur & 8) == 8)
            print("Le nom est vide\n");
        if (($erreur & 16) == 16)
            print("Les mots de passe ne correspondent pas\n");
        if (($erreur & 32) == 32)
            print("Les mots de passe ne doivent pas être vides\n");
    }
}