<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $status = $request->query('status', 'All');

        $eventsQuery = Event::query()->latest();

        if ($status === 'Upcoming') {
            $eventsQuery->where('start_date', '>', $now);
        } elseif ($status === 'Ongoing') {
            $eventsQuery->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now);
        } elseif ($status === 'Past') {
            $eventsQuery->where('end_date', '<', $now);
        }

        $events = $eventsQuery->paginate(10)->withQueryString();

        return view('modules.event.index', compact('events', 'status'));
    }

    public function show(Event $event)
    {
        return view('modules.event.show', compact('event'));
    }
}
