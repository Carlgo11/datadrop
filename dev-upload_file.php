<?php
include __DIR__ . '/res/init.php';
include __DIR__ . '/res/session.php';
include __DIR__ . '/lib/auth/API.php';
if (isset($_POST["submit"])) {
    $file = $_FILES["uploadedFile"];
    $fileContent = file_get_contents($file['tmp_name']);
    data_storage::uploadFile($fileContent, $file["name"], $file["size"], $file["type"], $_POST['password'], "Carl");
} else {
    ?>
    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="uploadedFile" id="uploadedFile">
            <input type="password" name="password" id="password" placeholder="Password">
            <input type="submit" value="Upload File" name="submit">
        </form>
    </body>
    </html>
<?php } ?>