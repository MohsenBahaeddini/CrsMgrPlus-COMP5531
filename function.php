<?php 
    function display_success() {
        
       
            echo '<div class="success" id="notification" onclick="this.remove()">';
            echo "ACTION SUCCESFULL" . '<br>';
            echo '</div>';
    
    }
    function genUserName($fire_name,$last_name,$id){
        $firstname = $fire_name; 
        $lastname = $last_name;
        $id = $id;
        return "{$firstname[0]}"."{$firstname[1]}"."{$lastname[0]}"."{$lastname[1]}".$id;
    }

    function genPassWord($lenght){
        $character = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890!@-_+=#%";
        $password = "";
        for($i=0; $i < $lenght; $i++){
            $password = $password.$character[rand(0,strlen($character)-1)];
        }
        return $password;
    }
?> 