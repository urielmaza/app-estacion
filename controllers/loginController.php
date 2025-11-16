<?php
session_start();
// Si ya está logueado redirigir
if(isset($_SESSION['id_cliente'])){
	header('Location: ?slug=panel');
	exit;
}

$error_login = "";
$chipidRedirect = isset($_GET['chipid']) ? $_GET['chipid'] : ''; // opcional capturar chipid que panel usó

function getClientContext(){
	$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
	$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'desconocido';
	$os = 'Desconocido';
	if(stripos($ua,'Windows')!==false) $os='Windows'; elseif(stripos($ua,'Linux')!==false) $os='Linux'; elseif(stripos($ua,'Mac')!==false) $os='macOS';
	$nav = 'Navegador';
	foreach(['Chrome','Firefox','Safari','Edge','Opera'] as $n){ if(stripos($ua,$n)!==false){ $nav=$n; break; } }
	return compact('ip','ua','os','nav');
}

if(isset($_POST['btn_ingresar'])){
	$usuario = new ClienteAppEstacion();
	$email = $_POST['txt_email'] ?? '';
	$pass = $_POST['txt_password'] ?? '';
	$ctx = getClientContext();
	$r = $usuario->validateLogin($email,$pass);
	if($r['errno']===202){
		// Login válido
		$_SESSION['id_cliente'] = $r['user']['id_cliente'];
		$_SESSION['token'] = $r['user']['token'];
		$_SESSION['email'] = $r['user']['email'];
		// Email notificación
		$blockedLink = BASE_URL.'?slug=blocked/'.urlencode($r['user']['token']);
		$html = '<h3>Inicio de sesión exitoso</h3>'
			.'<p>IP: '.htmlentities($ctx['ip']).'</p>'
			.'<p>Sistema Operativo: '.htmlentities($ctx['os']).'</p>'
			.'<p>Navegador: '.htmlentities($ctx['nav']).'</p>'
			.'<p>Si no fuiste tú puedes bloquear tu cuenta:</p>'
			.'<p><a style="display:inline-block;padding:10px 15px;background:#d62828;color:#fff;text-decoration:none;border-radius:6px" href="'.$blockedLink.'">No fui yo, bloquear cuenta</a></p>';
		$usuario->sendEmail($r['user']['email'],'Inicio de sesión en App Estación',$html);
		$dest = $chipidRedirect ? ('?slug=detalle/'.urlencode($chipidRedirect)) : '?slug=panel';
		header('Location: '.$dest); exit;
	} elseif($r['errno']===401){
		// Password incorrecta -> email de alerta
		if(isset($r['user']['email'])){
			$blockedLink = BASE_URL.'?slug=blocked/'.urlencode($r['user']['token']);
			$html = '<h3>Intento de acceso con contraseña inválida</h3>'
				.'<p>IP: '.htmlentities($ctx['ip']).'</p>'
				.'<p>Sistema Operativo: '.htmlentities($ctx['os']).'</p>'
				.'<p>Navegador: '.htmlentities($ctx['nav']).'</p>'
				.'<p>Si no fuiste tú puedes bloquear tu cuenta:</p>'
				.'<p><a style="display:inline-block;padding:10px 15px;background:#d62828;color:#fff;text-decoration:none;border-radius:6px" href="'.$blockedLink.'">No fui yo, bloquear cuenta</a></p>';
			$usuario->sendEmail($r['user']['email'],'Intento de acceso no válido',$html);
		}
		$error_login = 'credenciales no válidas';
	} else {
		$error_login = $r['error'];
	}
}

$tpl = new Palta('login');
$tpl->assign(['ERROR_LOGIN'=>$error_login]);
$tpl->printToScreen();
?>