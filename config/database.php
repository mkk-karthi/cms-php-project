<?php

// get env variables
$env = parse_ini_file('../.env');

// database connection
$conn = mysqli_connect($env["DB_SERVER"], $env["DB_USERNAME"], $env["DB_PASSWORD"], $env["DB_NAME"]);

if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
define('DATABASE', $conn);
