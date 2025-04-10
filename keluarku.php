<?php
	session_start();
	require_once "model/__connection.php";
	require_once "__cek-status.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
		<title>Pengambilan Saya - <?=$appName?></title>
    </head>
    <body class="sb-nav-fixed <?=$_SESSION['log']['akses']=='User' ? '' : 'sb-sidenav-toggled';?>">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h2 class="mt-4">Pengambilan Saya</h2>
						<div class="card-body p-1">
							<div class="table-responsive" >
							<table id="tableKeluar" width="80%" cellspacing="0"
								class="table table-bordered table-striped table-hover table-sm cell-border">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Nama Barang</th>
										<th>Jumlah</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th colspan="2" style="text-align:right">Jumlah:</th>
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
	<script>
		const table = new DataTable('#tableKeluar', {
			ajax: "data-keluarku.php",
			language: {url : 'js/id.json'},
			responsive: true,
			processing: true,
			serverSide: true,
			select: true,
			columnDefs  : [
				{   className: "dt-head-center", targets: [ 0,1,2,3 ]},
				{   className: "dt-right", width: "70px", targets: [ 2 ]},
				{   className: "dt-center", width: "100px", targets: [ 0 ]},
				{   width: "300px", targets: [ 1,3 ]}
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
					.column(2, { page: 'current' })
					.data()
					.reduce((a, b) => intVal(a) + intVal(b), 0);
				// Update footer
				api.column(2).footer().innerHTML = pageTotal;
			}
		}).order([0,'desc'],[1,'asc']).draw();;
	</script>
</html>