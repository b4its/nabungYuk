<?php
/* Database credentials. Assuming you are running MySQL server with default setting (user 'root' with no password) */
$hostname = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'nabungyuk';
/* Attempt to connect to MySQL database */
$db = mysqli_connect($hostname, $username, $password, $databaseName);

// Check connection
if($db === false){
    die("ERROR: Database tidak dapat terhubung. " . mysqli_connect_error());
} 
// else {
//     echo "Database berhasil Terhubung";
// }
?>