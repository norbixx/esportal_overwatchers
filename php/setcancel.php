<?php

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        
        include "connect.php";
        $time = date("Y-m-d H:i:s", (time() - (24*60*60) + 94));
        
        $checkQuery = "SELECT id, repdate from replays where ban=100";
        $checkDoQuery = mysqli_query($con, $checkQuery);
        
        if(mysqli_num_rows($checkDoQuery)){
            while($checkScore = mysqli_fetch_assoc($checkDoQuery)){
                if ($checkScore['repdate'] < $time){
                    
                    $cancelQuery = "UPDATE replays SET repadmin=NULL, ban=0, repdate=NULL where id=".$checkScore['id'];
                    $cancelDoQuery = mysqli_query($con, $cancelQuery);
                }
            }
        }
        
        @mysqli_free_result($cancelDoQuery);
        @mysqli_free_result($checkDoQuery);
        mysqli_close($con);
    }

?>