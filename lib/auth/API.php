<?php

class Login {

    public static function register($username, $password, $yubikey) {
        if (Login::userExists($username) == false) {
            $hash = password_hash($password, PASSWORD_BCRYPT, Login::generateHashCost());
            include_once __DIR__ . '/../../res/config.php';
            $con = mysqli_connect($conf['mysql-url'], $conf['mysql-user'], $conf['mysql-password'], $conf['mysql-db']) or die("Connection problem.");
            $query = $con->prepare("INSERT INTO `" . $conf['login-table'] . "` (`username`, `password`, `yubikey`) VALUES (?, ?, ?);");
            $query->bind_param("sss", $username, $hash, $yubikey);
            $query->execute();
            return 1;
        }
        return 0;
    }

    public static function getPassword($username, $password) {
        include_once __DIR__ . '/../../res/config.php';
        $con = mysqli_connect($conf['mysql-url'], $conf['mysql-user'], $conf['mysql-password'], $conf['login-db']) or die("Connection problem.");
        $query = $con->prepare("SELECT * FROM `" . $conf['login-table'] . "` WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($dbuser, $dbpassword, $dbyubikey);
        if ($query->fetch()) {
            if (password_verify($password, $dbpassword)) {
                return true;
            }
        }
        return false;
    }

    public static function verifyYubikey($username, $otp) {
        include_once __DIR__ . '/../../res/config.php';
        $con = mysqli_connect($conf['mysql-url'], $conf['mysql-user'], $conf['mysql-password'], $conf['login-db']) or die("Connection problem.");
        $query = $con->prepare("SELECT `yubikey` FROM `" . $conf['login-table'] . "` WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($dbyubikey);
        if ($query->fetch()) {
            if (substr($otp, 0, 12) == $dbyubikey) {
                return true;
            }
        }
        return false;
    }

    public static function doLogin($username, $password) {
        if (Login::userExists($username)) {
            if (Login::getPassword($username, $password)) {
                return true;
            }
        }
        return false;
    }

    public static function userExists($username) {
        include_once __DIR__ . '/../../res/config.php';
        $con = mysqli_connect($conf['mysql-url'], $conf['mysql-user'], $conf['mysql-password'], $conf['login-db']) or die("Connection problem.");

        $s = "SELECT COUNT(*) AS num FROM `" . $conf['login-table'] . "` WHERE `username` = ?";
        $query = $con->prepare($s);
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            foreach ($row as $r) {
                if ($r > 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function generateHashCost() {
        $timeTarget = 0.05;
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        return $cost;
    }

    public static function updatePassword($username, $oldpassword, $password) {
        if (Login::getPassword($username, $oldpassword)) {
            $hash = password_hash($password, PASSWORD_BCRYPT, Login::generateHashCost($password));
            include_once __DIR__ . '/../../res/config.php';
            $con = mysqli_connect($conf['mysql-url'], $conf['mysql-user'], $conf['mysql-password'], $conf['login-db']) or die("Connection problem.");
            $query = $con->prepare("UPDATE `" . $conf['login-table'] . "` SET `password`=? WHERE `username`=?;");
            $query->bind_param("ss", $hash, $username);
            $query->execute();
            return true;
        }
        return false;
    }

}

class Encryption {

    public static function decrypt($data, $password) {
        include_once __DIR__ . '/../../res/config.php';
        return openssl_decrypt($data, $conf['Encryption-Method'], $password);
    }

    public static function encrypt($data, $password) {
        include_once __DIR__ . '/../../res/config.php';
        return openssl_encrypt($data, $conf['Encryption-Method'], $password);
    }

}

class data_storage {

    public static function getFile($id, $password) {
        include_once __DIR__ . '/../../res/config.php';
        $con = mysqli_connect($conf['mysql-url'], $conf['mysql-user'], $conf['mysql-password'], $conf['data-db']) or die("Connection problem.");
        $query = $database->prepare("SELECT metadata, content FROM `" . $conf['data-db'] . "` WHERE `id` = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $query->bind_result($enc_filedata, $enc_content);
        return [
            Encryption::decrypt($enc_filedata, $password),
            Encryption::decrypt($enc_content, $password),
        ];
    }

    public static function uploadFile($content, $filename, $filesize, $filetype, $password, $author) {
        include_once __DIR__ . '/../../res/config.php';
        $enc_filedata = Encryption::encrypt(array($filename, $filesize, $filetype), $password);
        $enc_content = Encryption::encrypt($content, $password);
        $con = mysqli_connect($conf['mysql-url'], $conf['mysql-user'], $conf['mysql-password'], $conf['login-db']) or die("Connection problem.");
        $query = $con->prepare("INSERT INTO `" . $conf['data-table'] . "` (author, metadata, content) VALUES (?,?,?)");
        $query->bind_param("ssb", $author, $enc_filedata, $enc_content);
    }

}
