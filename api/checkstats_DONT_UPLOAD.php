<html>
<head>
    <style>
        body{
            text-align: center;
        }
        .con{
            width: 90%;
            margin: 0 auto;
        }
        .left-float{
            display: inline-block;
        }
        
        .heroleague{
            margin-left: auto;
            margin-right: auto;
            margin: 20px;
            box-shadow: 0px 0px 5px #0f0f0f;
            color: white;
            min-width: 300px;
            height: 401px;
            border: none;
        }
        
        .heroleague thead{
            background-color: #005574;
            font-size: 22px;
        }
        
        .heroleague tr{
        }
        
        .heroleague thead tr th{
            padding: 7px;
        }
        
        .heroleague thead tr th:nth-child(2){
            width: 240px;
        }
        
        .heroleague thead tr th img{
            width: 60px;
        }
        
        .heroleague tbody tr td{
            padding: 7px;
        }
        
        .heroleague tbody tr td:first-child{
            text-align: center;
        }
        
        .heroleague tbody tr td:nth-child(n+2){
            text-align: center;
        }
        
        .heroleague tbody tr:nth-child(odd){
            background-color: #252525;
        }
        
        .heroleague tbody tr:nth-child(even){
            background-color: #1c1c1c;
        }
        
        .heroleague tbody tr td a{
            color: #ffffff;
            text-decoration: none;
        }
        
        .heroleague tbody tr td a:hover{
            cursor: pointer;
            color: #00a9e9;
        }
        
        .first-heroleague td{
            background-color: #00a9e9;
            font-size: 16px;
            font-weight: bold;
        }
        
        .type{
            margin-right: 60px;
        }
    </style>
</head>
<body>
    <form method='GET' action='leagues.php'>
        <input type='date' name='date'><br /><br />
        <button type='submit'>Pobierz dane</button>
    </form>
    <?php
    include "connect.php";
    
    $types = array("ZMIANA ELO", "KILLE", "AK-47", "M4A1-M4A4", "AWP");
    
    if(!empty($_GET['date'])){
        
        echo "<div id='con'><h1>Wyniki ".$_GET['date']."</h1>";

        for($i = 0; $i < count($types); $i++){
            $top = $con->query("SELECT user, score FROM heroleague where type='".$types[$i]."' and last_update='".$_GET['date']."' ORDER BY score DESC LIMIT 10");

            echo "
                <table class='left-float heroleague' cellspacing='0' cellpadding='0'>
                    <thead>
                        <tr>
                            <th><img src='img/".$types[$i].".png' alt='".$types[$i]."'></img></th>
                            <th colspan='2'><span class='type'>".$types[$i]."</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='first-heroleague'>
                            <td>#</td>
                            <td>Gracz:</td>
                            <td>Punkty:</td>
                        </tr>";
            
            $k = 1;
            foreach($top as $row){
                echo 
                    "
                    <tr>
                        <td>".$k."</td>
                        <td><a href='https://beta.esportal.pl/profile/".$row['user']."'>".$row['user']."</a></td>
                        <td>".$row['score']."</td>
                    </tr>
                    ";
                
                $k++;
                }

            echo 
                "
                    </tbody>
                </table>
                ";
        }
        echo "</div>";
        
        mysqli_close($con);
    }else{
        echo "<div id='con'><h1>Wyniki ".date("Y-m-d", time()-86494)."</h1>";

        for($i = 0; $i < count($types); $i++){
            $top = $con->query("SELECT user, score FROM heroleague where type='".$types[$i]."' and last_update='".date("Y-m-d", time()-86494)."' ORDER BY score DESC LIMIT 10");

            echo "
                <table class='left-float heroleague' cellspacing='0' cellpadding='0'>
                    <thead>
                        <tr>
                            <th><img src='../img/".$types[$i].".png' alt='".$types[$i]."'></img></th>
                            <th colspan='2'><span class='type'>".$types[$i]."</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='first-heroleague'>
                            <td>#</td>
                            <td>Gracz:</td>
                            <td>Punkty:</td>
                        </tr>";
            
            $k = 1;
            foreach($top as $row){
                echo 
                    "
                    <tr>
                        <td>".$k."</td>
                        <td><a href='https://beta.esportal.pl/profile/".$row['user']."'>".$row['user']."</a></td>
                        <td>".$row['score']."</td>
                    </tr>
                    ";
                
                $k++;
                }

            echo 
                "
                    </tbody>
                </table>
                ";
        }
        echo "</div>";
    }
?>
</body>
</html>