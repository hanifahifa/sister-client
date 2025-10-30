<?php
// proses.php — diperbarui supaya cocok dengan client.php yang membuat $objek
include "client.php";

// Pastikan $objek ada
if (!isset($objek)) {
    die("Client SOAP belum diinisialisasi. Periksa client.php");
}

// Ambil aksi dengan aman (dari GET atau POST)
$aksi = isset($_REQUEST['aksi']) ? $_REQUEST['aksi'] : '';

if ($aksi === 'tambah') {
    // Ambil data dari form (POST)
    $id_jurusan   = isset($_POST['id_jurusan']) ? trim($_POST['id_jurusan']) : '';
    $nama_jurusan  = isset($_POST['nama_jurusan']) ? trim($_POST['nama_jurusan']) : '';
    $akreditasi = isset($_POST['akreditasi']) ? trim($_POST['akreditasi']) : '';
    $nama_fakultas = isset($_POST['nama_fakultas']) ? trim($_POST['nama_fakultas']) : '';

    // Siapkan array sesuai metode tambah_data($data) di client.php
    $data = [
        'id_jurusan'   => $id_jurusan,
        'nama_jurusan'  => $nama_jurusan,
        'akreditasi' => $akreditasi,
        'nama_fakultas' => $nama_fakultas,
    ];

    // Panggil method client (sesuai client.php sebelumnya)
    $objek->tambah_data($data);

    header("Location: index.php?page=daftar-server");
    exit;

} elseif ($aksi === 'hapus') {
    $id_jurusan = isset($_GET['id_jurusan']) ? trim($_GET['id_jurusan']) : '';
    if ($id_jurusan !== '') {
        $objek->hapus_data($id_jurusan);
    }
    header("Location: index.php?page=daftar-server");
    exit;

} elseif ($aksi === 'ubah' || $aksi === 'update') {
    // dari form ubah: aksi = 'ubah'
    $id_jurusan   = isset($_POST['id_jurusan']) ? trim($_POST['id_jurusan']) : '';
    $nama_jurusan  = isset($_POST['nama_jurusan']) ? trim($_POST['nama_jurusan']) : '';
    $akreditasi = isset($_POST['akreditasi']) ? trim($_POST['akreditasi']) : '';
    $nama_fakultas = isset($_POST['nama_fakultas']) ? trim($_POST['nama_fakultas']) : '';

    $data = [
        'id_jurusan'   => $id_jurusan,
        'nama_jurusan'  => $nama_jurusan,
        'akreditasi' => $akreditasi,
        'nama_fakultas' => $nama_fakultas,
    ];

    $objek->ubah_data($data);

    header("Location: index.php?page=daftar-server");
    exit;

} elseif ($aksi === 'sinkronisasi') {
    // memanggil sinkronisasi yang ada di client.php
    $objek->sinkronisasi();
    header("Location: index.php?page=daftar-client");
    exit;

} else {
    // aksi tidak dikenali
    header("Location: index.php");
    exit;
}
?>
