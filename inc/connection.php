<?php
try{
$db = new PDO("mysql:host=localhost;dbname=time_tracker",'root','');
}
catch(Exception $e){
  echo "Nasol ".$e->getMessage();
  exit();
}
