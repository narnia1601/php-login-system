<?php 
    include('db.php');
    include('config.php');
    session_start();

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

    // form defaults
    $error['alert'] = '';
    $error['username'] = '';
    $error['password'] = '';
    $error['email'] = '';
    $error['password2'] = '';
    $input['username'] = '';
    $input['email'] = '';
    $input['password'] = '';
    $input['password2'] = '';

    if(!isset($_SESSION['username'])){
        header("Location: login.php?timeout");
    }

    if(isset($_POST['submit'])){
        // check for validation
        if($_POST['username'] == '' || $_POST['password'] == '' || $_POST['password2'] == '' || $_POST['email'] == ''){
            if($_POST['username'] == ''){ $error['username'] = 'required!'; }
            if($_POST['password'] == ''){ $error['password'] = 'required!'; }
            if($_POST['password2'] == ''){ $error['password2'] = 'required!'; }
            if($_POST['email'] == ''){ $error['email'] = 'required!'; }
            $error['alert'] = 'Please fill up all required fields';
            $input['username'] = $_POST['username'];
            $input['password'] = $_POST['password'];
            $input['password2'] = $_POST['password2'];
            $input['email'] = $_POST['email'];
            include('../views/register.php');
        }else{
            // if all required fields are filled
            $input['username'] = htmlentities($_POST['username'], ENT_QUOTES);
            $input['password'] = htmlentities($_POST['password'], ENT_QUOTES);
            $input['password2'] = htmlentities($_POST['password2'], ENT_QUOTES);
            $input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
            // check if email address format is correct
            if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $error['alert'] = "Please enter a suitable email address";
                include('../views/register.php');
            }
            // check if passwords match
            else if($input['password'] == $input['password2']){
                // check if a similar username has been taken
                $stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?");
                $stmt->bind_param('s', $input['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                // check if a similar email has been taken
                $check = $mysqli->prepare('SELECT * FROM users WHERE email=?');
                $check->bind_param('s', $input['email']);
                $check->execute();
                $emailValidation = $check->get_result();
                if($result->num_rows > 0){
                    // if username has been taken
                    $error['alert'] = "This username has been taken";
                    include('../views/register.php');
                }
                else if($emailValidation->num_rows > 0){
                    // if email has been taken
                    $error['alert'] = 'This email has been taken';
                    include('../views/register.php');
                }
                else{
                    // if username has not been taken
                    $input['password'] = htmlentities(password_hash($_POST['password'], PASSWORD_BCRYPT), ENT_QUOTES);
                    $stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?,?,?)");
                    $stmt->bind_param('sss', $input['username'], $input['password'], $input['email']);
                    $stmt->execute();
                    $stmt->close();
                    header("Location: members.php");
                }
            }else{
                $error['alert'] = "Please ensure passwords match";
                include("../views/register.php");
            }
        }
    }else{
        include('../views/register.php');
    }
    // close db connection
    include('db_close.php');
?>