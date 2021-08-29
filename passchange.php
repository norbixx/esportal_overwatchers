<?php
    session_start();
    
    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
		$login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];
    }

    include "connect.php";
        
        if(!empty($_POST['password']) && !empty($_POST['repeatPassword'])){
            
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeatPassword'];
            
            if($password === $repeatPassword){
                
                $logAction = "Użytkownik zmienił swoje hasło.";
                include "php/setlogs.php";
                
                $password = md5(addslashes($_POST['password']));
                
                $passQuery = "UPDATE users SET password='".$password."' WHERE login='".$login."' and token='".$token."'";
                
                $passDoQuery = mysqli_query($con, $passQuery);

                @mysqli_free_result($passDoQuery); 
                mysqli_close($con);
                
                session_destroy();
                
                header('Location: index.php?id=changesuccess');
            }
            else{
                header('Location: changepass.php?id=wrong');
            }
        }
?>