<?php
    // connection starts
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "juliandb";
    // connect to database
    $con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    // check connection

    if(mysqli_connect_errno()) {
        echo "Failed to connect to MYSQL" . mysqli_connect_error();
        exit();
}
?>