@extends('bristolsu::base')

@section('content')
    <div id="flyerless-subscription-management-root">
        @yield('module-content')
    </div>
@endsection

@push('styles')
    <link href="{{ asset('modules/flyerless-subscription-management/css/module.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('modules/flyerless-subscription-management/js/module.js') }}"></script>
@endpush
