<?php
session_start();
if(isset($_SESSION['id_cliente'])){ header('Location: ?slug=panel'); exit; }
$error='';
if(isset($_POST['btn_recovery'])){
    $email=trim($_POST['rec_email']??'');
    if(!$email){ $error='Email requerido'; }
    else {
        $usr=new ClienteAppEstacion();
        $r=$usr->startRecovery($email);
        if($r['ok']){
            $link = BASE_URL.'?slug=reset/'.urlencode($r['token_action']);
            $html='<h3>Recuperación de contraseña</h3><p>Se inició el proceso de restablecimiento.</p>'
                .'<p><a style="display:inline-block;padding:10px 15px;background:#457b9d;color:#fff;text-decoration:none;border-radius:6px" href="'.$link.'">Click aquí para restablecer contraseña</a></p>';
            $usr->sendEmail($email,'Recuperación App Estación',$html);
            $error='Proceso iniciado. Revisa tu correo.';
        } else {
            $error = $r['msg']==='Email no registrado' ? 'El email no se encuentra registrado' : $r['msg'];
        }
    }
}
$tpl=new Palta('recovery');
$tpl->assign(['ERROR_RECOVERY'=>$error]);
$tpl->printToScreen();
?>