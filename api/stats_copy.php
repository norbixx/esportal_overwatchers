<?php

try{
    $con = new PDO('mysql:host=localhost;dbname=esportal_ov', 'esportal_ovadmin', 'gXeNsViw');
    //$con = new PDO('mysql:host=localhost;dbname=esportalov', 'root', '');
}
catch(PDOException $e){
    echo 'Połączenie nie mogło zostać nawiązane: ' . $e->getMessage();
}

function closeCon(&$con){
        $con = null;
}

try{
    $json = file_get_contents("https://se-api.esportal.se/challenges/get?stats=1&friends=0&country_ids=173");

    if($json === false)
        echo "Error 901. Bad URL!";
    
}catch (Exception $e){
    // Handle exception
}

$obj = json_decode($json, true);


$i = 0;
$j = 0;
$time = date("Y-m-d", time() + 94);

foreach($obj as $key => $value){
    
    if($i == 2 || $i == 3 || $i == 5 || $i == 6 || $i == 7){
        $scores[$j] = json_encode($value["scores"], true);
        
        if($i == 2)
            $type[$j] = "ZMIANA ELO";
        if($i == 3)
            $type[$j] = "KILLE";
        if($i == 5)
            $type[$j] = "AK-47";
        if($i == 6)
            $type[$j] = "M4A1-M4A4";
        if($i == 7)
            $type[$j] = "AWP";
        
        
        $tmp_obj = json_decode($scores[$j], true);

        foreach($tmp_obj as $key => $values){

            $username = $values["username"];
            $score = $values["score1"];

            $con->query("INSERT INTO heroleague_copy(user, score, type, last_update) VALUES('".$username."', ".$score.", '".$type[$j]."', '".$time."')");
            
        }
        
        $j++;
    }
    
    $i++;
        
    if($i > 8 )
        break;
}

closeCon($con);

?>