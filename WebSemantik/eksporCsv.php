<?php
$host       = "localhost";
$user       = "root";
$pass       = "12345";
$db         = "bes";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
$filename = 'dataBarang_' . time() . '.csv';
// Select query
$query = "SELECT * FROM stock";
$result = mysqli_query($koneksi, $query);
$barang_arr = array();

// file creation
$file = fopen($filename, "w");

// Header row - Remove this code if you don't want a header row in the export file.
$barang_arr = array("kode_barang", "nama_barang", "jumlah", "harga");
while ($row = mysqli_fetch_assoc($result)) {
    $kode = $row['kode_barang'];
    $nama = $row['nama_barang'];
    $jumlah = $row['jumlah'];
    $harga = $row['harga'];
    // Write to file 
    $barang_arr = array($kode,$nama,$jumlah,$harga);
    fputcsv($file, $barang_arr);
}

fclose($file);

header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: application/csv; ");
readfile($filename);
// deleting file
unlink($filename);
exit();
?>