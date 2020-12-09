<?php
    include_once(__DIR__ . "/../config.php");

    if(!isset($_SESSION)) 
        session_start(); 

    include_once(ROOT . "/templates/common/header.php");
    
    drawHeader("Helper Shelter - Main Page");

    include_once(ROOT . "/database/connection.php");
    include_once(ROOT . "/database/pets.php");
    
    include_once(ROOT . "/templates/adoption_grid.php");
    include_once(ROOT . "/templates/common/footer.php");
?>