<?php
// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Si usas Composer, asegurate de que la ruta es correcta.

function sendRecoveryEmail($recipientEmail, $token) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';            // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'tuemail@gmail.com';     // Tu correo de Gmail
        $mail->Password = 'tucontraseña';          // Tu contraseña de Gmail o App Password (para mayor seguridad)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;                         // Puerto para TLS

        // Configurar el emisor y el destinatario
        $mail->setFrom('tuemail@gmail.com', 'Nombre de tu tienda');
        $mail->addAddress($recipientEmail);

        // Contenido del email
        $mail->isHTML(true); 
        $mail->Subject = 'Recuperación de Contraseña';
        
        $enlace = "http://localhost/tienda_online/auth/restablecer.php?token=" . $token;
        $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='" . $enlace . "'>Restablecer Contraseña</a>";

        // Enviar el correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Si ocurre un error, lo capturamos y lo mostramos.
        return "No se pudo enviar el correo. Error de PHPMailer: {$mail->ErrorInfo}";
    }
}
