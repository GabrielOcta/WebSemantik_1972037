<?php
$conn = mysqli_connect('localhost','root','12345','bes');
header('Content-Type: application/json');

if (isset($_GET['kode_barang'])) {
    $id  = $_GET['kode_barang'];
    $sql = $conn->prepare("DELETE FROM stock WHERE kode_barang = ?");
    $sql->bind_param('s', $id);
    $sql->execute();
    if ($sql) {
        echo json_encode(array('RESPONSE' => 'SUCCESS'));
        //header("location:../readapi/tampil.php");
    } else {
        echo json_encode(array('RESPONSE' => 'FAILED'));
    }
} else {
    echo "GAGAL";
}