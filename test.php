<?php


for($i = 0; $i < 39; $i++){
    
    echo '$_auth'.($i+1)."".md5(time() - ($i*100))."<br />";
    
}


?>