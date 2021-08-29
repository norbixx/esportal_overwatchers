<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Esportal Overwatchers Panel - Beta">
    <meta name="author" content="Norbert 'Norbix' Grudzień">
    <title>Esportal OV Panel - Login</title>
    
    <!-- CSS -->
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    
    <!-- JS -->
    <script>
        function startTime() {
          var today = new Date();
          var h = today.getHours();
          var m = today.getMinutes();
          var s = today.getSeconds();
          m = checkTime(m);
          s = checkTime(s);
          document.getElementById('time').innerHTML =
          h + ":" + m + ":" + s;
          var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
          if (i < 10) {i = "0" + i};
          return i;
        }
        
        function unlockPass() {
          var x = document.getElementById("passInput");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
        }
    </script>
</head>
<body onload="startTime()">
    <div id="con">
        <div id="logo">
            <a href='index.php'><img src="img/esportal_white.png" alt="esportal_logo_black" /></a>
        </div>
        <div id="header" class="dark-theme">
            <div id="title">
                <p>Overwatchers Panel<span id="beta">1.5</span></p>
            </div>
        </div>
        <section>
            <?php
                if(!empty($_GET['id'])){
                    if($_GET['id']=='changesuccess'){
                        echo '<div class="success">Hasło zostało zmienione pomyślnie! Zaloguj się ponownie.</div>';
                    }
                }
                
                if(isset($_COOKIE['auth_token'])){
                    $token = $_COOKIE['auth_token'];
                    
                    include_once("connect.php");
                    $auth = "SELECT login, permission, lastlogin from users where token='".$token."'";
                    $authQuery = mysqli_query($con, $auth);
                    
                    if(mysqli_num_rows($authQuery)){
                        while($score = mysqli_fetch_assoc($authQuery)){
                            $login = $score['login'];
                            $permission = $score['permission'];
                            $lastLogin = $score['lastlogin'];
                            
                            if($permission == 2)
                                $img = "<img src='img/moderator.png' alt='cupmod' class='iconAcc' />";
                            else if($permission == 1)
                                $img = "<img src='img/admin.png' alt='admin' class='iconAcc' />";
                            else
                                $img = "<img src='img/overwatcher.png' alt='overwatcher' class='iconAcc' />";
                        }
                    }
                    
                    $authDate = new DateTime($lastLogin);
                    $authDate->modify("+2 week");
                    $remainingDate = strtotime($authDate->format("Y-m-d H:i:s"));
                    $remaining = $remainingDate - time();
                    $daysRemaining = floor($remaining / 86400);
                    
                    if($daysRemaining > 7)
                        $days = "za <span class='green'>".$daysRemaining."</span> dni";
                    else if($daysRemaining > 3)
                        $days = "za <span class='orange'>".$daysRemaining."</span> dni";
                    else if($daysRemaining > 1)
                        $days = "za <span class='red'>".$daysRemaining."</span> dni";
                    else
                        $days = "<span class='red'>DZIŚ</span>!";
                
                    echo "
                        <div id='auth-true'>
                            <p>Zalogowany jako ".$img."<span class='accTheme'>".$login."</span></p>
                            <p>Twoja sesja wygasa ".$days."</p>
                            <p>Data ostatniego logowania: ".$lastLogin."</p>
                            <a href='admin.php'><button class='buttonDark'><i class='fas fa-angle-double-up'></i> Przejdź do panelu</button></a>
                            <a href='logout.php'><button class='buttonDarkRed'><i class='fa fa-sign-out-alt'></i> Wyloguj</button></a>
                        </div>
                        ";
                    
                    @mysqli_free_result($authQuery);
                    mysqli_close($con);

                }else{
                    echo 
                        "
                        <form method='POST' action='login.php'>
                        <input type='text' name='login' placeholder='Login'><br />
                        <div id='pass'>
                            <input type='password' name='password' placeholder='Hasło' id='passInput'>
                            <input type='checkbox' onclick='unlockPass()' class='css-checkbox'><br />
                        </div>
                        <button type='submit'>ZALOGUJ <i class='fas fa-sign-in-alt'></i> </button>
                        </form>
                        <p>Jeżeli zapomniałeś hasła skontaktuj się z administratorem.</p>
                        ";
                }
            
            
                if(!empty($_GET['id'])){
                    if($_GET['id']=='wrong'){
                        echo '<br /><div class="wrong">Niepoprawny login lub hasło!</div><br />';
                    }
                }
                if(!empty($_GET['id'])){
                    if($_GET['id']=='nonactive'){
                        echo '<br /><div class="wrong">Twoje konto zostało zdezaktywowane. Jeżeli nie wiesz dlaczego tak się stało - skontaktuj się z administratorem.</div><br />';
                    }
                }
                    
                    
                ?>
        </section>
        <footer class="dark-theme">
            <span id="time"></span>
            <?php
                date_default_timezone_set('Europe/Warsaw');
                echo $timestamp = date(' - d/m/Y').' / (GMT0100)';
            
                include "ver.php";
            ?>
        </footer>
    </div>
</body>
</html>