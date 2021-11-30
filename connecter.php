<?php
    // === requires =============================
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');

    // === Init =================================
    $HTML = new HTML("Courriers - Connexion");

    // ==========================================

    $identifiant = "";
    $motdepasse = "";

    if($errors->check(isset($_POST['identifiant']),1))
    {
        $identifiant = $_POST['identifiant'];
        $errors->check($identifiant != "",2);
    }

    if ($errors->check($_POST['motdepasse'],4))
    {
        $motdepasse = $_POST['motdepasse'];
        $errors->check($motdepasse != "",8);
    }

    // ==========================================
    if ($errors->code == 0)
    {
        $db = new DB();

        $utilisateur = $db->sql("SELECT `id`,`prenom`,`nom`, `status` FROM `utilisateurs` WHERE `identifiant`=\"$identifiant\" AND `mot_de_passe`=\"$motdepasse\";");
    
        // --------------------------------------
        if($errors->check(count($utilisateur) == 1,16))
        {
            if($errors->check($utilisateur[0][3] == NULL,32))
            {
                $uid = $utilisateur[0][0];
                $session->open($uid);
                $_SESSION['identite'] = $utilisateur[0][1].' '.$utilisateur[0][2];

                header("Location: liste.php");
            }
        }
    }

    if($errors->code != 0)
    {
        $HTML->innerHTML("Erreur {$errors->code} :\n{$errors->text}");

        header("Location: index.php?error={$errors->code}");
    }
