<?php
include "client.php";

if ($_POST['aksi']=='login')
{	$data = array("id_pengguna"=>$_POST['id_pengguna'],
				  "pin"=>$_POST['pin'],
				  "aksi"=>$_POST['aksi']);		
	$data2 = $abc->login($data);
	//echo "<pre>";
	//print_r($data2); //cek $data2
	//echo "</pre>";

	if (isset($data2->jwt)) {
		setcookie('jwt',$data2->jwt,time()+3600); // hilang dalam 1 jam
		setcookie('id_pengguna',$data2->id_pengguna,time()+3600);
		setcookie('nama',$data2->nama,time()+3600);
		header('location:index.php?page=data-server'); 
	} else {
		header('location:index.php?page=login'); 
	}	
} else if ($_POST['aksi']=='tambah')
{	$data = array("nim"=>$_POST['nim'],
				  "nama"=>$_POST['nama'],
				  "jwt"=>$_POST['jwt'],
				  "aksi"=>$_POST['aksi']);		
	$abc->tambah_data($data);
	header('location:index.php?page=data-server'); 
} else if ($_POST['aksi']=='ubah')
{	$data = array("nim"=>$_POST['nim'],
				  "nama"=>$_POST['nama'],
				  "jwt"=>$_POST['jwt'],
				  "aksi"=>$_POST['aksi']);
	$abc->ubah_data($data);
	header('location:index.php?page=data-server'); 
} else if ($_GET['aksi']=='hapus')
{	$data = array("nim"=>$_GET['nim'],
				  "jwt"=>$_GET['jwt'],
				  "aksi"=>$_GET['aksi']);
	$abc->hapus_data($data);
	header('location:index.php?page=data-server'); 
} else if ($_POST['aksi']=='sinkronisasi')
{	$abc->sinkronisasi($_COOKIE['jwt']);
	//echo $_COOKIE['jwt'];
	header('location:index.php?page=data-client'); 
} else if ($_GET['aksi']=='logout')
{	setcookie('jwt','',time()-3600); 
	setcookie('id_pengguna','',time()-3600);
	setcookie('nama','',time()-3600);
	header('location:index.php?page=login'); 
} 
unset($abc,$data,$data2);
?>
