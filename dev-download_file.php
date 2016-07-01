<?php
if (isset($_POST["submit"])) {

# Form already sent.
    include __DIR__ . '/lib/auth/API.php';
    include __DIR__ . '/res/session.php';
    $e = data_storage::getFile($_POST["file-id"], $_POST["password"]);
    $new_filecontent = explode(" ", $e[0]);

    header('Content-Description: File Transfer');
    header('Content-Type: ' . $new_filecontent[2]);
    header('Content-Disposition: attachment; filename="' . $new_filecontent[0] . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $new_filecontent[1]);
    echo($e[1]);
    exit;
} else {
    # Form not sent.
    include __DIR__ . '/res/init.php';
    include __DIR__ . '/res/session.php';
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="number" name="file-id" id="file-id" placeholder="File-ID">
        <input type="password" name="password" id="password" placeholder="Password">
        <input type="submit" value="Download File" name="submit">
    </form>
    <?php
}