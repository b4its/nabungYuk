<?php
require_once('../../../helper/currency.php');
require_once('../../../database/connection.php');
session_start();

// Set zona waktu
date_default_timezone_set('Asia/Hong_Kong');

$idPengeluaran = (int)$_GET['id'];

try {
    // Mulai transaksi
    $db->begin_transaction();
    $query = "SELECT * FROM pengeluaran where idPengeluaran ='".$idPengeluaran."'";
    $result = $db->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['namaPengeluaran'] = $row['nama'];
        $_SESSION['totalPengeluaran'] = $row['total'];

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



    $totalAkhir = $cekSaldo + $_SESSION['totalPengeluaran'];
    var_dump($totalAkhir);
    // Query untuk tabel pengeluaran
    $sqlPengeluaran = "delete from pengeluaran WHERE idPengeluaran = ?";
    $stmtPengeluaran = $db->prepare($sqlPengeluaran);
    $stmtPengeluaran->bind_param("i",  $idPengeluaran);
    $stmtPengeluaran->execute();

    // Query untuk tabel saldo
    $catatan = "pengeluaran dari ".$_SESSION['namaPengeluaran']." telah berhasil dihapus";
    $sqlSaldo = "update saldo set nominal=?, catatan =? where user = ? order by created_at desc limit 1";
    $stmtSaldo = $db->prepare($sqlSaldo);
    $stmtSaldo->bind_param("isi",  $totalAkhir, $catatan, $_SESSION['idUser']);
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

