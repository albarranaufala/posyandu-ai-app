@extends('layouts.home')

@section('home-content')
<div class="p-3">
    <div class="row">
        <div class="col-12">
            <div id="check-form-container">
                <h1>Periksakan balita untuk melihat nilai gizi.</h1>
                <form id="check-form">
                    @csrf
                    <div class="form-group mt-3">
                        <label for="nama_anak">Cari nama balita yang ingin diperiksa</label>
                        <select id="nama_anak" type="text" class="form-control @error('nama_anak') is-invalid @enderror" name="nama_anak" value="{{ old('nama_anak') }}" required autocomplete="nama_anak">
                            <option value="" selected disabled>Cari nama balita</option>
                            @foreach ($babies as $baby)
                            <option value="{{$baby->id}}">{{$baby->baby_name}}</option>
                            @endforeach
                        </select>
    
                        @error('nama_anak')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="berat_badan">Berat badan balita (kg)</label>
                        <input id="berat_badan" type="text" class="form-control @error('berat_badan') is-invalid @enderror" name="berat_badan" value="{{ old('berat_badan') }}" required autocomplete="berat_badan" placeholder="Masukkan berat badan">
    
                        @error('berat_badan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tinggi_badan">Tinggi badan balita (cm)</label>
                        <input id="tinggi_badan" type="text" class="form-control @error('tinggi_badan') is-invalid @enderror" name="tinggi_badan" value="{{ old('tinggi_badan') }}" required autocomplete="tinggi_badan" placeholder="Masukkan tinggi badan">
    
                        @error('tinggi_badan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group text-right">
                        <button class="btn btn-posyandu px-5" type="submit">Periksa</button>
                    </div>
                </form>
            </div>
            <div id="check-container">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts-home')
    <script defer src="{{asset('js/checks.js')}}"></script>
@endsection


