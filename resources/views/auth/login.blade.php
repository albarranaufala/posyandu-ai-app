@extends('layouts.app')

@section('content')
<div class="login-container d-flex justify-content-center flex-column overflow-hidden">
    <div class="row h-550px">
        <div class="col-lg-9 mx-auto h-100">
            <div class="card shadow h-100">
                <div class="row h-100">
                    <div class="col-7 d-none d-md-block pr-0">
                        <div class="login-image">
                            <div>
                                <span>Sistem Pendukung Keputusan</span>
                                <h1>Posyandu</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 pl-md-0">
                        <div class="card-body d-flex flex-column align-items-center h-100 card-body-login">
                            <div class="login-toggle">
                                <ul>
                                    <li class="active" id="kader-button">
                                        Kader
                                    </li>
                                    <li id="ibu-button">
                                        Ibu
                                    </li>
                                    <li class="animation"></li>
                                </ul>
                            </div>
                            <div class="w-100 flex-fill d-flex align-items-center" id="login-container">
                                <form id="login-form" class="w-100" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group ">
                                        <label for="Username">{{ __('Username') }}</label>
                                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username">
                    
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                        
                                    {{-- <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    
                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div> --}}
                        
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-posyandu btn-block">
                                            {{ __('Login') }}
                                        </button>
                    
                                        {{-- @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif --}}
                                    </div>
                                </form>
                                <form id="ibu-form" class="hide w-100" action="">
                                    <div class="form-group ">
                                        <label for="Kode Anak">{{ __('Kode Anak') }}</label>
                                        <input id="kode_anak" type="text" class="form-control @error('kode_anak') is-invalid @enderror" name="kode_anak" value="{{ old('kode_anak') }}" required autocomplete="kode_anak">
                    
                                        @error('kode_anak')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-posyandu btn-block">
                                            {{ __('Cek Anak') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script defer src="{{asset('js/login.js')}}"></script>
@endsection
