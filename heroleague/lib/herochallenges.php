<?php

$challenges = array(
    "2019-03-04"=>"M4A1-M4A4",
    "2019-03-06"=>"ZMIANA ELO",
    "2019-03-08"=>"AWP",
    "2019-03-10"=>"KILLE",
    "2019-03-11"=>"ZMIANA ELO",
    "2019-03-13"=>"AK-47",
    "2019-03-15"=>"KILLE",
    "2019-03-17"=>"M4A1-M4A4",
    "2019-03-18"=>"AWP",
    "2019-03-20"=>"KILLE",
    "2019-03-22"=>"ZMIANA ELO",
    "2019-03-24"=>"AK-47",
    "2019-03-25"=>"KILLE",
    "2019-03-27"=>"AWP",
    "2019-03-29"=>"AK-47",
    "2019-03-31"=>"KILLE",
    );


echo 
    "
    <table class='hero-challenges' cellspacing='0' cellpadding='0'>
        <thead>
            <tr>
                <th colspan='12'>Statystyki wyzwań:</th>
            </tr>
            <tr>
                <th>Data<br />(Y-M-D):</th>
                <th>Wyzwanie:</th>
                <th>#1</th>
                <th>#2</th>
                <th>#3</th>
                <th>#4</th>
                <th>#5</th>
                <th>#6</th>
                <th>#7</th>
                <th>#8</th>
                <th>#9</th>
                <th>#10</th>
            </tr>
        </thead>
        <tbody>
    ";

foreach($challenges as $date=>$challenge){
    
    echo 
        "
            <tr>
                <td>".$date."</td>
                <td><img src='../img/".$challenge.".png' alt='".$challenge."' /></td>
        ";
    
    $tmpChallenge = $db_connect->query("SELECT user, score from heroleague where type like '".$challenge."' and last_update like '".$date."' order by score desc");
    
    foreach($tmpChallenge as $row){
        
        echo 
            "
                <td><a href='https://beta.esportal.pl/profile/".$row['user']."'>".$row['user']."</a><br /><br />".$row['score']."</td>
            ";
        
    }
    
    echo 
        "
            </tr>
        ";
    
}


echo 
    "
        </tbody>
    </table>
    ";

?>