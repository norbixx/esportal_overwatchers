<?php
    session_start();

    if(isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_login'])){
		$login = $_COOKIE['auth_login'];
        $token = $_COOKIE['auth_token'];
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
    </script>
</head>
<body onload="startTime()">
    <div id="con">
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
                    echo "
                    <div id='acc'>
                    <p>Zalogowany jako: <span class='accTheme'>".$login."</span>
                    <a href='logout.php'><button class='buttonDark'><i class='fa fa-sign-out-alt'></i> Wyloguj</button></a>
                    </p>
                    </div>
                    <div id='logo'>
                            <a href='index.php'><img src='img/esportal_white.png' alt='esportal_logo_black' /></a>
                        </div>
                        <div id='header' class='dark-theme'>
                            <div id='title'>
                                <p>Zmień hasło:</p>
                            </div>
                        </div>
                        <section>
                            <form method='POST' action='passchange.php'>
                                <input type='password' name='password' placeholder='Hasło' id='passInput'><br />
                                <input type='password' name='repeatPassword' placeholder='Powtórz hasło' id='passInput'><br />";
                    if(!empty($_GET['id'])){
                        if($_GET['id']=='wrong'){
                            echo '<div id="wrong">Hasła nie są takie same!</div>';
                        }
                    }
                    
                    echo "<button type='submit'><i class='fa fa-check-circle'></i> ZATWIERDŹ</button>
                                </form>
                        </section>";
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
</body>
</html>