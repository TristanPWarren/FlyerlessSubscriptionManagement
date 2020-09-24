<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlyerlessSubscriptionManagementUserSocietyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flyerless_subscription_management_user_society', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('user_name');
            $table->text('societies')->nullable();
            $table->unsignedInteger('module_instance_id');
            $table->unsignedInteger('activity_instance_id');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flyerless_subscription_management_user_society');
    }

}