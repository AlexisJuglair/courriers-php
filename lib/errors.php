<?php
    class Errors
    {
        public $messages;
        public $code;
        public $text;

        public function __construct()
        {
            $this->code = 0;
            $this->text = '';
        }

        public function check($value,$messageIndex)
        {
            global $page;
            if($value == false)
            {
                $this->code+= $messageIndex;
                $this->text = str_replace($this->messages[$page->current][$messageIndex],'',$this->text);
                $this->text.= "\n".$this->messages[$page->current][$messageIndex];
                $this->text = trim($this->text);

                if($this->code & 32768)
                {
                    header("Location: index.php");
                }
            }
            return($value);
        }

        public function getMessages($page,$code)
        {
            $messages = "";
            $i=1;
            foreach($this->messages[$page] as $error)
            {
                if($code & $i)
                {
                    $messages = str_replace($error,'',$messages);
                    $messages.= "\n".$error;
                    $messages = trim($messages);
                }
                $i *= 2;
            }

            return($messages);
        }
    }

    $errors = new Errors();

    $errors->messages['connecter'][1]  = "Le champ de l'identifiant est vide.";
    $errors->messages['connecter'][2]  = "Le champ de l'identifiant est vide.";
    $errors->messages['connecter'][4]  = "Le champ du mot de passe est vide.";
    $errors->messages['connecter'][8]  = "Le champ du mot de passe est vide.";
    $errors->messages['connecter'][16] = "Identifiant ou mot de passe incorrect.";
    $errors->messages['connecter'][32] = "L'utilisateur n'est plus autorisé à se connecter.";

    $errors->messages['liste'][32768] = "Accès interdit !";
    $errors->messages['destinataire_liste'][32768] = "Accès interdit !";
    $errors->messages['utilisateur'][32768] = "Accès interdit !";

    $errors->messages['ajouter'][32768] = "Accès interdit !";
    $errors->messages['modifier'][32768] = "Accès interdit !";
    $errors->messages['supprimer'][32768] = "Accès interdit !";
    $errors->messages['destinataire_ajouter'][32768] = "Accès interdit !";
    $errors->messages['destinataire_modifier'][32768] = "Accès interdit !";
    $errors->messages['destinataire_supprimer'][32768] = "Accès interdit !";

    $errors->messages['utilisateur_modifier'][32768] = "Accès interdit !";
    $errors->messages['utilisateur_dsisncription'][32768] = "Accès interdit !";