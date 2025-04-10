var i = 1;
var arrObject = [];
var globalKodeBrg = "";
var globalNamaBrg = "";
var globalJumlah = 0;    // Jumlah permintaan dari semua user
var globalStok = 0;
var globalPrioritas = 0;

// INIT TABLE PERMINTAAN
const tPermintaan = new DataTable('#tablePermintaan', {
    language: { url: 'js/id.json' },
    ajax: "data-permintaan.php",
    responsive: true,
    processing: true,
    serverSide: true,
    paging: false,
    info: false,
    select: true,
    columnDefs: [
        { className: "dt-head-center", targets: [1, 2, 3] },
        { width: "300px", targets: [1] },
        { className: "dt-right", width: "60px", targets: [2, 3] },
        { className: "dt-center", targets: [4] },
        {
            targets: [0, 5],
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
        pageTotalMinta = api
            .column(2)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
        pageTotalStok = api
            .column(3)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(2).footer().innerHTML = pageTotalMinta;
        api.column(3).footer().innerHTML = pageTotalStok;
    },
    layout: {
        topStart: {
            buttons: [{
                extend: 'excel',
                exportOptions: { columns: [1, 2, 3, 4] }
            }, {
                extend: 'pdf',
                exportOptions: { columns: [1, 2, 3, 4] }
            }, {
                extend: 'print',
                exportOptions: { columns: [1, 2, 3, 4] }
            }
            ]
        }
    }
});
tPermintaan.on('click', 'tbody tr', (e) => {
    let classList = e.currentTarget.classList;
    if (classList.contains('selected')) {
        classList.remove('selected');
    } else {
        tPermintaan.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
        classList.add('selected');
        disableButton();
        disableTakeButton();

        var oData = tPermintaan.rows('.selected').data();
        for (var i = 0; i < oData.length; i++) {
            globalKodeBrg = oData[i][0];
            globalNamaBrg = oData[i][1];
            globalJumlah = oData[i][2];
            globalStok = oData[i][3];
            var prio = oData[i][4];
            if (prio == "Ya") globalPrioritas = 1;
            else globalPrioritas = 0;
        }
        showDetail(globalKodeBrg);
    }
}).order([5, 'desc'], [1, 'asc']).draw();

tPermintaan.on('dblclick', 'tr', function () {
    var data = tPermintaan.row(this).data();
    var prodId = data[0];
    var prodName = data[1];
    var qtyProd = data[2];

    $("#prodReqID").val(prodId);
    $("#prodNameDelete").text(prodName);
    $("#qtyProdReq").text(qtyProd);

    $('#formDeleteProductRequest').modal('show');
});

// INIT TABLE DETAIL PERMINTAAN
const tDetail = new DataTable('#tableDetail', {
    language: { url: 'js/id.json' },
    responsive: true,
    processing: true,
    select: true,
    info: false,
    searching: true,
    paging: false,
    data: "",
    columns: [
        {
            data: "no",
            render: function (data, type, full, meta) {
                return meta.row + 1;
            }
        },
        { data: "id" },
        {
            data: "tanggal",
            render: DataTable.render.datetime("DD-MM-YYYY")
        },
        { data: "id_user" },
        { data: "nama" },
        { data: "jumlah" },
        { data: "keterangan" }
    ],
    columnDefs: [
        { className: "dt-head-center", targets: [0, 1, 2, 3, 4, 5, 6] },
        { className: "dt-center", targets: [2] },
        { className: "dt-right", targets: [0, 5] },
        {
            targets: [1, 3],
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
        // Total over this page
        pageTotal = api
            .column(5, { page: 'current' })
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(5).footer().innerHTML = pageTotal;
    }
});
tDetail.on('click', 'tbody tr', (e) => {
    let classList = e.currentTarget.classList;
    if (classList.contains('selected')) {
        classList.remove('selected');
        disableButton();
        disableTakeButton();
    } else {
        tDetail.rows('.selected').nodes().each((row) => row.classList.remove('selected'));
        classList.add('selected');
        var sessAkses = $("#sessAkses").val();
        var sessIdUser = $("#sessIdUser").val();
        enableButton();
        enableTakeButton();

        var idx = tDetail.row(this).index();
        var row = tDetail.row(idx).data();
        var oData = tDetail.rows('.selected').data();
        for (var i = 0; i < oData.length; i++) {
            var id = oData[i]['id'];
            var tanggal = oData[i]['tanggal'];
            var idUser = oData[i]['id_user'];
            var userName = oData[i]['nama'];
            var jumlah = oData[i]['jumlah'];
            var ket = oData[i]['keterangan'];
            tanggal = moment(tanggal).format('DD-MM-YYYY');//Ubah Format

            // Validasi : edit hanya pesanan sendiri
            if (sessAkses == 'User') {
                if (sessIdUser == idUser) enableButton();
                else disableButton();
                $("#idUserEdit").val(idUser);
            } else {
                $("#idUserEdit").val(idUser).change();
            }

            if (globalStok <= 0) disableTakeButton();
            else enableTakeButton();

            // Mengisi value Form Edit
            $("#idEdit").val(id);
            $("#prioritasEdit").val(globalPrioritas);
            $("#kodeBrgEdit").val(globalKodeBrg);
            $("#labelNamaBrg").text(globalNamaBrg);
            $("#tanggalEdit").val(tanggal);
            $("#jumlahEdit").val(jumlah);
            $("#ketEdit").val(ket);
            $("#idUserEdit").val(idUser);
            $("#userNameEdit").val(userName);

            // Mengisi value Form Hapus
            $("#idHapus").val(id);
            $("#kodeBrgHapus").val(globalKodeBrg);
            $("#jmlHapus").text(jumlah);
            $("#namaBrgHapus").text(globalNamaBrg);
            $("#namaHapus").text(userName);

            // Mengisi value Form Ambil Langsung
            $("#kodeBrgAmbil").val(globalKodeBrg);
            $("#namaBrgAmbil").text(globalNamaBrg);
            $("#idUser").val(idUser);
            $("#stokLama").val(globalStok);
        }
        $('#formDetail').modal('show');
    }
});

const tInfo = new DataTable('#tableInfo', {
    language: { url: 'js/id.json' },
    responsive: true,
    processing: true,
    paging: true,
    info: false,
    data: "",
    columns: [
        {
            data: "no",
            render: function (data, type, full, meta) {
                return meta.row + 1;
            }
        },
        {
            data: "tanggal",
            render: DataTable.render.datetime("DD-MM-YYYY")
        },
        { data: "nama_brg" },
        { data: "jumlah" },
        { data: "keterangan" }
    ],
    columnDefs: [
        { className: "dt-head-center", targets: [1, 2, 3, 4] },
        { width: "250px", targets: [2] },
        { width: "60px", targets: [0, 1, 3] },
        { className: "dt-right", targets: [0, 3] }
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
        pageTotal = api
            .column(3)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
        api.column(3).footer().innerHTML = pageTotal;
    }
});

// Menampilkan Detail Permintaan
function showDetail(kodeBrg) {
    $.ajax({
        url: "model/RequestModel.php",
        type: "POST",
        data: { "getDetail": kodeBrg },
        success: function (data) {
            tDetail.clear();
            tDetail.rows.add(JSON.parse(data)).draw();
        }
    });
}
function showMine() {
    var spinnerId = 'sprinnerShowMine';
    var btnId = 'btnShowMine';
    var btnText = 'Permintaan Saya';
    addLoading(btnId, spinnerId);
    $.ajax({
        url: "model/RequestModel.php",
        type: "POST",
        data: "getMine",
        success: function (data) {
            tInfo.clear();
            tInfo.rows.add(JSON.parse(data)).draw();
            removeLoading(btnId, btnText, spinnerId);
        }
    });
}
function inputToList() {
    var tanggal = $("#tanggal").val();
    var idPrio = $("#prioritas :selected").val();
    var prioritas = $("#prioritas :selected").text();
    var kode_brg = $("#kode_brg").val();
    var nama_brg = $("#nama").val();
    var jumlah = $("#jumlah").val();
    var ket = $('#ket').val();

    // Map user ID and name
    var idSelector = function () { return this.id; };
    var users = $(":checkbox:checked").map(idSelector).get();

    if (nama_brg == "") {
        showAlert('Silahkan input nama barang !');
        $("#nama").focus();
        return false;
    }
    if (users.length <= 0) {
        showAlert('Silahkan pilih Pemohon !');
        return false;
    }
    if (jumlah == "" || jumlah <= 0) {
        showAlert("Jumlah barang harus diisi dan lebih dari nol.");
        $("#jumlah").focus();
        return false;
    }

    var objPorperti = {};
    // Cek apakah ada barang + penerima yang sama dengan yang di tabel form.
    var found = false;
    if (arrObject.length <= 0) {
        // jika barang baru, maka dibuatkan kode sementara
        if (kode_brg == "") kode_brg = "new_" + i;
        $.each(users, function (index, val) {
            var user = val.split('_');
            var userID = user[0];
            var userName = user[1];

            objPorperti = {
                'kode_brg': kode_brg,
                'nama_brg': nama_brg,
                'tanggal': tanggal,
                'prioritas': idPrio,
                'jumlah': jumlah,
                'id_user': userID,
                'nama_user': userName,
                'keterangan': ket
            }
            addRow(i, kode_brg, tanggal, prioritas, nama_brg, userName, jumlah, ket);
            arrObject.push(objPorperti);
            i++;
        });
    } else {
        var jml_keluar_lama = 0;
        var total = 0;
        for (var c = 0; c < arrObject.length; c++) {
            $.each(users, function (index, val) {
                var user = val.split('_');
                var userID = user[0];

                if (kode_brg == "") {
                    if (arrObject[c].nama_brg == nama_brg) {
                        jml_keluar_lama = arrObject[c].jumlah;
                        total = parseInt(jml_keluar_lama) + parseInt(jumlah);
                        found = true;
                    }
                } else {

                    if (kode_brg == arrObject[c].kode_brg && userID == arrObject[c].id_user) {
                        jml_keluar_lama = arrObject[c].jumlah;
                        total = parseInt(jml_keluar_lama) + parseInt(jumlah);
                        found = true;
                    }
                }
            });
        }
        if (!found) {
            // jika barang baru, maka dibuatkan kode sementara
            if (kode_brg == "") kode_brg = "new_" + i;
            $.each(users, function (index, val) {
                var user = val.split('_');
                var userID = user[0];
                var userName = user[1];

                objPorperti = {
                    'kode_brg': kode_brg,
                    'nama_brg': nama_brg,
                    'tanggal': tanggal,
                    'prioritas': idPrio,
                    'jumlah': jumlah,
                    'id_user': userID,
                    'nama_user': userName,
                    'keterangan': ket
                }
                addRow(i, kode_brg, tanggal, prioritas, nama_brg, userName, jumlah, ket);
                arrObject.push(objPorperti);
                i++;
            });
        } else {
            $.each(users, function (index, val) {
                var user = val.split('_');
                var userID = user[0];
                var userName = user[1];

                if (kode_brg == "") {
                    for (var c = 0; c < arrObject.length; c++) {
                        if (arrObject[c].nama_brg == nama_brg) {
                            jml_keluar_lama = arrObject[c].jumlah;
                            total = parseInt(jml_keluar_lama) + parseInt(jumlah);
                            kode_brg = arrObject[c].kode_brg;
                        }
                    }
                }

                // Jika barang sudah ada di tabel, maka update data JSON
                objIndex = arrObject.findIndex(obj => obj.kode_brg == kode_brg && obj.id_user === userID);
                // if not found or new user and product
                if (objIndex == -1) {
                    objPorperti = {
                        'kode_brg': kode_brg,
                        'nama_brg': nama_brg,
                        'tanggal': tanggal,
                        'prioritas': idPrio,
                        'jumlah': jumlah,
                        'id_user': userID,
                        'nama_user': userName,
                        'keterangan': ket
                    }
                    addRow(i, kode_brg, tanggal, prioritas, nama_brg, userName, jumlah, ket);
                    arrObject.push(objPorperti);
                    i++;
                } else {
                    //console.log("Before update: ", arrObject[objIndex]);
                    arrObject[objIndex].tanggal = tanggal;
                    arrObject[objIndex].jumlah = total;
                    arrObject[objIndex].keterangan = ket;
                    arrObject[objIndex].prioritas = idPrio;
                }
                const idx = objIndex + 1;
                $("#labelTanggal_" + idx).html(tanggal);
                $("#labelJumlah_" + idx).html(total);
                $("#labelKeterangan_" + idx).html(ket);
                $("#labelPrioritas_" + idx).html(prioritas);
                //console.log("After update: ", arrObject[objIndex]);
            });
        }
    }
    $("#nama").val("");
    $("#jumlah").val("");
    $("#prioritas").val("1").change();
    $("#jmlItem").text(i - 1);
    $("#stok").text("0");
    $("#nama").focus();
}
function inputToListUser() {
    var tanggal = $("#tanggal").val();
    var idPrio = $("#prioritas :selected").val();
    var prioritas = $("#prioritas :selected").text();
    var kode_brg = $("#kode_brg").val();
    var nama_brg = $.trim($("#nama").val());
    var jumlah = $("#jumlah").val();
    var id_user = $('#listUser').val();
    var nama_user = $('#listUser').text();
    var ket = $.trim($('#ket').val());

    if (nama_brg == "") {
        showAlert("Silahkan input nama barang terlebih dahulu.");
        $("#nama").focus();
        return false;
    }
    if (jumlah == "" || jumlah <= 0) {
        showAlert("Jumlah barang harus diisi dan lebih dari 0 ( Nol ).");
        $("#jumlah").focus();
        return false;
    } else {
        var objPorperti = {};
        // Cek apakah ada barang + penerima yang sama dengan yang di tabel form.
        var found = false;
        if (arrObject.length <= 0) {
            // jika barang baru, maka dibuatkan kode sementara
            if (kode_brg == "") kode_brg = "new_" + i;
            objPorperti = {
                'kode_brg': kode_brg,
                'nama_brg': nama_brg,
                'tanggal': tanggal,
                'prioritas': idPrio,
                'jumlah': jumlah,
                'id_user': id_user,
                'nama_user': nama_user,
                'keterangan': ket
            }
            addRowUser(i, kode_brg, tanggal, prioritas, nama_brg, jumlah, ket);
            arrObject.push(objPorperti);
            i++;
        } else {
            var jml_keluar_lama = 0;
            var total = 0;
            for (var c = 0; c < arrObject.length; c++) {
                // Jika barang baru maka validasi berdasarkan nama saja
                if (kode_brg == "") {
                    if (arrObject[c].nama_brg == nama_brg) {
                        jml_keluar_lama = arrObject[c].jumlah;
                        total = parseInt(jml_keluar_lama) + parseInt(jumlah);
                        found = true;
                    }
                } else {
                    if (kode_brg == arrObject[c].kode_brg && id_user == arrObject[c].id_user) {
                        jml_keluar_lama = arrObject[c].jumlah;
                        total = parseInt(jml_keluar_lama) + parseInt(jumlah);
                        found = true;
                    }
                }
            }

            if (!found) {
                // jika barang baru, maka dibuatkan kode sementara
                if (kode_brg == "") kode_brg = "new_" + i;
                objPorperti = {
                    'kode_brg': kode_brg,
                    'nama_brg': nama_brg,
                    'tanggal': tanggal,
                    'prioritas': idPrio,
                    'jumlah': jumlah,
                    'id_user': id_user,
                    'nama_user': nama_user,
                    'keterangan': ket
                }
                addRowUser(i, kode_brg, tanggal, prioritas, nama_brg, jumlah, ket);
                arrObject.push(objPorperti);
                i++;
            } else {
                if (kode_brg == "") {
                    for (var c = 0; c < arrObject.length; c++) {
                        if (arrObject[c].nama_brg == nama_brg) {
                            jml_keluar_lama = arrObject[c].jumlah;
                            total = parseInt(jml_keluar_lama) + parseInt(jumlah);
                            kode_brg = arrObject[c].kode_brg;
                        }
                    }
                }
                // Jika barang sudah ada di tabel, maka update data JSON
                objIndex = arrObject.findIndex(obj => obj.kode_brg == kode_brg && obj.id_user == id_user);
                //console.log("Before update: ", arrObject[objIndex]);
                arrObject[objIndex].tanggal = tanggal;
                arrObject[objIndex].jumlah = total;
                arrObject[objIndex].keterangan = ket;
                arrObject[objIndex].prioritas = idPrio;

                const idx = objIndex + 1;
                $("#labelTanggal_" + idx).html(tanggal);
                $("#labelJumlah_" + idx).html(total);
                $("#labelKeterangan_" + idx).html(ket);
                $("#labelPrioritas_" + idx).html(prioritas);
                //console.log("After update: ", arrObject[objIndex]);
            }
        }
        $("#nama").val("");
        $("#jumlah").val("");
        $("#prioritas").val("0").trigger("change");
        $("#jmlItem").text(i - 1);
        $("#nama").focus();
    }
}
function addRow(i, kode_brg, tanggal, prioritas, nama_brg, nama_user, jumlah, ket) {
    $("#tListPermintaan tbody")
        .prepend("<tr><td style='text-align:center' width='100px'><label id='labelTanggal_" + i + "'>" + tanggal + "</label></td>" +
            "<td>" + nama_brg + "</td>" +
            "<td style='text-align:center' width='80px'><label id='labelJumlah_" + i + "'>" + jumlah + "</label></td>" +
            "<td>" + nama_user + "</td>" +
            "<td><label id='labelKeterangan_" + i + "'>" + ket + "</label></td>" +
            "<td style='text-align:center'><label id='labelPrioritas_" + i + "'>" + prioritas + "</label></td>" +
            "</tr>");
}
function addRowUser(i, kode_brg, tanggal, prioritas, nama_brg, jumlah, ket) {
    $("#tListPermintaan tbody")
        .prepend("<tr><td style='text-align:center' width='100px'><label id='labelTanggal_" + i + "'>" + tanggal + "</label></td>" +
            "<td>" + nama_brg + "</td>" +
            "<td style='text-align:center' width='80px'><label id='labelJumlah_" + i + "'>" + jumlah + "</label></td>" +
            "<td><label id='labelKeterangan_" + i + "'>" + ket + "</label></td>" +
            "<td style='text-align:center'><label id='labelPrioritas_" + i + "'>" + prioritas + "</label></td>" +
            "</tr>");
}
function inputToDB() {
    disableButton();
    disableTakeButton();
    if (arrObject.length > 0) {
        var spinnerId = 'sprinnerAdd';
        var btnId = 'btnAddReq';
        var btnText = 'Kirim';
        addLoading(btnId, spinnerId);
        $.ajax({
            type: "POST",
            url: "model/RequestModel.php",
            data: { 'addRequest': JSON.stringify(arrObject) },
            success: function (data) {
                arrObjData = JSON.parse(data);
                showToasts(arrObjData, 'Permintaan Barang');
                resetAfterSubmit();
                showDetail(globalKodeBrg);
                removeLoading(btnId, btnText, spinnerId);
            }
        });
    } else {
        showAlert("Silahkan tambahkan dulu barang yang akan diminta.");
    }
}
function inputToDBUser() {
    disableButton();
    disableTakeButton();
    if (arrObject.length > 0) {
        var spinnerId = 'sprinnerAdd';
        var btnId = 'btnAddReq';
        var btnText = 'Kirim';
        addLoading(btnId, spinnerId);
        $.ajax({
            type: "POST",
            url: "model/RequestModel.php",
            data: { 'addRequest': JSON.stringify(arrObject) },
            success: function (data) {
                arrObjData = JSON.parse(data);
                $.each(arrObjData, function (key, value) {
                    var message = 'Permintaan <b>' + value.prodName + '</b> telah berhasil dikirim';
                    showToast(key, 'Permintaan Barang', message);
                });
                resetAfterSubmit();
                removeLoading(btnId, btnText, spinnerId);
            }
        });
    } else {
        showAlert("Silahkan tambahkan dulu barang yang akan diminta.");
    }
}
function editRequest() {
    var id = $('#idEdit').val();
    var prodId = $('#kodeBrgEdit').val();
    var prodName = $('#labelNamaBrg').text();
    var date = $('#tanggalEdit').val();
    var priority = $('#prioritasEdit').val();
    var quantity = $('#jumlahEdit').val();
    var idUser = $('#idUserEdit').val();
    var userName = $('#userNameEdit').val();
    var info = $('#ketEdit').val();

    var spinnerId = 'sprinnerEdit';
    var btnId = 'btnEditRequest';
    var btnText = 'Perbarui';

    if (quantity < 1) {
        showAlert('Silahkan isi jumlah terlebih dahulu.');
        $('#jumlahEdit').focus();
        return false;
    }
    disableButton();
    disableTakeButton();
    addLoading(btnId, spinnerId);
    $.ajax({
        url: "model/RequestModel.php",
        type: "POST",
        data: {
            'editRequest': '1',
            'id': id,
            'prodId': prodId,
            'prodName': prodName,
            'date': date,
            'priority': priority,
            'quantity': quantity,
            'idUser': idUser,
            'userName': userName,
            'info': info
        },
        success: function (data) {
            var objData = JSON.parse(data);
            $("#tablePermintaan").DataTable().ajax.reload();
            tDetail.clear().draw();
            tInfo.clear().draw();
            showToast(objData.prodID, objData.title, objData.message);
            removeLoading(btnId, btnText, spinnerId);
            $('.modal').modal('hide');
        }
    });
}
function deleteRequest() {
    var id = $('#idEdit').val();
    var prodId = $('#kodeBrgHapus').val();
    var prodName = $('#namaBrgHapus').text();
    var quantity = $('#jmlHapus').text();
    var userName = $('#namaHapus').text();

    var spinnerId = 'sprinnerDelete';
    var btnId = 'btnDeleteRequest';
    var btnText = 'Hapus';

    disableButton();
    disableTakeButton();
    addLoading(btnId, spinnerId);
    $.ajax({
        url: "model/RequestModel.php",
        type: "POST",
        data: {
            'deleteRequest': '1',
            'id': id,
            'prodId': prodId,
            'prodName': prodName,
            'quantity': quantity,
            'userName': userName,
        },
        success: function (data) {
            //console.log("before Parse: "+ data);
            var objData = JSON.parse(data);
            //console.log("after Parse: "+ objData);
            $("#tablePermintaan").DataTable().ajax.reload();
            tDetail.clear().draw();
            tInfo.clear().draw();
            showToast(objData.prodID, objData.title, objData.message);
            removeLoading(btnId, btnText, spinnerId);
            $('.modal').modal('hide');
        }
    });
}
function deleteProductRequest() {
    var prodId  = $("#prodReqID").val();
    var prodName= $("#prodNameDelete").text();
    var qtyProdReq = $("#qtyProdReq").text();

    var spinnerId = 'sprinnerProductDelete';
    var btnId = 'btnDeleteProductRequest';
    var btnText = 'Hapus';

    disableButton();
    disableTakeButton();
    addLoading(btnId, spinnerId);
    $.ajax({
        url: "model/RequestModel.php",
        type: "POST",
        data: {
            'deleteProductRequest': '1',
            'prodReqID': prodId,
            'prodNameDelete': prodName,
            'qtyProdReq': qtyProdReq
        },
        success: function (data) {
            //console.log("before Parse: "+ data);
            var objData = JSON.parse(data);
            //console.log("after Parse: "+ objData);
            $("#tablePermintaan").DataTable().ajax.reload();
            tDetail.clear().draw();
            tInfo.clear().draw();
            showToast(objData.prodID, objData.title, objData.message);
            removeLoading(btnId, btnText, spinnerId);
            $('.modal').modal('hide');
        }
    });
}
function resetAfterSubmit() {
    resetListPermintaan();
    $("#tablePermintaan").DataTable().ajax.reload();
    tDetail.clear().draw();
    tInfo.clear().draw();
    $('.modal').modal('hide');
}
function resetListPermintaan() {
    i = 1;
    arrObject.length = 0;
    $("#kode_brg").val("");
    $("#prioritas").val("1").change();
    $("#nama").val("");
    $("#jumlah").val("");
    $('#ket').val("");
    $("#jmlItem").text('0');
    $("#tListPermintaan > tbody").html("");
    $("#nama").focus();
}
$(function () {
    $("#formTambah").on('shown.bs.modal', function () {
        $(this).find('#nama').focus();
    });
    $("#formEdit").on('shown.bs.modal', function () {
        $(this).find('#prioritasEdit').focus();
    });
    $("#formAmbil").on('shown.bs.modal', function () {
        $(this).find('#jumlahAmbil').focus();
    });
    $(".tanggal").val($.datepicker.formatDate('dd-mm-yy', new Date()));
    $(".tanggal").datepicker({
        dateFormat: "dd-mm-yy",
        maxDate: new Date(),
        changeMonth: true,
        changeYear: true,
        beforeShow: function () { $(".ui-datepicker").css('font-size', 13); }
    });
});