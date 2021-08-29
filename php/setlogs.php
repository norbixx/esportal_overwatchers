<?php

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        
        include "connect.php";
    
        $logId = $_COOKIE['auth_id'];
        $logLogin = $_COOKIE['auth_login'];
        $logIp = get_ip();
        $logTime = date("Y-m-d H:i:s", time() + 94);

        $setLogQuery = "INSERT INTO logs(u_id, login, action, actiondate, ip) VALUES (".$logId.", '".$logLogin."', '".$logAction."', '".$logTime."', '".$logIp."')";
        $setLogDoQuery = mysqli_query($con, $setLogQuery);

        @mysqli_free_result($setLogDoQuery);
    }


    function get_ip($ip2long = true){
        $ip = $_SERVER['REMOTE_ADDR'];
            
        return $ip;
    }
?>