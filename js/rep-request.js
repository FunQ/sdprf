const tableLap  = new DataTable('#tableLaporan', {
    language    : {url : 'js/id.json'},
    responsive  : true,
    processing  : true,
    paging      : false,
    info        : false,
    data        : "",
    columns     : [
        {
            data : "no",
            render  : function(data, type, full, meta){
                return meta.row + 1;
            }
        },
        {
            data    : "tanggal",
            render  : function(data, type, full, meta){
                return moment(data).format('DD-MM-YYYY');//Ubah Format
            }
        },
        {data : "nama"},
        {data : "jumlah"},
        {
            data    : "prioritas",
            render  : function(data, type){
                return (data <= 0) ? "Tidak" : "Ya";
            }
        }
    ],
    columnDefs  : [
        {   className: "dt-head-center", targets: [ 0,1,2,3 ]},
        {   width: "70px", targets: [ 0,1,3 ]}
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
        // Total over this page
        pageTotalMinta = api
            .column(3, { page: 'current' })
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
        // Update footer
        api.column(3).footer().innerHTML = pageTotalMinta;
    },
    layout: {
        topStart: {
            buttons: ['excel', 'pdf', 'print']
        }
    }
});
function showReport(){
    var dataObj = {
        'kodeBrg'   : $("#kodeBrg").val(),
        'idUser'    : $("#idUser").val()
    };
    var spinnerId= 'sprinner';
    var btnId   = 'btnReport';
    var btnText = 'Tampilkan';
    addLoading(btnId, spinnerId);
    $.ajax({
        url : "model/ReportModel.php",
        type: "POST",
        data: {"rep-request": JSON.stringify(dataObj)},
        success: function(data){
            tableLap.clear();
            tableLap.rows.add(JSON.parse(data)).draw();
            parseData = JSON.parse(data);
            if(parseData.length <= 0) $('#modalErrMsg').modal('show');
            removeLoading(btnId, btnText, spinnerId);
        }
    });
}
function changeTitle(textUser, textBarang){
    $(document).attr("title", "Laporan Permohonan"+ textUser + textBarang);
    $("#labelTitle").text(document.title);
}
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