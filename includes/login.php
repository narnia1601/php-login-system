<?php 
    // start session
    session_start();
    include('config.php');
    include('db.php');

    // form defaults
    $error['alert'] = '';
    $error['username'] = '';
    $error['password'] = '';
    $input['username'] = '';
    $input['password'] = '';

    if(isset($_POST['submit'])){
        // process form
        if($_POST['username'] == '' || $_POST['password'] == ''){
            // both fields need to be filled in
            if($_POST['username'] == ''){ $error['username'] = 'required!'; }
            if($_POST['password'] == ''){ $error['password'] = 'required!'; }
            $error['alert'] = 'Please fill in required fields';
            $input['username'] = $_POST['username'];
            $input['password'] = $_POST['password'];
            include('../views/login.php');
        }else{
            $input['username'] = htmlentities($_POST['username'], ENT_QUOTES);
            $input['password'] = htmlentities($_POST['password'], ENT_QUOTES);
            if($stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?")){
                $stmt->bind_param('s', $input['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_object()){
                    $hash = $row->password;
                }
                if(password_verify($input['password'], $hash)){
                    $_SESSION['username'] = $input['username'];
                    $_SESSION['last_active'] = time();
                    header("Location: members.php");
                }else{
                    $error['alert'] = "Username or password is incorrect";
                    include('../views/login.php');
                }
                $stmt->close();
            }else{
                echo "Unable to process mysql statement";
            }
        }
    }else{
        if(isset($_GET['unauthorized'])){
            $error['alert'] = "Please log in to view the page";
        }
        if(isset($_GET['timeout'])){
            $error['alert'] = "Session timeout please log in again";
        }
        include('../views/login.php');
    }
    // close db connection
    include('db_close.php');
?>