<?php
    session_start();

    if(!empty($_GET['id'])){
        $id = $_GET['id'];
        if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){

            $login = $_COOKIE['auth_login'];
            $token = $_COOKIE['auth_token'];

            include "connect.php";
            $adminQuery = "select permission from users where login='".$login."' and token='".$token."'";  
            $adminDoQuery = mysqli_query($con, $adminQuery);

            if(mysqli_num_rows($adminDoQuery)){
                 while($adminScore = mysqli_fetch_assoc($adminDoQuery))
                     $permission = $adminScore['permission'];
            }

            if($permission == 1){
                $logAction = "Zwiększył uprawnienia użytkownika o id #".$id.".";
                include "php/setlogs.php";
                
                $upgradeQuery = "UPDATE users SET permission=".$_GET['perm']." WHERE id=".$id;
                $upgradeDoQuery = mysqli_query($con, $upgradeQuery);
            }
            
            @mysqli_free_result($upgradeDoQuery);
            @mysqli_free_result($adminDoQuery); 
            mysqli_close($con);
            }
    }

    header("Location: account.php");
    
?>