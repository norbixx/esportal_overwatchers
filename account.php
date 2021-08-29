<?php
    session_start();

    $flag = 0;
    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
        include "php/setactive.php";
        include "connect.php";
		$login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];
        $limit = 10;
        $page = 1;
        
        $flag = 1;
        
        $adminQuery = "select permission from users where login='".$login."' and token='".$token."'";   
		$adminDoQuery = mysqli_query($con, $adminQuery);
		
		if(mysqli_num_rows($adminDoQuery)){
			while($adminScore = mysqli_fetch_assoc($adminDoQuery))
				$permission = $adminScore['permission'];
        }
        
        if($permission == 1){
           if(!empty($_GET['id'])){
                if($_GET['id'] == 'add'){
                    $genLogin = addslashes($_POST['login']);
                    $genPassword = addslashes($_POST['password']);
                    $genEmail = addslashes($_POST['email']);
                    $genPermission = addslashes($_POST['permission']);
                    $genToken = addslashes(md5(time()));
                    
                    $generateQuery = "INSERT INTO users(login, password, email, permission, token) VALUES ('".$genLogin."', '".md5($genPassword)."', '".$genEmail."', ".$genPermission.", '".$genToken."')";
                    $GenerateDoQuery = mysqli_query($con, $generateQuery);
                    header("Location: account.php?created=true");
                }
            }
        }
    }
    
    @mysqli_free_result($generateDoQuery);
    @mysqli_free_result($adminDoQuery); 
    mysqli_close($con);

    function uniquePass($l) {
        return substr(uniqid(mt_rand(), true), 0, $l);
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
    <title>Esportal OV Panel - Zarządzanie kontami</title>
    
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
                if(!isset($_COOKIE['auth_login']) && !isset($_COOKIE['auth_token'])){
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
                    <div id='adminlist' class='dark-theme'>
                    ";
                    
                    if($permission == 1){
                        echo "
                        <h1>Zarządzanie kontami</h1><br />
                        <a href='addreport.php'><button class='buttonDark'><i class='fa fa-plus-circle'></i> Dodaj zgłoszenia</button></a>
                        <a href='reportdone.php'><button class='buttonDark'><i class='fa fa-check-circle'></i> Sprawdzone zgłoszenia</button></a>
                        <a href='history.php'><button class='buttonDark'><i class='fa fa-history'></i> Historia zgłoszeń</button></a>
                        <a href='account.php'><button class='buttonDark active'><i class='fa fa-user-circle'></i> Zarządzanie kontami</button></a>
                        <a href='pending.php'><button class='buttonDark'><i class='fa fa-flag'></i> Aktywne zgłoszenia</button></a>
                        <a href='stats.php'><button class='buttonDark'><i class='fa fa-table'></i> Statystyki</button></a>
                        <a href='leagues.php'><button class='buttonDark'><i class='fa fa-trophy'></i> Ligi/Wyzwania</button></a>
                        <a href='logs.php'><button class='buttonDark'><i class='fa fa-file' aria-hidden='true'></i> Logi</button></a>
                        <a href='admin.php'><button class='buttonDark'><i class='fa fa-chevron-circle-left'></i> Wróć</button></a><br /><br />
                        <h3>Utwórz nowe konto:</h3>
                        <form method='POST' action='account.php?id=add'>
                            <input type='text' name='login' placeholder='Nazwa użytkownika'>
                            <input type='text' value='".uniquePass(8)."' name='password' readonly='readonly'>
                            <input type='text' name='email' placeholder='przyklad@domena.pl'>
                            <select name='permission'>
                                <option disabled selected>Wybierz uprawnienia</option>
                                <option value='0'>Overwatcher</option>
                                <option value='2'>Cup Mod</option>
                                <option value='1'>Administrator</option>
                            </select>
                            <button type='submit' class='buttonDark'><i class='fa fa-check-circle'></i> Utwórz konto</button>
                        </form><br />
                        ";
                        
                    if(!empty($_GET['created'])){
                        if($_GET['created'] == 'true'){
                            echo "<p class='success'>Konto utworzone pomyślnie!</p><br />";
                        }
                        if($_GET['created'] == 'false'){
                            echo "<p class='wrong'>Błąd podczas tworzenia konta. Skontaktuj się z administratorem.</p><br />";
                        }
                    }
                        
                    echo "
                        <table>
                            <tr class='first'>
                                <td>ID:</td>
                                <td>Login:</td>
                                <td>E-mail:</td>
                                <td>Uprawnienia:</td>
                                <td>Ostatnie logowanie:</td>
                                <td>Status konta:</td>
                                <td>Modyfikacja konta:</td>
                            </tr>
                                ";
                    if($flag == 1){
                        
                        include "connect.php";
                        
                        if(!empty($_GET['page'])){
                            $page = $_GET['page'];
                        }
                        
                        $countQuery = "select count(*) as count from users";
                        $countDoQuery = mysqli_query($con, $countQuery);
                        
                                                
                        if(mysqli_num_rows($countDoQuery)){
                            while($countScore = mysqli_fetch_array($countDoQuery)){
                                $count = $countScore['count'];
                            }
                        }
                        
                        $site = ($count/$limit);
                        
                        $accQuery = "select id, login, email, permission, lastlogin, active from users order by id LIMIT ".($page-1)*$limit.",".$limit;
                        $accDoQuery = mysqli_query($con, $accQuery);
                        
                        if(mysqli_num_rows($accDoQuery)){
                            while($accScore = mysqli_fetch_array($accDoQuery)){
                                
                                echo "
                                <tr>
                                    <td>".$accScore['id']."</td>
                                    <td><a href='myaccount.php?user=".$accScore['login']."'>".$accScore['login']."</a></td>
                                    <td>".$accScore['email']."</td>
                                    ";
                                if($accScore['permission'] == 0)
                                    echo "<td><img src='img/overwatcher.png' alt='overwatcher' class='icon'>Overwatcher</td>";
                                if($accScore['permission'] == 1)
                                    echo "<td><img src='img/admin.png' alt='admin' class='icon'>Administrator</td>";
                                if($accScore['permission'] == 2)
                                    echo "<td><img src='img/moderator.png' alt='admin' class='icon'>Cup Mod</td>";
                                
                                echo "
                                    <td>".$accScore['lastlogin']."</td>";
                                
                                if($accScore['active'] == 0)
                                    echo "<td><span class='wrong'>Nieaktywne</span></td>";
                                if($accScore['active'] == 1)
                                    echo "<td><span class='success'>Aktywne</span></td>";
                                
                                if($accScore['permission'] == 0){
                                    $upgrade = 1;
                                    $degrade = 0;
                                }
                                if($accScore['permission'] == 1){
                                    $upgrade = 2;
                                    $degrade = 0;
                                }
                                if($accScore['permission'] == 2){
                                    $upgrade = 2;
                                    $degrade = 1;
                                }
                                
                                echo "
                                    <td>
                                    </a><a href='accountUpgrade.php?id=".$accScore['id']."&perm=".$upgrade."'><button class='buttonDark static'><i class='fa fa-user-plus'></i> Dodaj uprawnienia</button></a>
                                    </a><a href='accountActivate.php?id=".$accScore['id']."'><button class='buttonDark Green static-last'><i class='fas fa-user-check'></i> Aktywuj konto</button></a>
                                    </a><a href='accountReset.php?id=".$accScore['id']."'><button class='buttonDark static-last'><i class='fas fa-user-edit'></i> Zresetuj hasło</button></a><br />
                                    </a><a href='accountDegrade.php?id=".$accScore['id']."&perm=".$degrade."'><button class='buttonDark static'><i class='fa fa-user-minus'></i> Odbierz uprawnienia</button></a>
                                    </a><a href='accountDeactivate.php?id=".$accScore['id']."'><button class='buttonDarkRed static-last'><i class='fas fa-user-lock'></i> Dezaktywuj konto</button></a>
                                    <a href='accountDelete.php?id=".$accScore['id']."'><button class='buttonDarkRed static-last'><i class='fa fa-user-times'></i> Usuń użytkownika</button></td>
                                </tr>";
                            }
                            
                        @mysqli_free_result($replayDoQuery); 
                        mysqli_close($con);
                        }
                    echo "
                        </table><br />";
                        
                    if($page > 1)
                        echo "<a href='account.php?page=".($page-1)."'><button class='buttonDark'><i class='fas fa-angle-left'></i></button></a>
                        ";
                    else
                        echo "<button class='buttonDark'><i class='fas fa-angle-left'></i></button>
                        ";
                        
                        for($k = 1; $k < $site + 1; $k++){
                            
                            if($k == $page)
                                echo "<a href='account.php?page=".$k."'><button class='buttonDark active'>".$k."</button></a>";
                            else
                                echo "<a href='account.php?page=".$k."'><button class='buttonDark'>".$k."</button></a>";
                        }
                        
                        
                        if($page > $site)    
                        echo "
                        <button class='buttonDark'><i class='fas fa-angle-right'></i></button>";
                    else
                        echo "
                        <a href='account.php?page=".($page+1)."'><button class='buttonDark'><i class='fas fa-angle-right'></i></button></a>";     
                        
                        echo "</div>";
                        }
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