<?php

$host       = "localhost";
$user       = "root";
$pass       = "12345";
$db         = "bes";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$filename = 'dataBarang_' . time() . '.xml';
$namaTabel = "stock";

$query = "SELECT * FROM $namaTabel";
$hasil = mysqli_query($koneksi, $query);
$jumField = mysqli_num_fields($hasil);
$sites = array();

while ($data = mysqli_fetch_array($hasil)) {
    $sites[] = array('kode_barang' => $data['kode_barang'], 'nama_barang' => $data['nama_barang'], 'jumlah' => $data['jumlah'], 'harga' => $data['harga']);
}

// 5. PARSING DATA SQL -> XML Document : print_r($sites);
$document = new DOMDocument();
$document->formatOutput = true;

$root = $document->createElement("bes");
$document->appendChild($root);

foreach ($sites as $barang) {
    $block = $document->createElement("stock");

    $kode = $document->createElement("kode_barang");
    $kode->appendChild(
        $document->createTextNode($barang['kode_barang'])
    );
    $block->appendChild($kode);

    $nama = $document->createElement("nama_barang");
    $nama->appendChild(
        $document->createTextNode($barang['nama_barang'])
    );
    $block->appendChild($nama);

    $jumlah = $document->createElement("jumlah");
    $jumlah->appendChild(
        $document->createTextNode($barang['jumlah'])
    );
    $block->appendChild($jumlah);

    $harga = $document->createElement("harga");
    $harga->appendChild(
        $document->createTextNode($barang['harga'])
    );
    $block->appendChild($harga);

    $root->appendChild($block);
}

// 6. MENYIMPAN DATA DALAM BENTUK FILE linksp.xml
$document->save('file/dataBarang_' . time() . '.xml');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="icon.png" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">

    <title>Data Barang</title>
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            background-image: url("bg6.jpg");
        }
    </style>
</head>
<div class="card">
    <div class="card-header">
        EXPORT TO XML FILE SUCCES!!!
    </div>
    <div class="card-body">
        <table cellspacing='0px' align='center' class="table" id="tableBarang">
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>Harga Barang</th>
            </tr>
            <?php
            $no = 1;
            $ambildata = mysqli_query($koneksi, "SELECT * FROM stock");
            while ($tampil = mysqli_fetch_array($ambildata)) {
                echo "
                        <tr>
                            <td>$no</td>
                            <td>$tampil[kode_barang]</td>
                            <td>$tampil[nama_barang]</td>
                            <td>$tampil[jumlah]</td>
                            <td>$tampil[harga]</td>
                        </tr>";
                $no++;
            }
            ?>
        </table>
    </div>
</div>