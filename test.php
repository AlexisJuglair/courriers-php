<?php
    require_once('lib/mysql.php');
    require_once('vendor/autoload.php');

    $faker = Faker\Factory::create('fr_FR');

    $db = new DB();
    $sql = "DELETE FROM `courriers` WHERE `utilisateur_id`=24";
    $db->sql($sql);

    $db = new DB();
    $sql = "DELETE FROM `destinataires` WHERE `utilisateur_id`=24";
    $db->sql($sql);
    
    for ($c=1; $c<=50; $c++) 
    { 
        $titre = $faker->randomElement(['M.', 'Mme.']);
        $nom = $faker->lastName();
        $prenom = $faker->firstName();
        $fonction = $faker->jobTitle();
        $denomination = $faker->company();
        $adresse = $faker->streetAddress();
        $cp = $faker->postcode();
        $localite = $faker->randomElement(['Bourg-en-Bresse', 'Lyon', 'Paris', 'Bordeaux']);
        $telephone = $faker->phoneNumber();
        $email = $faker->email();
        $commentaire = $faker->paragraph();

        $db = new DB();
        $sql1= "INSERT INTO `destinataires`(`utilisateur_id`, `titre`, `nom`, `prenom`, `fonction`, `denomination`, `adresse`, `code_postal`, `localite`, `telephone`, `email`, `commentaire`) VALUES (24,\"{$titre}\",\"{$nom}\",\"{$prenom}\",\"{$fonction}\",\"{$denomination}\",\"{$adresse}\",\"{$cp}\",\"{$localite}\",\"{$telephone}\",\"{$email}\",\"{$commentaire}\");";
        $db->sql($sql1);

        $db = new DB();
        $sql2="SELECT MAX(`id`) FROM `destinataires` WHERE utilisateur_id = 24;";
        $result = $db->sql($sql2);

        print("<strong>".$c." => ID: ".$result[0][0]."</strong><br>");
        print($sql1."<br><br>");

        $objet = $faker->sentence();
        $offre = strtoupper($faker->randomLetter()).$faker->randomNumber(9, true);
        $date_creation = $faker->date();
        $date_modification = $faker->date();
        $date_envoi = $faker->date();
        $date_relance = $faker->date();
        $paragraphe1 = $faker->paragraph();
        $paragraphe2 = $faker->paragraph();
        $paragraphe3 = $faker->paragraph();
        $paragraphe4 = $faker->paragraph();
        $status = $faker->randomElement(['Brouillon', 'Sélectionné', 'Envoyé']);
        $nosref = 'NOSREF'.$faker->randomNumber(4, true);
        $vosref = 'VOSREF'.$faker->randomNumber(4, true);
        $annonce = $faker->paragraph(10);

        $db = new DB();
        $sql3= "INSERT INTO `courriers`(`destinataire_id`, `utilisateur_id`, `objet`, `offre`, `date_creation`, `date_modification`, `date_envoi`, `date_relance`, `paragraphe1`, `paragraphe2`, `paragraphe3`, `paragraphe4`, `status`, `nosref`, `vosref`, `annonce`) VALUES ({$result[0][0]},24,\"{$objet}\",\"{$offre}\",\"{$date_creation}\",\"{$date_modification}\",\"{$date_envoi}\",\"{$date_relance}\",\"{$paragraphe1}\",\"{$paragraphe2}\",\"{$paragraphe3}\",\"{$paragraphe4}\",\"{$status}\",\"{$nosref}\",\"{$vosref}\",\"{$annonce}\");";
        $db->sql($sql3);
        
        $db = new DB();
        $sql4="SELECT MAX(`id`) FROM `courriers` WHERE utilisateur_id = 24;";
        $result2 = $db->sql($sql4);

        print("<strong>".$c." => ID: ".$result2[0][0]."</strong><br>");
        print($sql3."<br><br>");
    }
   