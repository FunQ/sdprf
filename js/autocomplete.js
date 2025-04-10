$( function() {
    var getData = function(request, response){
        $.ajax({
            url		: "autocomplete.php",
            type	: "GET",
            dataType: "json",
            data    : "keyword="+ $("#nama").val(),
            success: function(data) {
                //console.log(data);
                $("#kode_brg").val("");
                $("#ket_brg").val("");
                $("#btnTambahBarang").html("Tambah Barang <i id=spinner></i>");
                response(data);
            }
        });
    };
    var selected = function(event, ui){
        $("#kode_brg").val(ui.item.kode_brg);
        $("#nama").val(ui.item.value);
        $("#ket_brg").val(ui.item.keterangan);
        $("#btnTambahBarang").html("Perbarui <i id=spinner></i>");
        return false;
    };
    $('#nama').autocomplete({
        source: getData,
        select: selected,
        minLength: 1
    });
});