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
    $error['password'] = '';
    $error['password2'] = '';
    $error['password3'] = '';
    $input['password'] = '';
    $input['password2'] = '';
    $input['password3'] = '';

    if(isset($_POST['submit'])){
        // check for validation
        if($_POST['password'] == '' || $_POST['password2'] == '' || $_POST['password3'] == ''){
            if($_POST['password'] == ''){ $error['password'] = 'required!'; }
            if($_POST['password2'] == ''){ $error['password2'] = 'required!'; }
            if($_POST['password3'] == ''){ $error['password3'] = 'required!'; }
            $error['alert'] = 'Please fill up all required fields';
            $input['password'] = $_POST['password'];
            $input['password2'] = $_POST['password2'];
            $input['password3'] = $_POST['password3'];
            include('../views/change_password.php');
        }else{
            // if all required fields are filled
            $input['password'] = htmlentities($_POST['password'], ENT_QUOTES);
            $input['password2'] = htmlentities($_POST['password2'], ENT_QUOTES);
            $input['password3'] = htmlentities($_POST['password3'], ENT_QUOTES);
            if($stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?")){
                $stmt->bind_param('s', $_SESSION['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_object()){
                    $hash = $row->password;
                }
                if(password_verify($input['password'], $hash)){
                    // if old password is correct
                    // check if new passwords match
                    if($input['password2'] == $input['password3']){
                        $input['password2'] = htmlentities(password_hash($_POST['password2'], PASSWORD_BCRYPT), ENT_QUOTES);
                        $stmt = $mysqli->prepare("UPDATE users SET password=? WHERE username=?");
                        $stmt->bind_param('ss', $input['password2'], $_SESSION['username']);
                        $stmt->execute();
                        $stmt->close();
                        header('Location: members.php');
                    }else{
                        $error['alert'] = "New passwords do not match";
                        include('../views/change_password.php');
                    }
                }else{
                    // if old password is incorrect
                    $error['alert'] = "Old password is incorrect";
                    include('../views/change_password.php');
                }
                $stmt->close();
            }else{
                echo "Unable to process mysql statement";
            }
        }
    }else{
        include('../views/change_password.php');
    }
    // close db connection
    include('db_close.php');
?>