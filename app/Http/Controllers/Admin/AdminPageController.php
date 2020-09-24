<?php

namespace Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\Admin;

use Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\Controller;

class AdminPageController extends Controller
{
    
    public function index()
    {
        $this->authorize('admin.view-page');
        
        return view('flyerless-subscription-management::admin');
    }
    
}