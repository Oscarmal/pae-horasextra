<?php session_name('o3m_he'); session_start(); if(isset($_SESSION['header_path'])){include_once($_SESSION['header_path']);}else{header('location: '.dirname(__FILE__));}
require_once($Path[src].'admin/dao.admin.php');
function xsl_resumen($ids=array()){
      global $usuario, $cfg;
      $sqlData = array(
      			 auth 	   => 1
                        ,id_horas_extra=> $ids
      		);
      $tabla = select_xls_resumen($sqlData);
      $nameArchivo = 'HE_Horas-Extra_Resumen_'.$tabla[0][id_empresa];
      $tituloTabla = 'HE - Horas Extra';
      $titulos = array(
                         'ID HE'
                        ,'ID Empresa'
                        ,utf8_decode('Empresa')
                        ,utf8_decode('Nombre Completo')
                        ,'No. Empleado'
                        ,'Fecha'
                        ,'Semana-iso8601'
                        ,'Horas'
                        ,'Dobles'
                        ,'Triples'
                        ,'Rechazadas'
                        ,utf8_decode('Año')
                        ,'Periodo'         
                        ,'Semana'
                  );
      // $directorio = $cfg[path_docs].'autorizacion/';
      $directorio = $cfg[path_tmp];
      $xlsData = array(
                         descarga         => false
                        ,datos            => $tabla
                        ,colsTitulos      => $titulos
                        ,archivo          => $nameArchivo
                        ,tituloTabla      => $tituloTabla
                        ,hoja             => ''
                        ,directorio       => $directorio
                        ,id_empresa       => $usuario[id_empresa]
                  );
      $xls = xls($xlsData);      
      return $xls;
}


function xsl_nomina($ids=array()){
      global $usuario, $cfg;
      // Extrae datos para crear xls
      $sqlData = array(
                         auth          => 1
                        ,id_horas_extra=> $ids
                        ,orden         => 'a.id_horas_extra DESC'
                  );
      $tabla = select_xls_nomina($sqlData);
      $nameArchivo = 'HE_Horas-Extra_Nomina';
      $tituloTabla = false;
      $titulos = array(
                         'ID Empleado'
                        ,'Semana'
                        ,'Concepto'
                        ,'Cantidad'                        
                  );
      $directorio = $cfg[path_tmp];
      $xlsData = array(
                         descarga         => false
                        ,datos            => $tabla
                        ,colsTitulos      => $titulos
                        ,archivo          => $nameArchivo
                        ,tituloTabla      => $tituloTabla
                        ,hoja             => ''
                        ,directorio       => $directorio
                        ,id_empresa       => $usuario[id_empresa]
                  );
      // Crea xls
      $xls = xls($xlsData);
      // Actualiza registros con nombre de xls y semana
      $updateXls = array(
                         auth           => 1
                        ,id_horas_extra => $ids
                        ,xls            => $xls[filename]
                  );
      $updateXls = update_xls($updateXls); 
      return $xls;
}

function xls_nomina_rebuild($xls=array()){
      global $usuario, $cfg;
      // Extrae datos para crear xls
      $sqlData = array(
                         auth          => 1
                        ,orden         => 'a.id_horas_extra DESC'
                  );
      $tabla = select_xls_nomina_rebuild($sqlData);
      $nameArchivo = 'HE_Horas-Extra_Nomina';
      $tituloTabla = false;
      $titulos = array(
                         'ID Empleado'
                        ,'Semana'
                        ,'Concepto'
                        ,'Cantidad'                        
                  );
      $directorio = $cfg[path_tmp];
      $xlsData = array(
                         descarga         => false
                        ,datos            => $tabla
                        ,colsTitulos      => $titulos
                        ,archivo          => $nameArchivo
                        ,tituloTabla      => $tituloTabla
                        ,hoja             => ''
                        ,directorio       => $directorio
                        ,id_empresa       => $usuario[id_empresa]
                  );
      // Crea xls
      $xls = xls($xlsData);
      return $xls;
}
/*O3M*/
?>
