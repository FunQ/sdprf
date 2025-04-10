<?php
    require_once "model/__connection.php";

    if(isset($_GET['keyword'])){
        $keyword = $_GET['keyword'];
        $qry = $mysqli->query("SELECT * FROM stok WHERE nama_brg LIKE '%$keyword%' ORDER BY nama_brg LIMIT 15");    
    }else{
        $qry = $mysqli->query("SELECT * FROM stok ORDER BY nama_brg LIMIT 15");
    }
    
    $arrJSON = array();
    while($data = $qry->fetch_assoc()){
        $col['kode_brg']    = $data['kode_brg'];
        $col['value']       = $data['nama_brg'];    // WAJIB pakai key "value" saat menggunakan jquery autocomplete
        $col['jumlah']      = $data['jumlah'];
        $col['keterangan']  = $data['keterangan'];
        array_push($arrJSON, $col);
    }
    echo json_encode($arrJSON);
    $qry->free_result();
?>