<?php
//middleware de configuración de todo el sitio
require_once "libs/parameters.php";
function site_init(){
    global $host_server;
    addToContext("page_title","Examen de Segundo Parcial");
    addToContext("max_file_size",20); // In Megas
    addToContext("host_server",$host_server); 
    date_default_timezone_set ( "America/Tegucigalpa" );
}
site_init();

?>
