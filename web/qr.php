<?php
include "phpqrcode/qrlib.php";
// create a QR Code with this text and display it
if(isset($_GET['a'])){QRcode::png($_GET['a'],false,"H",3,0);}
else{QRcode::png('');}