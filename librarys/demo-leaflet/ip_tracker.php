<?php 

	/* Captura la ip del cliente */
	$ip = $_SERVER['REMOTE_ADDR'];

	/* Protección para evitar leer una ip local */
	if($ip == "127.0.0.1")
		$ip = "181.47.205.193"; /* Usamos un ip pública */
	
	/* Consulta a la api para obtener más información de la ip */
	$web = file_get_contents("http://ipwho.is/".$ip);

	/* Convierte el json recuperado en un objeto */
	$response = json_decode($web);

	/* Muestra parte de la informa */
	echo "Latitud: ".$response->latitude;
	echo "<br>";
	echo "Longitud: ".$response->longitude;

 ?>