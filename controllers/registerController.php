<?php
session_start();
if(isset($_SESSION['id_cliente'])){ header('Location: ?slug=panel'); exit; }
$error = '';
if(isset($_POST['btn_register'])){
    $email = trim($_POST['reg_email']??'');
    $pass = trim($_POST['reg_pass']??'');
    $pass2 = trim($_POST['reg_pass2']??'');
    if(!$email || !$pass || !$pass2){
        $error = 'Todos los campos son obligatorios';
    } elseif($pass !== $pass2){
        $error = 'Las contraseñas no coinciden';
    } else {
        $usr = new ClienteAppEstacion();
        $r = $usr->createUser($email,$pass);
        if($r['ok']){
            // Usar BASE_URL fija del entorno
            $link = BASE_URL.'?slug=validate/'.urlencode($r['token_action']);
            $html = '<h3>Bienvenido a App Estación</h3>'
                .'<p>Activa tu usuario para comenzar:</p>'
                .'<p><a style="display:inline-block;padding:10px 15px;background:#457b9d;color:#fff;text-decoration:none;border-radius:6px" href="'.$link.'">Click aquí para activar tu usuario</a></p>';
            $usr->sendEmail($email,'Activa tu usuario App Estación',$html);
            $error = 'Registro exitoso. Revisa tu correo para activar.';
        } else {
            $error = $r['msg']==='Email ya registrado' ? 'El email ya corresponde a un usuario. Inicia sesión.' : $r['msg'];
        }
    }
}
$tpl = new Palta('register');
$tpl->assign(['ERROR_REGISTER'=>$error]);
$tpl->printToScreen();
?>