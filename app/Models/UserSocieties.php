<?php

namespace Flyerless\FlyerlessSubscriptionManagement\Models;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\Authentication\HasResource;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstanceRepository;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class UserSocieties extends Model {
    use SoftDeletes, HasResource;

    protected $table = 'flyerless_subscription_management_user_society';

    protected $casts = [
        'societies' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'user_name',
        'societies',
        'module_instance_id',
        'activity_instance_id',
    ];

    /**
     * @return ModuleInstance
     */
    public function moduleInstance()
    {
        return app(ModuleInstanceRepository::class)->getById($this->module_instance_id);
    }

    /**
     * @return ActivityInstance
     */
    public function activityInstance()
    {
        return app(ActivityInstanceRepository::class)->getById($this->activity_instance_id);
    }
}