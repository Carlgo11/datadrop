<?php

# Time limit in seconds.
$limit = 2;
# Starting cost. Set higher if you want to skip low cost tests.
$cost = 1;
# Password to hash. It doesn't matter if it's a "secure" one or not.
$password = "password";

echo "<style>table, th, td {border: 1px solid black;border-collapse: collapse;}</style>";

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

echo "<table>";
echo "<tr><td>Cost</td><td>Time difference</td></tr><tr>";
while (true == true) {
    $start = microtime_float();
    $hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => $cost));
    $end = microtime_float();
    $diff = $end - $start;
    echo '<td>' . $cost . '</td><td>' . $diff . "</td></tr>";
    if ($diff >= $limit) {
        echo "</table><br><b>Limit of " . $limit . " reached, stopping.</b>";
        break;
    } else {
        $cost = $cost + 1;
    }
}
?>
