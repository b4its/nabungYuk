<?php 
setlocale(LC_MONETARY, 'id_ID.UTF-8');

// Fungsi untuk format angka menjadi format mata uang tanpa dua nol di belakang koma
function formatRupiah($amount) {
    // Format angka dengan pemisah ribuan menggunakan number_format
    $formatted = number_format($amount, 0, ',', '.');
    return 'Rp ' . $formatted;
}

?>