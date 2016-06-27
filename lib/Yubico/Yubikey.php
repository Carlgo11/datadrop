<?php

class yubikey {

    static public function verify($otp) {
        include __DIR__.'/../../res/config.php';
        $ask_url = 0;
        $url = "api.yubico.com/wsapi/2.0/verify,api2.yubico.com/wsapi/2.0/verify,api3.yubico.com/wsapi/2.0/verify,api4.yubico.com/wsapi/2.0/verify,api5.yubico.com/wsapi/2.0/verify";
        $id = $conf['yubikey-id'];
        $key = $conf['yubikey-key'];
        $https = "";
        $httpsverify = "";

        if (!$id || !$otp) {
            $key = "oBVbNt7IZehZGR99rvq8d6RZ1DM=";
        }
        if (!$id) {
            $id = "1851";
        }

        require_once 'Yubico.php';
        $yubi = new Auth_Yubico($id, $key, $https, $httpsverify);

        $auth = $yubi->verify($otp, false);

        if (PEAR::isError($auth)) {
            // fail
            return $auth->getMessage();
        } else {
            // success
            return true;
        }
    }

}
?>