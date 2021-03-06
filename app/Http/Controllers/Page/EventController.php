<?php

namespace App\Http\Controllers\Page;

use App\Events\EventHasNewSignup;
use App\Models\EventSignup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('pages.event');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:event_signups'
        ]);


        $eventUser = EventSignup::updateOrCreate(
            [
                'email' => $request->email,
            ],
            [
                'email' => $request->email,
                'year' => date('Y')
            ]
        );


        event(new EventHasNewSignup($eventUser));

        // @todo do we need to confirm their email ?
        return back()->with([
            'message' => 'Thank you, we will be in touch with more information about the event!',
            'note' => 'Note,  Please check your spam folder if you can\'t see our email in your inbox.'
        ]);
    }
}
