<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public function index()
    {
        try {
            $allActivities = Activity::paginate(config('inbox.items_per_page'));
            return view('activities')->with('activities', $allActivities);
        } catch (\Exception $exception) {
            Log::error("Error on activity listing" . $exception->getMessage());
        }
    }
}
