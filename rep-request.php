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
		<title>Laporan Permohonan - Semua Pemohon - Semua Barang</title>
    </head>
    <body class="sb-nav-fixed sb-sidenav-toggled">
        <?php require "__navbar.php";?>
        <div id="layoutSidenav">
            <?php include_once "__side-menu.php";?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h3 class="mt-4 text-center">Laporan Permohonan Barang</h3>
                        <div class="card-header form-inline p-1">
                            <select name="idUser" id="idUser" class="form-control mr-2 mb-2">
                                <option value="allUser" style="text-align:center">-- Pemohon --</option>
                                <?php foreach($arrUser as $user){?>
                                    <option value="<?=$user->id_user;?>"><?=$user->nama_user;?></option>
                                <?php } ?>
                            </select>
                            <select name="kodeBrg" id="kodeBrg" class="form-control mr-2 mb-2">
                                <option value="allBarang" style="text-align:center">-- Barang --</option>
                                <?php foreach($arrBrgMasuk as $brg){?>
                                    <option value="<?=$brg->kode_brg;?>"><?=$brg->nama_brg;?></option>
                                <?php } ?>
                            </select>
                            <button type="button" id="btnReport" class="shadow btn btn-success mr-2 mb-2 buttonload" onclick="showReport()">
                                Tampilkan <i id="spinner" class=""></i>
                            </button>
                        </div>
                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table id="tableLaporan" width="50%" cellspacing="0"
                                    class="table table-bordered table-striped table-hover table-sm cell-border">
                                    <thead style="text-align:center">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang / Pemohon</th>
                                            <th>Jumlah</th>
                                            <th>Prioritas</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align:right">Jumlah:</th>
                                            <th class="text-right"></th>
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
        <?php include_once "__script-report.php";?>
        <script src="js/rep-request.js"></script>
        <script src="js/moment.min.js"></script>
    </body>
    <?php require "__modal-error-message.php"?>
    <script>
        $(function(){
            var textUser    = "";
            var textBarang  = "";
            $("#labelTitle").text(document.title);
            $("#idUser").on("change", function(){
                var idUser = $("#idUser").val();
                if(idUser == "allUser")
                    textUser = " - Semua Pemohon";
                else
                    textUser = " - "+ $("#idUser :selected").text();
                changeTitle(textUser, textBarang);
            });
            $("#kodeBrg").on("change", function(){
                var kodeBrg = $("#kodeBrg").val();
                if(kodeBrg == 'allBarang')
                    textBarang = " - Semua Barang";
                else
                    textBarang = " - "+ $("#kodeBrg :selected").text();
                changeTitle(textUser, textBarang);
            });
        });
        function changeTitle(textUser, textBarang){
            $(document).attr("title", "Laporan Permohonan"+ textUser + textBarang);
            $("#labelTitle").text(document.title);
        }
    </script>
</html>