<?php
class ApiService
{
    const Tampil_Barang_Url = 'http://localhost/WebSemantik/web_service/v1/tampil_barang.php';
    const Add_Barang_Url = 'http://localhost/WebSemantik/web_service/v1/add_barang.php';
    const Update_Barang_Url = 'http://localhost/WebSemantik/web_service/v1/update_barang.php';
    const Delete_Barang_Url = 'http://localhost/WebSemantik/web_service/v1/delete_barang.php';
}
class utility
{
    public static function curl_post($url, array $post = NULL, array $options = array())
    {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_POSTFIELDS => http_build_query($post)
        );
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
    public static function curl_get($url, array $get = NULL, array $options = array())
    {
        $defaults = array(
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url . (strpos($url, '?') === FALSE ? '?' : '') . http_build_query($get),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 4,
        );
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
?>
<?php
$koneksi    = mysqli_connect("localhost", "root", "12345", "bes");
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $kode       = $_GET['kode'];
    $sendData = array('kode_barang' => $kode);
    $sql1       = utility::curl_get(ApiService::Delete_Barang_Url, $sendData);
    $q1         = json_decode($sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error  = "Gagal melakukan delete data";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $kode        = $_POST['kode'];
    $nama       = $_POST['nama'];
    $jumlah     = $_POST['jumlah'];
    $harga      = $_POST['harga'];
    $sendData = array('kode' => $kode, 'nama' => $nama, 'jumlah' => $jumlah, 'harga' => $harga);
    $sql1   = utility::curl_post(ApiService::Add_Barang_Url, $sendData);
    $q1     = json_decode($sql1);
    if ($q1) {
        $sukses     = "Berhasil memasukkan data baru";
    } else {
        $error      = "Gagal memasukkan data";
    }
}
?>
<?php
if (isset($_POST['input'])) {
    $filename = $_FILES["file"]["tmp_name"];
    $dataBarang = $_FILES["file"]["name"];
    $exe = pathinfo($dataBarang, PATHINFO_EXTENSION);
    if ($exe == 'csv') {
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($filename, "r");
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                $sqlInsert = "INSERT INTO stock (kode_barang, nama_barang, jumlah, harga) 
            VALUES ('" . $column[0] . "', '" . $column[1] . "', '" . $column[2] . "', '" . $column[3] . "')";

                $result = mysqli_query($koneksi, $sqlInsert);
            }
        }
    } elseif ($exe == 'xml') {
        $rowaffected = 0;
        if ($_FILES["file"]["size"] > 0) {
            $xml = simplexml_load_file($_FILES['file']['tmp_name']);
            foreach ($xml as $value) {
                $kode = $value->kode_barang;
                $nama = $value->nama_barang;
                $jumlah = $value->jumlah;
                $harga = $value->harga;

                $sql = "INSERT INTO stock(kode_barang,nama_barang,jumlah,harga) 
                VALUES('" . $kode . "', '" . $nama . "', '" . $jumlah . "','" . $harga . "')";
                $result = mysqli_query($koneksi, $sql);
                if (!empty($result)) {
                    $rowaffected++;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="image/icon.png" />
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
            background-image: url("image/bg6.jpg");
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#projects">Projects</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <header class="masthead">
        <div class="container d-flex h-100 align-items-center">
            <div class="mx-auto text-center">
                <h1 class="mx-auto my-0 text-uppercase">Kelompok Bingung</h1><br>
                <h2 class="text-white-50 mx-auto mt-1 mb-4">1972037 - Gabriel Octa Mahardika</h2>
                <h2 class="text-white-50 mx-auto mt-1 mb-4">1972040 - Adriel Rianson</h2>
                <h2 class="text-white-50 mx-auto mt-1 mb-4">1972043 - Michael Marenden</h2>
            </div>
        </div>
    </header>
    <section class="projects-section bg-light" id="projects">
        <h3 class="mx-auto my-0 text-uppercase" align='center'>FORM & DATA BARANG</h3>
        <div class="mx-auto">
            <!-- untuk memasukkan data -->
            <div class="card">
                <div class="card-header">
                    Form Data Barang
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="nrp" class="col-sm-2 col-form-label">Kode Barang</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="kode" name="kode">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Jumlah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="jumlah" name="jumlah">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Harga</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="harga" name="harga">
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <br>
            <div class="card">
                <div class="card-header">
                    Import Data Barang
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="file">
                        <input type="submit" name="input" value="IMPORT" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    Data Barang
                </div>
                <div class="card-body">
                    <table cellspacing='0px' align='center' class="table" id="tableBarang">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Barang</th>
                            <th>Harga Barang</th>
                            <th colspan="2" style="text-align: center;">Aksi</th>
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
                            <td><a href='DataBarang.php?op=delete&kode=$tampil[kode_barang]'onclick='return confirm('Yakin mau delete data?')'>Delete</td>
                            <td><a href='UbahData.php?op=edit&kode=$tampil[kode_barang]'>Update</a></td>
                        </tr>";
                            $no++;
                        }
                        ?>
                    </table>
                </div>
            </div>
            <a href="eksporCsv.php" type="button" class="btn btn-success">Eksport To CSV</a>
            <a href="eksporXml.php" type="button" class="btn btn-warning">Eksport To XML</a>
            <br>
            <br>
            <br>
            <?php
            $link = "DataJSON.json";
            $konten = file_get_contents($link);
            $data = json_decode($konten, true);
            ?>
            <div class="card">
                <div class="card-header">
                    Tampil Data JSON
                </div>
                <div class="card-body">
                    <table cellspacing='0px' align='center' class="table">
                        <tr>
                            <th>No</th>
                            <th>NRP</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                        </tr>
                        <tr>
                            <td><?php echo $data[0]["No"]; ?></td>
                            <td><?php echo $data[0]["NRP"]; ?></td>
                            <th><?php echo $data[0]["Nama"]; ?></th>
                            <th><?php echo $data[0]["Alamat"]; ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $data[1]["No"]; ?></td>
                            <td><?php echo $data[1]["NRP"]; ?></td>
                            <th><?php echo $data[1]["Nama"]; ?></th>
                            <th><?php echo $data[1]["Alamat"]; ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $data[2]["No"]; ?></td>
                            <td><?php echo $data[2]["NRP"]; ?></td>
                            <th><?php echo $data[2]["Nama"]; ?></th>
                            <th><?php echo $data[2]["Alamat"]; ?></th>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
            <br>
            <div class="card">
                <div class="card-header">
                    Tampil Data XML
                </div>
                <div class="card-body">
                    <table cellspacing='0px' align='center' class="table">
                        <tr>
                            <th>NRP</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                        </tr>
                        <?php
                        $link = "http://localhost/WebSemantik/DataXML.xml";
                        $temp = file_get_contents($link);
                        $xml = simplexml_load_string($temp);
                        foreach ($xml as $data) {
                        ?>
                            <tr>
                                <td><?= $data->NRP ?></td>
                                <td><?= $data->NAMA ?></td>
                                <th><?= $data->ALAMAT ?></th>
                                <th><?= $data->KOTA ?></th>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
    </section>

</body>

</html>