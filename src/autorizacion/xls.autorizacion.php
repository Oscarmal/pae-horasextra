<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
require_once($Path[src].'autorizacion/dao.autorizacion.php');
function xsl_autorizaciones($ids){
      global $cfg;
      $sqlData = array(
      			 auth 	   => 1
                        ,id_horas_extra=> $ids
      			,orden         => 'a.id_horas_extra DESC'
      		);
      $tabla = xls_select($sqlData);
      $nameArchivo = 'HE_Horas-Extra';
      $tituloTabla = 'HE - Horas Extra';
      $titulos = array('ID','Nombre Completo','No. Empleado','Fecha','Horas','Estatus','Capturado por','Capturado el');
      $directorio = $cfg[path_docs].'autorizacion/';
      $xlsData = array(
                         descarga         => false
                        ,datos            => $tabla
                        ,colsTitulos      => $titulos
                        ,archivo          => $nameArchivo
                        ,tituloTabla      => $tituloTabla
                        ,hoja             => ''
                        ,directorio       => $directorio
                  );
      $xls = xls($xlsData);
      $updateXls = array(
                         auth           => 1
                        ,id_horas_extra => $ids
                        ,xls            => $xls[filename]
                  );
      $updateXls = autorizacion_update($updateXls); 
      return $xls;
}
/*O3M*/
?>
