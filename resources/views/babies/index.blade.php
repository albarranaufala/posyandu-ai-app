@extends('layouts.home')

@section('home-content')
<div class="p-3">
    <div id="page-babies">
        <div class="row">
            <div class="col-12">
                <div class="card-add-baby">
                    <div id="add-baby-button" class="card-body">
                        Tambah Anak
                    </div>
                    <form id="add-baby-form" class="card-body hide">
                        @csrf
                        <div class="form-group">
                            <label for="nama_anak">Nama Anak</label>
                            <input id="nama_anak" type="text"
                                class="form-control @error('nama_anak') is-invalid @enderror" name="nama_anak"
                                value="{{ old('nama_anak') }}" required autocomplete="nama_anak"
                                placeholder="Masukkan nama anak">

                            @error('nama_anak')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                name="jenis_kelamin" value="{{ old('jenis_kelamin') }}" required
                                autocomplete="jenis_kelamin">
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
                            <input id="tanggal_lahir" type="date"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir') }}" required autocomplete="tanggal_lahir">

                            @error('tanggal_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_ibu">Nama Ibu</label>
                            <input id="nama_ibu" type="text"
                                class="form-control @error('nama_ibu') is-invalid @enderror" name="nama_ibu"
                                value="{{ old('nama_ibu') }}" required autocomplete="nama_ibu"
                                placeholder="Masukkan nama ibu">

                            @error('nama_ibu')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kontak">Kontak yang bisa dihubungi</label>
                            <input id="kontak" type="number" class="form-control @error('kontak') is-invalid @enderror"
                                name="kontak" value="{{ old('kontak') }}" required autocomplete="kontak"
                                placeholder="Masukkan kontak">

                            @error('kontak')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat rumah</label>
                            <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                name="alamat" required autocomplete="alamat">{{ old('alamat') }}</textarea>

                            @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <button class="btn btn-light" id="cancel-add-button" type="button">Batal</button>
                            <button class="btn btn-posyandu px-5" type="submit">Daftarkan Anak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 d-flex flex-wrap justify-content-between">
                <h3><strong>Data Anak</strong></h3>
                <div class="form-group">
                    <input id="baby-search" type="text" class="form-control" placeholder="Cari anak...">
                </div>
            </div>
            <div class="col-12" id="babies-container">
                <div style="height:250px" class="d-flex justify-content-center align-items-center">
                    <div class="lds-ring">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="page-detail-baby">
        
    </div>
</div>
@endsection

@section('scripts-home')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script defer src="{{asset('js/babies.js')}}"></script>
@endsection