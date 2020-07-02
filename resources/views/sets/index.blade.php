@extends('layouts.home')

@section('home-content')
<div class="p-3">
    <div aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home/rules">Aturan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Himpunan Variabel</li>
        </ol>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h2><strong>Daftar Himpunan Variabel</strong></h2>
        <div>
            <button class="btn btn-posyandu" type="button" id="btn-add-set">Tambah</button>
        </div>
    </div>
    <form class="card-posyandu my-3 d-none" id="form-add-set" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="variabel">Variabel</label>
                        <select name="variabel" id="variabel" class="form-control" required>
                            <option value="" disabled selected>Pilih variabel</option>
                            @foreach ($variables as $variable)
                            <option value="{{$variable->id}}">{{$variable->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="nama_himpunan">Nama Himpunan</label>
                        <input name="nama_himpunan" id="nama_himpunan" class="form-control" placeholder="cth: Sedang" required/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="kode_himpunan">Kode Himpunan</label>
                        <input name="kode_himpunan" id="kode_himpunan" class="form-control" placeholder="cth: UF1" required/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="range">Range</label>
                        <input name="range" id="range" class="form-control" placeholder="cth: 6,12" required/>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="kurva">Kurva</label>
                        <select name="kurva" id="kurva" class="form-control" required>
                            <option value="" disabled selected>Pilih kurva</option>
                            <option>linear turun</option>
                            <option>segitiga</option>
                            <option>linear naik</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right">
                    <button class="btn btn-light" type="button" id="btn-cancel-add-set">Batal</button>
                    <button class="btn btn-posyandu px-5" type="submit" id="submit-form">Tambah</button>
                </div>
            </div>
        </div>
    </form>
    <div class="card-posyandu">
        <div class="card-body">
            <div class = "table-responsive">
                <table class = "table table-borderless mb-0">
                    <tr>
                        <th scope = "row">No.</th>
                        <th>Variabel</th> 
                        <th>Himpunan</th> 
                        <th>Range</th> 
                        <th>Kurva</th> 
                        <th></th> 
                    </tr>
                    @foreach ($sets as $set)
                    <tr>
                        <td scope = "row">{{$loop->iteration}}</td>
                        <td>{{$set->variable_name}}</td> 
                        <td>{{$set->name}}</td> 
                        <td>{{$set->range}}</td> 
                        <td>{{$set->curve}}</td> 
                        <td>
                            <i class = "material-icons icon-posyandu btn-edit-set" data-set-id="{{$set->id}}">edit</i>
                            <form action="/sets/delete/{{$set->id}}" id="delete-form" class="d-inline" method="POST">
                                @csrf
                                <i class = "material-icons icon-posyandu btn-delete-set">delete</i>
                            </form>
                        </td> 
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts-home')
{{-- script --}}
<script>
    let sets = @json($sets);
</script>
<script src="{{asset('js/sets.js')}}" defer></script>
@endsection