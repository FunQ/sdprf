<div class="modal fade" id="formTambahKeluar">
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
                <input	type="text" class="form-control tanggal" name="tanggal" id="tanggal" readonly><br />
                    <select name="listBarang" id="listBarang" class="form-control">
                        <?php foreach($arrBrgKeluar as $barang){ ?>
                            <option value="<?=$barang->kode_brg;?>:<?=$barang->jumlah?>">
                                <?=$barang->nama_brg;?> : <?=$barang->jumlah;?>
                            </option>
                        <?php }	?>
                    </select>
                </div>
                <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah" required><br />
                <div class="form-group">
                    <select name="listUser" id="listUser" class="form-control">
                        <?php foreach($arrUser as $user){ ?>
                            <option value="<?=$user->id_user;?>"><?=$user->nama_user;?></option>
                        <?php } ?>
                    </select>
                </div>
                <textarea class="form-control" id="ket" name="ket" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
                <div class="form-group" style="width: 200%">
                    <button type="button" class="shadow btn btn-primary buttonload" id="btnAddToList" onclick="inputToList('keluar')">
                        Tambahkan <i id="spinnerAddToList"></i>
                    </button>
                    <button type="button" class="shadow btn btn-success ml-1 buttonload" id="btnAddOut" onclick="inputToDBKeluar()">
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
                            <th>Penerima</th>
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