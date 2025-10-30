<?php
error_reporting(1); // error ditampilkan
require_once('nusoap.php');

class Client
{
	private $host = "localhost";
	private $dbname = "serviceclient";
	private $api;

	// koneksi ke database mysql di client
	private $driver = "mysql";
	private $user = "root";
	private $password = "";
	private $port = "3306";

	/*
	// koneksi ke database postgresql di client
	private $driver="pgsql";
	private $user="postgres";
	private $password="postgres";
	private $port="5432";
	*/

	// function yang pertama kali di-load saat class dipanggil
	public function __construct($api)
	{	// buat objek baru dari class nusoap client
		$this->api = new nusoap_client($api, true);
		// koneksi database lokal client
		try {
			if ($this->driver == 'mysql') {
				$this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
			} elseif ($this->driver == 'pgsql') {
				$this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->password");
			}
		} catch (PDOException $e) {
			echo "Koneksi gagal";
		}
		unset($api);
	}

	public function tampil_semua_data()
	{	// memanggil method/fungsi yang ada di server dan dimasukkan ke variable $data
		$data = $this->api->call('tampil_semua_data');
		// mengembalikan data
		return $data;
		// menghapus variable dari memory
		unset($data);
	}

	public function tampil_data($nim)
	{
		$data = $this->api->call('tampil_data', array($nim));
		return $data;
		unset($nim, $data);
	}

	public function tambah_data($data)
	{
		$this->api->call('tambah_data', array($data));
		unset($data);
	}

	public function ubah_data($data)
	{
		$this->api->call('ubah_data', array($data));
		unset($data);
	}

	public function hapus_data($nim)
	{
		$this->api->call('hapus_data', array($nim));
		unset($nim);
	}

	public function sinkronisasi()
	{	// query ke lokal database client
		$query = $this->conn->prepare("delete from mahasiswa");
		$query->execute();
		// menghapus query dari memory
		$query->closeCursor();
		// memanggil method/fungsi yang ada di server dan dimasukkan ke variabel $data
		$data = $this->api->call('tampil_semua_data');
		foreach ($data as $r) {	// query ke lokal database client
			$query = $this->conn->prepare("insert into mahasiswa (nim,nama,no_hp,email,alamat) values (?,?,?,?,?)");
			$query->execute(array($r['nim'], $r['nama'], $r['no_hp'], $r['email'], $r['alamat']));
			$query->closeCursor();
		}
		unset($data, $r);
	}

	public function daftar_mhs_client()
	{
		$query = $this->conn->prepare("select nim,nama,no_hp,email,alamat from mahasiswa order by nim");
		$query->execute();
		// mengambil banyak record data dengan fetchAll()
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		// mengembalikan data
		return $data;
		// menghapus variable dari memory 
		$query->closeCursor();
		unset($data);
		// atau dapat menggunakan
		// $query=null;
		// $data=null;
	}

	// function yang terakhir kali di-load saat class dipanggil
	public function __destruct()
	{	// menghapus variable $api dari memory
		unset($this->api);
	}
}

$api = "http://192.168.56.2/wsdl-mahasiswa/server/server.php?wsdl";

// buat objek baru dari class Client
$objek = new Client($api);