<?php

require_once "models/examendata.model.php";
function run()
{
    $viewData = array();



    renderizar("examenform", $viewData);
}

run();
?>
