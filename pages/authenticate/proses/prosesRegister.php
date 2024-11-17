<?php
include_once '../../../database/connection.php';
session_start();

$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password1 = $_POST['password1'] ?? '';
$password2 = $_POST['password2'] ?? '';

// Validasi input
if (empty($email) || empty($username) || empty($password1) || empty($password2)) {
    $_SESSION['messages'] = 'Semua field harus diisi.';
    $_SESSION['statusAlert'] = 'error';
    var_dump($email ."|".$username."|pass: ".$password."|".$password2);
    echo "SALAH SEMUA";
    header("Location:../register.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['messages'] = 'Email tidak valid.';
    $_SESSION['statusAlert'] = 'error';
    echo "EMAIL";
    header("Location:../register.php");
    exit();
}


if ($password1 !== $password2) {
    $_SESSION['messages'] = 'Password dan konfirmasi password tidak sesuai.';
    $_SESSION['statusAlert'] = 'error';
    echo "PASSWORD TIDAK SAMA";
    header("Location:../register.php");
    exit();
}

// Enkripsi password
$encryptedPassword = password_hash($password1, PASSWORD_DEFAULT);

try {
    // Gunakan prepared statement
    $stmt = $db->prepare("INSERT INTO user (email, username, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $db->error);
    }
    
    $stmt->bind_param("sss", $email, $username, $encryptedPassword);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['messages'] = 'Akun berhasil dibuat. Silakan login.';
        $_SESSION['statusAlert'] = 'success';
        header("Location:../../../index.php");
        exit();
    } else {
        throw new Exception("Error executing query: " . $stmt->error);
    }
} catch (Exception $e) {
    $_SESSION['messages'] = 'Terjadi kesalahan: ' . $e->getMessage();
    $_SESSION['statusAlert'] = 'error';
    header("Location:../register.php");
    exit();
}
