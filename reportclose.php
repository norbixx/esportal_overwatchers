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

            if($permission == 0 || $permission == 1 || $permission == 2){
                
                if(!empty($_POST['description']) && !empty($_POST['ban'])){
                    
                    $logAction = "Zakończył zgłoszenie o id #".$id.".";
                    include "php/setlogs.php";
                    
                    $description = addslashes($_POST['description']);
                    $ban = addslashes($_POST['ban']);
                
                    $reportQuery = "UPDATE replays SET description='".$description."', ban=".$ban." WHERE id=".$id;
                    $reportDoQuery = mysqli_query($con, $reportQuery);
                }
            }
            
            @mysqli_free_result($reportDoQuery);
            @mysqli_free_result($adminDoQuery); 
            mysqli_close($con);
            }
    }

        header("Location: admin.php?report=true");
    
?>