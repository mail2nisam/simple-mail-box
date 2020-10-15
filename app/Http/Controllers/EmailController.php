<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Email;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    protected $emailService;

    /**
     * EmailController constructor.
     * @param $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->middleware('auth');
        $this->emailService = $emailService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $searchKey = request('s', '');
        if (!$searchKey) {
            $emails = Email::orderBy("received_on", "DESC")->paginate(config('inbox.items_per_page'));
        } else {
            $emails = Email::where("subject", "LIKE", "%$searchKey%")
                ->orwhere("from", "regexp", "$searchKey")
                ->orwhere("subject", "regexp", "$searchKey")
                ->orwhere("body", "regexp", "$searchKey")
                ->orderBy("received_on", "DESC")
                ->paginate(config('inbox.items_per_page'));

            Activity::create(
                [
                    'activity_type' => 'USER',
                    'activity' => 'Search with a key - ' . $searchKey,
                    'user_id' => auth()->id()
                ]
            );
        }
        return view('home')->with('emails', $emails);
    }

    /**
     * Delete an email from inbox and database
     * @param Email $email
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Email $email)
    {
        try {
            Activity::create(
                [
                    'activity_type' => 'USER',
                    'activity' => "Email deleted with an id {$email->message_id} and the subject {$email->subject}",
                ]
            );
            $status = $this->emailService->deleteEmail($email);
            if ($status) {
                session()->flash('status', 'email has been deleted');
                return redirect('home');
            }
        } catch (\Exception $exception) {
            Log::error("Error on deletion" . $exception->getMessage());
        }
    }
}
