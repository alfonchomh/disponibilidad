<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';
include 'conexion.php';
$archivoExcel="VIDEO.xlsx";

class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
    public function __construct($Columns,$rowIni=0,$rowFin=0) {
        $this->columns = $Columns;
        $this->rowIni=$rowIni;
        $this->rowFin=$rowFin;
    }

	public function readCell($column, $row, $worksheetName = '') {

		// Read title row and rows 0 - 30
		if($this->rowIni>0 && $this->rowFin>0){
			if ($row >= $this->rowIni && $row <= $this->rowFin) {
				//Read Columns $columns[]
			 	if (in_array($column, $this->columns)) {
              		return true;
          		}
			}

		}else if($this->rowIni>0){

			if ($row >= $this->rowIni) {
				//Read Columns $columns[]
			 	if (in_array($column, $this->columns)) {
              		return true;
          		}
			}

		}else if($this->rowFin>0){
			if ($row <= $this->rowFin) {
				//Read Columns $columns[]
			 	if (in_array($column, $this->columns)) {
              		return true;
          		}
			}


		}else{
			if (in_array($column, $this->columns)) {
          		return true;
      		}
		}

		return false;
	}
}
function excelExec8k($archivoExcel,$hoja,$columnas){

	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	//$objReader->setLoadSheetsOnly("Detalle 7K´S"); 
	$objReader->setLoadSheetsOnly($hoja); 
	$objReader->setReadDataOnly(true);
	$objReader->setReadFilter( new MyReadFilter($columnas,2 ,null) );

	$objPHPExcel = $objReader->load($archivoExcel);
	$objWorksheet = $objPHPExcel->getActiveSheet();

	$i=0;
	foreach ($objWorksheet->getRowIterator() as $row) {
	  $cellIterator = $row->getCellIterator();
	  $cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
	                                                     // even if it is not set.
	                                                     // By default, only cells
	                                                     // that are set will be
	                                                     // iterated.
	  if($i==0){
	  	$i++;
	  	continue;
	  }
	  $j=0;
	  $row=array();
	  foreach ($cellIterator as $cell) {
	    $row[]=preg_replace("/'/", "", $cell->getValue());
	    $j++;

	    if($j==count($columnas)){
	    	break;
	    }
	  }
	  if(count($row)>0){
		  $query="INSERT INTO stvs_8k
			(
			id_stv,
			id_thales,
			c2_conexion,
			ip_man,
			vlan,
			enlace,
			fecha_consulta)
			VALUES
			('$row[0]',
			'$row[1]',
			'$row[2]',
			'$row[3]',
			'$row[6]',
			'$row[7]',
			sysdate())";
			$result1=conexion('bgpr','vlan',$query);

			if($result1[0]['evento']=='correcto'){
				$id=$result1[0]['id'];
				$query="INSERT INTO stvs_8k_detalles
				(id_stv8k,
				ipcamara,
				direccion_ip_lan,
				tipo_poste,
				medio,
				edo_router,
				estado_camara,
				delegacion,
				central_survey,
				referencia_sisa,
				ups,
				time,
				bgp,
				fecha_validacion_enlace,
				sector,
				direccion,
				senas_esquina,
				colonia,
				a_tipo,
				a_nombre,
				postes_coordx,
				postes_coordy,
				postes_direccion,
				postes_esquina,
				postes_colonia,
				postes_delegacion,
				postes_cp,
				comunidad_snmp,
				puerto,
				ip,
				firmware_samsung,
				actualizacion)
				VALUES
				($id,
				'$row[4]',
				'$row[5]',
				'$row[8]',
				'$row[9]',
				'$row[10]',
				'$row[11]',
				'$row[12]',
				'$row[13]',
				'$row[14]',
				'$row[15]',
				'$row[16]',
				'$row[17]',
				'$row[18]',
				'$row[19]',
				'$row[20]',
				'$row[21]',
				'$row[22]',
				'$row[23]',
				'$row[24]',
				'$row[25]',
				'$row[26]',
				'$row[27]',
				'$row[28]',
				'$row[29]',
				'$row[30]',
				'$row[31]',
				'$row[32]',
				'$row[33]',
				'$row[34]',
				'$row[35]',
				'$row[36]')";
				$result2=conexion('bgpr','vlan',$query);
				if($result2[0]['evento']=='error'){
					echo($result2[0]['msg']);
				}
			}
		}
	$i++;
	}
}
function excelExec7k($archivoExcel,$hoja,$columnas){

	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	//$objReader->setLoadSheetsOnly("Detalle 7K´S"); 
	$objReader->setLoadSheetsOnly($hoja); 
	$objReader->setReadDataOnly(true);
	$objReader->setReadFilter( new MyReadFilter($columnas,2 ,null) );

	$objPHPExcel = $objReader->load($archivoExcel);
	$objWorksheet = $objPHPExcel->getActiveSheet();

	$i=0;
	foreach ($objWorksheet->getRowIterator() as $row) {
	  $cellIterator = $row->getCellIterator();
	  $cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
	                                                     // even if it is not set.
	                                                     // By default, only cells
	                                                     // that are set will be
	                                                     // iterated.
	  if($i==0){
	  	$i++;
	  	continue;
	  }
	  $j=0;
	  $row=array();
	  foreach ($cellIterator as $cell) {
	    $row[]=preg_replace("/'/", "", $cell->getValue());
	    $j++;

	    if($j==count($columnas)){
	    	break;
	    }
	  }
	  if(count($row)>0){
		  $query="INSERT INTO stvs_7k
			(id_poste,
			c2,
			c2_respaldo,
			vlan_trabajo,
			vlan_respaldo,
			referencia_trabajo,
			referencia_respaldo,
			ref_c2_trabajo,
			ref_c2_respaldo,
			ip_man,
			ip_man_respaldo,
			gigabitethernet_trabajo,
			gigabitethernet_respaldo,
			repisa,
			fecha_consulta)
			VALUES
			('$row[0]',
			'$row[1]',
			'$row[2]',
			'$row[4]',
			'$row[5]',
			'$row[6]',
			'$row[7]',
			'$row[9]',
			'$row[10]',
			'$row[16]',
			'$row[17]',
			'$row[18]',
			'$row[19]',
			'$row[46]',
			sysdate())";
			$result1=conexion('bgpr','vlan',$query);

			if($result1[0]['evento']=='correcto'){
				$id=$result1[0]['id'];
				$query="INSERT INTO stvs_7k_detalles
					(id_stv7k,
					tipo_poste,
					medio,
					ip_lan,
					dir_ip_camara,
					dir_ip_intercom,
					dir_ip_planta_fuerza,
					fecha_validacion_router,
					direccion,
					senas_esquina,
					colonia,
					codigo_cep,
					delegacion,
					a_nombre,
					a_tipo,
					long_stv,
					lat_stv,
					central_survey,
					sector,
					vms,
					bgp,
					time,
					etiqueta_gepe,
					diripsensor1maestro,
					diripsensor2maestro,
					diripsensor3maestro,
					diripsensor4maestro,
					diripsensor5maestro,
					diripsensor1esclavo,
					diripsensor2esclavo,
					diripsensor3esclavo,
					diripsensor4esclavo,
					diripsensor5esclavo,
					sitio_anpr)
					VALUES
					($id,
					'$row[3]',
					'$row[8]',
					'$row[11]',
					'$row[12]',
					'$row[13]',
					'$row[14]',
					'$row[15]',
					'$row[20]',
					'$row[21]',
					'$row[22]',
					'$row[23]',
					'$row[24]',
					'$row[25]',
					'$row[26]',
					'$row[27]',
					'$row[28]',
					'$row[29]',
					'$row[30]',
					'$row[31]',
					'$row[32]',
					'$row[33]',
					'$row[34]',
					'$row[35]',
					'$row[36]',
					'$row[37]',
					'$row[38]',
					'$row[39]',
					'$row[40]',
					'$row[41]',
					'$row[42]',
					'$row[43]',
					'$row[44]',
					'$row[45]')";
				$result2=conexion('bgpr','vlan',$query);
				if($result2[0]['evento']=='error'){
					echo($result2[0]['msg']);
				}
			}
		}
	$i++;
	}
	
}
//excelExec8k($archivoExcel,"Detalle 8088 IDs",['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK']);
excelExec7k($archivoExcel,"Detalle 7K´S",['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU']);
echo("Termino");
?>