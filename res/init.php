<?php

$config_location = __DIR__ . '/config.php';
include __DIR__ . '/lib/auth/API.php';
ini_set('display_errors', 'On');

function error($status, $title, $message) {
    global $config;
    header($_SERVER["SERVER_PROTOCOL"] . " " . $status . " " . $title);
    echo('<div class="content">');
    echo('<h2>Whoops, that wasn\'t supposed to happen!</h2><br>' . PHP_EOL);
    echo('<img src="./res/media/errors/' . rand(1, 11) . '.jpg" width="500"/><br><br>' . PHP_EOL);
    echo('<h4>But do not fear! Out tech-kittens have already been notified!</h4>' . PHP_EOL);
    echo('<p>' . $message . '</p>' . PHP_EOL);
    echo('</div>');
    if (defined("DEBUG"))
        echo('<hr>' . PHP_EOL . $_SERVER['SERVER_SIGNATURE'] . PHP_EOL);
    //include(__DIR__.'/../res/footer.php');
}

if (file_exists($config_location)) {
    $config = include_once $config_location;
} else {
    die(error(500, "Configuration Error", "The Configuration file could not be found " . $config_location));
}
include(__DIR__ . '/header.php');
