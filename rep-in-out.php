<?php
    require_once "model/ClassesModel.php";
	require_once "model/ReportModel.php";
	require_once "__cek-status.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once "__header.php";?>
		<?php require_once "__load-barang.php";?>
		<?php require_once "__load-user.php";?>
		<title>Laporan Barang Keluar</title>
    </head>
    <body class="sb-nav-fixed sb-sidenav-toggled">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h3 class="mt-4 text-center">Laporan Keluar Masuk Barang</h3>
                        <div class="card-header form-inline p-1">
                            <select name="jenisLap" id="jenisLap" class="form-control mr-2 mb-2">
                                <option value="data_keluar">Barang Keluar</option>
                                <option value="data_masuk">Barang Masuk</option>
                            </select>
                            <select name="kodeBrg" id="kodeBrg" class="form-control mr-2 mb-2">
                                <option value="all" style="text-align:center">-- Barang --</option>
                                <?php foreach($arrBrgMasuk as $brg){?>
                                    <option value="<?=$brg->kode_brg;?>"><?=$brg->nama_brg;?></option>
                                <?php } ?>
                            </select>
                            <select name="idUser" id="idUser" class="form-control mr-2 mb-2">
                                <option value="allUser" style="text-align:center">-- Penerima --</option>
                                <?php foreach($arrUser as $user){?>
                                    <option value="<?=$user->id_user;?>"><?=$user->nama_user;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="card-header form-inline p-1">
                            <input type="date" class="form-control mr-2 mb-2" style="max-width: 150px" name="tglAwal" id="tglAwal">
                            <input type="date" class="form-control mr-2 mb-2" style="max-width: 150px" name="tglAkhir" id="tglAkhir">
                            <button type="button" id="btnReport" class="shadow btn btn-success mr-2 buttonload" onclick="showReport()">
                                Tampilkan <i id="spinner" class=""></i>
                            </button>
                        </div>
                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table id="tableLaporan" width="100%" cellspacing="0"
                                    class="table table-bordered table-striped table-hover table-sm cell-border">
                                    <thead style="text-align:center">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Penerima</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align:right">Jumlah:</th>
                                            <th style="text-align:right"></th>
                                            <th colspan="2" style="text-align:left"></th>
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
        <?php include_once "__script-report.php";?>
        <script src="js/rep-in-out.js"></script>
        <script src="js/moment.min.js"></script>
    </body>
    <?php require "__modal-error-message.php"?>
</html>