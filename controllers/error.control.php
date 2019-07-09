<?php
/* Error Controller
 * 2014-10-14
 * Created By OJBA
 * Last Modification 2014-10-14 20:04
 */

  function run(){
    http_response_code(404);
    renderizar("error", array("page_title"=>"Error 404"));
  }
 

  run();
?>
