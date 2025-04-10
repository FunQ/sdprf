<?php
	require_once "model/ClassesModel.php";
	require_once "model/ProductModel.php";
	require_once "__cek-status.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
		<?php require_once "__load-barang.php";?>
		<script src="js/script-keluar-masuk.js"></script>
		<title>Barang Keluar - <?=$appName?></title>
    </head>
    <body class="sb-nav-fixed">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
					<!-- Awal : Toast / Notification -->
					<div id="notif" style="position:absolute; top:0; right:0; z-index:99;"></div>
					<!-- Akhir : Toast -->
                    <div class="container-fluid">
                        <h2 class="mt-4">Pengambilan Barang</h2>
						<div class="card-header p-1">
							<!-- Button to Open the Modal -->
							<button type="button" class="shadow btn btn-primary" data-toggle="modal" data-target="#formTambah">
							Ambil<br/>Beberapa Barang
							</button>
							<button type="button" class="shadow btn btn-secondary ml-1" id="btnAmbil" data-toggle="modal" data-target="#formAmbil" disabled>
							Ambil<br/>Langsung
							</button>
						</div>
						<div class="card-body p-1">
							<div class="table-responsive" >
								<table id="tableBarang" width="80%" cellspacing="0"
										class="table table-bordered table-striped table-hover table-sm cell-border">
									<thead>
										<tr>
											<th>Kode Barang</th>
											<th>Nama Barang</th>
											<th>Stok</th>
											<th>Permintaan</th>
											<th>Keterangan</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th colspan="2" style="text-align:right">Jumlah:</th>
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
    </body>
	<!-- The Modal : Tambah barang Keluar -->
	<div class="modal fade" id="formTambah">
		<div class="modal-dialog modal-lg">
		  <div class="modal-content">
		  
			<!-- Modal Header -->
			<div class="modal-header">
			  <h4 class="modal-title">Barang Keluar</h4>
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<!-- Modal body -->
			<form method="post">
			<div class="modal-body">
				<div class="col-md-6">
					<div class="form-group">
					<input type="text" class="form-control tanggal" style="max-width:110px" name="tanggal" id="tanggal" readonly><br />
						<select name="listBarang" id="listBarang" class="form-control">
							<?php foreach($arrBrgKeluar as $barang){ ?>
								<option value="<?=$barang->kode_brg;?>:<?=$barang->jumlah?>">
									<?=$barang->nama_brg;?> : <?=$barang->jumlah;?>
								</option>
							<?php }	?>
						</select>
					</div>
					<input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" required><br />
					<input type="hidden" class="form-control" name="listUser" id="listUser" value="<?=$_SESSION['log']['id_user']?>" disabled>
					<textarea class="form-control" id="ket" name="ket" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
					<div class="form-group" style="width: 200%">
						<button type="button" class="shadow btn btn-primary" onclick="inputToList('keluarUser')">Tambahkan</button>
						<button type="button" class="shadow btn btn-success ml-1" id="btnAddOut" onclick="inputToDBKeluar()">
							Kirim <i id="spinnerAdd" class=""></i>
						</button>
						<button type="button" class="shadow btn btn-danger ml-1" onclick="resetInput()">Reset Inputan</button>
						<div class="float-lg-right"><strong>Jumlah Item : <label id="jmlItem">0</label></strong></div>
					</div>
				</div>
				<!-- Coba pakai table array -->
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover table-sm" id="tableKeluarMasuk" width="100%" cellspacing="0">
						<thead style="text-align:center">
							<tr>
								<th>Tanggal</th>
								<th>Nama Barang</th>
								<th>Jumlah</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			</form>
		  </div>
		</div>
	</div>

	<!-- Modal untuk Ambil Langsung -->
	<?php include_once "__modal-ambil-langsung.php";?>
	
	<script>
		const table = new DataTable('#tableBarang', {
			ajax: "data-barang.php",
			language: {url : 'js/id.json'},
			responsive: true,
			processing: true,
			serverSide: true,
			select: true,
			columnDefs : [
				{   className: "dt-head-center", targets: [ 1,2,3]},
				{   className: "dt-right", width: "100px", targets: [ 2,3 ]},
				{   width: "30%", targets: [ 1 ]},
				{// kolom ID
					target: 0,
					visible: false,
					searchable: false
				}
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
				pageTotal = api
					.column(2, { page: 'current' })
					.data()
					.reduce((a, b) => intVal(a) + intVal(b), 0);
				pageTotalReq = api
					.column(3, { page: 'current' })
					.data()
					.reduce((a, b) => intVal(a) + intVal(b), 0);
				
				// Update footer
				api.column(2).footer().innerHTML = pageTotal;
				api.column(3).footer().innerHTML = pageTotalReq;
			}
		});
		table.order([1,'asc']);
		
		var kodeBrgAmbil= 0;
		var namaBrgAmbil= "";
		var stokLama	= 0;
		var ketAmbil		= "";
		table.on('click', 'tbody tr', (e) => {
			let classList = e.currentTarget.classList;
			if(classList.contains('selected')){
				classList.remove('selected');
				disableTakeButton();
			}else{
				table.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
				classList.add('selected');
				enableTakeButton();
			}
			var idx = table.row(this).index();
			var row = table.row(idx).data();
			var oData = table.rows('.selected').data();
			for (var i=0; i < oData.length ;i++){
				kodeBrgAmbil= oData[i][0];
				namaBrgAmbil= oData[i][1];
				stokLama	= oData[i][2];
				ketAmbil	= oData[i][4];
				// Mengisi value Form Ambil Langsung
				$("#kodeBrgAmbil").val(kodeBrgAmbil);
				$("#namaBrgAmbil").text(namaBrgAmbil);
				$("#stokLama").val(stokLama);
				$("#ketAmbil").val(ketAmbil);
			}
		});
		
		$(document).ready(function(){
			disableTakeButton();
			$("#formTambah").on('shown.bs.modal', function(){
				$(this).find('#listBarang').focus();
			});
			$("#formAmbil").on('shown.bs.modal', function(){
				$(this).find('#jumlahAmbil').focus();
			});
			$("#tanggalAmbil").val($.datepicker.formatDate('dd-mm-yy', new Date()));
			$("#tanggal").val($.datepicker.formatDate('dd-mm-yy', new Date()));
			$("#tanggal").datepicker({
				dateFormat	: "dd-mm-yy",
				maxDate		: new Date(),
				changeMonth	: true,
				changeYear	: true,
				beforeShow	: function(){
					$(".ui-datepicker").css('font-size',12);
				}
			});
		});
	</script>
</html>