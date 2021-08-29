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

    if(!empty($_GET['user']))
        $user = $_GET['user'];
            
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
    <title>Esportal OV Panel - Moje statystyki</title>
    
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
    <div id="con-sta">
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
                    
                if($permission == 0 || $permission == 2)
                    echo "<h1>Moje konto</h1><br />";
                if($permission == 1 && !empty($user))
                    echo "<h1>Konto użytkownika ".$user."</h1><br />";
                    
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
                        <a href='logs.php'><button class='buttonDark'><i class='fa fa-file' aria-hidden='true'></i> Logi</button></a>
                        <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć</button></a><br /><br />";
                    }
                    if($permission == 0 || $permission == 2){
                    echo 
                        "
                        <a href='myreport.php'><button class='buttonDark'><i class='fa fa-flag'></i> Moje aktualne zgłoszenia</button></a>
                        <a href='ovhistory.php'><button class='buttonDark'><i class='fa fa-history'></i> Historia moich zgłoszeń</button></a>
                        <a href='myaccount.php'><button class='buttonDark active'><i class='fa fa-table'></i> Moje statystyki</button></a>
                        <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć</button></a>
                        <br /><br />
                        ";
                    }
                    if($flag == 1){
                        
                        if(!empty($_GET['user'])){
                            if($permission == 1){

                                include "connect.php";

                                //user
                                $uQuery_0 = "select id, login, email, permission, lastlogin, active from users where login='".$user."'";
                                //checked
                                $uQuery_1 = "select u.login as login, u.permission as permission, count(case when r.repadmin = u.login then 1 else null end) as repCount from users as u, replays as r where (r.ban = 1 or r.ban = 2) and u.login='".$user."' group by u.login order by repCount desc";
                                //banned
                                $uQuery_2 = "select u.login as login, u.permission as permission, count(case when r.repadmin = u.login then 1 else null end) as repCount from users as u, replays as r where r.ban = 1 and u.login='".$user."' group by u.login order by repCount desc";
                                //unbanned
                                $uQuery_3 = "select u.login as login, u.permission as permission, count(case when r.repadmin = u.login then 1 else null end) as repCount from users as u, replays as r where r.ban = 2 and u.login='".$user."' group by u.login order by repCount desc";

                                $uQueryDone_0 = mysqli_query($con, $uQuery_0);
                                $uQueryDone_1 = mysqli_query($con, $uQuery_1);
                                $uQueryDone_2 = mysqli_query($con, $uQuery_2);
                                $uQueryDone_3 = mysqli_query($con, $uQuery_3);

                                if(mysqli_num_rows($uQueryDone_0)){
                                    while($uScore_0 = mysqli_fetch_array($uQueryDone_0)){
                                        $myId = $uScore_0['id'];
                                        $myLogin = $uScore_0['login'];
                                        $myEmail = $uScore_0['email'];
                                        $myPermission = $uScore_0['permission'];
                                        $myLast = $uScore_0['lastlogin'];
                                        $myStatus = $uScore_0['active'];
                                    }
                                }

                                if(mysqli_num_rows($uQueryDone_1)){
                                    while($uScore_1 = mysqli_fetch_array($uQueryDone_1)){
                                        $count_1 = $uScore_1['repCount'];
                                    }
                                }

                                if(mysqli_num_rows($uQueryDone_2)){
                                    while($uScore_2 = mysqli_fetch_array($uQueryDone_2)){
                                        $count_2 = $uScore_2['repCount'];
                                    }
                                }

                                if(mysqli_num_rows($uQueryDone_3)){
                                    while($uScore_3 = mysqli_fetch_array($uQueryDone_3)){
                                        $count_3 = $uScore_3['repCount'];
                                    }
                                }

                                @mysqli_free_result($uQueryDone_0);
                                @mysqli_free_result($uQueryDone_1);
                                @mysqli_free_result($uQueryDone_2);
                                @mysqli_free_result($uQueryDone_3);
                                mysqli_close($con);


                                echo 
                                    "
                                    <div id='myacc-stats'>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th colspan='2'>Dane konta:</th>
                                            <tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Użytkownik:</td>
                                            <td>".$myLogin."</td>
                                        </tr>
                                        <tr>
                                            <td>Unikalne Id:</td>
                                            <td>#".$myId."</td>
                                        </tr>
                                        <tr>
                                            <td>E-mail:</td>
                                            <td>".$myEmail."</td>
                                        </tr>
                                        <tr>
                                            <td>Poziom uprawnień:</td>
                                        ";

                                    if($myPermission == 0)
                                        echo "<td><img src='img/overwatcher.png' alt='overwatcher' class='iconAcc'> Overwatcher</td>";
                                    if($myPermission == 1)
                                        echo "<td><img src='img/admin.png' alt='admin' class='iconAcc'> Administrator</td>";
                                    if($myPermission == 2)
                                        echo "<td><img src='img/moderator.png' alt='moderator' class='iconAcc'> Cup Mod</td>";

                                echo 
                                    "
                                        </tr>
                                        <tr>
                                            <td>Ostatnie logowanie:</td>
                                            <td>".$myLast."</td>
                                        </tr>
                                        <tr>
                                            <td>Status konta:</td>";

                                if($myStatus == 0)
                                        echo "<td><span class='wrong'>Nieaktywne</span></td>";
                                if($myStatus == 1)
                                        echo "<td><span class='success'>Aktywne</span></td>";


                                echo "
                                        </tr>
                                        <tr>
                                            <td>Ilość zakończonych zgłoszeń:</td>
                                            <td>".$count_1."</td>
                                        </tr>
                                        <tr>
                                            <td>Ilość decyzji o banie:</td>
                                            <td>".$count_2."</td>
                                        </tr>
                                        <tr>
                                            <td>Ilość decyzji o braku bana:</td>
                                            <td>".$count_3."</td>
                                        </tr>
                                        </tbody>
                                    </table><br />
                                    </div>

                                    <div class='acc-logs'>
                                    <table>
                                    <thead>
                                        <tr>
                                            <th colspan='4'>Ostatnie działania użytkownika ".$user.":</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class='first'>
                                            <td>Użytkownik:</td>
                                            <td>Wykonana operacja:</td>
                                            <td>Data wykonania:</td>
                                            <td>Twoje ip:</td>
                                        </tr>
                                    ";

                                include "connect.php";

                                $lastQuery = "SELECT login, action, actiondate, ip from logs where login='".$user."' ORDER BY id desc LIMIT 20";
                                $lastDoQuery = mysqli_query($con, $lastQuery);

                                if(mysqli_num_rows($lastDoQuery)){
                                    while($lastScore = mysqli_fetch_array($lastDoQuery)){
                                        echo 
                                            "
                                            <tr>
                                                <td>".$lastScore['login']."</td>
                                                <td>".$lastScore['action']."</td>
                                                <td>".$lastScore['actiondate']."</td>
                                                <td>".$lastScore['ip']."</td>
                                            </tr>
                                            ";
                                    }
                                }


                                    echo "
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    ";

                                @mysqli_free_result($lastDoQuery);
                                mysqli_close($con);
                            }else{
                                header ("Location: myaccount.php");
                            }
                        }else{
                            include "connect.php";

                            //user
                            $uQuery_0 = "select id, login, email, permission, lastlogin, active from users where login='".$login."'";
                            //checked
                            $uQuery_1 = "select u.login as login, u.permission as permission, count(case when r.repadmin = u.login then 1 else null end) as repCount from users as u, replays as r where (r.ban = 1 or r.ban = 2) and u.login='".$login."' group by u.login order by repCount desc";
                            //banned
                            $uQuery_2 = "select u.login as login, u.permission as permission, count(case when r.repadmin = u.login then 1 else null end) as repCount from users as u, replays as r where r.ban = 1 and u.login='".$login."' group by u.login order by repCount desc";
                            //unbanned
                            $uQuery_3 = "select u.login as login, u.permission as permission, count(case when r.repadmin = u.login then 1 else null end) as repCount from users as u, replays as r where r.ban = 2 and u.login='".$login."' group by u.login order by repCount desc";

                            $uQueryDone_0 = mysqli_query($con, $uQuery_0);
                            $uQueryDone_1 = mysqli_query($con, $uQuery_1);
                            $uQueryDone_2 = mysqli_query($con, $uQuery_2);
                            $uQueryDone_3 = mysqli_query($con, $uQuery_3);

                            if(mysqli_num_rows($uQueryDone_0)){
                                while($uScore_0 = mysqli_fetch_array($uQueryDone_0)){
                                    $myId = $uScore_0['id'];
                                    $myLogin = $uScore_0['login'];
                                    $myEmail = $uScore_0['email'];
                                    $myPermission = $uScore_0['permission'];
                                    $myLast = $uScore_0['lastlogin'];
                                    $myStatus = $uScore_0['active'];
                                }
                            }

                            if(mysqli_num_rows($uQueryDone_1)){
                                while($uScore_1 = mysqli_fetch_array($uQueryDone_1)){
                                    $count_1 = $uScore_1['repCount'];
                                }
                            }

                            if(mysqli_num_rows($uQueryDone_2)){
                                while($uScore_2 = mysqli_fetch_array($uQueryDone_2)){
                                    $count_2 = $uScore_2['repCount'];
                                }
                            }

                            if(mysqli_num_rows($uQueryDone_3)){
                                while($uScore_3 = mysqli_fetch_array($uQueryDone_3)){
                                    $count_3 = $uScore_3['repCount'];
                                }
                            }

                            @mysqli_free_result($uQueryDone_0);
                            @mysqli_free_result($uQueryDone_1);
                            @mysqli_free_result($uQueryDone_2);
                            @mysqli_free_result($uQueryDone_3);
                            mysqli_close($con);


                            echo 
                                "
                                <div id='myacc-stats'>
                                <table>
                                    <thead>
                                        <tr>
                                            <th colspan='2'>Dane konta:</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Użytkownik:</td>
                                        <td>".$myLogin."</td>
                                    </tr>
                                    <tr>
                                        <td>Unikalne Id:</td>
                                        <td>#".$myId."</td>
                                    </tr>
                                    <tr>
                                        <td>E-mail:</td>
                                        <td>".$myEmail."</td>
                                    </tr>
                                    <tr>
                                        <td>Poziom uprawnień:</td>
                                    ";

                                if($myPermission == 0)
                                    echo "<td><img src='img/overwatcher.png' alt='overwatcher' class='iconAcc'> Overwatcher</td>";
                                if($myPermission == 1)
                                    echo "<td><img src='img/admin.png' alt='admin' class='iconAcc'> Administrator</td>";
                                if($myPermission == 2)
                                    echo "<td><img src='img/moderator.png' alt='moderator' class='iconAcc'> Cup Mod</td>";

                            echo 
                                "
                                    </tr>
                                    <tr>
                                        <td>Twoje ostatnie logowanie:</td>
                                        <td>".$myLast."</td>
                                    </tr>
                                    <tr>
                                        <td>Status konta:</td>";

                            if($myStatus == 0)
                                    echo "<td><span class='wrong'>Nieaktywne</span></td>";
                            if($myStatus == 1)
                                    echo "<td><span class='success'>Aktywne</span></td>";


                            echo "
                                    </tr>
                                    <tr>
                                        <td>Ilość zakończonych zgłoszeń:</td>
                                        <td>".$count_1."</td>
                                    </tr>
                                    <tr>
                                        <td>Ilość decyzji o banie:</td>
                                        <td>".$count_2."</td>
                                    </tr>
                                    <tr>
                                        <td>Ilość decyzji o braku bana:</td>
                                        <td>".$count_3."</td>
                                    </tr>
                                    </tbody>
                                </table><br />
                                </div>

                                <div class='acc-logs'>
                                <table>
                                <thead>
                                    <tr>
                                        <th colspan='4'>Twoje ostatnie działania:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class='first'>
                                        <td>Użytkownik:</td>
                                        <td>Wykonana operacja:</td>
                                        <td>Data wykonania:</td>
                                        <td>Twoje ip:</td>
                                    </tr>
                                ";

                            include "connect.php";

                            $lastQuery = "SELECT login, action, actiondate, ip from logs where login='".$login."' ORDER BY id desc LIMIT 20";
                            $lastDoQuery = mysqli_query($con, $lastQuery);

                            if(mysqli_num_rows($lastDoQuery)){
                                while($lastScore = mysqli_fetch_array($lastDoQuery)){
                                    echo 
                                        "
                                        <tr>
                                            <td>".$lastScore['login']."</td>
                                            <td>".$lastScore['action']."</td>
                                            <td>".$lastScore['actiondate']."</td>
                                            <td>".$lastScore['ip']."</td>
                                        </tr>
                                        ";
                                }
                            }


                                echo "
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                ";

                            @mysqli_free_result($lastDoQuery);
                            mysqli_close($con);
                        }
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