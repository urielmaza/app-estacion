<?php 


	/* dentro de los controladores solo hay codigo php*/


	/* LIBRERIAS */
	/* CLASES */


	/* LÓGICA DE NEGOCIO */

	/* IMPRIMO LA VISTA */
	$tpl = new Palta("landing");

	/* sección para título dinámico en head */
	$tpl->assign([
		"APP_SECTION" => "Inicio"
	]);

	$tpl->printToScreen();

	

 ?>