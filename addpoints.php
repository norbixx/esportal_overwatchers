<?php
    session_start();

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        
        $time = date("Y-m-d H:i:s", time() + 94);
        
        $login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];

        include "connect.php";
        $adminQuery = "select permission from users where login='".$login."' and token='".$token."'";
        $adminDoQuery = mysqli_query($con, $adminQuery);

        if(mysqli_num_rows($adminDoQuery)){
             while($adminScore = mysqli_fetch_assoc($adminDoQuery))
                $permission = $adminScore['permission'];
        }

        if($permission = 1){
            
            $logAction = "Wprowadził punkty do tabeli Hero League.";
            include "php/setlogs.php";
            
            for($i = 1; $i < 11; $i++){
                if(!empty($_POST['user_'.$i]) && !empty($_POST['points_'.$i]) && !empty($_POST['date'])){

                    $user = addslashes($_POST['user_'.$i]);
                    $points = addslashes($_POST['points_'.$i]);
                    $date = addslashes($_POST['date']);

                    $addQuery = "INSERT INTO league_score(user, points, l_id, date, add_date, admin) VALUES ('".$user."', '".$points."', 003, '".$date."', '".$time."', '".$login."')";
                    $addDoQuery = mysqli_query($con, $addQuery);
                }
                else{
                    break;
                }
            }
        }
            
        @mysqli_free_result($addDoQuery);
        @mysqli_free_result($adminDoQuery); 
        mysqli_close($con);
    }

    header("Location: leagues.php?add=true");
    
?>