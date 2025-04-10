
function disableButton(){
    // Enable/Disable tombol Edit
    $("#btnEdit").removeClass('btn-warning').addClass('btn-secondary');
    $("#btnEdit").attr('disabled', true);
    // Enable/Disable tombol Hapus
    $("#btnHapus").removeClass('btn-danger').addClass('btn-secondary');
    $("#btnHapus").attr('disabled', true);
}
function enableButton(){
    // Enable/Disable tombol Edit
    $("#btnEdit").removeClass('btn-secondary').addClass('btn-warning');
    $("#btnEdit").removeAttr('disabled');
    // Enable/Disable tombol Hapus
    $("#btnHapus").removeClass('btn-secondary').addClass('btn-danger');
    $("#btnHapus").removeAttr('disabled', true);
}
function disableTakeButton(){
    $("#btnAmbil").removeClass('btn-success').addClass('btn-secondary');
    $("#btnAmbil").attr('disabled', true);
}
function enableTakeButton(){
    $("#btnAmbil").removeClass('btn-secondary').addClass('btn-success');
    $("#btnAmbil").removeAttr('disabled');
}
function addLoading(button, spinnerId){ // button = id button
    var btnText = $('#'+ button).text();
    $('#'+ button).html(btnText +' <i id="'+ spinnerId +'"></i>');
    $('#'+ spinnerId).addClass('fa fa-spinner fa-spin');
    $('#'+ button).attr('disabled', true);
}
function removeLoading(button, textDefault, spinnerId){ // textDefault = Teks pada button
    $('#'+ button).html(textDefault +' <i id="'+ spinnerId +'"></i>');
    $('#'+ spinnerId).removeClass('fa fa-spinner fa-spin');
    $('#'+ button).removeAttr('disabled', true);
}
function showToast(id, title, message){
    var time = Date.now();
    $('#notif').append('<div id="toast_'+ time +'_'+ id +'" class="toast" role="alert" aria-live="assertive" aria-atomic="true" >'+
        '<div class="toast-header">'+
            '<svg class="bd-placeholder-img rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#ffc107" /></svg>'+
            '<strong class="mr-auto"><label id="toastTitle" style="margin-rigth:10px;padding-top:5px">'+ title +'</label></strong>'+
            '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">'+
                '<span aria-hidden="true">&times;</span>'+
            '</button>'+
        '</div>'+
        '<div class="toast-body"><span style="text-align:justify">'+ message +'</span></div>'+
    '</div>');
    $('#toast_'+ time +'_'+ id).toast({
        animation: true,
        autohide: true,
        delay : 20000
    });
    $('#toast_'+ time +'_'+ id).toast('show');
}
function showToasts(arrObj, title){
    $.each(arrObj, function(key, value){
        const {userName} = value;
        const {prodName} = value;
        const {quantity} = value;
        const {state}    = value;
        var message         = '<strong>'+ quantity +'</strong> buah <strong>'+ prodName +'</strong> atas nama <strong>'+ userName +'</strong> telah '+ state +' diinput.';
        if(userName == '') message = '<strong>'+ quantity +'</strong> buah <strong>'+ prodName +'</strong> telah '+ state +' diinput.';
        showToast(key, title, message);
    });
}
function showAlert(text){
    Swal.fire({
        title: false,
        text: text,
        icon: false,
        confirmButtonText: 'OK',
        timer: 5000
    });
}
function reloadProducts(){
    $.ajax({
        type	: "POST",
        url		: "model/ProductModel.php",
        data	: 'reloadProducts',
        success : function(data){
            data = JSON.parse(data);
            var listBarang = $('#listBarang').empty(); // remove previously loaded options
            $.each(data, function(key, val){
                listBarang.append('<option value=' + val.kode_brg + ':'+ val.jumlah +'>'+ val.nama_brg +' : '+ val.jumlah +'</option>');
            });
        }
    });
}