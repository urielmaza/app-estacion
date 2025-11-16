<?php
session_start();
if(isset($_SESSION['id_cliente'])){ header('Location: ?slug=panel'); exit; }
$token_action='';
if(isset($_GET['slug'])){ $p=explode('/',$_GET['slug']); if(count($p)>1) $token_action=$p[1]; }
$error='';
$usr = new ClienteAppEstacion();
if(!$token_action){
    $error='Token faltante';
} else {
    if(isset($_POST['btn_reset'])){
        $p1=trim($_POST['new_pass']??'');
        $p2=trim($_POST['new_pass2']??'');
        if(!$p1||!$p2){ $error='Completa ambos campos'; }
        elseif($p1!==$p2){ $error='Las contraseñas no coinciden'; }
        else {
            $r=$usr->resetPasswordByTokenAction($token_action,$p1);
            if($r['ok']){
                $ctxIp=$_SERVER['REMOTE_ADDR']??'0.0.0.0';
                $ua=$_SERVER['HTTP_USER_AGENT']??'';
                $blockedLink = BASE_URL.'?slug=blocked/'.urlencode($r['user']['token']);
                $html='<h3>Contraseña restablecida</h3>'
                    .'<p>IP: '.htmlentities($ctxIp).'</p>'
                    .'<p>UA: '.htmlentities($ua).'</p>'
                    .'<p><a style="display:inline-block;padding:10px 15px;background:#d62828;color:#fff;text-decoration:none;border-radius:6px" href="'.$blockedLink.'">No fui yo, bloquear cuenta</a></p>';
                $usr->sendEmail($r['user']['email'],'Contraseña restablecida',$html);
                $error='Contraseña actualizada. Redirigiendo...';
                header('Refresh: 3; URL=?slug=login');
            } else { $error=$r['msg']; }
        }
    } else {
        // Validar token antes de mostrar formulario
        if(!$usr->findByTokenAction($token_action)){
            $error='El token no corresponde a un usuario';
        }
    }
}
$tpl=new Palta('reset');
$tpl->assign(['ERROR_RESET'=>$error,'TOKEN_ACTION'=>$token_action]);
$tpl->printToScreen();
?>