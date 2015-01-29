<?php /*O3M*/

function send_mail_smtp($data=array()){
/**
* Descripcion:	Envia email usando SMTP
* Creación:		2015-01-28
* @author 		Oscar Maldonado - O3M
*/
	global $Path, $cfg;
	require_once $Path[php].'phpmailer/PHPMailerAutoload.php';
	// Variables recibidas
	$html_tpl 	= $data[html_tpl];
	$email_to 	= $data[email_to];
	$nombre_to 	= $data[nombre_to];
	$asunto 	= $data[asunto];
	$adjunto 	= $data[adjunto];
	//Crea instancia
	$mail = new PHPMailer;
	//Establece uso de SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Servidor
	$mail->Debugoutput 	= 'html';
	$mail->Host 		= $cfg[email_host];
	$mail->Port 		= $cfg[email_port];
	$mail->SMTPSecure 	= $cfg[email_stmp_secure];
	$mail->SMTPAuth 	= $cfg[email_stmp_auth];
	//Emisor Data
	$mail->Username = $cfg[email_user];
	$mail->Password = $cfg[email_pass];
	$mail->setFrom($cfg[email_address], $cfg[email_name]);
	//Direccion de respuesta
	$mail->addReplyTo($cfg[email_address], $cfg[email_name]);
	//Receptor Data
	$mail->addAddress($email_to, $nombre_to);
	//Asunto
	$mail->Subject = $asunto;
	//Insertar HTML
	$mail->msgHTML(file_get_contents($html_tpl), dirname(__FILE__));
	//Texto plano alternativo al HTML
	$mail->AltBody = 'Su correo no soporta HTML, por favor, contacte a su administrador de correo.';
	//Adjunto
	if($adjunto){
		$mail->addAttachment($adjunto);
	}
	//Envío de correo e imprime mensajes
	if (!$mail->send()) {
	    $resultado = "Error al enviar: " . $mail->ErrorInfo;
	    $success = false;
	} else {
	    $resultado = "Correo enviado!";
	    $success = true;
	}
	return $success;
}