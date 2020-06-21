@extends('layouts.app')

@section('content')
<div class="app-container">
    <div class="home-container pt-4">
        <nav class="card-custom">
            <ul>
                <li class="@if(Route::currentRouteName() == 'checks') active @endif"><a href="/home/checks" class="text-decoration-none">Periksa</a></li>
                <li class="@if(Route::currentRouteName() == 'babies') active @endif"><a href="/home/babies" class="text-decoration-none">Data Anak</a></li>
                <li><a href="{{ url('/logout') }}" class="text-decoration-none">Logout</a></li>
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