<?php

$i = 1;
$table = $db_connect->query("SELECT ls.user, sum(ls.points) as count from `league_score` as ls, `league` as l WHERE l.l_id = ls.l_id and l.l_id=003 group by ls.user order by count desc limit ".$LIMIT."");
$update = $db_connect->query("SELECT add_date, admin from league_score order by id desc limit 1");
$leagueName = $db_connect->query("SELECT name FROM league WHERE l_id=003");

foreach($leagueName as $name)
    $leagueNameText = $name['name'];

foreach($update as $row){
    $updateTime = $row['add_date'];
    $updateAdmin = $row['admin'];
        
}

echo 
    "
    <table class='hero-table' cellspacing='0' cellpadding='0'>
        <thead>
            <tr>
                <th colspan='3'>".$leagueNameText."</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Gracz:</th>
                <th>Wynik:</th>
            </tr>
        </thead>
        <tbody>
    ";

foreach($table as $row){
    echo 
        "
            <tr>
                <td>".$i."</td>
                <td><a href='https://beta.esportal.pl/profile/".$row['user']."'>".$row['user']."</a></td>
                <td>".$row['count']." pkt.</td>
            </tr>
        ";

    $i++;
}

if(empty($_GET['table'])){
    echo 
        "
            <tr>
                <td colspan='3' class='more-records'><a href='stats?table=true'>Wczytaj więcej rekordów...</a></td>
            </tr>
        ";
}else{
    echo 
        "
            <tr>
                <td colspan='3' class='more-records'><a href='stats'>Schowaj dodatkowe rekordy...</a></td>
            </tr>
        ";    
}



echo 
    "
            <tr>
                <td colspan='3'>Ostatnia aktualizacja: ".$updateTime." przez ".$updateAdmin."</td>
            </tr>
        </tbody>
    </table>
    ";
    
    $table->closeCursor();
    $update->closeCursor();
    $leagueName->closeCursor();

?>