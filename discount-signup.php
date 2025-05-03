<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $email = $_POST['email'] ?? '';
    $source = $_POST['source'] ?? 'popup_discount';
    $discount = $_POST['discount'] ?? '20%';
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Email inválido"]);
        exit;
    }
    
    // Configurar el correo
    $to = "info@tudominio.com"; // Tu correo en GoDaddy
    $subject = "Nueva solicitud de descuento del 20%";
    
    // Crear el cuerpo del mensaje
    $email_body = "Has recibido una nueva solicitud de descuento.\n\n".
                  "Detalles:\n\n".
                  "Email: $email\n".
                  "Origen: $source\n".
                  "Descuento: $discount\n".
                  "Fecha: " . date("Y-m-d H:i:s") . "\n";
    
    $headers = "From: noreply@tudominio.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    
    // Enviar el correo
    if(mail($to, $subject, $email_body, $headers)) {
        // También puedes guardar el email en un archivo o base de datos
        $file = fopen("discount_subscribers.txt", "a");
        fwrite($file, "$email,$source,$discount," . date("Y-m-d H:i:s") . "\n");
        fclose($file);
        
        // Enviar correo de confirmación al usuario
        $user_subject = "Tu cupón de descuento del 20% - Ugly Content";
        $user_message = "Hola,\n\n".
                        "¡Gracias por tu interés en nuestros servicios!\n\n".
                        "Aquí tienes tu cupón de descuento del 20% para tu primer proyecto con nosotros: UGLY20\n\n".
                        "Para utilizarlo, simplemente menciona este código cuando hablemos por WhatsApp o en tu primer contacto con nosotros.\n\n".
                        "¡Esperamos trabajar contigo pronto!\n\n".
                        "El equipo de Ugly Content";
        
        $user_headers = "From: info@tudominio.com\r\n";
        mail($email, $user_subject, $user_message, $user_headers);
        
        echo json_encode(["status" => "success", "message" => "Solicitud recibida correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al procesar la solicitud"]);
    }
    exit;
}
?>
