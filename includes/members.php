<?php 
    // start session
    session_start();
    include("config.php");

    // check if user is logged in
    if(!isset($_SESSION['username'])){
        header("Location: login.php?unauthorized");
    }

    // check for inactivity
    if(time() > $_SESSION['last_active'] + $config['session_timeout']){
        // log out user
        session_destroy();
        header("Location: login.php?timeout");
    }else{
        // update session last active
        $_SESSION['last_active'] = time();
    }

    // display view
    include('../views/members.php');
?>