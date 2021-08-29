<?php
    session_start();

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login']) && isset($_COOKIE['auth_id'])){
        unset($_COOKIE['auth_token']);
        unset($_COOKIE['auth_login']);
        unset($_COOKIE['auth_id']);
        setcookie('auth_token', null, time() - 3600, '/');
        setcookie('auth_login', null, time() - 3600, '/');
        setcookie('auth_id', null, time() - 3600, '/');
        
        $login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];
    }
    
    include "connect.php";
    $query = "UPDATE users SET activitytime=NOW()-1000 WHERE login='".$login."' and token='".$token."'"; 
    $doQuery = mysqli_query($con, $query);

    $logAction = "Użytkownik wylogował się.";
    include "php/setlogs.php";

    @mysqli_free_result($doQuery);
    mysqli_close($con);
    
    session_destroy();
    header("Location: index.php");

?>