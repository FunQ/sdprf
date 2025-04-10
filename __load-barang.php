<?php
    // Menampilkan list seluruh Barang
    $idxBrgKeluar	= 0;
    $arrBrgKeluar	= array();
    if(isset($_SESSION['log']) && $_SESSION['log']['akses'] == 'Admin')
        $qryBrgKeluar	= $mysqli->query("SELECT * FROM stok ORDER BY nama_brg");
    else
        $qryBrgKeluar	= $mysqli->query("SELECT * FROM stok WHERE jumlah > 0 ORDER BY nama_brg");
    while($data = mysqli_fetch_array($qryBrgKeluar)){
        $arrBrgKeluar[$idxBrgKeluar] = new Product();
        $arrBrgKeluar[$idxBrgKeluar]->kode_brg	= $data['kode_brg'];
        $arrBrgKeluar[$idxBrgKeluar]->nama_brg	= $data['nama_brg'];
        $arrBrgKeluar[$idxBrgKeluar]->jumlah	= $data['jumlah'];
        $idxBrgKeluar++;
    }

    // Menampilkan list seluruh Barang untuk akses User dan Laporan
    $idxBrgMasuk	= 0;
    $arrBrgMasuk	= array();
    $qryBrgMasuk	= $mysqli->query("SELECT * FROM stok ORDER BY nama_brg");
    while($data = mysqli_fetch_array($qryBrgMasuk)){
        $arrBrgMasuk[$idxBrgKeluar] = new Product();
        $arrBrgMasuk[$idxBrgKeluar]->kode_brg	= $data['kode_brg'];
        $arrBrgMasuk[$idxBrgKeluar]->nama_brg	= $data['nama_brg'];
        $arrBrgMasuk[$idxBrgKeluar]->jumlah 	= $data['jumlah'];
        $idxBrgKeluar++;
    }
?>