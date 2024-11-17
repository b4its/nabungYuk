<?php
require_once "../../../database/connection.php";
session_start();
if (isset($_SESSION['idUser'])) {
    $id = $_SESSION['idUser'];
    
    // Hapus semua variabel sesi
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['messages'] = 'Anda telah berhasil logout..';
    $_SESSION['statusAlert'] = 'success';
    echo "<meta http-equiv='refresh' content='0; url=../../../index.php'>";    
}
?>