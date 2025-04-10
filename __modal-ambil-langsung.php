<div class="modal fade" id="formAmbil">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
        
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Ambil Langsung</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <form method="post">
        <div class="modal-body">
            <input type="hidden" name="kodeBrgAmbil" id="kodeBrgAmbil">
            <p>BARANG : <strong><label id="namaBrgAmbil"></label></strong></p>
            <?php if($_SESSION['log']['akses'] == "User"){ ?>
                <input type="hidden" name="tanggalAmbil" id="tanggalAmbil">
                <input type="hidden" name="idUser" id="idUser" value="<?=$_SESSION['log']['id_user'];?>">
            <?php }else{?>
                <input type="text" class="form-control tanggal"
                    style="max-width:110px" name="tanggalAmbil" id="tanggalAmbil" readonly><br />
                <select name="idUser" id="idUser" class="form-control">
                    <?php foreach($arrUser as $user){ ?>
                        <option value="<?=$user->id_user;?>"><?=$user->nama_user;?></option>
                    <?php } ?>
                </select><br/>
            <?php } ?>
            <input type="hidden" name="stokLama" id="stokLama">
            <input type="number" class="form-control model-sm" name="jumlahAmbil" id="jumlahAmbil" placeholder="Jumlah" required><br />
            <textarea class="form-control" id="ketAmbil" name="ketAmbil" rows="2" cols="50" maxlength="100" placeholder="Keterangan"></textarea><br/>
            <button type="button" class="shadow btn btn-success" name="barang_keluar" id="btnTakeAway" onclick="directProductPickup()">
                Ambil Langsung <i id="spinnerTake"></i>
            </button>
        </div>
        </form>
        </div>
    </div>
</div>