<?php

require_once "config.php";

try{
    return $db_connect = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME."", "".DB_USER."", "".DB_PASSWORD."");
}catch(PDOException $e){
    if($e)
        echo 'Połączenie nie mogło zostać nawiązane: ' . $e->getMessage();
}

function closeConnect(&$db_connect){
    $db_connect = null;
}

?>