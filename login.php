<?php    
    
    include "connect.php";
    
    if(!empty($_POST['login']) && !empty($_POST['password'])){
        
        session_start();
        $time = date("Y-m-d H:i:s", time() + 94);
        
		$login = addslashes($_POST['login']);
		$password = md5(addslashes($_POST['password']));
		
		$passQuery = "select id,login,password,permission,active,token from users where login='$login' and password='$password'";
		$passDoQuery = mysqli_query($con, $passQuery);
		
        echo "Jestem tu";
        
		$x = mysqli_num_rows($passDoQuery);
		if($x!=0){
			while($passScore = mysqli_fetch_assoc($passDoQuery)){
                
                if($passScore['active'] == 1){
                    
                    setcookie("auth_token", $passScore['token'], time()+1209600, '/');
                    setcookie("auth_login", $passScore['login'], time()+1209600, '/');
                    setcookie("auth_id", $passScore['id'], time()+1209600, '/');
                    $status = passScore['active'];

                    $logQuery = "update users set lastlogin='".$time."' where id=".$passScore['id'];
                    $logDoQuery = mysqli_query($con, $logQuery);

                    $logAction = "Użytkownik zalogował się.";
                    include "php/setlogs.php";
                    
                    @mysqli_free_result($logDoQuery);
                    @mysqli_free_result($passDoQuery); 
                    mysqli_close($con);

                    header('Location: admin.php');
                    
                }else{
                    @mysqli_free_result($passDoQuery); 
                    mysqli_close($con);
                    
                    header('Location: index.php?id=nonactive');
                }
                
            }
        }else{
            @mysqli_free_result($passDoQuery); 
            mysqli_close($con);
            
            header('Location: index.php?id=wrong');
		}
    }

?>