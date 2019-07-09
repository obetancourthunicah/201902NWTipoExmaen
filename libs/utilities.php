<?php
/**
 * PHP Version 5
 * Libreria de Utilidades
 *
 * @category Utilidades
 * @package  Utilities
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  Comercial http://
 *
 * @version 1.0.0
 *
 * @link http://url.com
 */
require_once "libs/template_engine.php";

define('ENCRYPT_SECRET', 'SOMEEas@$ad_11.˜');

$global_context = array();

/**
 * Agrega Variable al Contexto Global
 *
 * @param string $key   Nombre de la Variable
 * @param any    $value Valor de la Variable
 *
 * @return void
 */
function addToContext($key, $value)
{
    global $global_context;
    $global_context[$key] = $value;
}

/**
 * Elimina Variable del Contexto Global
 *
 * @param string $key Nombre de la Variable
 *
 * @return void
 */
function unsetContext($key)
{
    global $global_context;
    unset($global_context[$key]);
}

/**
 * Redirigir a Url con Mensaje en Javascript
 *
 * @param string $message Mensaje a mostrar
 * @param string $url     Url a redirigir
 *
 * @return void
 */
function redirectWithMessage($message, $url="index.php")
{
    echo "<script>alert('$message'); window.location='$url';</script>";
    die();
}

/**
 * Redirigir a Url Con mensaje en HTML
 *
 * @param string $message Mensaje a mostrar antes de redirigir
 * @param string $url     URL a redirigir
 *
 * @return void
 */
function redirectWithHtmlMessage($message, $url="index.php")
{
    $messageArray = array(
        "message" => $message,
        "url" => $url
    );
    renderizar("htmlmessage", $messageArray);
    die();
}

/**
 * Reidirige a URL
 *
 * @param string $url Redirige a la url especificada
 *
 * @return void
 */
function redirectToUrl($url)
{
    header("Location: $url");
    die();
}

/**
 * Combina el arreglo de origen con el arreglo destino donde las llaves
 * del destino coinciden con las llaves del origen.
 *
 * @param array $origin  Arreglo de Origen
 * @param array $destiny Arreglo de Destino
 * 
 * @return void
 */
function mergeArrayTo(&$origin, &$destiny)
{
    if (is_array($origin) && is_array($destiny)) {
        foreach ($origin as $okey => $ovalue) {
            if (isset($destiny[$okey])) {
                $destiny[$okey] = $ovalue;
            }
        }
    }
}

/**
 * Combina el arreglo de origen con el arreglo destino donde las llaves
 * del destino coinciden con las llaves del origen y agregando las 
 * llaves no existentes a las de origen.
 *
 * @param array $origin  Arreglo de Origen
 * @param array $destiny Arreglo de Destino
 * 
 * @return void
 */
function mergeFullArrayTo(&$origin, &$destiny)
{
    if (is_array($origin) && is_array($destiny)) {
        foreach ($origin as $okey => $ovalue) {
            $destiny[$okey] = $ovalue;
        }
    }
}

/**
 * Agregar Link a Hoja de Estilo solo para la plantilla
 *
 * @param string $uri URI de la ruta a la hoja de css
 * 
 * @return void
 */
function addCssRef($uri)
{
    global $global_context;
    if (isset($global_context["css_ref"])) {
        $global_context["css_ref"][] = array("uri"=>$uri);
    } else {
        $global_context["css_ref"] = array(array("uri"=>$uri));
    }
}

/**
 * Agrega Archivo Javascript al Cuerpo de la página
 *
 * @param string  $uri   Ruta al archivo javascript
 * @param boolean $first Determina si el archivo javascript se carga antes del body o al final del body
 * 
 * @return void
 */
function addJsRef($uri, $first = true)
{
    global $global_context;
    if (isset($global_context["js_ref"])) {
        $global_context["js_ref"][] = array("uri"=>$uri);
    } else {
        $global_context["js_ref"] = array(array("uri"=>$uri));
    }
}

/**
 * Agrega una columna con el texto selected si el valor del atributo y el atributo coinciden
 *
 * @param array  $arreglo     Arreglo con la data a comparar
 * @param string $atributo    Nombre de la Columna cuyo valor se compara
 * @param [type] $valor       Valor a comparar 
 * @param string $selAtributo El nombre de la columna que tendra como selected o texto vacio.
 * 
 * @return array
 */
function addSelectedCmbArray($arreglo, $atributo, $valor, $selAtributo="selected")
{
    for ($i = 0; $i < count($arreglo); $i++) {
        $arreglo[$i][$selAtributo] = ($arreglo[$i][$atributo]==$valor)?"selected":"";
    }
    return $arreglo;
}

/**
 * Descarga el Archivo solicitado
 *
 * @param string $source   El archivo de donde se enviara el documento
 * @param string $filename El nombre que llevará el documento
 * 
 * @return void
 */
function sendFile($source, $filename)
{
    $fp = @fopen($source, 'rb');
    header('Content-Type: "application/octet-stream"');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Expires: 0');
    header("Content-Transfer-Encoding: binary");
    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    } else {
        header('Pragma: no-cache');
    }
    header("Content-Length: ".filesize($fp));
    fpassthru($fp);
    fclose($fp);
    die();
}

/**
 * Firma el mensaje
 *
 * @param [type] $message Mensaje a firmar
 * @param [type] $key     Llave con la que se firma
 * 
 * @return hash
 */
function sign($message, $key)
{
    return hash_hmac('sha256', $message, $key) . $message;
}

/**
 * Verifica el Mensaje
 *
 * @param [type] $bundle Verifica un mensaje encriptado con la llave
 * @param [type] $key    Llave a utilizar
 * 
 * @return boolean
 */
function verify($bundle, $key) 
{
    return hash_equals(
        hash_hmac('sha256', mb_substr($bundle, 64, null, '8bit'), $key),
        mb_substr($bundle, 0, 64, '8bit')
    );
}

/**
 * Obtiene la llave de un mensaje
 *
 * @param [type]  $string  cadena
 * @param integer $keysize tamaño de la llave
 * 
 * @return hash
 */
function getKey($string, $keysize = 16) 
{
    return hash_pbkdf2('sha256', $string, '10djfh7%43', 100000, $keysize, true);
}

/**
 * Encripta el mensaje
 *
 * @param string $message Mensaje a encriptar
 * @param string $secret  Llave a utilizar
 * 
 * @return string
 */
function encrypt($message, $secret) 
{
    $iv = random_bytes(16);
    $key = getKey($secret);
    $result = sign(openssl_encrypt($message, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv), $key);
    return bin2hex($iv).bin2hex($result);
}

/**
 * Desencripta un mensaje
 *
 * @param [type] $hash   Mensaje a desencriptar
 * @param [type] $secret Secreto para desencriptar
 * 
 * @return string
 */
function decrypt($hash, $secret) 
{
    $iv = hex2bin(substr($hash, 0, 32));
    $data = hex2bin(substr($hash, 32));
    $key = getKey($secret);
    if (!verify($data, $key)) {
        return null;
    }
    return openssl_decrypt(mb_substr($data, 64, null, '8bit'), 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv);
}

/**
 * Exportación Simple de Archivo de Excel como Separado por Tabuladores
 *
 * @param array $dataArray Arreglo con la data a Exportar
 * 
 * @return void
 */
function exportSimpleExcelFile($dataArray)
{
    //header("Content-Type: application/xls");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ConsultasCasos.xls"');
    header('Cache-Control: max-age=0');
    header("Pragma: no-cache");
    header("Expires: 0");
    //tabulador
    $sep = "\t";
    //encabezados
    foreach ($dataArray[0] as $header=>$value) {
          print $header.$sep;
    }
    print("\n");
    //contenido
    foreach ($dataArray as $data) {
        foreach ($data as $clave=>$valor) {
            print $valor.$sep;
        }
        print("\n");
    }
    die();
}

//https://github.com/PHPOffice/PHPExcel
/**
 * Exporta Archivo de Excel en formato nativo xlxs
 *
 * @param array $dataArray Arreglo de Datos a exportar
 * 
 * @return void
 */
function exportRealExcelFile($dataArray)
{
    /**
     * PHPExcel
     *
     * Copyright (c) 2006 - 2015 PHPExcel
     *
     * This library is free software; you can redistribute it and/or
     * modify it under the terms of the GNU Lesser General Public
     * License as published by the Free Software Foundation; either
     * version 2.1 of the License, or (at your option) any later version.
     *
     * This library is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
     * Lesser General Public License for more details.
     *
     * You should have received a copy of the GNU Lesser General Public
     * License along with this library; if not, write to the Free Software
     * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
     *
     * @category  PHPExcel
     * @package   PHPExcel
     * @copyright Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
     * @license   http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
     * @version   ##VERSION##, ##DATE##
     */
    
     error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
    date_default_timezone_set('America/Tegucigalpa');
    if (PHP_SAPI == 'cli')
        die('This example should only be run from a Web Browser');
    /** 
     * Include PHPExcel 
     */
    require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    // Set document properties
    $objPHPExcel->getProperties()->setCreator($_SESSION['userScreenName'])
        ->setLastModifiedBy($_SESSION['userScreenName'])
        ->setTitle("Legal Bureau")
        ->setSubject("Consulta Casos ")
        ->setDescription("Este es un archivo excel generado desde el sistema.")
        ->setKeywords("Microsoft Office Excel")
        ->setCategory("Archivo Final");

    //Cabeceras del Excel
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Nombre')
        ->setCellValue('B1', 'Fecha Creado')
        ->setCellValue('C1', 'Monto')
        ->setCellValue('D1', 'Estado');

    //Cuerpo del Excel
    $fila = 2;
    foreach ($dataArray as $data) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, $data['Nombre'])
            ->setCellValue('B'.$fila, $data['Fecha_creado'])
            ->setCellValue('C'.$fila, $data['Monto'])
            ->setCellValue('D'.$fila, $data['Estado']);
        $fila++;
    }

    // Nombre de la hoja de trabajo
    $objPHPExcel->getActiveSheet()->setTitle('ConsultaCasos');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ConsultasCasos.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}

?>
