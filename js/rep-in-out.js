const tableLap  = new DataTable('#tableLaporan', {
    language    : {url : 'js/id.json'},
    responsive  : true,
    processing  : true,
    paging      : false,
    data        : "",
    columns     : [
        {
            data : "no",
            render  : function(data, type, full, meta){
                return meta.row + 1;
            }
        },
        {
            data : "tanggal",
            render: DataTable.render.datetime("DD-MM-YYYY")
        },
        {data : "nama_brg"},
        {data : "jumlah"},
        {data : "nama"},
        {data : "keterangan"}
    ],
    columnDefs  : [
        {   className: "dt-head-center", targets: [ 0,1,2,3,4,5 ]},
        {   width: "70px", targets: [ 0, 3 ]},
        {   className: "dt-center", width: "110px", targets: [ 0,1 ]},
        {   width: "20%", targets: [ 2 ]},
        {   width: "25%", targets: [ 4 ]}
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
 
        // Total over all pages
        total = api
            .column(3)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
 
        // Total over this page
        pageTotal = api
            .column(3, { page: 'current' })
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
 
        // Update footer
        api.column(3).footer().innerHTML = pageTotal;
        api.column(4).footer().innerHTML = '( Total barang: '+ total +' )';
    },
    layout: {
        topStart: {
            buttons: ['excel', 'pdf', 'print']
        }
    }
});
function showReport(){
    var jenisLap= $("#jenisLap").val();
    var dataObj = {
        'jenisLap'  : jenisLap,
        'kodeBrg'   : $("#kodeBrg").val(),
        'idUser'    : $("#idUser").val(),
        'tglAwal'   : $("#tglAwal").val(),
        'tglAkhir'  : $("#tglAkhir").val()
    };
    var spinnerId= 'sprinner';
    var btnId   = 'btnReport';
    var btnText = 'Tampilkan';
    addLoading(btnId, spinnerId);
    $.ajax({
        url : "model/ReportModel.php",
        type: "POST",
        data: {"rep-in-out": JSON.stringify(dataObj)},
        success: function(data){
            tableLap.clear();
            tableLap.rows.add(JSON.parse(data)).draw();
            parseData = JSON.parse(data);
            if(parseData.length <= 0) $('#modalErrMsg').modal('show');
            removeLoading(btnId, btnText, spinnerId);
        }
    });
}
$(function(){
    $("#labelTitle").text(document.title);
    $("#jenisLap").on("change", function(){
        $(document).attr("title", "Laporan "+ $("#jenisLap :selected").text());
        $("#labelTitle").text(document.title);
    });
    //Display Only Date till today // 
    var dtToday = new Date();
    var month = dtToday.getMonth() + 1;     // getMonth() is zero-based
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10) month = '0' + month.toString();
    if(day < 10) day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    $('#tglAwal').attr('max', maxDate);
    $('#tglAkhir').attr('max', maxDate);

    $('#tglAwal').change(function(){
        let tglAwal     = document.getElementById("tglAwal").value;
        $('#tglAkhir').attr('min', tglAwal);
    });
    $('#tglAkhir').change(function(){
        let tglAkhir     = document.getElementById("tglAkhir").value;
        $('#tglAwal').attr('max', tglAkhir);
    });
});