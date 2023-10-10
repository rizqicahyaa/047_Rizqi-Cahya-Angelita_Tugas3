<?php
session_start();
include "koneksi.php";
$kon = new Koneksi();

$user = @$_POST['tuser'];
$pass = @$_POST['tpass'];

$abc = $kon->kueri("SELECT user, nama FROM tabel_user WHERE user = '$user' AND pass = MD5('$pass')");
$jumlah = $kon-> jumlah_data(
	$abc);

if ($jumlah == 0) {
	echo "<script>alert('Login Sukses');</script>";
}else{
	$hasil = $kon->hasil_data($abc);
	$_SESSION['username']=$hasil['user'];
	$_SESSION['nama']=$hasil['nama'];
	echo "<script>alert('Welcome $hasil[nama]');</script>";
}
?>
<meta http-equiv="refresh" content="1;url=index.php"/>