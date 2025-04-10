<?php
    // DB table to use
    $table = 'data_masuk';
    
    // Table's primary key
    $primaryKey = 'id';
    
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'id',      'dt' => 0 ),
        array(
            'db'        => 'tanggal',
            'dt'        => 1,
            'formatter' => function( $d, $row ) {
                return date( 'd-m-Y', strtotime($d));
            }
        ),
        array( 'db' => 'kode_brg',  'dt' => 2 ),
        array( 'db' => 'nama_brg',  'dt' => 3 ),
        array( 'db' => 'jumlah',    'dt' => 4 ),
        array( 'db' => 'id_user',   'dt' => 5 ),
        array( 'db' => 'nama',      'dt' => 6 ),
        array( 'db' => 'keterangan','dt' => 7 ),
        array( 'db' => 'waktu','dt' => 8 )
    );
    
    require_once "model/__conn-datatable.php";
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * If you just want to use the basic configuration for DataTables with PHP
    * server-side, there is no need to edit below this line.
    */
    
    require( 'ssp.class.php' );
    
    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
?>