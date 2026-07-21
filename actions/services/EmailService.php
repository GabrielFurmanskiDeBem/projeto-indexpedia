<?php
    // Correção dos caminhos: Usando __DIR__ para garantir que o PHP encontre os arquivos independente de onde o script é executado.
    require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
    require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class EmailService {
        public function enviarVerificacaoEmail($usuarioEmail, $codigoVerificacao) {
            $assunto = "Verifique seu e-mail - IndexPedia";
            $link = "http://localhost/353/PROJETO-INTEGRADOR/projeto-indexpedia/assets/pages/verificar.php?code=" . $codigoVerificacao;
            
            $corpo = "
                <html>
                    <head>
                        <title>Verificação de E-mail</title>
                    </head>
                    <body>
                        <h2>Bem-vindo ao IndexPedia!</h2>
                        <p>Para ativar sua conta, clique no botão abaixo:</p>
                        <a href='$link' style='display:inline-block;padding:10px 20px;background-color:#0275d8;color:white;text-decoration:none;border-radius:5px;'>Verificar Minha Conta</a>
                        <p>Se o botão não funcionar, copie e cole este link no seu navegador: <br> $link</p>
                    </body>
                </html>
            ";

            // --- OPÇÃO RECOMENDADA: PHPMailer ---
            // Você deve configurar seus dados SMTP abaixo para que o envio funcione de verdade.
            
            $mail = new PHPMailer(true);
            try {
                // Configurações do Servidor
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Servidor SMTP (ex: Gmail)
                $mail->SMTPAuth   = true;
                $mail->Username   = 'indexpediaoriginal@gmail.com'; // Seu e-mail
                $mail->Password   = 'ofav wsmt qjrc syxc';    // Sua senha de aplicativo
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                // Destinatários
                $mail->setFrom('indexpediaoriginal@gmail.com', 'indexpedia');
                $mail->addAddress($usuarioEmail);

                // Conteúdo
                $mail->isHTML(true);
                $mail->Subject = $assunto;
                $mail->Body    = $corpo;

                if ($mail->send()) {
                    error_log("E-mail de verificação enviado com sucesso para: $usuarioEmail");
                    return true;
                }
            } catch (Exception $e) {
                // Se falhar o PHPMailer, tenta a função mail() nativa como fallback
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <no-reply@indexpedia.com>' . "\r\n";
                
                if (@mail($usuarioEmail, $assunto, $corpo, $headers)) {
                    return true;
                }

                error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
                return false;
            }
        }
    }
?>