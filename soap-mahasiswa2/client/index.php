<?php
include "client.php";
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>SOAP Jurusan</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>

<body>
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="#">SOAP</a>
			<ul class="nav">
				<li><a href="?page=home"><i class="icon-home"></i> Home</a></li>
				<li><a href="?page=tambah"><i class="icon-plus-sign"></i> Tambah Data</a></li>
				<li><a href="?page=daftar-server"><i class="icon-list"></i> Data Server</a></li>
				<li><a href="?page=daftar-client"><i class="icon-list"></i> Data Client</a></li>
			</ul>
		</div>
	</div>

	<div class="container">
		<fieldset>
			<?php if ($page == 'tambah') { ?>
				<legend>Tambah Data</legend>
				<div class="row-fluid">
					<div class="span8 alert alert-info">
						<form class="form-horizontal" name="form1" method="POST" action="proses.php" novalidate>
							<input type="hidden" name="aksi" value="tambah" />

							<div class="control-group">
								<label class="control-label" for="id_jurusan">ID</label>
								<div class="controls">
									<input type="text" name="id_jurusan" class="input-small" placeholder="ID" required>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="nama_jurusan">Nama Jurusan</label>
								<div class="controls">
									<input type="text" name="nama_jurusan" class="input-medium" placeholder="Nama Jurusan"
										required>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="akreditasi">Akreditasi</label>
								<div class="controls">
									<input type="text" name="akreditasi" class="input-medium" placeholder="Akreditasi" required>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="nama_fakultas">Nama Fakultas</label>
								<div class="controls">
									<input type="text" name="nama_fakultas" class="input-medium" placeholder="Nama Fakultas" required>
								</div>
							</div>

											<div class="control-group">
								<div class="controls">
									<button type="submit" name="simpan" class="btn btn-primary">
										<i class="icon-ok icon-white"></i> Simpan
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>

			<?php } elseif ($page == 'ubah') {
				$r = $objek->tampil_data($_GET['id_jurusan']);
				?>
				<legend>Ubah Data</legend>
				<form name="form1" method="post" action="proses.php" class="form-horizontal">
					<input type="hidden" name="aksi" value="ubah" />
					<input type="hidden" name="id_jurusan" value="<?= $r['id_jurusan'] ?>" />

					<div class="control-group">
						<label class="control-label">id_jurusan</label>
						<div class="controls">
							<input type="text" disabled class="input-small" value="<?= $r['id_jurusan'] ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Nama Jurusan</label>
						<div class="controls">
							<input type="text" name="nama_jurusan" class="input-medium" value="<?= $r['nama_jurusan'] ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Akreditasi</label>
						<div class="controls">
							<input type="text" name="akreditasi" class="input-medium" value="<?= $r['akreditasi'] ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Nama Fakultas</label>
						<div class="controls">
							<input type="text" name="nama_fakultas" class="input-medium" value="<?= $r['nama_fakultas'] ?>">
						</div>
					</div>

					

					<div class="control-group">
						<div class="controls">
							<button type="submit" name="ubah" class="btn btn-primary">
								<i class="icon-ok icon-white"></i> Ubah
							</button>
						</div>
					</div>
				</form>
				<?php unset($r); ?>

			<?php } elseif ($page == 'daftar-server') { ?>
				<legend>Daftar Data Server</legend>
				<form name="form1" method="post" action="proses.php" class="form-inline">
					<input type="hidden" name="aksi" value="sinkronisasi" />
					<button type="submit" name="sinkronisasi" class="btn btn-primary"
						onclick="return confirm('Apakah Anda akan melakukan proses sinkronisasi data?')">
						<i class="icon-ok icon-white"></i> Sinkronisasi Data
					</button>
				</form>

				<table class="table table-hover">
					<tr>
						<th>No</th>
						<th>id_jurusan</th>
						<th>Nama Jurusan</th>
						<th>Akreditasi</th>
						<th>Nama Fakultas</th>
						<th>Ubah</th>
						<th>Hapus</th>
					</tr>
					<?php
					$no = 1;
					$data_array = $objek->tampil_semua_data();
					foreach ($data_array as $r) {
						?>
						<tr>
							<td><?= $no ?></td>
							<td><?= $r['id_jurusan'] ?></td>
							<td><?= $r['nama_jurusan'] ?></td>
							<td><?= $r['akreditasi'] ?></td>
							<td><?= $r['nama_fakultas'] ?></td>
							<td><a href="?page=ubah&id_jurusan=<?= $r['id_jurusan'] ?>" class="btn btn-success"><i
										class="icon-pencil"></i></a></td>
							<td><a href="proses.php?aksi=hapus&id_jurusan=<?= $r['id_jurusan'] ?>" class="btn btn-danger"
									onclick="return confirm('Yakin hapus data ini?')"><i class="icon-remove"></i></a></td>
						</tr>
						<?php $no++;
					}
					unset($data_array, $r, $no);
					?>
				</table>

			<?php } elseif ($page == 'daftar-client') { ?>
				<legend>Daftar Data Client</legend>
				<table class="table table-hover">
					<tr>
						<th>No</th>
						<th>id_jurusan</th>
						<th>Nama Jurusan</th>
						<th>Akreditasi</th>
						<th>Nama Fakultas</th>
					</tr>
					<?php
					$no = 1;
					$data_array = $objek->daftar_mhs_client();
					foreach ($data_array as $r) {
						?>
						<tr>
							<td><?= $no ?></td>
							<td><?= $r['id_jurusan'] ?></td>
							<td><?= $r['nama_jurusan'] ?></td>
							<td><?= $r['akreditasi'] ?></td>
							<td><?= $r['nama_fakultas'] ?></td>
						</tr>
						<?php $no++;
					}
					unset($data_array, $r, $no);
					?>
				</table>

			<?php } else { ?>
				<legend>Home</legend>
				<p>
					Aplikasi sederhana ini menggunakan web service SOAP (Simple Object Access Protocol)
					dengan format data XML (Extensible Markup Language).
				</p>
			<?php } ?>
		</fieldset>
	</div>

	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/tooltip.js"></script>
	<script src="js/jqBootstrapValidation.js"></script>
	<script>
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); });
	</script>
</body>

</html>