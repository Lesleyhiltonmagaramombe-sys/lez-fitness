<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/vendor/autoload.php";

$mailConfig = require __DIR__ . "/config/mail.php";

$mail = new PHPMailer(true);

try {

    // SMTP settings
    $mail->isSMTP();
    $mail->Host = $mailConfig["host"];
    $mail->SMTPAuth = true;
    $mail->Username = $mailConfig["username"];
    $mail->Password = $mailConfig["password"];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $mailConfig["port"];

    // Sender
    $mail->setFrom(
        $mailConfig["from_email"],
        $mailConfig["from_name"]
    );

    // TEST RECIPIENT
    // Change this to an email address you can access.
    $mail->addAddress("mhazos@nssa.org.zw");

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "LEZ FITNESS - Test Email";

    $mail->Body = "
        <div style='font-family: Arial, sans-serif; padding: 20px;'>
            <h2 style='color:#f5b942;'>LEZ FITNESS</h2>

            <p>Hello!</p>

            <p>
                This is a test email from the
                <strong>LEZ FITNESS Membership & Attendance Management System.</strong>
            </p>

            <p style='color:green;'>
                Email notification system is working successfully.
            </p>

            <hr>

            <small>
                LEZ FITNESS<br>
                Membership & Attendance Management System
            </small>
        </div>
    ";

    $mail->AltBody =
        "LEZ FITNESS email notification system is working successfully.";

    $mail->send();

    echo "
        <div style='font-family:Arial; padding:30px;'>
            <h2 style='color:green;'>Email sent successfully!</h2>
            <p>Check the recipient's inbox.</p>
        </div>
    ";

} catch (Exception $e) {

    echo "
        <div style='font-family:Arial; padding:30px;'>
            <h2 style='color:red;'>Email could not be sent.</h2>
            <p>{$mail->ErrorInfo}</p>
        </div>
    ";
}
?>