<?php

  function isEmpty($value){
    return preg_match('/^\s*$/', $value) ;
  }

  function isValidEmail($value){
    return filter_var($value, FILTER_VALIDATE_EMAIL);
  }

  function isValidPassword($value){
    return preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%])[0-9A-Za-z!@#$%]{8,32}$/', $value) ;
  }

  function isValidText($value){
    return preg_match("/^[a-zA-Z 'áéíóúüÁÉÍÓÚÜÑñ0-9\-&:]*$/",$value);
  }
?>
