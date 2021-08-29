<?php
    session_start();

    $time = date("Y-m-d H:i:s", time() + 94);

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
                    $logAction = "Zaarchiwizował zgłoszenie o id #".$id.".";
                    include "php/setlogs.php";
                    
                    $archiveQuery = "UPDATE replays SET done=1, archivedate='".$time."', archiveuser='".$login."' WHERE id='".$id."'";
                    $archiveDoQuery = mysqli_query($con, $archiveQuery);
                }

                @mysqli_free_result($archivedoQuery);
                @mysqli_free_result($adminDoQuery); 
                mysqli_close($con);
        }
    }
    
    header("Location: reportdone.php");
    
?>