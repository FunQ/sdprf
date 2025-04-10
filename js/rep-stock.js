const tableLap = new DataTable('#tableLaporan', {
    language    : {url : 'js/id.json'},
    responsive  : true,
    processing  : true,
    select      : true,
    paging      : false,
    data        : "",
    columns     : [
        {
            data : "no",
            render  : function(data, type, full, meta){
                return meta.row + 1;
            }
        },
        //{data : "kode_brg"},
        {data : "nama_brg"},
        {data : "jumlah"},
        {data : "keterangan"}
    ],
    columnDefs  : [
        {   orderable: false, targets: 0},
        {   className: "dt-head-center", targets: [ 0,1,2,3 ]},
        {   width: "60px", targets: [ 0, 2 ]},
        {   width: "300px", targets: 1 }
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
            .column(2)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
 
        // Total over this page
        pageTotal = api
            .column(2, { page: 'current' })
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
 
        // Update footer
        api.column(2).footer().innerHTML = pageTotal;
        
        api.column(3).footer().innerHTML = '( Total barang: '+ total +' )';
    },
    layout: {
        topStart: {
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        }
    }
});
function showReport(){
    var jenisLap= $("#jenisLap").val();
    var kodeBrg = $("#kodeBrg").val();
    var dataObj = {
        'jenisLap'  : jenisLap,
        'kodeBrg'   : kodeBrg
    };
    var spinnerId= 'sprinner';
    var btnId   = 'btnReport';
    var btnText = 'Tampilkan';
    addLoading(btnId, spinnerId);
    $.ajax({
        url : "model/ReportModel.php",
        type: "POST",
        data: {"rep-stock": JSON.stringify(dataObj)},
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
        var jLap = $("#jenisLap :selected").text();
        if(jLap != "Barang Habis" && jLap != "Barang Masih Ada") jLap = "Semua Barang";
        $(document).attr("title", "Laporan Stok - "+ jLap );
        $("#labelTitle").text(document.title);
    });
});