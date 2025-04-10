<?php
	require_once "model/ClassesModel.php";
	require_once "model/ProductModel.php";
	require_once "__cek-status.php";
	if($_SESSION['log']['akses']=='User') header('location:index');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
		<?php require_once "__load-user.php";?>
		<?php require_once "__load-barang.php";?>
		<script src="js/script-keluar-masuk.js"></script>
		<title>Barang Masuk - <?=$appName?></title>
    </head>
    <body class="sb-nav-fixed sb-sidenav-toggled">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
					<!-- Awal : Toast / Notification -->
					<div id="notif" style="position:absolute; top:0; right:0; z-index:99;"></div>
					<!-- Akhir : Toast -->
                    <div class="container-fluid">
                        <h2 class="mt-4">Barang Masuk</h2>
						<div class="card-header p-1">
							<!-- Button to Open the Modal -->
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formTambahMasuk">
							Input
							</button>
							<button type="button" class="btn btn-secondary ml-1" id="btnEdit" data-toggle="modal" data-target="#formEdit" disabled>
							Edit
							</button>
							<button type="button" class="btn btn-secondary ml-1" id="btnHapus" data-toggle="modal" data-target="#formHapus" disabled>
							Hapus
							</button>
						</div>
						<div class="card-body p-1">
							<div class="table-responsive" >
							<table id="tableInOut" width="100%"
								class="table table-bordered table-striped table-hover table-sm cell-border">
								<thead>
									<tr>
										<th>ID</th>
										<th>Tanggal</th>
										<th>Kode Barang</th>
										<th>Nama Barang</th>
										<th>Jumlah</th>
										<th>ID User</th>
										<th>Penerima</th>
										<th>Keterangan</th>
										<th>Waktu</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th colspan="4" style="text-align:right">Jumlah:</th>
										<th style="text-align:right"></th>
										<th colspan="3" style="text-align:left"></th>
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
    </body>
	<!-- The Modal : Tambah barang Masuk -->
	<?php include_once "__modal-tambah-masuk.php";?>

	<!-- Modal untuk Edit Barang -->
	<div class="modal fade" id="formEdit">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Edit Barang Masuk</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="idEdit" id="idEdit">
					Tanggal Masuk <br />
					<input type="text" class="form-control tanggal" style="max-width:110px" name="tanggalEdit" id="tanggalEdit" readonly><br />
					Nama Barang <br />
					<input type="hidden" class="form-control" name="listBarangEdit" id="listBarangEdit"><br />
					<input type="text" class="form-control" name="namaBrgEdit" id="namaBrgEdit" disabled><br />
					Jumlah <br />
					<input type="number" class="form-control" name="jumlahEdit" id="jumlahEdit" required><br />
					Penerima <br />
					<select name="listUserEdit" id="listUserEdit" class="form-control">
						<?php foreach($arrUser as $user){ ?>
							<option value="<?=$user->id_user;?>">
								<?=$user->nama_user;?>
							</option>
						<?php }	?>
					</select><br />
					Keterangan <br />
					<textarea class="form-control" id="ketEdit" name="ketEdit" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
					<button class="shadow btn btn-primary btn-block buttonload" id="btnEditProduct" onclick="editProductInOut('in')">
						Perbarui <i id="spinnerEdit"></i>
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal untuk Delete Barang -->
	<div class="modal fade" id="formHapus">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Hapus Barang Masuk</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="idHapus" id="idHapus">
					<input type="hidden" name="kodeBrgHapus" id="kodeBrgHapus">
					<input type="hidden" name="jumlahHapus" id="jumlahHapus">
					<p>Apakah anda yakin ingin menghapus <b><label id="namaBrgHapus"></label></b> ?</p>
					<button class="shadow btn btn-danger btn-block buttonload" id="btnDeleteProduct" name="hapus_barang_masuk" onclick="deleteProductInOut('in')">
						Hapus Barang <i id="spinnerDelete"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		const table = new DataTable('#tableInOut', {
			ajax: "data-masuk.php",
			language: {url : 'js/id.json'},
			responsive: true,
			processing: true,
			serverSide: true,
			select: true,
			columnDefs  : [
				{   className: "dt-head-center", targets: [ 1,3,4,6,7 ]},
				{   className: "dt-right", width: "70px", targets: [ 4 ]},
				{   className: "dt-center", width: "100px", targets: [ 1 ]},
				{   width: "150px", targets: [ 6 ]},
				{   width: "250px", targets: [ 3 ]},
				{   width: "300px", targets: [ 7 ]},
				{	targets: [0,2,5,8],	visible: false,	searchable: false}
			],
			footerCallback: function (row, data, start, end, display) {
				let api = this.api();
				// Remove the formatting to get integer data for summation
				let intVal = function (i) {
					return typeof i === 'string'
						? i.replace(/[\$,]/g, '') * 1
						: typeof i === 'number'
						? i
						: 0;
				};
				// Total over this page
				pageTotal = api
					.column(4, { page: 'current' })
					.data()
					.reduce((a, b) => intVal(a) + intVal(b), 0);
				// Update footer
				api.column(4).footer().innerHTML = pageTotal;
			}
		});
		table.on('click', 'tbody tr', (e) => {
			let classList = e.currentTarget.classList;
			if(classList.contains('selected')){
				classList.remove('selected');
				disableButton();
			}else{
				table.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
				classList.add('selected');
				enableButton();
			}
			var idx = table.row(this).index();
			var row = table.row(idx).data();
			var oData = table.rows('.selected').data();
			for (var i=0; i < oData.length ;i++){
				var id_masuk	= oData[i][0];
				var tanggal		= oData[i][1];
				var kode_brg	= oData[i][2];
				var nama_brg	= oData[i][3];
				var jumlah		= oData[i][4];
				var id_user		= oData[i][5];
				var nama_user	= oData[i][6];
				var keterangan	= oData[i][7];
				// Mengisi value Form Edit
				$("#idEdit").val(id_masuk);
				$("#tanggalEdit").val(tanggal);
				$("#listBarangEdit").val(kode_brg).change();
				$("#namaBrgEdit").val(nama_brg);
				$("#jumlahEdit").val(jumlah);
				$("#listUserEdit").val(id_user).change();
				$("#ketEdit").val(keterangan);

				// Mengisi value Form Hapus
				$("#idHapus").val(id_masuk);
				$("#kodeBrgHapus").val(kode_brg);
				$("#namaBrgHapus").text(nama_brg);
				$("#jumlahHapus").val(jumlah);
			}
		}).order([8,'desc'],[1,'desc'],[3,'asc']).draw();
		
		$(document).ready(function(){
			$("#formTambahMasuk").on('shown.bs.modal', function(){
				$(this).find('#listBarang').focus();
			});
			$("#formEdit").on('shown.bs.modal', function(){
				$(this).find('#listBarang').focus();
			});
			
			$(".tanggal").val($.datepicker.formatDate('dd-mm-yy', new Date()));
			$(".tanggal").datepicker({
				dateFormat	: "dd-mm-yy",
				maxDate		: new Date(),
				changeMonth	: true,
				changeYear	: true,
				beforeShow	: function(){
					$(".ui-datepicker").css('font-size',13);
				}
			});
		});
	</script>
</html>