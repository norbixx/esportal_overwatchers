<?php
    session_start();

    $flag = 0;
    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        include "php/setactive.php";
        include "connect.php";
		$login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];
        
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
    <title>Esportal OV Panel - Dodaj zgłoszenia</title>
    
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
    <div id="con-adm">
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
                    <div id='replays' class='dark-theme'>
                    ";
                    
                    if($permission == 1){
                        echo "
                        <h1>Dodaj zgłoszenia</h1><br />
                        <a href='addreport.php'><button class='buttonDark active'><i class='fa fa-plus-circle'></i> Dodaj zgłoszenia</button></a>
                        <a href='reportdone.php'><button class='buttonDark'><i class='fa fa-check-circle'></i> Sprawdzone zgłoszenia</button></a>
                        <a href='history.php'><button class='buttonDark'><i class='fa fa-history'></i> Historia zgłoszeń</button></a>
                        <a href='account.php'><button class='buttonDark'><i class='fa fa-user-circle'></i> Zarządzanie kontami</button></a>
                        <a href='pending.php'><button class='buttonDark'><i class='fa fa-flag'></i> Aktywne zgłoszenia</button></a>
                        <a href='stats.php'><button class='buttonDark'><i class='fa fa-table'></i> Statystyki</button></a>
                        <a href='leagues.php'><button class='buttonDark'><i class='fa fa-trophy'></i> Ligi/Wyzwania</button></a>
                        <a href='logs.php'><button class='buttonDark'><i class='fa fa-file' aria-hidden='true'></i> Logi</button></a>
                        <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć</button></a><br /><br />";
                    echo 
                        "
                        <div id='reportlist' class='dark-theme'>
                        <form method='POST' action='addreportquery.php'>";
                        
                        if(!empty($_GET['id'])){
                            if($_GET['id'] == 'true'){
                                echo "<p class='success'>Zgłoszenia zostały dodane pomyślnie!</p><br />";
                            }
                        }
                        
                        for($i = 1; $i < 11; $i++){
                            echo "
                                <span id='rep_".$i."'>#".$i."</span><input type='text' name='link_".$i."' placeholder='https://static.esportal.com/demo/xxxxxx.dem' class='linkInput'>
                                <input type='text' name='repuser_".$i."' placeholder='Reportowany użytkownik' class='userInput'>
                                <select name='report_".$i."' class='selectInput'>
                                    <option disabled selected>Wybierz powód</option>
                                    <option value='Zle zachowanie / Griefing'>Złe zachowanie / Griefing</option>
                                    <option value='Ragequit'>Ragequit</option>
                                    <option value='Podejrzenie oszustwa'>Podejrzenie oszustwa</option>
                                    <option value='Naduzycie czatu tekstowego'>Nadużycie czatu tekstowego</option>
                                    <option value='Naduzycie czatu glosowego'>Nadużycie czatu głosowego</option>
                                </select>
                                <input type='text' name='replink_".$i."' placeholder='https://esportal.pl/admin/report/xxxxxxx' class='linkInput'>
                                <textarea name='admindesc_".$i."' placeholder='Opis zgłoszenia(niewymagane)' class='textareaInputLow'></textarea><br />";
                        }
                    echo "
                                <br /><button type='submit' class='buttonDarkRed'><i class='fa fa-check-circle'></i> Dodaj zgłoszenia</button>
                            </form>
                        </div>
                        ";
                    }
                    else{
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