<?php
$conn = mysqli_connect('localhost','root','12345','bes');
header('Content-Type: application/json');

if (isset($_GET['kode_barang'])) {
    $id = $_GET['kode_barang'];
    $SQL = $conn->prepare("SELECT * FROM stock WHERE kode_barang = ?");
    $SQL->bind_param("i", $id);
    $SQL->execute();
    $hasil = $SQL->get_result();
    $myArray = array();
    while ($users = $hasil->fetch_array(MYSQLI_ASSOC)) {
        $myArray = $users;
    }
    echo json_encode($myArray);
} else {
    echo "data tidak ditemukan";
}