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
$idPengeluaran = (int)$_POST['idPengeluaran'];
$created_at = date('Y-m-d H:i:s');
$total = $jumlah * $nominal;

// Validasi data
if (empty($nama) || $jumlah <= 0 || $nominal <= 0) {
    $_SESSION['messages'] = 'Data tidak valid.';
    $_SESSION['statusAlert'] = 'error';
    header("Location: ../../pengeluaran.php?p=dataPengeluaran");
    // exit();
}

try {
    // Mulai transaksi
    $db->begin_transaction();

    $query = "SELECT nominal FROM saldo ORDER BY created_at DESC LIMIT 1";
    $result = $db->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['saldoTerakhir'] = $row['nominal'];
        $cekSaldo = (int)$_SESSION['saldoTerakhir'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }

    

    // Query untuk tabel pengeluaran
    $sqlPengeluaran = "UPDATE pengeluaran SET nama = ?, jumlah = ?, nominal = ?, total = ? WHERE idPengeluaran = ?";
    $stmtPengeluaran = $db->prepare($sqlPengeluaran);
    $stmtPengeluaran->bind_param("siiii", $nama, $jumlah, $nominal, $total, $idPengeluaran);
    $stmtPengeluaran->execute();

    $queryPemasukan = "SELECT sum(total) as total FROM pemasukan where user = '".$_SESSION['idUser']."'";
    $resultPemasukan = $db->query($queryPemasukan);
    if ($resultPemasukan && $resultPemasukan->num_rows > 0) {
        $rowA = $resultPemasukan->fetch_assoc();
        unset($_SESSION['totalPemasukan']);
        $_SESSION['totalPemasukan'] = $rowA['total'];
        $totalPemasukan = (int)$_SESSION['totalPemasukan'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }

    $queryPengeluaran = "SELECT sum(total) as totalA FROM pengeluaran where user = '".$_SESSION['idUser']."'";
    $resultPengeluaran = $db->query($queryPengeluaran);
    if ($resultPengeluaran && $resultPengeluaran->num_rows > 0) {
        $rowB = $resultPengeluaran->fetch_assoc();
        unset($_SESSION['totalPengeluaran']);
        $_SESSION['totalPengeluaran'] = $rowB['totalA'];
        $totalPengeluaran = (int)$_SESSION['totalPengeluaran'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }


    if($totalPemasukan>$totalPengeluaran){
        $totalAkhir = $totalPemasukan - $totalPengeluaran;

    }else{
            $_SESSION['messages'] = 'Saldo anda tidak mencukupi..';
            $_SESSION['statusAlert'] = 'warning';
            echo "SALDO MU GA CUKUP";
            header("Location: ../../pengeluaran.php?p=dataPengeluaran");
            exit();
        
    }


    // Query untuk tabel saldo
    $catatan = "Update Pengeluaran dari $nama dengan jumlah $jumlah dan nominal $nominal, total: $total";
    $sqlSaldo = "update saldo set nominal=?, catatan =? where user = ? order by created_at desc limit 1";
    $stmtSaldo = $db->prepare($sqlSaldo);
    $stmtSaldo->bind_param("isi",  $totalAkhir, $catatan, $_SESSION['idUser'],);
    $stmtSaldo->execute();

    // Commit transaksi
    $db->commit();

    // Set pesan sukses
    $_SESSION['messages'] = 'Update pengeluaran telah berhasil.';
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

