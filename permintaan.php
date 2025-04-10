<?php
require_once "model/ClassesModel.php";
require_once "model/RequestModel.php";
require_once "__cek-status.php";
if ($_SESSION['log']['akses'] == 'User') header('location:permintaan-user');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once "__header.php"; ?>
	<?php require_once "__load-barang.php"; ?>
	<?php require_once "__load-user.php"; ?>
	<link href="css/bootstrap4-toggle.min.css" rel="stylesheet">

	<script src="js/bootstrap4-toggle.min.js"></script>
	<script src="js/script-keluar-masuk.js"></script>
	<script src="js/autocomplete-permintaan.js"></script>
	<title>Permohonan Barang - <?= $appName ?></title>
</head>

<body class="sb-nav-fixed sb-sidenav-toggled">
	<?php include_once "__navbar.php"; ?>
	<div id="layoutSidenav">
		<?php include_once "__side-menu.php"; ?>
		<div id="layoutSidenav_content">
			<main>
				<?php include_once "__permintaan-main.php"; ?>
			</main>
		</div>
	</div>
	<?php include_once "__script.php"; ?>
	<?php include_once "__script-report.php";?>
	<script src="js/script-permintaan.js"></script>
	<script src="js/moment.min.js"></script>
	<input type="hidden" id="sessIdUser" value="<?= $_SESSION['log']['id_user']; ?>"></input>
	<input type="hidden" id="sessNamaUser" value="<?= $_SESSION['log']['nama']; ?>"></input>
	<input type="hidden" id="sessAkses" value="<?= $_SESSION['log']['akses']; ?>"></input>
</body>
<div class="modal fade" id="formTambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Permohonan Barang</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<form method="post">
				<div class="modal-body ui-front">
					<div class="container mb-2">
						<div class="row">
							<div class="col">
								<input type="hidden" name="kode_brg" id="kode_brg">
								<div class="row mb-3">
									<div class="col">
										<label class="mb-2">Tanggal Permohonan</label>
										<input type="text" class="form-control tanggal" style="max-width:110px" name="tanggal" id="tanggal" readonly>
									</div>
									<div class="col">
										<label class="mb-2">Prioritas</label>
										<select name="prioritas" id="prioritas" class="form-control" style="width:100px">
											<option value="1">Ya</option>
											<option value="0">Tidak</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<div class="input-group">
												<input type="text" class="form-control" style="max-width:250px; height:38px" name="nama" id="nama" aria-label="nama" placeholder="Input Nama Barang">
												<div class="input-group-append">
													<span class="input-group-text">Stok</span>
													<span class="input-group-text" id="stok">0</span>
												</div>
											</div>
										</div>
										<input type="number" class="form-control mb-3" style="max-width:150px" name="jumlah" id="jumlah" placeholder="Jumlah" required>
										<textarea class="form-control" id="ket" name="ket" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea>
									</div>
								</div>
								<div class="row mb-0 mt-5 align-items-end">
									<div class="col-8">
										<button type="button" class="shadow btn btn-success" onclick="inputToList()">Tambah</button>
										<button type="button" class="shadow btn btn-primary ml-1 buttonload" id="btnAddReq" onclick="inputToDB()">
											Kirim <i id="spinnerAdd" class=""></i>
										</button>
										<button type="button" class="shadow btn btn-danger ml-1" onclick="resetListPermintaan()">Reset</button>
									</div>
									<div class="col-4 align-self-end">
										<strong>Jumlah Item : <label id="jmlItem">0</label></strong>
									</div>
								</div>
							</div>
							<div class="col">
								<h4 style="line-height: 0.5;">Pemohon: </h4>
								<hr class="mb-2" />
								<?php foreach ($arrUser as $user) { ?>
									<div class="form-check form-switch mb-1">
										<input class="form-check-input" data-toggle="toggle" data-style="ios" data-size="xs" type="checkbox" id="<?= $user->id_user . "_" . $user->nama_user; ?>">
										<label class="form-check-label"><?= $user->nama_user ?></label>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="table-responsive">
						<table id="tListPermintaan" class="table table-bordered table-striped table-hover table-sm cell-border" width="100%" cellspacing="0">
							<thead style="text-align:center">
								<tr>
									<th>Tanggal</th>
									<th>Nama Barang</th>
									<th>Jumlah</th>
									<th>Pemohon</th>
									<th>Keterangan</th>
									<th>Prioritas</th>
								</tr>
							</thead>
							<tbody>
								<tr></tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="formEdit">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Permohonan</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body ui-front">
				<h4 class="text-center"><label id="labelNamaBrg" style="color:#C70039"></label></h4>
				<div class="row">
					<div class="col-sm">
						<input type="hidden" name="idEdit" id="idEdit">
						<input type="hidden" id="kodeBrgEdit">
						Tanggal
						<input type="text" class="form-control tanggal w-75" id="tanggalEdit" readonly><br />
					</div>
					<div class="col-sm">
						Prioritas
						<select id="prioritasEdit" class="form-control w-75">
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select>
					</div>
					<div class="col-sm">
						Jumlah
						<input type="number" class="form-control w-75 p-3" id="jumlahEdit" required><br />
					</div>
				</div>
				<div class="form-row">
					<div class="col-lg">
						Pemohon<br />
						<input type="hidden" id="idUserEdit">
						<input type="text" class="form-control w-75 p-3" id="userNameEdit" disabled><br />
						Keterangan
						<textarea class="form-control" id="ketEdit" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br />
					</div>
				</div>
				<button class="shadow btn btn-block btn-primary buttonload" id="btnEditRequest" onclick="editRequest()">
					Perbarui <i id="spinnerEdit"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="formHapus">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Hapus Permohonan Barang</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="idHapus">
				<input type="hidden" id="kodeBrgHapus">
				<p>Yakin ingin menghapus permintaan
					<strong><label id="jmlHapus"></label></strong> buah
					<strong><label id="namaBrgHapus"></label></strong> atas nama
					<strong><label id="namaHapus"></label></strong> ?
				</p>
				<button class="shadow btn btn-danger btn-block buttonload" id="btnDeleteRequest" onclick="deleteRequest()">
					Hapus <i id="spinnerDelete"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="formDeleteProductRequest">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Hapus Permohonan Barang</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="prodReqID">
				<p>Yakin ingin menghapus permintaan
					<strong><label id="qtyProdReq"></label></strong> buah
					<strong><label id="prodNameDelete"></label></strong> ?
				</p>
				<button class="shadow btn btn-danger btn-block buttonload" id="btnDeleteProductRequest" onclick="deleteProductRequest()">
					Hapus <i id="spinnerProductDelete"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<?php include_once "__modal-ambil-langsung.php"; ?>

</html>