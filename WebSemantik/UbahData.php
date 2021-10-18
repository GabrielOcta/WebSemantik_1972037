<?php
$host       = "localhost";
$user       = "root";
$pass       = "12345";
$db         = "bes";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'edit') {
    $id         = $_GET['kode'];
    $sql1       = "select * from stock where kode_barang = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $kode       = $r1['kode_barang'];
    $nama       = $r1['nama_barang'];
    $jumlah     = $r1['jumlah'];
    $harga   = $r1['harga'];
    if ($kode == '') {
        $error = "Data tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Ubah Data Stock</title>
	<link rel="stylesheet" href="css/signup.css">
    <style>
        body {
            background-image: url("image/bg6.jpg");
        }
    </style>
</head>
<body>
	<div class="signUp">
		<img src="image/icon.png">
		<h1>Update Barang</h1>
		<form method="post" action="update_koneksi.php">
			<input type="text" class="inputBox" name="kode" placeholder="Kode Barang" value="<?php echo $kode ?>">
			<input type="text" class="inputBox" name="nama" placeholder="Nama Barang" value="<?php echo $nama ?>">
			<input type="text" class="inputBox" name="jumlah" placeholder="Jumlah" value="<?php echo $jumlah ?>">
			<input type="text" class="inputBox" name="harga" placeholder="Harga" value="<?php echo $harga ?>">
			<input type="submit" name="submit" class="signbtn" color="#64a19d" value="Ubah Data">
		</form>
	</div>
</body>
</html>