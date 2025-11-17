<?php 
session_start();


	/* dentro de los controladores solo hay codigo php*/


	/* LIBRERIAS */
	/* CLASES */


	/* LÓGICA DE NEGOCIO */

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("landing");

	// Enlaces dinámicos según sesión/rol
	$navHref = '?slug=login';
	$navText = 'Acceder';
	$heroHref = '?slug=panel';
	$heroText = 'Ver Panel';

	if(isset($_SESSION['id_cliente'])){
		// Usuario logueado
		$navHref = '?slug=panel';
		$navText = 'Panel';
		$heroHref = '?slug=panel';
		$heroText = 'Ver Panel';
		if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true){
			$navHref = '?slug=administrator';
			$navText = 'Administrador';
			$heroHref = '?slug=administrator';
			$heroText = 'Ir al Administrador';
		}
	}

	/* sección para título dinámico en head */
	$tpl->assign([
		"APP_SECTION" => "Inicio",
		"NAV_PRIMARY_HREF" => $navHref,
		"NAV_PRIMARY_TEXT" => $navText,
		"HERO_PRIMARY_HREF" => $heroHref,
		"HERO_PRIMARY_TEXT" => $heroText
	]);

	$tpl->printToScreen();

	

 ?>