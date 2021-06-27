<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/style.css">
    <title>Change Password</title>
</head>
<body>
    <h1>Change password</h1>
    <div id="content">
        <form action="" method="POST">
            <div>
                <?php if($error['alert'] != '') { echo "<div class='alert'>".$error['alert']."</div>";} ?>
                <label for="password">Old Password: *</label>
                <input type="text" name="password" value="<?php echo $input['password']; ?>">
                <div class="error"><?php echo $error['password'] ?></div>
                <label for="password2">New Password: *</label>
                <input type="password" name="password2" value="<?php echo $input['password2']; ?>">
                <div class="error"><?php echo $error['password2'] ?></div>
                <label for="password3">Confirm Password: *</label>
                <input type="password" name="password3" value="<?php echo $input['password3']; ?>">
                <div class="error"><?php echo $error['password3'] ?></div>
                <p class="required">* required fields</p>
                <input type="submit" name="submit" class="submit" value="submit">
            </div>
        </form>
    </div>
</body>
</html>