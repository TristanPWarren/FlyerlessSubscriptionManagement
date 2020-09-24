<?php

use Illuminate\Support\Facades\Route;

Route::namespace('ParticipantApi')->group(function() {
    Route::apiResource('user_societies', 'UserSocietyController')->only(['index', 'store']);
});