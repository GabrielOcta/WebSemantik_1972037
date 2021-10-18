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
if (isset($_POST['submit'])) {
	$kode = filter_input(INPUT_POST, 'kode');
	$nama = filter_input(INPUT_POST, 'nama');
	$jumlah = filter_input(INPUT_POST, 'jumlah');
	$harga = filter_input(INPUT_POST, 'harga');
	$sendData = array('kode_barang' => $kode, 'nama_barang' => $nama, 'jumlah' => $jumlah, 'harga' => $harga);
	$sql   = utility::curl_post(ApiService::Update_Barang_Url, $sendData);
	$q1     = json_decode($sql);
	if ($q1) {
		header('location: DataBarang.php');
	} else {
		echo "Error: ";
	}
}
?>