<?php
    // === requires =============================
    require_once('lib/common.php');
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');
    
    // ==========================================
    if($errors->check($page->referer == "liste",32768) || $errors->check($page->referer == "destinataire_liste",32768) || $errors->check($page->referer == "utilisateur",32768) && $errors->check($session->check(),32768))
    {
        $db = new DB();
        $sql = $db->sql("UPDATE `utilisateurs` SET `status`= \"désinscrit\" WHERE `id`={$_SESSION["uid"]};");

        header("Location: deconnecter.php"); 
    }
