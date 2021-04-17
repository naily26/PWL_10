@extends('mahasiswa.layout') 
@section('content') 

<div class="container mt-5"> 
    
    <div class="row justify-content-center align-items-center"> 
        <div class="card" style="width: 24rem;"> 
            <div class="card-header"> Tambah Mahasiswa </div> 
        <div class="card-body"> 
            
        @if ($errors->any()) 
        <div class="alert alert-danger"> 
            <strong>Whoops!</strong> There were some problems with your input.<br><br> 
            <ul> 
                @foreach ($errors->all() as $error) 
                <li>{{ $error }}</li> @endforeach 
            </ul> 
        </div> 
        @endif 
        
        <form method="post" action="{{ route('mahasiswa.store') }}" id="myForm"> 
            @csrf 
            <div class="form-group"> 
                <label for="Nim">Nim</label> 
                <input type="text" name="Nim" class="form-control" id="Nim" aria-describedby="Nim" > 
            </div> 
            <div class="form-group"> 
                <label for="Nama">Nama</label> 
                <input type="Nama" name="Nama" class="form-control" id="Nama" aria-describedby="Nama" > 
            </div>
            <div class="form-group"> 
                <label for="email">E-mail</label> 
                <input type="email" name="Email" class="form-control" id="email" aria-describedby="email" > 
            </div> 
            <div class="form-group"> 
                <label for="Kelas_Id">Kelas</label>
                <select class="form-control" name="Kelas_Id">
                    @foreach($kelas as $kls)
                    <option  value="{{$kls->id }}" name="Kelas_Id"  id="Kelas_Id">{{$kls->nama_kelas}}</option>
                    @endforeach
                </select>
            </div> 
            <div class="form-group"> 
                <label for="Jurusan">Jurusan</label> 
                <input type="Jurusan" name="Jurusan" class="form-control" id="Jurusan" aria-describedby="Jurusan" > 
            </div> 
            <div class="form-group"> 
                <label for="No_Handphone">No_Handphone</label> 
                <input type="No_Handphone" name="No_Handphone" class="form-control" id="No_Handphone" aria-describedby="No_Handphone" > 
            </div> 
            <div class="form-group"> 
                <label for="tanggal_lahir">Tanggal_Lahir</label> 
                <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir" aria-describedby="tanggal_lahir" > 
            </div> 
            <div class="form-group">
                <label for="foto">foto</label>
                <input type="file" name="foto" class="form-control" id="foto" aria-describedby="foto">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button> 
        </form> 
        </div> 
        </div> 
    </div> 
</div>
    @endsection