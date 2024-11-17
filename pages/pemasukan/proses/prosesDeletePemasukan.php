<?php
require_once('../../../helper/currency.php');
require_once('../../../database/connection.php');
session_start();

// Set zona waktu
date_default_timezone_set('Asia/Hong_Kong');

$idPemasukan = (int)$_GET['id'];

try {
    // Mulai transaksi
    $db->begin_transaction();
    $query = "SELECT * FROM pemasukan where idPemasukan ='".$idPemasukan."'";
    $result = $db->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['namaPemasukan'] = $row['nama'];
        $_SESSION['totalPemasukan'] = $row['total'];

    } else {
        echo "Tidak ada data di tabel saldo.";
    }

    $query = "SELECT nominal FROM saldo where user = '".$_SESSION['idUser']."' ORDER BY created_at DESC LIMIT 1";
    $result = $db->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['saldoTerakhir'] = $row['nominal'];
        $cekSaldo = (int)$_SESSION['saldoTerakhir'];
    } else {
        echo "Tidak ada data di tabel saldo.";
    }






    // Query untuk tabel pemasukan
    $sqlPemasukan = "delete from pemasukan WHERE idPemasukan = ?";
    $stmtPemasukan = $db->prepare($sqlPemasukan);
    $stmtPemasukan->bind_param("i",  $idPemasukan);
    $stmtPemasukan->execute();

    $queryPemasukan = "SELECT sum(total) as total FROM pemasukan where user = '".$_SESSION['idUser']."'"; 
    $resultPemasukan = $db->query($queryPemasukan);
    if ($resultPemasukan && $resultPemasukan->num_rows > 0) {
        $rowB = $resultPemasukan->fetch_assoc();
        unset($_SESSION['totalPemasukan']);
        $_SESSION['totalPemasukan'] = $rowB['total'];
        $totalPemasukan = (int)$_SESSION['totalPemasukan'];
    } else {
        $totalPemasukan = (int)0;
        echo "Tidak ada data di tabel saldo.";
    }

    // Logika ketika totalPemasukan bernilai 0
    if ($totalPemasukan <= 0) {
        // Jalankan SQL TRUNCATE TABLE pengeluaran
        $queryHapusPengeluaran = "delete from pengeluaran where user = '".$_SESSION['idUser']."'";
        if ($db->query($queryHapusPengeluaran)) {
            echo "Tabel pengeluaran berhasil dikosongkan.";
        } else {
            echo "Error saat mengosongkan tabel pengeluaran: " . $db->error;
        }
    } else {
        // Logika ketika totalPemasukan tidak 0
        $totalAkhir = $cekSaldo - $totalPemasukan;

    }

    // Query untuk memperbarui saldo
    $sqlUpdateSaldo = "UPDATE saldo SET nominal = ?, catatan = ? where user = ? ORDER BY created_at DESC LIMIT 1";
    $stmtSaldo = $db->prepare($sqlUpdateSaldo);
    
    if (!$stmtSaldo) {
        die("Error preparing statement: " . $db->error);
    }
    
    $catatan = "Saldo diperbarui setelah penghapusan pemasukan.";
    $stmtSaldo->bind_param("isi",  $totalAkhir, $catatan, $_SESSION['idUser']);
    if ($stmtSaldo->execute()) {
        echo "Saldo berhasil diperbarui.";
    } else {
        echo "Error saat memperbarui saldo: " . $stmtSaldo->error;
    }

    // Commit transaksi
    $db->commit();

    // Set pesan sukses
    $_SESSION['messages'] = 'Penghapusan pemasukan telah berhasil.';
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

