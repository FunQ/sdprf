<?php
	require_once "model/ClassesModel.php";
	require_once "model/RequestModel.php";
	require_once "__cek-status.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
		<?php require_once "__load-barang.php";?>
		<?php require_once "__load-user.php";?>
		<script src="js/script-keluar-masuk.js"></script>
		<script src="js/autocomplete-permintaan.js"></script>
		<title>Permohonan Barang - <?=$appName?></title>
    </head>
    <body class="sb-nav-fixed">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
					<?php include_once "__permintaan-main.php";?>
                </main>
            </div>
        </div>
		<?php include_once "__script.php";?>
		<?php include_once "__script-report.php";?>
		<script src="js/script-permintaan.js"></script>
		<script src="js/moment.min.js"></script>
		<input type="hidden" id="sessIdUser" value="<?=$_SESSION['log']['id_user'];?>"></input>
		<input type="hidden" id="sessAkses" value="<?=$_SESSION['log']['akses'];?>"></input>
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
				<input type="hidden" name="listUser" id="listUser" value="<?=$_SESSION['log']['id_user'];?>">
				<input type="hidden" name="kode_brg" id="kode_brg">
				<div class="form-row" style="max-width:300px;">
					<div class="col-md-6">
						Tanggal Permohonan
						<input	type="text" class="form-control tanggal" style="max-width:110px" name="tanggal" id="tanggal" readonly>
					</div>
					<div class="col-md-6">
						Prioritas
						<select name="prioritas" id="prioritas" class="form-control" style="width:100px">
							<option value="0">Tidak</option>
							<option value="1">Ya</option>
						</select><br />
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6">
						<div class="form-group" style="width: 125%">
							<div class="input-group">
								<input type="text" class="form-control" style="width:300px; height:38px" name="nama" id="nama" aria-label="Stok" placeholder="Input Nama Barang">
								<div class="input-group-append">
									<span class="input-group-text">Stok</span>
									<span class="input-group-text" id="stok">0</span>
								</div>
							</div>
						</div>
						<input type="number" class="form-control" style="max-width:150px" name="jumlah" id="jumlah" placeholder="Jumlah" required><br />
						<textarea class="form-control" id="ket" name="ket" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
						<div class="form-group" style="width: 200%">
							<button type="button" class="shadow btn btn-success" onclick="inputToListUser()">Tambah</button>
							<button type="button" class="shadow btn btn-primary ml-1" id="btnAddReq" onclick="inputToDBUser()">
								Kirim <i id="spinnerAdd" class=""></i>
							</button>
							<button type="button" class="shadow btn btn-danger ml-1" onclick="resetListPermintaan()">Reset</button>
							<div class="float-lg-right"><strong>Jumlah Item : <label id="jmlItem">0</label></strong></div>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table id="tListPermintaan" 
						class="table table-bordered table-striped table-hover table-sm cell-border"
						width="100%" cellspacing="0">
						<thead style="text-align:center">
							<tr>
								<th>Tanggal</th>
								<th>Nama Barang</th>
								<th>Jumlah</th>
								<th>Keterangan</th>
								<th>Prioritas</th>
							</tr>
						</thead>
						<tbody><tr></tr></tbody>
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
				<div class="modal-body">
					<input type="hidden" name="idEdit" id="idEdit">
					<input type="hidden" name="kodeBrgEdit" id="kodeBrgEdit">
					<input type="hidden" name="idUserEdit" id="idUserEdit">
					<h5><label id="labelNamaBrg"></label></h5>
					<div class="form-row" style="max-width:300px;">
						<div class="col-md-6">
							Tanggal
							<input	type="text" class="form-control tanggal" style="max-width:110px" id="tanggalEdit" readonly><br />
						</div>
						<div class="col-md-6">
							Prioritas
							<select name="prioritasEdit" id="prioritasEdit" class="form-control" style="width:100px">
								<option value="0">Tidak</option>
								<option value="1">Ya</option>
							</select><br />
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-6" style="max-width:150px">
							Jumlah
							<input type="number" class="form-control" style="max-width:100px" id="jumlahEdit" required><br />
						</div>
						<div class="col-md-6" style="flex:100;max-width:100%;">
							Keterangan
							<textarea class="form-control" id="ketEdit" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
						</div>
					</div>
					<button class="shadow btn btn-primary btn-block" id="btnEditRequest" onclick="editRequest()">
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
    				<h4 class="modal-title">Hapus Permohonan</h4>
    				<button type="button" class="close" data-dismiss="modal">&times;</button>
    			</div>
    			<div class="modal-body">
    				<input type="hidden" name="idHapus" id="idHapus">
					<input type="hidden" name="kodeBrgHapus" id="kodeBrgHapus">
					<input type="hidden" name="jmlHapus" id="jmlHapus">
    				<p>Yakin ingin menghapus permintaan <b><label id="namaBrgHapus"></label></b> ?</p>
    				<button class="shadow btn btn-danger btn-block buttonload" id="btnDeleteRequest" onclick="deleteRequest()">
						Hapus <i id="spinnerDelete"></i>
					</button>
    			</div>
			</div>
		</div>
	</div>
	<?php include_once "__modal-ambil-langsung.php";?>
</html>