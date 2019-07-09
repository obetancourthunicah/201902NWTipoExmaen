<?php
require "models/support/bitacora.model.php";

function addBitacora($prg, $desc,$obsr,$tipo)
{
      $fch = Date('Y-m-d h:i:s');
      $user = intval($_SESSION["userCode"]);
      insertBitacora($fch, $prg,  $desc, $obsr, $tipo, $user);
}
  
?>
