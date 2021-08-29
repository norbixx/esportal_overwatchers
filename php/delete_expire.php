<?php

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        
        include "connect.php";
        $time = date("Y-m-d H:i:s", time() + 94);
        
        $checkQuery = "SELECT id, expiredate from replays where done=0 and ban=0";
        $checkDoQuery = mysqli_query($con, $checkQuery);
        
        if(mysqli_num_rows($checkDoQuery)){
            while($checkScore = mysqli_fetch_assoc($checkDoQuery)){
                if ($checkScore['expiredate'] < $time){
                    
                    $deleteQuery = "UPDATE replays set ban=999, done=999 where id=".$checkScore['id'];
                    $deleteDoQuery = mysqli_query($con, $deleteQuery);
                }
            }
        }
        
        @mysqli_free_result($deleteDoQuery);
        @mysqli_free_result($checkDoQuery);
        mysqli_close($con);
    }

?>