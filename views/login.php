<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/style.css">
    <title>Login</title>
</head>
<body>
    <h1>Log In</h1>
    <div id="content">
        <form action="" method="POST">
            <div>
                <?php if($error['alert'] != '') { echo "<div class='alert'>".$error['alert']."</div>";} ?>
                <label for="username">Username: *</label>
                <input type="text" name="username" value="<?php echo $input['username']; ?>">
                <div class="error"><?php echo $error['username'] ?></div>
                <label for="password">Password: *</label>
                <input type="password" name="password" value="<?php echo $input['password']; ?>">
                <div class="error"><?php echo $error['password'] ?></div>
                <p class="required">* required fields</p>
                <input type="submit" name="submit" class="submit" value="submit">
            </div>
        </form>
        <p><a href="reset_password.php">Reset Password</a></p>
    </div>
</body>
</html>