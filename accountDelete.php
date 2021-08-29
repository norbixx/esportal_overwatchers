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

            if($permission = 1){
                $logAction = "Usunął konto użytkownika o id #".$id.".";
                include "php/setlogs.php";
                
                $deleteQuery = "DELETE FROM users WHERE id=".$id;
                $deleteDoQuery = mysqli_query($con, $deleteQuery);
            }
            
            @mysqli_free_result($deleteDoQuery);
            @mysqli_free_result($adminDoQuery); 
            mysqli_close($con);
            }
    }

        header("Location: account.php");
    
?>