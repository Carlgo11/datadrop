<?php
if (isset($_POST['login'])) {
    require 'lib/auth/API.php';
    $output = Login::doLogin($_POST['username'], $_POST['password']);
    require 'lib/Yubico/Yubikey.php';
    $yubi = yubikey::verify($_POST['otp']);
    if ($yubi && $output){
        $ayubi = Login::verifyYubikey($_POST['username'], $_POST['otp']);
        if($ayubi){
        session_start();
        $_SESSION['name'] = $_POST['username'];
        session_commit();
        header('Location: ' . 'index.php');
        die;
        }else{
            echo "yubikey: ".$ayubi;
        }
    }else{
        echo $yubi." ,".$output;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <link rel="icon" type="image/vnd.microsoft.icon"  href="./resources/favicon.ico"/>
        <title>
            <?php include __DIR__.'/res/config.php'; echo $conf['title']; ?>
        </title>
    </head>
    <body>
        <form class="form-signin" role="form" action="" method="POST">
            <?php
            if (isset($output) && !$output) {
                echo '<div clasn"s="alert alert-danger" role="alert"><b>Wrong Username or Password!</b><br>Maybe you misspelled something?</div>';
            }
            if(isset($yubi)){
                echo $yubi;
            }
            ?>
            <input type="text" name="username" class="form-control" placeholder="Username" required="" autofocus="" autocomplete="off" style="margin-top: 20px">
            <input type="password" name="password" class="form-control" placeholder="Password" required="" autofocus="" autocomplete="off" style="margin-top: 10px">
            <input type="text" name="otp" class="form-control" placeholder="OTP" autofocus="" autocomplete="off" style="margin-top: 10px">
            <button type="submit" name="login" style="margin-top: 5px" >Login</button>
        </form>
        <p>or <a href="dev-signup.php">register</a></p>
    </body>
</html>