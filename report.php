<?php
    session_start();

    $flag = 0;
    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        include "php/setactive.php";
        if(!empty($_GET['id'])){
            include "connect.php";
            
            $id = $_GET['id'];
            $login = $_COOKIE['auth_login'];
            $token = $_COOKIE['auth_token'];
            $time = date("Y-m-d H:i:s", time() + 94);

            $flag = 1;

            $adminQuery = "select permission from users where login='".$login."' and token='".$token."'";  
            $adminDoQuery = mysqli_query($con, $adminQuery);

            if(mysqli_num_rows($adminDoQuery)){
                while($adminScore = mysqli_fetch_assoc($adminDoQuery))
                    $permission = $adminScore['permission'];
            }
        }
        else{
            header("Location: admin.php");
        }
        @mysqli_free_result($adminDoQuery); 
        mysqli_close($con);
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Esportal Overwatchers Panel - Beta">
    <meta name="author" content="Norbert 'Norbix' Grudzień">
    <title>Esportal OV Panel - Zgłoszenie #<?php echo $id; ?></title>
    
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
                    <div id='replays' class='dark-theme'>";
                    if($permission == 2){
                        echo "<a href='leagues.php'><button class='buttonDark'><i class='fa fa-trophy'></i> Ligi/Wyzwania</button></a>";
                    }
                    if($permission == 1){
                        echo "
                        <a href='addreport.php'><button class='buttonDark'><i class='fa fa-plus-circle'></i> Dodaj zgłoszenia</button></a>
                        <a href='reportdone.php'><button class='buttonDark'><i class='fa fa-check-circle'></i> Sprawdzone zgłoszenia</button></a>
                        <a href='history.php'><button class='buttonDark'><i class='fa fa-history'></i> Historia zgłoszeń</button></a>
                        <a href='account.php'><button class='buttonDark'><i class='fa fa-user-circle'></i> Zarządzanie kontami</button></a>
                        <a href='pending.php'><button class='buttonDark'><i class='fa fa-flag'></i> Aktywne zgłoszenia</button></a>
                        <a href='stats.php'><button class='buttonDark'><i class='fa fa-table'></i> Statystyki</button></a>
                        <a href='leagues.php'><button class='buttonDark'><i class='fa fa-trophy'></i> Ligi/Wyzwania</button></a>
                        <a href='logs.php'><button class='buttonDark'><i class='fa fa-file' aria-hidden='true'></i> Logi</button></a>";
                    }
                    echo 
                        "
                        <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć do panelu</button></a>
                        ";
                    if($flag == 1){

                        include "connect.php";
                        
                        $updateReportQuery = "UPDATE replays SET repadmin='".$login."', repdate='".$time."', ban=100 WHERE id=".$id." and repadmin IS NULL";
                        $updateReportDoQuery = mysqli_query($con, $updateReportQuery);
                        
                        $reportQuery = "SELECT link, repuser, report, repadmin, admindesc, repdate from replays where id=".$id;
                        $reportDoQuery = mysqli_query($con, $reportQuery);
                        
                        if(mysqli_num_rows($reportDoQuery)){
                            while($reportScore = mysqli_fetch_array($reportDoQuery)){
                                
                                $repuser = $reportScore['repuser'];
                                echo 
                                    "
                                    <div id='reportov'>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th colspan=2 class='table-header'>Zgłoszenie #".$id."</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td class='first-row'>Link do dema:</td>
                                                <td><a href='".$reportScore['link']."'>".$reportScore['link']."</a></td>
                                            </tr>
                                            <tr>
                                                <td class='first-row'>Reportowany użytkownik:</td>
                                                <td>".$reportScore['repuser']."</td>
                                            </tr>
                                            <tr>
                                                <td class='first-row'>Powód zgłoszenia:</td>
                                                <td>".$reportScore['report']."</td>
                                            </tr>
                                            <tr>
                                                <td class='first-row'>Sprawdzający zgłoszenie:</td>";
                                        if($permission == 0){
                                            echo "<td><img src='img/overwatcher.png' alt='overwatcher' class='icon'>";
                                            $logAction = "Przyjął zgłoszenie o id #".$id.".";
                                            include "php/setlogs.php";
                                        }
                                        if($permission == 1){
                                            echo "<td><img src='img/admin.png' alt='admin' class='icon'>";
                                            $logAction = "Przeglądnąl zgłoszenie o id #".$id.".";
                                            include "php/setlogs.php";
                                        }
                                        if($permission == 2){
                                            echo "<td><img src='img/moderator.png' alt='moderator' class='icon'>";
                                            $logAction = "Przyjął zgłoszenie o id #".$id.".";
                                            include "php/setlogs.php";
                                        }
                                echo
                                    "
                                    <span class='accTheme'>".$reportScore['repadmin']."</span></td>
                                            </tr>
                                            <tr>
                                                <td class='first-row'>Data przyjęcia zgłoszenia:</td>
                                                <td>".$reportScore['repdate']."</td>
                                            </tr>
                                            <tr>
                                                <td class='first-row'>Dodatkowy opis zgłoszenia:</td>
                                                <td>".$reportScore['admindesc']."</td>
                                            </tr>
                                        </table><br />

                                        <form method='POST' action='reportclose.php?id=".$id."'>
                                            <textarea name='description' placeholder='Szczegółowy opis decyzji(max 255 znaków)' class='textareaInput' required></textarea><br />
                                            <p>Twoja decyzja (BAN):</p>
                                            <select name='ban' class='selectInput' required>
                                                <option disabled='disabled'>Wybierz opcje:</option>
                                                <option value='1'>Tak</option>
                                                <option value='2'>Niewystarczające dowody</option>
                                            </select><br />
                                            <button type'submit' class='buttonDarkRed'>Zamknij zgłoszenie</button><br /><br />
                                        </form>
                                    </div>
                                    ";
                            }
                        }
                        
                        $historyQuery = "SELECT repuser, report, repadmin, ban, archivedate from replays where repuser='".$repuser."' and archivedate IS NOT NULL";
                        $historyDoQuery = mysqli_query($con, $historyQuery);
                        
                        echo 
                            "
                            <table>
                                <thead>
                                    <tr>
                                        <th colspan=5 class='history-header'>Historia użytkownika:</th>
                                    </tr>
                                </thead>
                                <tr class='first'>
                                    <td>Użytkownik:</td>
                                    <td>Powód zgłoszenia:</td>
                                    <td>Sprawdzający zgłoszenie:</td>
                                    <td>Decyzja sprawdzającego:</td>
                                    <td>Data zakończenia zgłoszenia:</td>
                                </tr>";
                        
                        if(mysqli_num_rows($historyDoQuery)){
                            while($historyScore = mysqli_fetch_array($historyDoQuery)){
                                
                                $ban = $historyScore['ban'];
                                
                                if($ban == 1)
                                    $decise = "Blokada";
                                if($ban == 2)
                                    $decise = "Niewystarczające dowody";
                                
                                echo 
                                    "
                                    <tr>
                                        <td>".$historyScore['repuser']."</td>
                                        <td>".$historyScore['report']."</td>
                                        ";
                                    if($permission == 0)
                                        echo "<td><img src='img/overwatcher.png' alt='overwatcher' class='icon'>";
                                    if($permission == 1)
                                        echo "<td><img src='img/admin.png' alt='admin' class='icon'>";
                                echo "
                                        <span class='accTheme'>".$historyScore['repadmin']."</span></td>
                                        <td>".$decise."</td>
                                        <td>".$historyScore['archivedate']."</td>
                                    </tr>
                                    ";
                            }
                        }
                        
                        echo "</table>";
                        
                        @mysqli_free_result($historyDoQuery);
                        @mysqli_free_result($reportDoQuery);
                        @mysqli_free_result($updateReportDoQuery);
                        mysqli_close($con);
                    }
                    echo "
                        </div>
                        ";
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