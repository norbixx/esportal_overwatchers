<?php
    session_start();

    $flag = 0;
    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        include "php/setactive.php";
        include "connect.php";
		$login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];
        $time = date("Y-m-d", time() + 94);
        $limit = 15;
        
        if(!empty($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = 1;
        }
        
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
    <title>Esportal OV Panel - Logi</title>
    
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
    <div id="con-rep">
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
                    <div id='logs' class='dark-theme'>
                    <h1>Logi użytkowników</h1><br />
                    ";
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
                        <a href='logs.php'><button class='buttonDark active'><i class='fa fa-file' aria-hidden='true'></i> Logi</button></a>
                        <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć</button></a><br /><br />";
                        if($flag == 1){
                        
                            include "connect.php";
                            
                            if(!empty($_GET['page'])){
                                $page = $_GET['page'];
                            }
                            
                            $userSelect = "SELECT login from users ORDER BY login asc";
                            $userDoSelect = mysqli_query($con, $userSelect);
                                
                            echo 
                                "
                                <form action='logs.php' method='GET'>
                                    <select name='login' class='selectInput'>
                                ";
                            
                            if(mysqli_num_rows($userDoSelect)){
                                while($uScore = mysqli_fetch_array($userDoSelect)){

                                    echo "<option value='".$uScore['login']."' >".$uScore['login']."</option>";

                                }
                            }
                            
                            echo "
                                        </select><br />
                                        <button type='submit' class='buttonDark'><i class='fas fa-search'></i> Wyszukaj logi</button>
                                    </form><br />";
                            
                            if(!empty($_GET['login'])){
                                $userLogs = $_GET['login'];
                            }
                            
                            
                            if(!empty($userLogs)){
                                $countQuery = "select count(*) as count from logs where login='".$_GET['login']."'";

                                $countDoQuery = mysqli_query($con, $countQuery);


                                if(mysqli_num_rows($countDoQuery)){
                                    while($countScore = mysqli_fetch_array($countDoQuery)){
                                        $count = $countScore['count'];
                                    }
                                }

                                $site = ($count/$limit);

                                echo 
                                    "
                                    <table>
                                        <thead>
                                            <tr>
                                                <th colspan='4'>Logi użytkownika ".$_GET['login'].":</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <tr class='first'>
                                            <td>Użytkownik:</td>
                                            <td>Wykonana operacja:</td>
                                            <td>Data wykonania:</td>
                                            <td>Ip użytkownika:</td>
                                        </tr>
                                    ";

                                $lastQuery = "SELECT login, action, actiondate, ip from logs where login='".$_GET['login']."' ORDER BY id desc LIMIT ".($page-1)*$limit.",".$limit;
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
                                
                            }else{
                                
                                $countQuery = "select count(*) as count from logs where actiondate like '".$time."%'";
                                
                                $countDoQuery = mysqli_query($con, $countQuery);


                                if(mysqli_num_rows($countDoQuery)){
                                    while($countScore = mysqli_fetch_array($countDoQuery)){
                                        $count = $countScore['count'];
                                    }
                                }

                                $site = ($count/$limit);
                                
                                echo 
                                    "
                                    <table>
                                        <thead>
                                            <tr>
                                                <th colspan='4'>Ostatnie działania na stronie ( ".$time." ):</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class='first'>
                                                <td>Użytkownik:</td>
                                                <td>Wykonana operacja:</td>
                                                <td>Data wykonania:</td>
                                                <td>Ip użytkownika:</td>
                                            </tr>
                                    ";

                                $lastQuery = "SELECT login, action, actiondate, ip from logs where actiondate like '".$time."%' ORDER BY id desc LIMIT ".($page-1)*$limit.",".$limit;
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
                            }
                            
                                                        
                            echo "    
                                    </tbody>
                                </table><br />
                                ";
                            
                            if(!empty($userLogs)){
                                
                                $k = 1;
                                
                                if($page > 1)
                                    echo "<a href='logs.php?login=".$_GET['login']."&page=".($page-1)."'><button class='buttonDark'><i class='fas fa-angle-left'></i></button></a>
                                    ";
                                else
                                    echo "<button class='buttonDark'><i class='fas fa-angle-left'></i></button>
                                    ";

                                for($k = 1; $k < $site + 1; $k++){
                                    if($k == $page)
                                        echo "<a href='logs.php?login=".$_GET['login']."&page=".$k."'><button class='buttonDark active'>".$k."</button></a>";
                                    else
                                        echo "<a href='logs.php?login=".$_GET['login']."&page=".$k."'><button class='buttonDark'>".$k."</button></a>";
                                }


                                if($page > $site)    
                                    echo "
                                        <button class='buttonDark'><i class='fas fa-angle-right'></i></button>";
                                    else
                                        echo "
                                        <a href='logs.php?login=".$_GET['login']."&page=".($page+1)."'><button class='buttonDark'><i class='fas fa-angle-right'></i></button></a>";
                                
                                
                                echo "<br /><br /><a href='logs.php'><button class='buttonDark'><i class='fas fa-backward'></i> Wróć do logów głównych</button></a>";
                                
                            }else{
                                if($page > 1)
                                    echo "<a href='logs.php?page=".($page-1)."'><button class='buttonDark'><i class='fas fa-angle-left'></i></button></a>
                                    ";
                                else
                                    echo "<button class='buttonDark'><i class='fas fa-angle-left'></i></button>
                                    ";

                                for($k = 1; $k < $site + 1; $k++){
                                    if($k == $page)
                                        echo "<a href='logs.php?page=".$k."'><button class='buttonDark active'>".$k."</button></a>";
                                    else
                                        echo "<a href='logs.php?page=".$k."'><button class='buttonDark'>".$k."</button></a>";
                                }


                                if($page > $site)    
                                    echo "
                                        <button class='buttonDark'><i class='fas fa-angle-right'></i></button>";
                                    else
                                        echo "
                                        <a href='logs.php?page=".($page+1)."'><button class='buttonDark'><i class='fas fa-angle-right'></i></button></a>";

                            }
                            
                            @mysqli_free_result($countDoQuery);
                            @mysqli_free_result($userDoSelect);
                            @mysqli_free_result($lastDoQuery);
                            mysqli_close($con);
                            
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