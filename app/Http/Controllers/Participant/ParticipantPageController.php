<?php

namespace Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\Participant;

use Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\Controller;

class ParticipantPageController extends Controller
{

    public function index()
    {
        $this->authorize('view-page');
        
        return view('flyerless-subscription-management::participant');
    }
    
}