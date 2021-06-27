<?php 
    // start session
    session_start();
    session_destroy();

    //display view
    include('../views/logout.php');
?>