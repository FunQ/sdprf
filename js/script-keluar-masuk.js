var	i = 1;
var arrObject	= [];

function inputToList(state){
    var prodID  = $("#listBarang :selected").val();
    var date    = $("#tanggal").val();
    var prodName= $("#listBarang :selected").text();
    var quantity= $("#jumlah").val();
    var userID = $('#listUser').val();
    var userName= $('#listUser :selected').text();
    var info     = $('#ket').val();

    const arrSplitKode = prodID.split(':');
    prodID= arrSplitKode[0];
    stok    = parseInt(arrSplitKode[1]);

    const arrSplitNama = prodName.split(":");
    prodName= arrSplitNama[0];

    spinnerId = 'sprinnerAddToList';
    btnId   = 'btnAddToList';
    btnText = 'Tambahkan';

    addLoading(btnId, spinnerId);
    if(quantity == "" || quantity < 1){
        showAlert("Jumlah barang harus diisi dengan benar.");
        $("#jumlah").focus();
    }else{
        // Jika stok Mencukupi maka hentikan Proses
        if(state == 'keluar'){
            if(quantity > stok){
                showAlert("Mohon maaf stok tidak mencukupi.");
                removeLoading(btnId, btnText, spinnerId);
                return false;
            }
        }
        quantity = parseInt(quantity);
        var objData	= {
            'kode_brg'	: prodID,
            'nama_brg'  : prodName,
            'tanggal'   : date,
            'jumlah'	: quantity,
            'id_user'	: userID,
            'userName'	: userName,
            'keterangan': info
        }
        // Cek apakah ada barang + penerima yang sama dengan yang di tabel form.
        var found = false;
        if(arrObject.length <= 0){
            addRow(i, prodID, date, prodName, userName, quantity, info, state);
            arrObject.push(objData);
            i++;
        }else{
            var jml_keluar_lama = 0;
            var total = 0;
            var totalKeluarPerbarang = 0;
            var jmlDiTable = 0;

            for(var c=0; c<arrObject.length; c++){
                // Mencari barang berdasarkan Pemesan dan Barang
                if(prodID == arrObject[c].kode_brg && userID == arrObject[c].id_user){
                    jml_keluar_lama = arrObject[c].jumlah;
                    total = parseInt(jml_keluar_lama) + parseInt(quantity);
                    found = true;
                }
                // Menghitung total barang keluar perbarang
                if(prodID == arrObject[c].kode_brg){
                    jmlDiTable += parseInt(arrObject[c].jumlah);
                    totalKeluarPerbarang = quantity + jmlDiTable;
                }
            }

            if(totalKeluarPerbarang <= stok || state == 'masuk'){
                if(!found){
                    // Jika barang belum ada di tabel
                    addRow(i, prodID, date, prodName, userName, quantity, info, state);
                    arrObject.push(objData);
                    i++;
                }else{
                    // Jika barang sudah ada di tabel, maka update data JSON
                    objIndex = arrObject.findIndex(obj => obj.kode_brg == prodID && obj.id_user == userID);
                    arrObject[objIndex].tanggal = date;
                    arrObject[objIndex].jumlah = total;
                    arrObject[objIndex].keterangan = info;
    
                    const idx = objIndex + 1;
                    $("#labelTanggal_"+ idx).html(date);
                    $("#labelJumlah_"+ idx).html(total);
                    $("#labelKeterangan_"+ idx).html(info);
                }
            }else{
                showAlert("Jumlah melebihi stok yang sudah di masukan ke Tabel.");
            }
        }
        $("#jumlah").val("");
        $("#jmlItem").text(i-1);
        $("#listBarang").focus();
    }
    removeLoading(btnId, btnText, spinnerId);
}
function addRow(i, prodID, date, prodName, nama_user, quantity, info, state){
    var keluarUser = "";
    if(state != 'keluarUser') {
        keluarUser = "<td>"+ nama_user +"</td>";
    }
    $("#tableKeluarMasuk tbody")
        .prepend("<tr><td style='text-align:center' width='100px'><label id='labelTanggal_"+ i +"'>"+ date +"</label></td>"+
        "<td>"+ prodName +"</td>"+
        "<td style='text-align:center' width='80px'><label id='labelJumlah_"+ i +"'>"+ quantity +"</label></td>"+
        keluarUser +
        "<td><label id='labelKeterangan_"+ i +"'>"+ info +"</label></td></tr>");
}
function inputToDBKeluar(){
    disableButton();
    if(arrObject.length > 0){
        var spinnerId= 'sprinnerAdd';
        var btnId   = 'btnAddOut';
        var btnText = 'Kirim';
        addLoading(btnId, spinnerId);
        $.ajax({
            type	: "POST",
            url		: "model/ProductModel.php",
            data	: {'productOut'	: JSON.stringify(arrObject)},
            success : function(data){
                arrObjData = JSON.parse(data);
                showToasts(arrObjData, 'Pengambilan Barang');
                $("#tableInOut").DataTable().ajax.reload();
                $("#tableBarang").DataTable().ajax.reload();
                resetInput(); // include reloadProducts
                $('.modal').modal('hide');
                removeLoading(btnId, btnText, spinnerId);
            }
        });
    }else{
        showAlert("Silahkan tambahkan dulu barang keluarnya.");
    }
}
function inputToDBMasuk(){
    disableButton();
    if(arrObject.length > 0){
        var spinnerId= 'sprinnerAdd';
        var btnId   = 'btnAddIn';
        var btnText = 'Kirim';
        addLoading(btnId, spinnerId);
        $.ajax({
            type	: "POST",
            url		: "model/ProductModel.php",
            data	: {'productIn'	: JSON.stringify(arrObject)},
            success : function(data){
                arrObject = JSON.parse(data);
                showToasts(arrObject, 'Tambah Barang Masuk');
                $("#tableInOut").DataTable().ajax.reload();
                $("#tableBarang").DataTable().ajax.reload();
                resetInput(); // include reloadProducts
                removeLoading(btnId, btnText, spinnerId);
                $('.modal').modal('hide');
            }
        });
    }else{
        showAlert("Silahkan tambahkan dulu barang yang akan di masukkan.");
    }
}
function editProductInOut(state){
    disableButton();
    var id      = $('#idEdit').val();
    var idUser  = $('#listUserEdit').val();
    var userName= $('#listUserEdit :selected').text();
    var prodID  = $('#listBarangEdit').val();
    var namaBrg = $('#namaBrgEdit').val();
    var qtyEdit = $('#jumlahEdit').val();
    var ketBrg  = $('#ketEdit').val();
    var date	= $('#tanggalEdit').val();

    var spinnerId= 'sprinnerEdit';
    var btnId   = 'btnEditProduct';
    var btnText = 'Perbarui';

    if(qtyEdit < 1 || qtyEdit == ''){
        showAlert ("Tentukan jumlah barang terlebih dahulu.");
        return false;
    }else
        qtyEdit = parseInt(qtyEdit);
    
    addLoading(btnId, spinnerId);
    $.ajax({
        type	: "POST",
        url		: "model/ProductModel.php",
        data: {
            "editProductIO" : '1',
            "state"         : state,
            "id"            : id,
            "idUser"        : idUser,
            "userName"      : userName,
            "kodeBrg"       : prodID,
            "namaBrg"       : namaBrg,
            "jumlah"        : qtyEdit,
            "ket"           : ketBrg,
            "tanggal"       : date
        },
        success : function(data){
            var objData = JSON.parse(data);
            removeLoading(btnId, btnText, spinnerId);
            reloadProducts();
            $('.modal').modal('hide');
            showToast(objData.prodID, objData.title, objData.message);
            $("#tableInOut").DataTable().ajax.reload();
        }
    });
}
function deleteProductInOut(state){
    disableButton();
    var id      = $('#idHapus').val();
    var prodID = $('#kodeBrgHapus').val();
    var namaBrg = $('#namaBrgHapus').text();
    var quantity  = $('#jumlahHapus').val();

    var spinnerId = 'sprinnerDelete';
    var btnId   = 'btnDeleteProduct';
    var btnText = 'Hapus Barang';

    addLoading(btnId, spinnerId);
    $.ajax({
        url : "model/ProductModel.php",
        type: "POST",
        data: {
            "deleteProductIO"   : "1",
            "state"             : state,
            "id"                : id,
            "kodeBrg"           : prodID,
            "namaBrg"           : namaBrg,
            "jumlah"            : quantity
        },
        success: function(data){
            var objData = JSON.parse(data);
            reloadProducts();
            $("#tableInOut").DataTable().ajax.reload();
            showToast(objData.prodID, objData.title, objData.message);
            removeLoading(btnId, btnText, spinnerId);
            $('.modal').modal('hide');
        }
    });
}
function directProductPickup(){
    var quantity= $("#jumlahAmbil").val();
    var prodID  = $('#kodeBrgAmbil').val();
    var namaBrg = $("#namaBrgAmbil").text();
    var info    = $('#ketAmbil').val();
    var date    = $('#tanggalAmbil').val();
    var idUser  = $('#idUser').val();
    var userName= $('#idUser :selected').text();
    var oldStock= $('#stokLama').val();
    
    if(quantity == "" || quantity <= 0){
        showAlert("Jumlah Barang harus diisi dulu.");
        $("#jumlahAmbil").focus();
        return false;
    }else{
        quantity = parseInt(quantity);
        oldStock = parseInt(oldStock);
    }
    if(quantity > oldStock){
        showAlert("Stok Barang tidak mencukupi.");
        return false;
    }else{
        var dataObj = {
            'kodeBrg'   : prodID,
            'prodName'  : namaBrg,
            'jumlah'    : quantity,
            'ket'       : info,
            'tanggal'   : date,
            'idUser'    : idUser,
            'userName'  : userName
        }
        var spinnerId= 'sprinnerTake';
        var btnId   = 'btnTakeAway';
        var btnText = 'Ambil Langsung';
        addLoading(btnId, spinnerId);
        $.ajax({
            url : "model/ProductModel.php",
            type: "POST",
            data: {"directProductPickup": JSON.stringify(dataObj)},
            success: function(message){
                showToast(prodID, 'Barang Keluar', message);
                resetFormDirectPickup();
                disableTakeButton();
                disableButton();
                reloadProducts();
                removeLoading(btnId, btnText, spinnerId);
            }
        });
    }
}
function resetFormDirectPickup(){
    $("#kodeBrgAmbil").val('');
    $("#namaBrgAmbil").text('');
    $("#jumlahAmbil").val('');
    $("#ketAmbil").val('');
    $('.modal').modal('hide');
    $("#tableBarang").DataTable().ajax.reload();
    $("#tablePermintaan").DataTable().ajax.reload();
    $("#tableDetail").DataTable().clear().draw();
}
function resetInput(){
    i = 1;
    arrObject.length = 0;
    $('#ket').val("");
    $("#jumlah").val("");
    $("#listBarang").focus();
    $("#jmlItem").text('0');
    $("#tableKeluarMasuk > tbody").html("");
    reloadProducts();
}