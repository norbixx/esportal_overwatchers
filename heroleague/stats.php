<?php

    require_once("lib/connect.php");
    
    if(!empty($_GET['table'])){
        if($_GET['table'] == true)
            $LIMIT = 100;
    }else{
        $LIMIT = 10;
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Esportal Overwatchers Panel - Beta">
    <meta name="author" content="Norbert 'Norbix' Grudzień">
    
    <title>Hero League - Esportal</title>
    
    <link href="../css/heroleague.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body>
    <div id='container'>
    
        <?php
            include_once("lib/herotable.php");
        
            echo 
                "
                <br />
                <p>Wyniki są wpisywane przez administrację po dokładnej weryfikacji, dlatego też prosimy o zachowanie cierpliwości.</p>
                <br />
                ";
            
            include_once("lib/herochallenges.php");
        
            closeConnect($db_connect);
        ?>
        
    </div>
</body>
</html>