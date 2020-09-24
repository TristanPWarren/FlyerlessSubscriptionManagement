<?php

namespace Flyerless\FlyerlessSubscriptionManagement;

use BristolSU\Support\Module\ModuleServiceProvider as ServiceProvider;
use FormSchema\Schema\Form;

class ModuleServiceProvider extends ServiceProvider
{

    protected $permissions = [
        //Web
        'view-page' => [
            'name' => 'View Participant Page',
            'description' => 'View the main page of the module.',
            'admin' => false
        ],
        'admin.view-page' => [
            'name' => 'View Admin Page',
            'description' => 'View the administrator page of the module.',
            'admin' => true
        ],



        //API
        'user-society.index' => [
            'name' => 'View Subscribed Societies',
            'description' => 'Allows users to view currently subscribed societies',
            'admin' => false,
        ],
        'user-society.update' => [
            'name' => 'Modify Subscription Preferences',
            'description' => 'Modify the subscription preferences of a user',
            'admin' => false,
        ],
    ];

    protected $events = [

    ];

    protected $requiredServices = [
        'flyerless'
    ];

    protected  $completionConditions = [
        'flyerless_subscription_management_preferences_updated' => \Flyerless\FlyerlessSubscriptionManagement\CompletionConditions\PreferenceCompletion::class
    ];
    
    protected $commands = [
        
    ];
    
    public function alias(): string
    {
        return 'flyerless-subscription-management';
    }

    public function namespace()
    {
        return '\Flyerless\FlyerlessSubscriptionManagement\Http\Controllers';
    }
    
    public function baseDirectory()
    {
        return __DIR__ . '/..';
    }

    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();
    }

    /**
     * @inheritDoc
     */
    public function settings(): Form
    {
        return \FormSchema\Generator\Form::make()->getSchema();
    }
}
