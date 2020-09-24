<?php

namespace Flyerless\FlyerlessSubscriptionManagement\CompletionConditions;

use Flyerless\FlyerlessSubscriptionManagement\Models\UserSocieties;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use FormSchema\Schema\Form;


class PreferenceCompletion extends CompletionCondition
{

    public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        //Check if club description exists
        $description = UserSocieties::forResource($activityInstance->id, $moduleInstance->id)->first();

        if ($description === null ) {
            return false;
        } else {
            return true;
        }
    }

    public function percentage($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): int
    {
        //Check if club description exists
        $description = UserSocieties::forResource($activityInstance->id, $moduleInstance->id)->first();

        if ($description === null ) {
            return 0;
        } else {
            return 100;
        }
    }


    public function options(): Form
    {
        return \FormSchema\Generator\Form::make()->getSchema();
    }

    public function name(): string
    {
        return 'Society Preferences have been updated';
    }

    public function description(): string
    {
        return 'Completed when a user confirms their society preferences';
    }

    public function alias(): string
    {
        return 'flyerless_subscription_management_preferences_updated';
    }
}
