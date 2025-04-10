const tblBarang = new DataTable('#tableBarang', {
    ajax: "data-semua-barang.php",
    language: {url : 'js/id.json'},
    responsive: true,
    processing: true,
    serverSide: true,
    select: true,
    columnDefs : [
        {   className: "dt-head-center", targets: [ 0,2,3,4 ]},
        {   className: "dt-right", width: "100px", targets: [ 3,4 ]},
        {   width: "30%", targets: [ 2 ]},
        {// kolom kode_brg
            targets: [0,1],
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
        stokPerPage = api
            .column(3)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
        reqPerPage = api
            .column(4)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(3).footer().innerHtml = stokPerPage;
        api.column(4).footer().innerHtml = reqPerPage;
        //console.log('stok: '+stokPerPage);
        //console.log('stok: '+reqPerPage);
    }
});

tblBarang.on('click', 'tbody tr', (e) => {
    let classList = e.currentTarget.classList;
    if(classList.contains('selected')){
        classList.remove('selected');
        disableButton();
        disableTakeButton();
    }else{
        tblBarang.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
        classList.add('selected');
        enableButton();
        enableTakeButton();
    }
    var kodeBrg		= "";
    var namaBrg		= "";
    var stokLama	= 0;
    var keterangan	= "";
    var idx = tblBarang.row(this).index();
    var row = tblBarang.row(idx).data();
    var oData = tblBarang.rows('.selected').data();
    for (var i=0; i < oData.length ;i++){
        waktu		= oData[i][0];
        kodeBrg		= oData[i][1];
        namaBrg		= oData[i][2];
        stokLama	= oData[i][3];
        keterangan	= oData[i][5];
        // Mengisi value Form Edit
        $("#kodeBrgEdit").val(kodeBrg);
        $("#namaBrgEdit").val(namaBrg);
        $("#ketBrgEdit").val(keterangan);

        // Mengisi value Form Hapus
        $("#kodeBrgHapus").val(kodeBrg);
        $("#namaBrgHapus").text(namaBrg);
        
        // Mengisi value Form Ambil Langsung
        $("#kodeBrgAmbil").val(kodeBrg);
        $("#namaBrgAmbil").text(namaBrg);
        $("#stokLama").val(stokLama);
        $("#ketAmbil").val(keterangan);
    }
    if(stokLama < 1) disableTakeButton();
    else enableTakeButton();
}).order([0,'desc'],[1,'asc']).draw();

function inputToTable(state){
    disableButton();
    disableTakeButton();
    var kodeBrg = '';
    var namaBrg = '';
    var ketBrg  = '';
    var btnId   = '';
    var btnText = '';
    var spinnerId = '';
    if(state == 'add'){
        kodeBrg = $('#kode_brg').val();
        namaBrg = $('#nama').val();
        ketBrg  = $('#ket_brg').val();
        spinnerId = 'sprinnerAdd';
        btnId   = 'btnTambahBarang';
        btnText = 'Tambah Barang';
    }else{
        kodeBrg = $('#kodeBrgEdit').val();
        namaBrg = $('#namaBrgEdit').val();
        ketBrg  = $('#ketBrgEdit').val();
        spinnerId = 'sprinnerEdit';
        btnId   = 'btnEditBarang';
        btnText = 'Perbarui';
    }
    if(namaBrg != ""){
        addLoading(btnId, spinnerId);
        $.ajax({
            url : "model/ProductModel.php",
            type: "POST",
            data: {
                "addEditProduct": "1",
                "kode_brg"      : kodeBrg,
                "nama"          : namaBrg,
                "ket_brg"       : ketBrg,
            },
            success: function(data){
                var objData = JSON.parse(data);
                $("#tableBarang").DataTable().ajax.reload();
                resetInputBarang();
                showToast(objData.prodID, objData.title, objData.message);
                removeLoading(btnId, btnText, spinnerId);
                $('.modal').modal('hide');
            }
        });
    }else{
        showAlert("Nama Barang harus diisi.");
        $('#nama').focus();
    }
}
function deleteProduct(){
    disableButton();
    kodeBrg = $('#kodeBrgHapus').val();
    namaBrg = $('#namaBrgHapus').text();
    
    spinnerId = 'sprinnerDelete';
    btnId   = 'btnDeleteProduct';
    btnText = 'Hapus Barang';

    addLoading(btnId, spinnerId);
    $.ajax({
        url : "model/ProductModel.php",
        type: "POST",
        data: {
            "deleteProduct" : "1",
            "kodeBrg"       : kodeBrg,
            "namaBrg"       : namaBrg
        },
        success: function(data){
            //console.log("before Parse: "+ data);
            var objData = JSON.parse(data);
            //console.log("after Parse: "+ objData);
            $("#tableBarang").DataTable().ajax.reload();
            reloadProducts();
            showToast(objData.prodID, objData.title, objData.message);
            removeLoading(btnId, btnText, spinnerId);
            $('.modal').modal('hide');
        }
    });
}
function resetInputBarang(){
    spinnerId = 'sprinnerReset';
    btnId   = 'btnReset';
    btnText = 'Reset Inputan';

    addLoading(btnId, spinnerId);
    $('#kode_brg').val('');
    $('#nama').val('');
    $('#ket_brg').val('');
    reloadProducts();
    removeLoading(btnId, btnText, spinnerId);
}
$(function(){
    $("#formTambah").on('shown.bs.modal', function(){
        $(this).find('#nama').focus();
    });
    $("#formEdit").on('shown.bs.modal', function(){
        $(this).find('#namaBrgEdit').focus();
    });
    $("#formTambahKeluar").on('shown.bs.modal', function(){
        $(this).find('#listBarang').focus();
    });
    $("#formTambahMasuk").on('shown.bs.modal', function(){
        $(this).find('#listBarang').focus();
    });
    $("#formAmbil").on('shown.bs.modal', function(){
        $(this).find('#idUser').focus();
    });

    $(".tanggal").val($.datepicker.formatDate('dd-mm-yy', new Date()));
    $(".tanggal").datepicker({
        dateFormat	: "dd-mm-yy",
        maxDate		: new Date(),
        changeMonth	: true,
        changeYear	: true,
        beforeShow	: function(){
            $(".ui-datepicker").css('font-size',12);
        }
    });
});