<?php
error_reporting(0);
date_default_timezone_set('Asia/Jakarta'); //Sesuikan TimeZonenya
//////////////Config Database///////////////
$SQL 	= mssql_connect('192.168.11.222\SANGGABUANA','p2k','kpt@#1234')or die("er");
$DBSQL	= mssql_select_db("keuanganfix")or die('er');

////////////////////////////////////////////

///////////Include Function Parsing/////////

require('class_parsing.php');
$parsing = new Parsing();

////////////////////////////////////////////

////////////////Client Area/////////////////

$cid = "053"; //Client ID
$secret = "d68f045c09ca1f2874e82952b4d377aa"; //Secret ID
////////////////////////////////////////////
$data = file_get_contents('php://input');
$data_json = json_decode($data, true);
if (!$data_json) {
    echo'{"status" : "001"}';
}else{
    if ($data_json['client_id'] === $cid) {
        $d= $parsing->parseData($data_json['data'], $cid, $secret);
        if (!$d) {
            echo'{"status" : "001"}';
        }else {
            $que=mssql_query(" 
              insert into p2k(nama,no_pokok,nosel,tanggal,no_nota,kmhs,totBayar,detail) 
              values('$d[nama]','$d[no_pokok]','$d[nosel]','$d[tanggal]','$d[no_nota]','$d[kmhs]','$d[total_pembayaran]','$d[detail_pembayaran]')");
            if($que){
                echo'{"status" : "000"}'; //Jika Sukses
            }else{
                echo'{"status" : "001"}'; //Jika Sukses
            }
        }
    }else{echo'{"status" : "001"}';}
}
?>