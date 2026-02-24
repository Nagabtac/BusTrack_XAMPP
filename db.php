<?php
$host = "localhost";
$port = "5432";
$db   = "bustrack";
$user = "postgres";
$pass = "1234";

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if(!$conn){
    die("Database connection failed");
}
?>