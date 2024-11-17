<?php
require_once('../../../helper/currency.php');
require_once('../../../database/connection.php');
session_start();

// Set zona waktu
date_default_timezone_set('Asia/Hong_Kong');

// Ambil data dari form
$nama = htmlspecialchars($_POST['nama']);
$jumlah = (int)$_POST['jumlah'];
$nominal = (int)$_POST['nominal'];
$created_at = date('Y-m-d H:i:s');
$total = $jumlah * $nominal;


// Validasi data
if (empty($nama) || $jumlah <= 0 || $nominal <= 0) {
    $_SESSION['messages'] = 'Data tidak valid.';
    $_SESSION['statusAlert'] = 'error';
    header("Location: ../../pengeluaran.php?p=dataPengeluaran");
    exit();
}

try {
    // Mulai transaksi
    $db->begin_transaction();
    // Query untuk mendapatkan data terakhir
    $query = "SELECT nominal FROM saldo where user = '".$_SESSION['idUser']."' ORDER BY created_at DESC LIMIT 1";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['saldoTerakhir'] = $row['nominal'];
        $cekSaldo = (int)$_SESSION['saldoTerakhir'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }

    if ($total > $cekSaldo){
        $_SESSION['messages'] = 'Saldo anda tidak mencukupi..';
        $_SESSION['statusAlert'] = 'warning';
        header("Location: ../../pengeluaran.php?p=dataPengeluaran");
        exit();
    }
    $totalAkhir = $cekSaldo - $total;
    // Query untuk tabel pemasukan
    $sqlPemasukan = "INSERT INTO pengeluaran (user, nama, jumlah, nominal, total, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmtPemasukan = $db->prepare($sqlPemasukan);
    $stmtPemasukan->bind_param("isiiis", $_SESSION['idUser'], $nama, $jumlah, $nominal, $total, $created_at);
    $stmtPemasukan->execute();

    // Query untuk tabel saldo
    $catatan = "Pengeluaran dari $nama dengan jumlah $jumlah dan nominal $nominal, total: $total";
    $sqlSaldo = "INSERT INTO saldo (user, nominal, catatan, created_at) 
                 VALUES (?, ?, ?, ?)";
    $stmtSaldo = $db->prepare($sqlSaldo);
    $stmtSaldo->bind_param("iiss", $_SESSION['idUser'], $totalAkhir, $catatan, $created_at);
    $stmtSaldo->execute();

    // Commit transaksi
    $db->commit();

    // Set pesan sukses
    $_SESSION['messages'] = 'Penambahan pengeluaran telah berhasil.';
    $_SESSION['statusAlert'] = 'success';
    header("Location: ../../pengeluaran.php?p=dataPengeluaran");
    exit();
} catch (Exception $e) {
    // Rollback jika terjadi error
    $db->rollback();

    // Tampilkan error
    echo "Error: " . $e->getMessage();
}
?>

