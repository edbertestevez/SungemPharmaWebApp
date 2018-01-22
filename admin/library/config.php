<?php
session_start();

// config control 
$hosted = false;

if($hosted){
  define('DB_SERVER','localhost');
  define('DB_USERNAME','id2192302_sungemadmin');
  define('DB_PASSWORD','sungem');
  define('DB_DATABASE','id2192302_db_sungempharma');
}else{
  define('DB_SERVER','localhost');
  define('DB_USERNAME','root');
  define('DB_PASSWORD','');
  define('DB_DATABASE','db_sungem');
}