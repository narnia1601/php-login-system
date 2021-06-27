<?php 
    include('db.php');
    include('config.php');

    // set form default
    $error['alert'] = '';
    $error['email'] = '';
    $error['password'] = '';
    $error['password2'] = '';
    $input['email'] = '';
    $input['password'] = '';
    $input['password2'] = '';

    if(isset($_POST['submit'])){
        // check if all fields have been filled
        if($_POST['email'] == '' || $_POST['password'] == '' || $_POST['password2'] == ''){
            if($_POST['email'] == ''){ $error['email'] = 'required!'; }
            if($_POST['password'] == ''){ $error['password'] = 'required!'; }
            if($_POST['password2'] == ''){ $error['password2'] = 'required!'; }
            $error['alert'] = "Please ensure all fields are filled";
            $input['email'] = $_POST['email'];
            $input['password'] = $_POST['password'];
            $input['password2'] = $_POST['password2'];
            include('../views/reset_password.php');
        }else{
            // if fields are filled
            $input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
            $input['password'] = htmlentities($_POST['password'], ENT_QUOTES);
            $input['password2'] = htmlentities($_POST['password2'], ENT_QUOTES);
            // check if email format is correct
            if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $error['alert'] = "Please enter a suitable email address";
                include('../views/reset_password.php');
            }
            else if($input['password'] == $input['password2']){
                // check if email exists in database
                $check = $mysqli->prepare("SELECT * FROM users WHERE email=?");
                $check->bind_param('s', $input['email']);
                $check->execute();
                $result = $check->get_result();
                if($result->num_rows > 0){
                    // email exists in database
                    // create email
                    $subject = "Password reset request from http://localhost.com";
                    $message = "<html><body>";
                    $message .= "<p>Hello,</p>";
                    $message .= "<p>You recently asked that your localhost account password be reset. If so, please click the link below to reset your password. If you do not want to reset your password, or if the request was an error, please ignore this message.</p>";
                    $message .= "<a href='localhost/php-login-system/includes/reset_password.php'></a>";
                    $message .= "<p>Thanks, <br>The Administrator</p>";
                    $message .= "</body></html>";

                    // create email headers
                    $header = "MIME-Version: 1.0 \r\n";
                    $header .= "Content-type: text/html; charset=iso-8859-1 /r/n";
                    $header .= "From: localhost"." <noreply@localhost> \r\n";
                    $header .= "X-Sender: <noreply@localhost> \r\n";
                    $header .= "Reply-To: <noreply@localhost> \r\n";

                    // send email
                    mail($input['email'], $subject, $message, $header);

                    // add alert and clear form value
                    $error['alert'] = "Password reset sent successfully. Please check your email";
                    $input['email'] = '';
                    $input['password'] = '';
                    $input['password2'] = '';
                    include('../views/reset_password.php');
                }else{
                    // email does not exist in database
                    $error['alert'] = "Email does not exist";
                    include('../views/reset_password.php');
                }
            }else{
                $error['alert'] = "Please ensure passwords match";
                include('../views/reset_password.php');
            }
        }
    }else{
        include('../views/reset_password.php');
    }
    // close db connection
    include('db_close.php');
?>