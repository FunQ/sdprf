<?php
	require_once "model/ClassesModel.php";
	require_once "model/ProductModel.php";
	require_once "__cek-status.php";
	if(isset($_SESSION['log']) && ($_SESSION['log']['akses']=='User')) header('location:keluar-user');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
        <?php require_once "__load-user.php";?>
		<?php require_once "__load-barang.php";?>
		<script src="js/script-keluar-masuk.js"></script>
		<script src="js/autocomplete.js"></script>
		<title><?=$appName?></title>
    </head>
    <body class="sb-nav-fixed sb-sidenav-toggled">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content" aria-live="polite" aria-atomic="true" style="position: relative">
                <main>
					<!-- Awal : Toast / Notification -->
					<div id="notif" style="position:absolute; top:0; right:0; z-index:99;"></div>
					<!-- Akhir : Toast -->
                    <div class="container-fluid">
                        <h2 class="mt-4">Stok Barang</h2>
						<div class="card-header p-1">
							<!-- Button to Open the Modal -->
							<button type="button" class="shadow btn btn-primary mr-1 mb-2" id="btnModal" data-toggle="modal" data-target="#formTambah">
								Tambah<br/>Barang Baru
							</button>
							<button type="button" class="shadow btn btn-secondary mr-1 mb-2" id="btnEdit" data-toggle="modal" data-target="#formEdit" disabled>
								Edit<br/>Barang
							</button>
							<button type="button" class="shadow btn btn-secondary mr-1 mb-2" id="btnHapus" data-toggle="modal" data-target="#formHapus" disabled>
								Hapus<br/>Barang
							</button>
							<button type="button" class="shadow btn btn-secondary mr-1 mb-2" id="btnAmbil" data-toggle="modal" data-target="#formAmbil" disabled>
								Ambil<br/>Langsung
							</button>
							<button type="button" class="shadow btn btn-info mr-1 mb-2" id="btnTambahMasuk" data-toggle="modal" data-target="#formTambahMasuk">
								Masukkan<br/>Barang
							</button>
						</div>
						<div class="card-body p-1">
							<div class="table-responsive">
								<table id="tableBarang" width="80%" cellspacing="0"
									class="table table-bordered table-striped table-hover table-sm cell-border">
									<thead style="text-align:center">
										<tr>
											<th>Waktu</th>
											<th>Kode Barang</th>
											<th>Nama Barang</th>
											<th>Stok</th>
											<th>Permintaan</th>
											<th>Keterangan</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th colspan="3" style="text-align:right">Jumlah:</th>
											<th style="text-align:right"></th>
											<th style="text-align:right"></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
                    </div>
                </main>
                
            </div>
        </div>
		<?php include_once "__script.php";?>
		<script src="js/script-barang.js"></script>
    </body>
	<div class="modal fade" id="formTambah">
		<div class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Tambah Barang Baru</h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body ui-front">
				<input type="hidden" class="form-control" name="kode_brg" id="kode_brg"><br />
				<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Barang" required><br />
				<textarea class="form-control" id="ket_brg" name="ket_brg" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
				<button class="shadow btn btn-primary btn-block buttonload" name="barang_baru" id="btnTambahBarang" onclick="inputToTable('add');">
					Tambah Barang <i id="spinnerAdd" class=""></i>
				</button>
			</div>
		  </div>
		</div>
	</div>
	<div class="modal fade" id="formEdit">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Edit Barang</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					Nama Barang <br />
					<input type="hidden" name="kodeBrgEdit" id="kodeBrgEdit">
					<input type="text" class="form-control" name="namaBrgEdit" id="namaBrgEdit"><br />
					Keterangan <br />
					<textarea class="form-control" id="ketBrgEdit" name="ketBrgEdit" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
					<button class="shadow btn btn-primary btn-block" name="edit_barang" id="btnEditBarang" onclick="inputToTable('edit');">
						Perbarui <i id="spinnerEdit" class=""></i>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="formHapus">
		<div class="modal-dialog">
			<div class="modal-content">
			
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Hapus Barang</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<form method="post">
			<div class="modal-body">
				<input type="hidden" name="kodeBrgHapus" id="kodeBrgHapus">
				<input type="hidden" name="jumlahBrgHapus" id="jumlahBrgHapus">
				<p>Apakah anda yakin ingin menghapus <b><label id="namaBrgHapus"></label></b> ?</p>
				<button class="shadow btn btn-danger btn-block buttonload" id="btnDeleteProduct" onclick="deleteProduct()">
					Hapus Barang <i id="spinnerDelete" class=""></i>
				</button>
			</div>
			</form>
			</div>
		</div>
	</div>

	<?php include_once "__modal-tambah-masuk.php";?>
	<?php include_once "__modal-ambil-langsung.php";?>
</html>