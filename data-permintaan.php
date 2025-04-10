<?php
    // DB table to use
    $table = 'data_permintaan';
    
    // Table's primary key
    $primaryKey = 'kode_brg';
    
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'kode_brg',  'dt' => 0 ),
        array( 'db' => 'nama_brg',  'dt' => 1 ),
        array( 'db' => 'total',     'dt' => 2 ),
        array( 'db' => 'stok',      'dt' => 3 ),
        array(
            'db' => 'prioritas',
            'dt' => 4,
            'formatter' => function( $d, $row ) {
                return ($d == 0) ? "Tidak": "Ya";
        }),
        array( 'db' => 'waktu',      'dt' => 5 )
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