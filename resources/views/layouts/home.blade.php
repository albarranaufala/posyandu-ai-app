@extends('layouts.app')

@section('content')
<div class="app-container">
    <div class="home-container pt-4">
        <nav class="card-nav">
            <ul>
                <li id="to-checks-button" class="@if(Route::currentRouteName() == 'checks') active @endif">Periksa</li>
                <li id="to-babies-button" class="@if(Route::currentRouteName() == 'babies') active @endif">Data Anak</li>
                <li id="to-logout-button">Logout</li>
                <li class="animation"></li>
            </ul>
        </nav>
        @yield('home-content')
    </div>
</div>
@endsection

@section('scripts')
    <script defer src="{{asset('js/home.js')}}"></script>
@endsection