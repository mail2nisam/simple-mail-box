<?php


namespace App\Services;


use App\Email;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SMTPService
{
    public $inbox;

    /**
     * EmailService constructor.
     * @param $inbox
     */
    public function __construct()
    {
        $this->inbox = $this->openInbox();
    }


    /**
     * Connect SMTP server via imap and open inbox
     * @return false|resource
     */
    private function openInbox()
    {
        try {
            return imap_open(config('inbox.imap_server'), config('inbox.imap_user_name'), config('inbox.imap_password'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * fetch All email from the inbox
     * @return array|false
     */
    private function fetchEmails()
    {
        try {
            return imap_search($this->inbox, 'ALL');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Store all emails to database
     * @return false
     */
    public function storeEmailsToDatabase()
    {
        try {
            $emails = $this->fetchEmails();
            if (!$emails) {
                return false;
            }
            foreach ($emails as $emailNumber) {
                $emailOverView = $this->getEmailOverView($emailNumber);
                if ($emailOverView) {
                    $receivedOn = new Carbon($emailOverView->date);
                    $emailObject = Email::updateOrCreate(['message_id' => $emailOverView->message_id],
                        [
                            'from' => $emailOverView->from,
                            'subject' => $emailOverView->subject,
                            'received_on' => $receivedOn,
                            'message_id' => $emailOverView->message_id,
                            'message_number' => $emailOverView->msgno,
                        ]);
                    if ($emailObject) {
                        $emailBody = $this->getEmailBody($emailNumber);
                        $emailObject->update(['body' => $emailBody]);
                    }
                }
            }
            imap_close($this->inbox);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * fetch Email metadata
     * @param $emailNumber
     * @return mixed
     */

    private function getEmailOverView($emailNumber)
    {
        try {
            return collect(imap_fetch_overview($this->inbox, $emailNumber, 0))->first();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Fetch plain text email body
     * @param $emailNumber
     * @return string
     */
    private function getEmailBody($emailNumber)
    {
        try {
            // fetching plain text email for simplicity
            return imap_fetchbody($this->inbox, $emailNumber, 1);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Delete an email from mailbox
     * @param $emailNumber
     * @return boolean
     */
    public function deleteEmailFromInbox($emailNumber)
    {
        try {
            return imap_delete($this->inbox, $emailNumber);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

}
