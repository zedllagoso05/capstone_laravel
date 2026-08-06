<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log; // <-- Add this if you don't want the backslash

class Mailer
{
    public static function send(string $toEmail, string $toName, string $subject, string $bodyHtml): bool
    {
        // Support Laravel's 'log' mailer driver for local development/testing without real SMTP config
        if (env('MAIL_MAILER') === 'log') {
            Log::info("Email sent to [{$toEmail}] (Name: {$toName})\nSubject: {$subject}\nBody:\n" . strip_tags($bodyHtml) . "\nHTML Body:\n{$bodyHtml}");
            return true;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
            $mail->Port       = env('MAIL_PORT', 587);

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $bodyHtml;
            $mail->AltBody = strip_tags($bodyHtml);

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error('Mail send failed: ' . $mail->ErrorInfo);
            return false;
        }
    }
}