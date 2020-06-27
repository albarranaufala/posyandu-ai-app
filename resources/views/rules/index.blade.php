@extends('layouts.home')

@section('home-content')
<div class="p-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap my-3">
        <h2><strong>Daftar Aturan</strong></h2>
        <div>
            <button class="btn btn-success">Variabel</button>
            <button class="btn btn-posyandu" type="button" id="btn-add-rule">Tambah</button>
        </div>
    </div>
    <form class="card-posyandu my-3 d-none" id="form-add-rule">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="kode">Kode</label>
                        <input type="text" name="kode" id="kode" class="form-control">
                    </div>
                </div>    
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="umur">Umur (input)</label>
                        <select name="umur" id="umur" class="form-control">
                            @foreach ($variables[0]->sets as $set)
                            <option value="{{$set->id}}">{{$set->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="berat_badan">Berat Badan (input)</label>
                        <select name="berat_badan" id="berat_badan" class="form-control">
                            @foreach ($variables[1]->sets as $set)
                            <option value="{{$set->id}}">{{$set->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="tinggi_badan">Tinggi Badan (input)</label>
                        <select name="tinggi_badan" id="tinggi_badan" class="form-control">
                            @foreach ($variables[2]->sets as $set)
                            <option value="{{$set->id}}">{{$set->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="nilai_gizi">Nilai Gizi (output)</label>
                        <select name="nilai_gizi" id="nilai_gizi" class="form-control">
                            @foreach ($variables[3]->sets as $set)
                            <option value="{{$set->id}}">{{$set->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right">
                    <button class="btn btn-light" type="button" id="btn-cancel-add-rule">Batal</button>
                    <button class="btn btn-posyandu px-5" type="submit">Tambah</button>
                </div>
            </div>
        </div>
    </form>
    <div id="rules-container">
        {{-- tabel rules --}}
    </div>
</div>
@endsection

@section('scripts-home')
{{-- script --}}
<script defer>
    let rules = @json($rules);
    let variables = @json($variables);
    let csrfToken = @json(csrf_token())
</script>
<script src="{{asset('js/rules.js')}}" defer></script>
@endsection