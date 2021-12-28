<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

class Mensagem
{
    private $para;
    private $assunto;
    private $mensagem;
    public $status = array( 'codigo_status' => null, 'descricao_status' => '' );


    public function __get($atributo)
    {
        return $this -> $atributo;
    }

    public function __set($atributo, $valor)
    {
        return $this -> $atributo = $valor;    
    }

    public function validaMensagem()
    {
        if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
            return false;
        
        } return true;
    }

}

$mensagem = new Mensagem();
$mensagem -> __set ('para', $_POST['para']);
$mensagem -> __set ('assunto', $_POST['assunto']);
$mensagem -> __set ('mensagem', $_POST['mensagem']);


if (!($mensagem -> validaMensagem()))
{
    echo "Mensagem não pode ser enviada, faltam campos.";
} 

$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'testeweb03@gmail.com';                 //SMTP username
    $mail->Password   = '!@#$4321';                             //SMTP password
    $mail->SMTPSecure = 'tls';                                  //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('testeweb03@gmail.com', 'Remetente');
    $mail->addAddress($mensagem -> __get ('para'), 'Destinatário');     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem -> __get('assunto');
    $mail->Body    = $mensagem -> __get('mensagem');

    $mail->send();
    //echo 'Message has been sent';
    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';

} catch (Exception $e) {
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde. Detalhes do erro: ' . $mail->ErrorInfo;
}
?>


<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>

	<body>

		<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12">

					<?php if($mensagem->status['codigo_status'] == 1) { ?>

						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p><?= $mensagem->status['descricao_status'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

					<?php if($mensagem->status['codigo_status'] == 2) { ?>

						<div class="container">
							<h1 class="display-4 text-danger">Ops!</h1>
							<p><?= $mensagem->status['descricao_status'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

				</div>
			</div>
		</div>

	</body>
</html>
