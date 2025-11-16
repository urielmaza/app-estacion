<?php
$token='';
if(isset($_GET['slug'])){ $p=explode('/',$_GET['slug']); if(count($p)>1) $token=$p[1]; }
$msg='';
if($token){
    $usr=new ClienteAppEstacion();
    $r=$usr->setBlockedByToken($token);
    if($r['ok']){
        // Forzar cierre de sesión si estaba iniciada
        if(session_status() !== PHP_SESSION_ACTIVE){ session_start(); }
        if(isset($_SESSION['id_cliente'])){
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']
                );
            }
            session_destroy();
        }
        $link = BASE_URL.'?slug=reset/'.urlencode($r['token_action']);
        $html='<h3>Cuenta bloqueada</h3><p>Has bloqueado tu cuenta. Para cambiar contraseña usa el siguiente enlace:</p>'
            .'<p><a style="display:inline-block;padding:10px 15px;background:#457b9d;color:#fff;text-decoration:none;border-radius:6px" href="'.$link.'">Click aquí para cambiar contraseña</a></p>';
        $usr->sendEmail($r['user']['email'],'Cuenta bloqueada',$html);
        $msg='Usuario bloqueado, revise su correo electrónico';
    } else {
        $msg='El token no corresponde a un usuario';
    }
} else { $msg='Token faltante'; }
$tpl=new Palta('error');
$tpl->assign(['ERROR_MESSAGE'=>$msg]);
$tpl->printToScreen();
?>