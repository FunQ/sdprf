$( function() {
    var getData = function(request, response){
        $.ajax({
            url		: "autocomplete.php",
            type	: "GET",
            dataType: "json",
            data    : "keyword="+ $("#nama").val(),
            success: function(data) {
                $("#kode_brg").val("");
                $("#stok").text("0");
                $("#ket_brg").val("");
                response(data);
            }
        });
    };
    var selected = function(event, ui){
        $("#kode_brg").val(ui.item.kode_brg);
        $("#nama").val(ui.item.value);
        $("#stok").text(ui.item.jumlah);
        $("#ket_brg").val(ui.item.keterangan);
        return false;
    };
    $('#nama').autocomplete({
        source: getData,
        select: selected,
        minLength: 1
    });
});