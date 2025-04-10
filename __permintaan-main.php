<!-- Awal : Toast / Notification -->
<div id="notif" style="position:absolute; top:0; right:0; z-index:99;"></div>
<!-- Akhir : Toast -->
<div class="container-fluid">
    <h2 class="mt-4">Permohonan Barang</h2>
    <div class="card-header p-1">
        <!-- Button to Open the Modal -->
        <button type="button" class="shadow btn btn-success mr-1 mb-2" data-toggle="modal" data-target="#formTambah">
            Input
        </button>
        <button type="button" class="shadow btn btn-secondary mr-1 mb-2" id="btnEdit" data-toggle="modal" data-target="#formEdit" disabled>
            Edit
        </button>
        <button type="button" class="shadow btn btn-secondary mr-1 mb-2" id="btnHapus" data-toggle="modal" data-target="#formHapus" disabled>
            Hapus
        </button>
        <a href="#tableInfo" class="shadow btn btn-info mr-1 mb-2 buttonload" id="btnShowMine" onclick="showMine()">
            Permohonan Saya <i id="spinnerShowMine" class=""></i>
        </a>
        <button type="button" class="shadow btn btn-secondary mr-1 mb-2" id="btnAmbil" data-toggle="modal" data-target="#formAmbil" disabled>
            Ambil Langsung
        </button>
    </div>
    <div class="card-body p-1">
        <style>
            #tablePermintaan_wrapper{width: 40%;margin-right:30px}
            #tableDetail_wrapper{width:60%}
            #ui-datepicker-div {z-index: 10000 !important}
            @media screen and (max-width: 720px) {
                #tableSplit{display: block !important;}
                #tablePermintaan_wrapper{width: 100%;margin-right:30px}
                #tableDetail_wrapper{width:100%}
            }
        </style>
        <div class="table-responsive" id="tableSplit" style="display:flex">
            <table id="tablePermintaan" style="width:100%"
                class="table table-bordered table-striped table-hover table-sm cell-border">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Stok</th>
                        <th>Prio</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th style="text-align:right">Jumlah:</th>
                        <th style="text-align:right"></th>
                        <th style="text-align:right"></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <table id="tableDetail" width="100%"
                class="table table-bordered table-striped table-hover table-sm cell-border">
                <thead style="text-align:center">
                    <tr>
                        <th width="3%">No</th>
                        <th>ID</th>
                        <th width="12%">Tanggal</th>
                        <th>ID User</th>
                        <th width="35%">Pemohon</th>
                        <th width="4%">Jumlah</th>
                        <th width="35%">Keterangan</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align:right">Jumlah:</th>
                        <th style="text-align:right"></th>
                        <th style="text-align:left"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="container-fluid">
    <h2 class="mt-4">Permohonan Saya</h2>
    <div class="card-body p-1">
        <div class="table-responsive">
            <table id="tableInfo" width="80%"
                class="table table-bordered table-striped table-hover table-sm cell-border">
                <thead style="text-align:center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right">Jumlah:</th>
                        <th style="text-align:right"></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>