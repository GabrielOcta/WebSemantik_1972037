<?php
$conn = mysqli_connect('localhost','root','12345','bes');
header('Content-Type: application/json');

if (isset($_POST['kode_barang'])) {
    $kode_barang   	= $_POST['kode_barang'];
	$nama_barang 	= $_POST['nama_barang'];
	$jumlah		= $_POST['jumlah'];
	$harga			= $_POST['harga'];
    $sql = $conn->prepare("UPDATE stock SET nama_barang = ?, jumlah = ?, harga = ? WHERE kode_barang = ?");
    $sql->bind_param('sdds', $nama_barang, $jumlah, $harga, $kode_barang);
    $sql->execute();
    if ($sql) {
        echo json_encode(array('RESPONSE' => 'SUCCESS'));
    } else {
        echo json_encode(array('RESPONSE' => 'FAILED'));
    }
} else {
    echo "GAGAL";
}