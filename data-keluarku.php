<?php
    session_start();
    // DB table to use
    $table = 'data_keluar';
    
    // Table's primary key
    $primaryKey = 'id';
    
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array(
            'db'        => 'tanggal',
            'dt'        => 0,
            'formatter' => function( $d, $row ) {
                return date( 'd-m-Y', strtotime($d));
            }
        ),
        array( 'db' => 'nama_brg',  'dt' => 1 ),
        array( 'db' => 'jumlah',    'dt' => 2 ),
        array( 'db' => 'keterangan','dt' => 3 )
    );
    
    require_once "model/__conn-datatable.php";
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * If you just want to use the basic configuration for DataTables with PHP
    * server-side, there is no need to edit below this line.
    */
    
    require( 'ssp.class.php' );
    
    echo json_encode(
        SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, "id_user='".$_SESSION['log']['id_user']."'" )
    );
?>