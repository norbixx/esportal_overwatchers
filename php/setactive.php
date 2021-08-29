<?php

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        
        include "connect.php";
        
        if (time() > (time() - (5*60) + 94)){
            $query = "UPDATE users SET activitytime=NOW() WHERE login='".$_COOKIE['auth_login']."'";
            $doQuery = mysqli_query($con, $query);
        }
        
        @mysqli_free_result($doQuery);
        mysqli_close($con);
    }
?>