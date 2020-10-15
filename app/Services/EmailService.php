<?php


namespace App\Services;


use App\Email;

class EmailService
{
    public function deleteEmail(Email $email)
    {
        if ($email) {
            $smtpService = new SMTPService();
            $status = $smtpService->deleteEmailFromInbox($email->message_number);
            if ($status) {
                $email->delete();
                return true;
            }
            return false;
        }
    }

}
