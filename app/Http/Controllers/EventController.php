<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);

        return view('modules.event.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('modules.event.show', compact('event'));
    }
}
