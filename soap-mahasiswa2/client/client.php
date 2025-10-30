<?php
error_reporting(1); // error ditampilkan
class Client
{
	private $host = "localhost";
	private $dbname = "serviceclient";
	private $conn, $options, $api;

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
	public function __construct($uri, $location)
	{	// set uri SOAP server
		$this->options = array('uri' => $uri, 'location' => $location);
		// buat objek baru dari class SOAP Client
		$this->api = new SoapClient(NULL, $this->options);
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
		unset($uri, $location);
	}

	// function untuk menghapus selain huruf dan angka
	public function filter($data)
	{
		$data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
		return $data;
		unset($data);
	}

	public function tampil_semua_data()
	{
		$data = $this->api->tampil_semua_data();
		return $data;
		unset($data);
	}

	public function tampil_data($id_jurusan)
	{
		$id_jurusan = $this->filter($id_jurusan);
		$data = $this->api->tampil_data($id_jurusan);
		return $data;
		unset($id_jurusan, $data);
	}

	public function tambah_data($data)
	{
		$this->api->tambah_data($data);
		unset($data);
	}

	public function ubah_data($data)
	{
		$this->api->ubah_data($data);
		unset($data);
	}

	public function hapus_data($id_jurusan)
	{
		$this->api->hapus_data($id_jurusan);
		unset($id_jurusan);
	}

	public function sinkronisasi()
	{	// query ke lokal database client
		$query = $this->conn->prepare("delete from jurusan");
		$query->execute();
		// menghapus query dari memory
		$query->closeCursor();
		// memanggil method/fungsi yang ada di server dan dimasukkan ke variabel $data
		$data = $this->api->tampil_semua_data();
		foreach ($data as $r) {	// query ke lokal database client
			$query = $this->conn->prepare("insert into jurusan (id_jurusan,nama_jurusan,akreditasi,nama_fakultas) values (?,?,?,?)");
			$query->execute(
				array(
					isset($r['id_jurusan']) ? $r['id_jurusan'] : '',
					isset($r['nama_jurusan']) ? $r['nama_jurusan'] : '',
					isset($r['akreditasi']) ? $r['akreditasi'] : '',
					isset($r['nama_fakultas']) ? $r['nama_fakultas'] : ''
				)
			);
			$query->closeCursor();

		}
		unset($data, $r);
	}

	public function daftar_mhs_client()
	{
		$query = $this->conn->prepare("select id_jurusan, nama_jurusan, akreditasi, nama_fakultas from jurusan order by id_jurusan");
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
	{
		unset($this->options, $this->api);
	}
}

// uri dan location server
$uri = 'http://192.168.56.2';
$location = $uri . '/soap-mahasiswa2/server/server.php';
// buat objek baru dari class Client
$objek = new Client($uri, $location);
?>















