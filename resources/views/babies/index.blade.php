@extends('layouts.home')

@section('home-content')
<div class="px-3 py-5">
    <div class="row">
        <div class="col-12">
            <div class="card-add-baby">
                <div id="add-baby-button" class="card-body">
                    Tambah Anak
                </div>
                <div id="add-baby-form" class="card-body hide">
                    <div class="form-group">
                        <label for="nama_anak">Nama Anak</label>
                        <input id="nama_anak" type="text" class="form-control @error('nama_anak') is-invalid @enderror" name="nama_anak" value="{{ old('nama_anak') }}" required autocomplete="nama_anak" placeholder="Masukkan nama anak">
    
                        @error('nama_anak')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" value="{{ old('jenis_kelamin') }}" required autocomplete="jenis_kelamin">
                            <option value="" selected disabled>Pilih jenis kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
    
                        @error('jenis_kelamin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input id="tanggal_lahir" type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required autocomplete="tanggal_lahir">
    
                        @error('tanggal_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_ibu">Nama Ibu</label>
                        <input id="nama_ibu" type="text" class="form-control @error('nama_ibu') is-invalid @enderror" name="nama_ibu" value="{{ old('nama_ibu') }}" required autocomplete="nama_ibu" placeholder="Masukkan nama ibu">
    
                        @error('nama_ibu')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="kontak">Kontak yang bisa dihubungi</label>
                        <input id="kontak" type="number" class="form-control @error('kontak') is-invalid @enderror" name="kontak" value="{{ old('kontak') }}" required autocomplete="kontak" placeholder="Masukkan kontak">
    
                        @error('kontak')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat rumah</label>
                        <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat" required autocomplete="alamat">{{ old('alamat') }}</textarea>
    
                        @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group text-right">
                        <button class="btn btn-light" id="cancel-add-button">Batal</button>
                        <button class="btn btn-posyandu px-5">Daftarkan Anak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <div class="card-add-baby mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center">
                            <span><strong>Albarra Naufala Erdanto</strong></span>
                            <span class="color-red"><small>ASDNFLASDLK</small></span>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                            <span>12/12/2019</span>
                            <span><small>Laki-laki</small></span>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                            <span>Erni Catur</span>
                            <span><small>08123123123</small></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-add-baby mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center">
                            <span><strong>Fakhri Pradana</strong></span>
                            <span class="color-red"><small>ASDNFLASDLK</small></span>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                            <span>12/12/2019</span>
                            <span><small>Laki-laki</small></span>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                            <span>Erni Catur</span>
                            <span><small>08123123123</small></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-add-baby mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center">
                            <span><strong>Salsabila Zahirah Ramania Musdalifah</strong></span>
                            <span class="color-red"><small>ASDNFLASDLK</small></span>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                            <span>12/12/2019</span>
                            <span><small>Laki-laki</small></span>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                            <span>Erni Catur</span>
                            <span><small>08123123123</small></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts-home')
    <script defer src="{{asset('js/babies.js')}}"></script>
@endsection
