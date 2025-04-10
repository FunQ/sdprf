<?php
    // Menampilkan list seluruh User
    $idxUser	= 0;
    $arrUser	= array();
    $qryUser	= $mysqli->query("SELECT * FROM user ORDER BY nama");
    while($data = $qryUser->fetch_assoc()){
        $arrUser[$idxUser] = new User();
        $arrUser[$idxUser]->id_user     = $data['id_user'];
        $arrUser[$idxUser]->nama_user	= $data['nama'];
        $arrUser[$idxUser]->akses       = $data['akses'];
        $arrUser[$idxUser]->gender      = $data['gender'];
        $idxUser++;
    }
?>