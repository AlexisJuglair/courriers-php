#!/usr/bin/php
<?php

require_once("lib/mysql.php");

$db = new DB();
$tables = $db->getTables();
foreach($tables as $table)
{
    if(substr($table,0,1)!="_" && substr($table,0,5)!="list_")
    {
        $db->sql("DELETE FROM `$table` WHERE `status`=\"Supprimé\";");
    }
}

$db1 = new DB();
$records = $db1->sql("SELECT * FROM `utilisateurs`;","ASSOC");
print_r($records);
