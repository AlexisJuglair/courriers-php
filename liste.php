<?php
    // === requires =============================
    require_once('lib/mysql.php');
    require_once('lib/page.php');
    require_once('lib/session.php');
    require_once('lib/html.php');
    require_once('lib/errors.php');

    // === Init =================================
    $HTML = new HTML("Courriers - Liste");
    
    // ==========================================

    if($errors->check($session->check(),32768))
    {
        // --------------------------------------
        $header = new HTML();
            $header->a('','utilisateur_desinscription.php','Se désinscrire',['title'=>"Se désinscrire", "onclick"=>"return confirm('Êtes-vous sûr de vouloir vous désinscrire ?')"]);
            $header->space();
            $header->a('','deconnecter.php','Déconnecter',['title'=>"Déconnecter la session et retourner à la page d'identification."]);
            $header->space();
            $header->a('','utilisateur.php',$_SESSION['identite'],['title'=>"Mes informations."]);
            $header->space();
            $header->a('','destinataire_liste.php', 'Liste des destinataires',['title'=>"Mes informations."]);
        $HTML->header($header->HTML);
        $HTML->form_('','','POST',['class'=>'formList']);

        // --------------------------------------
        $main = new HTML();
            $db = new DB();
            $courriers = $db->sql("SELECT `id`,`date_modification`,`date_envoi`,CONCAT(`prenom`,' ',`nom`) AS `destinataire`,`denomination`,CONCAT(`code_postal`,' ',`localite`) AS `lieu`,`status` FROM `list_courriers` WHERE `utilisateur_id`={$_SESSION["uid"]} AND `status` <> \"Supprimé\" ORDER BY `date_modification` DESC, `date_envoi` DESC;");
            $main->tableFilled('courriers',['id','Modification','Envoi','Destinataire','Dénomination','Lieu','Status'],$courriers);
        $HTML->main($main->HTML);

        // --------------------------------------
        $footer = new HTML();

        $footer->submit('', 'Imprimer', ['title'=>'Imprimer les courriers sélectionnés.', 'class'=>'button', 'formaction'=>'pdf.php?cmd=imprimer&sid='.session_id()]);
        $footer->submit('', 'Télécharger', ['title'=>'Télécharger les courriers sélectionnés.', 'class'=>'button', 'formaction'=>'pdf.php?cmd=telecharger&sid='.session_id()]);
        $footer->submit('', 'Ajouter', ['title'=>'Créer un nouveau courrier.', 'class'=>'button','formaction'=>'formulaire.php?cmd=ajouter']);
        $footer->submit('', 'Modifier', ['title'=>'Modifier les courriers sélectionnés.', 'class'=>'button','formaction'=>'formulaire.php?cmd=modifier']);
        $footer->submit('', 'Supprimer', ['title'=>'Supprimer les courriers sélectionnés.', 'class'=>'button','formaction'=>'supprimer.php']);

        $HTML->footer($footer->HTML,['class'=>'cmd']);
        $HTML->_form();
    }

    $HTML->output();
