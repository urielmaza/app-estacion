<?php 
	
	/**
	 * 
	 * Clase Usuarios
	 * Contiene todo lo que debe tener / hacer un usuario
	 * 
	 * */
	class ClienteAppEstacion extends DBAbstract {
		public $email, $password;

		function __construct(){
			parent::__construct();
			$this->email = "";
			$this->password = "";
		}

		private function generateToken($len=32){
			return bin2hex(random_bytes($len/2));
		}

		private function now(){
			return date('Y-m-d H:i:s');
		}

		public function setDeleteDateNowById($idCliente){
			$id = (int)$idCliente;
			$sql = "UPDATE clienteAppEstacion SET delete_date='".$this->now()."' WHERE id_cliente=".$id;
			return $this->ejecutar($sql);
		}

		public function findByEmail($email){
			$email = $this->escape($email);
			$r = $this->consultar("SELECT * FROM clienteAppEstacion WHERE email='$email' LIMIT 1");
			return count($r)? $r[0] : null;
		}

		public function findByTokenAction($tokenAction){
			$tokenAction = $this->escape($tokenAction);
			$r = $this->consultar("SELECT * FROM clienteAppEstacion WHERE token_action='$tokenAction' LIMIT 1");
			return count($r)? $r[0] : null;
		}

		public function findByToken($token){
			$token = $this->escape($token);
			$r = $this->consultar("SELECT * FROM clienteAppEstacion WHERE token='$token' LIMIT 1");
			return count($r)? $r[0] : null;
		}

		public function createUser($email, $password){
			$email = trim($email); $password = trim($password);
			if(!$email || !$password) return ["ok"=>false,"msg"=>"Faltan datos"];
			if($this->findByEmail($email)) return ["ok"=>false,"msg"=>"Email ya registrado"];
			$token = $this->generateToken();
			$tokenAction = $this->generateToken();
			// Hashear contraseña
			$hashed = password_hash($password, PASSWORD_DEFAULT);
			$emailEsc = $this->escape($email);
			$passEsc = $this->escape($hashed);
			$tokenEsc = $this->escape($token);
			$tokActEsc = $this->escape($tokenAction);
			$now = $this->now();
			$sql = "INSERT INTO clienteAppEstacion (token,email,contraseña,activo,bloqueado,recupero,token_action,add_date) VALUES ('$tokenEsc','$emailEsc','$passEsc',0,0,0,'$tokActEsc','$now')";
			$ok = $this->ejecutar($sql);
			return ["ok"=>$ok, "token"=>$token, "token_action"=>$tokenAction];
		}

		public function setActiveByTokenAction($tokenAction){
			$tok = $this->escape($tokenAction);
			$user = $this->findByTokenAction($tok);
			if(!$user || $user['activo']==1) return ["ok"=>false,"msg"=>"Token inválido o usuario ya activo"];
			$sql = "UPDATE clienteAppEstacion SET activo=1, active_date='".$this->now()."', token_action='' WHERE id_cliente=".(int)$user['id_cliente'];
			return ["ok"=>$this->ejecutar($sql),"user"=>$user];
		}

		public function startRecovery($email){
			$user = $this->findByEmail($email);
			if(!$user) return ["ok"=>false,"msg"=>"Email no registrado"];
			$tokenAction = $this->generateToken();
			$sql = "UPDATE clienteAppEstacion SET recupero=1, token_action='".$this->escape($tokenAction)."', recover_date='".$this->now()."' WHERE id_cliente=".(int)$user['id_cliente'];
			$ok = $this->ejecutar($sql);
			return ["ok"=>$ok, "token_action"=>$tokenAction, "user"=>$user];
		}

		public function setBlockedByToken($token){
			$user = $this->findByToken($token);
			if(!$user) return ["ok"=>false,"msg"=>"Token inválido"];
			$tokenAction = $this->generateToken();
			$sql = "UPDATE clienteAppEstacion SET bloqueado=1, token_action='".$this->escape($tokenAction)."', blocked_date='".$this->now()."' WHERE id_cliente=".(int)$user['id_cliente'];
			$ok = $this->ejecutar($sql);
			return ["ok"=>$ok, "token_action"=>$tokenAction, "user"=>$user];
		}

		public function resetPasswordByTokenAction($tokenAction,$newPassword){
			$user = $this->findByTokenAction($tokenAction);
			if(!$user) return ["ok"=>false,"msg"=>"Token inválido"];
			$hashed = password_hash($newPassword, PASSWORD_DEFAULT);
			$passEsc = $this->escape($hashed);
			$sql = "UPDATE clienteAppEstacion SET contraseña='$passEsc', token_action='', recupero=0, bloqueado=0, update_date='".$this->now()."' WHERE id_cliente=".(int)$user['id_cliente'];
			return ["ok"=>$this->ejecutar($sql), "user"=>$user];
		}

		public function validateLogin($email,$password){
			$user = $this->findByEmail($email);
			if(!$user) return ["errno"=>400,"error"=>"credenciales no válidas"];
			$stored = $user['contraseña'];
			$validPass = false;
			if(password_verify($password, $stored)){
				$validPass = true;
			} elseif ($stored === $password && !preg_match('/^\$2y\$/', $stored)) {
				// Contraseña legacy en texto plano: migrar a hash
				$newHash = password_hash($password, PASSWORD_DEFAULT);
				$escHash = $this->escape($newHash);
				$this->ejecutar("UPDATE clienteAppEstacion SET contraseña='$escHash', update_date='".$this->now()."' WHERE id_cliente=".(int)$user['id_cliente']);
				$validPass = true;
			}
			if(!$validPass){
				return ["errno"=>401,"error"=>"contraseña incorrecta","user"=>$user];
			}
			if((int)$user['activo']!==1) return ["errno"=>402,"error"=>"Su usuario aún no se ha validado, revise su casilla de correo","user"=>$user];
			if((int)$user['bloqueado']===1 || (int)$user['recupero']===1) return ["errno"=>403,"error"=>"Su usuario está bloqueado, revise su casilla de correo","user"=>$user];
			return ["errno"=>202,"error"=>"Acceso válido","user"=>$user];
		}

		/* Stub de envío de email (reemplazar con mail()/PHPMailer según servidor) */
		public function sendEmail($to,$subject,$html){
			// PHPMailer via SMTP
			require_once __DIR__ . '/../librarys/mp-mailer/Mailer/src/PHPMailer.php';
			require_once __DIR__ . '/../librarys/mp-mailer/Mailer/src/SMTP.php';
			require_once __DIR__ . '/../librarys/mp-mailer/Mailer/src/Exception.php';
			try {
				$mail = new PHPMailer\PHPMailer\PHPMailer(true);
				$mail->isSMTP();
				$mail->Host = SMTP_HOST;
				$mail->SMTPAuth = true;
				$mail->Username = SMTP_USER;
				$mail->Password = SMTP_PASS; // Usar app password / variable segura
				$mail->SMTPSecure = SMTP_SECURE;
				$mail->Port = SMTP_PORT;
				$mail->CharSet = 'UTF-8';
				$mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
				$mail->addAddress($to);
				$mail->Subject = $subject;
				$mail->isHTML(true);
				$wrap = '<div style="font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.5;color:#222;">'.$html.'<hr style="margin:24px 0;border:none;border-top:1px solid #ddd"/><p style="font-size:11px;color:#666">Este correo fue generado automáticamente por App Estación.</p></div>';
				$mail->Body = $wrap;
				$mail->send();
				return true;
			} catch (Exception $e){
				// Log simple: en producción registrar en archivo
				// echo 'Mailer Error: '.$e->getMessage();
				return false;
			}
		}

		private function escape($v){
			return addslashes($v); // Simplificación: usar prepared statements idealmente
		}
	}


 ?>