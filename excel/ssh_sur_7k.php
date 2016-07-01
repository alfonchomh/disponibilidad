<?php
	$proyecto="7k";
	$c2="sur";
	$ipasr='10.199.1.254';
	$dbname='sur_bgp7k';

	function conexionssh($c2,$proyecto,$ipasr)
	{
		if(!($connection = ssh2_connect($ipasr, 22))){
			echo "No se pudo establecer conexion\n";
		}
		else{
		if(!ssh2_auth_password($connection, 'casmto', 'c4smt0')){
			echo "No se pudo autenticar\n";
		}
		else{
		$datos=ssh2_shell($connection, 'VT220');
		fwrite( $datos, "sh clock" . PHP_EOL . "term len 0" . PHP_EOL . "sh ip bgp summ" . PHP_EOL . "exit" . PHP_EOL);
		stream_set_blocking($datos, true);
		$c=0;

		if(file_exists($c2."_h_".$proyecto.".txt"))
			{
				$fp=fopen($c2."_h_".$proyecto.".txt","w");
				fputs($fp,"");
				fclose($fp);
			}
			if(file_exists($c2."_bgp_".$proyecto.".sql"))
			{
				$fp=fopen($c2."_bgp_".$proyecto.".sql","w");
				fputs($fp,"");
				fclose($fp);
			}

			$fp_h=fopen($c2."_h_".$proyecto.".txt","a");
			$fp_bgp=fopen($c2."_bgp_".$proyecto.".sql","a");

		while ($stream = @fgets($datos)) {
			//echo ">>> $c ".$stream;

			if($c>1 && $c<17 && trim($stream) != "" && $c!=4 && $c!=5)
				{
					//echo $c." ".$stream."<br>";
					$linea=preg_replace('/[\s]+/'," ",$stream);
					fputs($fp_h,trim($linea)."\n");
					echo ">>> $c ".$stream;
				}
			if($c==17)
				{
					fclose($fp_h);

					$fechaconsulta=fecha_consulta($c2,$proyecto);
					echo ("Fecha Consulta: ".$fechaconsulta);
				}
				/*Obtiene fecha de h*/
				/*Abre y escribe archivo bgp*/
			$linea=preg_replace('/[\s]+/',"	",(string)$stream);
			if($c>22 && (!($linea=="	")) && (!($linea=="")) && (!(preg_match('/RP/',$linea)))){
				{
					fputs($fp_bgp,$linea.$fechaconsulta."\n");
				}
			}
				/*Abre y escribe archivo bgp*/
			$c++;
		}
		fclose($fp_bgp);
		fclose($datos); 
	}
	}
	}
	function fecha_consulta($c2,$proyecto)
	{
		$arrMonth= array("Jan"=>"01","Feb"=>"02","Mar"=>"03","Apr"=>"04","May"=>"05","Jun"=>"06"
						,"Jul"=>"07","Agu"=>"08","Sep"=>"09","Oct"=>"10","Nov"=>"11","Dec"=>"12");

		$cent=0;
		$fp=fopen($c2."_h_".$proyecto.".txt","r");
		$datos=fread($fp,filesize($c2."_h_".$proyecto.".txt"));
		if(preg_match('/CENTRAL/',$datos) && $cent==0)
		{			
			$tiempo=explode(' ',$datos);
			$fechaconsulta=date("Y")."-".$arrMonth[$tiempo[2]]."-".$tiempo[3]." ".$tiempo[4];
			$cent++;
		}
		fclose($fp);
		return $fechaconsulta;
	}

	function sql($con,$c2,$proyecto)
	{
		$qry="LOAD DATA LOCAL INFILE '/var/www/html/gets/".$c2."_bgp_".$proyecto.".sql' REPLACE INTO TABLE consulta_asr";
		if(!$query=mysqli_query($con,$qry))
		{
			echo "Error al insertar ".mysqli_error($con)."<br>";
		}
		mysqli_close($con);
	}
	
	conexionssh($c2,$proyecto,$ipasr);
	include("conexion.php");
	sql($con,$c2,$proyecto);
?>