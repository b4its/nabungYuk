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
$idPemasukan = (int)$_POST['idPemasukan'];
$created_at = date('Y-m-d H:i:s');
$total = $jumlah * $nominal;

// Validasi data
if (empty($nama) || $jumlah <= 0 || $nominal <= 0) {
    $_SESSION['messages'] = 'Data tidak valid.';
    $_SESSION['statusAlert'] = 'error';
    header("Location: ../../pemasukan.php?p=formPemasukan");
    exit();
}

try {
    // Mulai transaksi
    $db->begin_transaction();

    $query = "SELECT nominal FROM saldo where user = '".$_SESSION['idUser']."' ORDER BY created_at DESC LIMIT 1";
    $result = $db->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['saldoTerakhir'] = $row['nominal'];
        $cekSaldo = (int)$_SESSION['saldoTerakhir'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }

    $query = "SELECT total FROM pemasukan where idPemasukan = '".$idPemasukan."'";
    $result = $db->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['saldoAwal'] = $row['total'];
        $saldoAwal = (int)$_SESSION['saldoAwal'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }

    

    // Query untuk tabel pemasukan
    $sqlPemasukan = "UPDATE pemasukan SET nama = ?, jumlah = ?, nominal = ?, total = ? WHERE idPemasukan = ?";
    $stmtPemasukan = $db->prepare($sqlPemasukan);
    $stmtPemasukan->bind_param("siiii", $nama, $jumlah, $nominal, $total, $idPemasukan);
    $stmtPemasukan->execute();

    $queryPengeluaran = "SELECT sum(total) as total FROM pengeluaran where user = '".$_SESSION['idUser']."'";
    $resultPengeluaran = $db->query($queryPengeluaran);
    if ($resultPengeluaran && $resultPengeluaran->num_rows > 0) {
        $rowA = $resultPengeluaran->fetch_assoc();
        unset($_SESSION['totalPengeluaran']);
        $_SESSION['totalPengeluaran'] = $rowA['total'];
        $totalPengeluaran = (int)$_SESSION['totalPengeluaran'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }

    $queryPemasukan = "SELECT sum(total) as totalA FROM pemasukan where user = '".$_SESSION['idUser']."'";
    $resultPemasukan = $db->query($queryPemasukan);
    if ($resultPemasukan && $resultPemasukan->num_rows > 0) {
        $rowB = $resultPemasukan->fetch_assoc();
        unset($_SESSION['totalPemasukan']);
        $_SESSION['totalPemasukan'] = $rowB['totalA'];
        $totalPemasukan = (int)$_SESSION['totalPemasukan'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }


    if($totalPemasukan>$totalPengeluaran){
            $totalAkhir = $totalPemasukan - $totalPengeluaran;
    }else{
            $_SESSION['messages'] = 'Saldo anda tidak mencukupi..';
            $_SESSION['statusAlert'] = 'warning';
            header("Location: ../../pemasukan.php?p=dataPemasukan");
            exit();
        
    }


    // Query untuk tabel saldo
    $catatan = "Update Pemasukan dari $nama dengan jumlah $jumlah dan nominal $nominal, total: $total";
    $sqlSaldo = "update saldo set nominal=?, catatan =? where user = ? order by created_at desc limit 1";
    $stmtSaldo = $db->prepare($sqlSaldo);
    $stmtSaldo->bind_param("isi", $totalAkhir, $catatan, $_SESSION['idUser']);
    $stmtSaldo->execute();

    // Commit transaksi
    $db->commit();

    // Set pesan sukses
    $_SESSION['messages'] = 'Update pemasukan telah berhasil.';
    $_SESSION['statusAlert'] = 'success';
    header("Location: ../../pemasukan.php?p=dataPemasukan");
    exit();
} catch (Exception $e) {
    // Rollback jika terjadi error
    $db->rollback();

    // Tampilkan error
    echo "Error: " . $e->getMessage();
}
?>

