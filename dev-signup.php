<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
    </head>
    <body>
        <?php
        if (isset($_POST['register'])) {
            if ($_POST['repassword'] == $_POST['password']) {
                require 'lib/auth/API.php';
                if (Login::register($_POST['username'], $_POST['password'], $_POST['yubikey']) == 1) {
                    echo "true";
                } elseif (Login::register($_POST['username'], $_POST['password'], $_POST['yubikey']) == 0) {
                    $error = "<b>User already exists!</b>";
                } else {
                    echo "false";
                }
            } else {
                $error = "<b>Passwords doesn't match!</b>";
            }
        }
        ?>
        <form class="form-signin" role="form" action="" method="POST">
            <?php
            if (isset($error)) {
                echo '<div clasn"s="alert alert-danger" role="alert">' . $error . '</div>';
            }
            ?>
            <input type="text" name="username" class="form-control" placeholder="Username" required="" autofocus="" autocomplete="off" style="margin-top: 20px">
            <input type="password" name="password" class="form-control" placeholder="Password" required="" autofocus="" autocomplete="off" style="margin-top: 10px">
            <input type="password" name="repassword" class="form-control" placeholder="Renter Password" required="" autofocus="" autocomplete="off" style="margin-top: 10px">
            <input type="text" name="yubikey" class="form-control" placeholder="Yubikey" required="" maxlength="12" autofocus="" autocomplete="off" style="margin-top: 10px">
            <button type="submit" name="register" style="margin-top: 5px">Register</button>
        </form>
    </body>
</html>