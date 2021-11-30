<?php
    // === requires =============================
    require_once('lib/common.php');
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');
    
    // ==========================================
    if($errors->check($page->referer == "destinataire_formulaire",32768) && $errors->check($session->check(),32768)) 
    {
        eval(arrayToVars($_POST));  
            
        $db = new DB();
        $sql= "UPDATE `destinataires` SET `status`= \"Supprimé\" WHERE `id`={$_SESSION["destinataire_id"]};";

        $db->sql($sql);
    }

    if($errors->check($page->referer == "destinataire_liste",32768) && $errors->check($session->check(),32768)) 
    {      
        if(!empty($_POST['destinataires'][0] != '') )
        {
            foreach($_POST['destinataires'] as $destinataire) 
            {
                $db = new DB();
                $sql= "UPDATE `destinataires` SET `status`= \"Supprimé\" WHERE `id`={$destinataire};";
        
                $db->sql($sql);
            }
            
        }  
    }

    header("Location: destinataire_liste.php");
