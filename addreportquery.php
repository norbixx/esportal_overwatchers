<?php
    session_start();

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        
        $expireTime = date("Y-m-d H:i:s", (time() + (96*60*60) + 94));
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
            
            for($i = 1; $i < 11; $i++){
                if(!empty($_POST['link_'.$i]) && !empty($_POST['repuser_'.$i]) && !empty($_POST['report_'.$i]) && !empty($_POST['replink_'.$i])){

                    $link = addslashes($_POST['link_'.$i]);
                    $repuser = addslashes($_POST['repuser_'.$i]);
                    $report = addslashes($_POST['report_'.$i]);
                    $replink = addslashes($_POST['replink_'.$i]);
                    if(!empty($_POST['admindesc_'.$i])){
                        $admindesc = addslashes($_POST['admindesc_'.$i]);
                        $addQuery = "INSERT INTO replays(link, repuser, report, admindesc, replink, expiredate) VALUES ('".$link."', '".$repuser."', '".$report."', '".$admindesc."', '".$replink."', '".$expireTime."')";
                    }else{
                        $addQuery = "INSERT INTO replays(link, repuser, report, admindesc, replink, expiredate) VALUES ('".$link."', '".$repuser."', '".$report."', NULL, '".$replink."', '".$expireTime."')";
                    }

                    $addDoQuery = mysqli_query($con, $addQuery);
                }
                else{
                    break;
                }
            }
            
            $logAction = "Dodał ".($i-1)." zgłoszeń.";
                include "php/setlogs.php";
        }
            
        @mysqli_free_result($addDoQuery);
        @mysqli_free_result($adminDoQuery); 
        mysqli_close($con);
    }

    header("Location: addreport.php?id=true");
    
?>