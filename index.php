<?php 



	/****
	 * 
	 * 
	 * ROuter firewall
	 * 
	 * 
	 * */

	include 'env.php';

	// Credenciales SMTP para envío de correos
	include 'librarys/mp-mailer/credenciales.php';

	include 'librarys/palta/Palta.php';

	include 'models/DBAbstract.php';
	include 'models/ClienteAppEstacion.php';

	$section = "landing";

	// Soporta slugs con segmentos (p.ej. detalle/CHIPID)
	if(isset($_GET["slug"])){
		$slugRaw = trim($_GET['slug'], "/");
		$parts = explode('/', $slugRaw);
		$section = preg_replace('/[^a-zA-Z0-9_-]/', '', $parts[0]);
		if(count($parts) > 1){
			$_GET['chipid'] = $parts[1];
		}
	}

	if(!file_exists( 'controllers/'.$section.'Controller.php')){
		$section = "error";
	}

	include 'controllers/'.$section.'Controller.php';



 ?>