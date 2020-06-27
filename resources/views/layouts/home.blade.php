@extends('layouts.app')

@section('content')
<div class="app-container">
    <div class="home-container pt-4">
        <nav class="card-nav">
            <ul>
                <li id="to-checks-button" class="text-truncate @if(Route::currentRouteName() == 'checks') active @endif">Periksa</li>
                <li id="to-babies-button" class="text-truncate @if(Route::currentRouteName() == 'babies') active @endif">Data Balita</li>
                <li id="to-rules-button" class="text-truncate @if(Route::currentRouteName() == 'rules') active @endif">Kelola Perhitungan</li>
                <li id="to-logout-button" class="text-truncate"><img src="{{asset('icon/power.svg')}}" alt="" style="width:20px; height:20px"></li>
                <li class="animation"></li>
            </ul>
        </nav>
        @yield('home-content')
    </div>
</div>
@endsection

@section('scripts')
    <script defer src="{{asset('js/nav.js')}}"></script>
    @yield('scripts-home')
@endsection