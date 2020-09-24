@extends('flyerless-subscription-management::layouts.app')

@section('Flyerless Subscription Management', 'Flyerless Subscription Management')

@section('module-content')
    <div id="p-main">
        <div id="p-title"> Flyerless Subscription Management </div>
        <div id="p-description"> From here you can manage your society subscriptions as well as manage your mailing lists </div>
    </div>

    <subscription-management-form
        :can-update="{{(\BristolSU\Support\Permissions\Facade\PermissionTester::evaluate('flyerless-subscription-management.user-society.update')?'true':'false')}}"
        query-string="{{url()->getAuthQueryString()}}"
    >

    </subscription-management-form>

@endsection

