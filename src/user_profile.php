<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

    include_once("templates/common/header.php");
    
    if(array_key_exists('username', $_SESSION) && !empty($_SESSION['username'])){
        include_once("database/connection.php");
        include_once("database/users.php");
        include_once("database/pets.php");

        $user = getUser($_SESSION['username'], $_SESSION['password']);

        $user_name = $user['Name'];
        drawHeader("Helper Shelter - $user_name's Profile");

        include_once("templates/user_profile.php");
    }
    else{
        echo '<script language="javascript">
                alert("Please log in to acces user profile!");
                window.location.href="/index.php";
                </script>';
        }

    include_once("templates/common/footer.php");
?>