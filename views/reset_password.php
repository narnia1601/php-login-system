<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/style.css">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset password</h1>
    <div id="content">
        <form action="" method="POST">
            <div>
                <?php if($error['alert'] != '') { echo "<div class='alert'>".$error['alert']."</div>";} ?>
                <label for="email">Email: *</label>
                <input type="text" name="email" value="<?php echo $input['email']; ?>">
                <div class="error"><?php echo $error['email'] ?></div>
                <label for="password">New Password: *</label>
                <input type="password" name="password" value="<?php echo $input['password']; ?>">
                <div class="error"><?php echo $error['password'] ?></div>
                <label for="password2">Confirm Password: *</label>
                <input type="password" name="password2" value="<?php echo $input['password2']; ?>">
                <div class="error"><?php echo $error['password2'] ?></div>
                <p class="required">* required fields</p>
                <input type="submit" name="submit" class="submit" value="submit">
            </div>
        </form>
    </div>
</body>
</html>