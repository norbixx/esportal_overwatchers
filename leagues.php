<?php
    session_start();

    $flag = 0;
    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        include "php/setactive.php";
        include "connect.php";
        $login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];
        $time = date("Y-m", time() + 94);
        
        $flag = 1;
        
        $adminQuery = "select permission from users where login='".$login."' and token='".$token."'";  
		$adminDoQuery = mysqli_query($con, $adminQuery);
		
		if(mysqli_num_rows($adminDoQuery)){
			while($adminScore = mysqli_fetch_assoc($adminDoQuery))
				$permission = $adminScore['permission'];
        }
    }
            
    @mysqli_free_result($adminDoQuery); 
    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Esportal Overwatchers Panel - Beta">
    <meta name="author" content="Norbert 'Norbix' Grudzień">
    <title>Esportal OV Panel - Ligi/wyzwania</title>
    
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
    <div id="con-league">
            <?php
                if(!isset($_COOKIE['auth_token']) && !isset($_COOKIE['auth_login'])){
                    echo "<nav>
                        <a href='index.php'><img src='img/esportal_white.png' alt='esportal_logo_black' /></a>
                    </nav>
                    <header class='dark-theme'>
                        <div id='title'>
                            Overwatchers Panel<span id='beta'>.BETA</span>
                        </div>
                    </header>
                    <section>
                    <span id='noneperm'>Nie posiadasz uprawnień do przeglądania tej strony!</span><br />
                    <a href='index.php'><button class='offsite'>Strona główna</button></a>
                    </section>";
                }
                else{
                    echo "<div id='acc'>
                    <p>Zalogowany jako: ";
                        
                    if($permission == 0)
                        echo "<img src='img/overwatcher.png' alt='overwatcher' class='iconAcc'>";
                    if($permission == 1)
                        echo "<img src='img/admin.png' alt='admin' class='iconAcc'>";
                    if($permission == 2)
                        echo "<img src='img/moderator.png' alt='moderator' class='iconAcc'>";
                        
                    echo"<span class='accTheme'>".$login."</span>
                    <a href='changepass.php'><button class='buttonDark' id='change'><i class='fa fa-key'></i> Zmień hasło</button></a>
                    <a href='logout.php'><button class='buttonDark'><i class='fa fa-sign-out-alt'></i> Wyloguj</button></a>
                    </p></div>
                    <div id='leagues' class='dark-theme'>
                    <h1>Ligi / Wyzwania</h1><br />
                    ";
                    if($permission == 2){
                        echo 
                            "
                            <a href='leagues.php'><button class='buttonDark active'><i class='fa fa-trophy'></i> Ligi/Wyzwania</button></a>
                            <a href='myreport.php'><button class='buttonDark'><i class='fa fa-flag'></i> Moje aktualne zgłoszenia</button></a>
                            <a href='ovhistory.php'><button class='buttonDark'><i class='fa fa-history'></i> Historia moich zgłoszeń</button></a>
                            <a href='myaccount.php'><button class='buttonDark'><i class='fa fa-table'></i> Moje statystyki</button></a>
                            <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć</button></a>
                            <br /><br />
                        ";
                    }
                    if($permission == 1){
                        echo "
                        <a href='addreport.php'><button class='buttonDark'><i class='fa fa-plus-circle'></i> Dodaj zgłoszenia</button></a>
                        <a href='reportdone.php'><button class='buttonDark'><i class='fa fa-check-circle'></i> Sprawdzone zgłoszenia</button></a>
                        <a href='history.php'><button class='buttonDark'><i class='fa fa-history'></i> Historia zgłoszeń</button></a>
                        <a href='account.php'><button class='buttonDark'><i class='fa fa-user-circle'></i> Zarządzanie kontami</button></a>
                        <a href='pending.php'><button class='buttonDark'><i class='fa fa-flag'></i> Aktywne zgłoszenia</button></a>
                        <a href='stats.php'><button class='buttonDark'><i class='fa fa-table'></i> Statystyki</button></a>
                        <a href='leagues.php'><button class='buttonDark active'><i class='fa fa-trophy'></i> Ligi/Wyzwania</button></a>
                        <a href='logs.php'><button class='buttonDark'><i class='fa fa-file' aria-hidden='true'></i> Logi</button></a>
                        <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć</button></a><br /><br />";
                    }
                    if($permission == 1 || $permission == 2){
                        if($flag == 1){
                            
                            include "connect.php";
                            
                            echo "<div id='herodiv'>";
                            
                            if(!empty($_GET['add'])){
                                if($_GET['add'] == 'true'){
                                    echo "<br /><p class='success'>Punkty zostały dodane pomyślnie!</p><br />";
                                }
                            }
                            
                            $types = array("ZMIANA ELO", "KILLE", "AK-47", "M4A1-M4A4", "AWP");
                            
                            if(!empty($_GET['date'])){
        
                                echo 
                                    "
                                <h2>Wyniki z dnia ".$_GET['date']."</h2><br />
                                    <form method='GET' action='leagues.php'>
                                        <label><input type='date' name='date'></label><br />
                                        <label><button type='submit' class='buttonDark'><i class='fas fa-search'></i> Wyszukaj dane</button></label>
                                    </form>
                                    ";

                                for($i = 0; $i < count($types); $i++){
                                    $top = $con->query("SELECT user, score FROM heroleague where type='".$types[$i]."' and last_update='".$_GET['date']."' ORDER BY score DESC LIMIT 10");

                                    echo "
                                        <table class='left-float heroleague' cellspacing='0' cellpadding='0'>
                                            <thead>
                                                <tr>
                                                    <th><img src='img/".$types[$i].".png' alt='".$types[$i]."'></img></th>
                                                    <th colspan='2'><span class='type'>".$types[$i]."</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class='first-heroleague'>
                                                    <td>#</td>
                                                    <td>Gracz:</td>
                                                    <td>Punkty:</td>
                                                </tr>";

                                    $k = 1;
                                    foreach($top as $row){
                                        echo 
                                            "
                                            <tr>
                                                <td>".$k."</td>
                                                <td><a href='https://beta.esportal.pl/profile/".$row['user']."'>".$row['user']."</a></td>
                                                <td>".$row['score']."</td>
                                            </tr>
                                            ";

                                        $k++;
                                        }

                                    echo 
                                        "
                                            </tbody>
                                        </table>
                                        ";
                                }
                                echo "</div>";

                            }else{
                                echo 
                                    "
                                    <h2>Wyniki z dnia ".date("Y-m-d", time()-86494)."</h2><br />
                                    <form method='GET' action='leagues.php'>
                                        <label><input type='date' name='date'></label><br />
                                        <label><button type='submit' class='buttonDark'><i class='fas fa-search'></i> Wyszukaj dane</button></label>
                                    </form>
                                    ";

                                for($i = 0; $i < count($types); $i++){
                                    $top = $con->query("SELECT user, score FROM heroleague where type='".$types[$i]."' and last_update='".date("Y-m-d", time()-86494)."' ORDER BY score DESC LIMIT 10");

                                    echo "
                                        <table class='left-float heroleague' cellspacing='0' cellpadding='0'>
                                            <thead>
                                                <tr>
                                                    <th><img src='img/".$types[$i].".png' alt='".$types[$i]."'></img></th>
                                                    <th colspan='2'><span class='type'>".$types[$i]."</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class='first-heroleague'>
                                                    <td>#</td>
                                                    <td>Gracz:</td>
                                                    <td>Punkty:</td>
                                                </tr>";

                                    $k = 1;
                                    foreach($top as $row){
                                        echo 
                                            "
                                            <tr>
                                                <td>".$k."</td>
                                                <td><a href='https://beta.esportal.pl/profile/".$row['user']."'>".$row['user']."</a></td>
                                                <td>".$row['score']."</td>
                                            </tr>
                                            ";

                                        $k++;
                                        }

                                    echo 
                                        "
                                            </tbody>
                                        </table>
                                        ";
                                }
                                echo "</div>";
                            }
                            
                            
                            $actualDate = date("Y-m-d");
                            
                            echo 
                                "
                                <div id='heroleague-table'>
                                <h2>Dodaj punkty do tabeli TOP10: </h2><br />
                                <form action='addpoints.php' method='POST'>
                                    <input type='date' name='date' value='".$actualDate."'><br /><br/>";
                            
                            
                            for($i = 1; $i < 11; $i++){
                                
                                if($i == 1)
                                    $startPoints = 10;
                                if($i == 2)
                                    $startPoints = 6;
                                if($i == 3)
                                    $startPoints = 4;
                                if($i == 4)
                                    $startPoints = 3;
                                if($i == 5)
                                    $startPoints = 2;
                                if($i > 5)
                                    $startPoints = 1;
                                
                                echo 
                                    "
                                    <span id='rep_".$i."'>#".$i."</span>
                                    <input type='text' name='user_".$i."' placeholder='Nazwa użytkownika' class='userInput'>
                                    <input type='number' name='points_".$i."' placeholder='Liczba punktów' value='".$startPoints."' class='userInput'><br />
                                    ";
                                }

                                echo 
                                    "
                                        <br /><button type='submit' class='buttonDark'><i class='fas fa-plus-circle'></i> Dodaj punkty</button>
                                    </form>
                                    ";
                        echo 
                            "
                                </div>
                            </div>
                        </div>
                        ";
                        }
                    
                }else{
                        echo "<nav>
                        <a href='index.php'><img src='img/esportal_white.png' alt='esportal_logo_black' /></a>
                            </nav>
                            <header class='dark-theme'>
                                <div id='title'>
                                    Overwatchers Panel<span id='beta'>.BETA</span>
                                </div>
                            </header>
                            <section>
                            <span id='noneperm'>Nie posiadasz uprawnień do przeglądania tej strony!</span><br />
                            <a href='admin.php'><button class='offsite'>▲Wróć</button></a>
                            </section>";
                    }
                }
            ?>
        <div class='clear'></div>
        <footer class="dark-theme">
            <span id="time"></span>
            <?php
                date_default_timezone_set('Europe/Warsaw');
                echo $timestamp = date(' - d/m/Y').' / (GMT0100)';
            
                include "ver.php";
            ?>
        </footer>
    </div>
    <div id='activity'>
        
            <?php
            
            if($flag == 1){
                
                include "connect.php";
                
                $aTime = date("Y-m-d H:i:s", (time() - (5*60) + 94));
                
                $aQuery = "SELECT login, permission from users where activitytime > '".$aTime."'";
                $aDoQuery = mysqli_query($con, $aQuery);
                
                echo 
                    "
                    <table>
                        <thead>
                            <tr>
                                <th>Użytkownicy online:</th>
                            </tr>
                        </thead>
                        <tbody>
                    ";
                
                if(mysqli_num_rows($aDoQuery)){
                    while($aScore = mysqli_fetch_array($aDoQuery)){
                        
                        if($aScore['permission'] == 0)
                            echo "<tr><td><img src='img/overwatcher.png' alt='overwatcher' class='iconAcc'><span class='accTheme'>".$aScore['login']."</span> <span class='dot'></span></td></tr>"; 
                        if($aScore['permission'] == 1)
                            echo "<tr><td><img src='img/admin.png' alt='admin' class='iconAcc'><span class='accTheme'>".$aScore['login']."</span> <span class='dot'></span></td></tr>";
                        if($aScore['permission'] == 2)
                            echo "<tr><td><img src='img/moderator.png' alt='moderator' class='iconAcc'><span class='accTheme'>".$aScore['login']."</span> <span class='dot'></span></td></tr>";
                    }
                }
                
                echo 
                    "
                        </tbody>
                    </table>
                    ";
                
                
                @mysqli_free_result($aDoQuery);
                mysqli_close($con);
            }
            
            
            ?>
            
    </div>
</body>
</html>