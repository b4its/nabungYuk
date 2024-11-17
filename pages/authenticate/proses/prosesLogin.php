<?php
session_start();
require_once "../../../database/connection.php";

if (isset($_POST['login'])) {
    // Escape input
    $username_input = mysqli_real_escape_string($db, $_POST["username"]);
    $password_input = $_POST["password"];

    // Prepared statement untuk query
    $stmt = $db->prepare("SELECT idUser, username, password FROM user WHERE username = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $db->error);
    }

    // Bind parameter
    $stmt->bind_param("s", $username_input);
    $stmt->execute();
    $stmt->store_result();

    // Cek apakah user ditemukan
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($idUser, $username_input, $hashed_password);
        $stmt->fetch();


        // Verifikasi password
        if (password_verify($password_input, $hashed_password)) {
            // Simpan data ke session
            unset($_SESSION['idUser']);
            unset($_SESSION['username']);
            unset($_SESSION['messages']);
            unset($_SESSION['statusAlert']);
            $_SESSION['idUser'] = $idUser;
            $_SESSION['username'] = $username_input;
            $_SESSION['messages'] = 'Selamat datang ' . $_SESSION['username'];
            $_SESSION['statusAlert'] = 'success';

            // Redirect ke dashboard
            echo "<meta http-equiv='refresh' content='0;url=../../dashboard.php?p=halamanDashboard'>";
        } else {
            echo "PASSWORD SALAH YA";
            
            handleLoginError("Password salah");
        }
    } else {
        echo "GA ADA USERNAME";
        handleLoginError("Username tidak ditemukan");
    }

    // Tutup statement
    $stmt->close();
} else {
    session_destroy();
    include "../../../index.php";
}

// Fungsi untuk menangani error login
function handleLoginError($message) {
    session_destroy();
    session_start();
    $_SESSION['messages'] = $message;
    $_SESSION['statusAlert'] = 'error';
    // echo "<meta http-equiv='refresh' content='0;url=../../../index.php'>";
}
?>
