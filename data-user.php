<?php
    // DB table to use
    $table = 'user';
    
    // Table's primary key
    $primaryKey = 'id_user';
    
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'id_user',   'dt' => 0 ),
        array( 'db' => 'nama',      'dt' => 1 ),
        array( 'db' => 'username',  'dt' => 2 ),
        array( 'db' => 'email',     'dt' => 3 ),
        array( 'db' => 'akses',     'dt' => 4 ),
        array( 'db' => 'gender',    'dt' => 5 ),
        array( 'db' => 'waktu',    'dt' => 6 )
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