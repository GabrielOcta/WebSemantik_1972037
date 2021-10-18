<?php
$conn = mysqli_connect('localhost','root','12345','bes');
header('Content-Type: application/json');

if (isset($_POST['kode']) && isset($_POST['nama']) && isset($_POST['jumlah']) && isset($_POST['harga'])) {
	$kode_barang   	= $_POST['kode'];
	$nama_barang 	= $_POST['nama'];
	$jumlah		= $_POST['jumlah'];
	$harga			= $_POST['harga'];
    $query = "INSERT INTO stock (kode_barang, nama_barang, jumlah, harga) VALUES ('$kode_barang', '$nama_barang', '$jumlah', '$harga')";
	$sql = mysqli_query($conn, $query);
	if ($sql) {
		echo json_encode(array('RESPONSE' => 'SUCCESS'));
	} else {
		echo json_encode(array('RESPONSE' => 'FAILED'));
	}
} else{
    echo 'GAGAL';
}
?>