<?php
include 'serverData.php';
try {
    $connection = new PDO("mysql:host=localhost;dbname=flutter_app;charset=utf8", "root", "");

} catch (PDOException $err) {
    exit("no connection");
}
sleep(2);

$sever_data = new ServerData($connection);

if (isset($_GET['action'])) {
   $method_name=$_GET['action'];
   if(method_exists($sever_data,$method_name)){
       $sever_data->$method_name($_GET);
   }
   //how to push it
}
