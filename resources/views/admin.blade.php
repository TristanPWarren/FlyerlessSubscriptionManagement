@extends('flyerless-subscription-management::layouts.app')

@section('title', 'Your Module')

@section('module-content')
    <div id="p-main">
        <div id="p-title"> Flyerless Subscription Management </div>
        <div id="p-description"> From here you can download a list of the emails of every member of your society </div>
    </div>

    <subscription-download
        :can-download="{{(\BristolSU\Support\Permissions\Facade\PermissionTester::evaluate('flyerless-subscription-management.download-members.download')?'true':'false')}}"
        query-string="{{url()->getAuthQueryString()}}"
    >

    </subscription-download>


@endsection