<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
Use App\Models\Kelas;
use App\Models\matakuliah;
use PDF;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination 
        $mahasiswa = Mahasiswa::with('kelas')->get();
        $paginate = Mahasiswa::orderBy('nim', 'asc')->paginate(3);
        return view('mahasiswa.index', ['mahasiswa' => $mahasiswa, 'paginate' => $paginate]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswa.create', ['kelas' => $kelas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //melakukan validasi data
         $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas_Id' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required',
            'tanggal_lahir' => 'required',
            'foto'=>'required',
        ]);
        // $image = $request->foto;
        //  if($image)
        //  {
        //     $image_up = $request->file('foto')->store('images','public');
        //  }
        // Mahasiswa::create([

        // 'Nim'=>$request->Nim
        // ,'Nama'=>$request->Nama
        // ,'Kelas_Id'=>$request->Kelas_Id
        // ,'Jurusan'=>$request->Jurusan
        // ,'No_Handphone'=>$request->No_Handphone
        // ,'email'=>$request->Email
        // ,'tanggal_lahir'=>$request->tanggal_lahir
        // ,'foto'=>$image_up

        // ]);
       
        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = $request->get('Nim');
        $mahasiswa->nama = $request->get('Nama');
        $mahasiswa->jurusan = $request->get('Jurusan');
        $mahasiswa->no_handphone = $request->get('No_Handphone');
        $mahasiswa->email = $request->get('Email');
        $mahasiswa->tanggal_lahir = $request->get('tanggal_lahir');
        $image_name = $request->file('foto')->store('images','public');
        $mahasiswa->foto = $image_name;

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas_Id');

        //fungsi eloquent untuk menambah data dengan relasi belongsTo
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa 
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        return view('mahasiswa.detail', ['Mahasiswa' => $Mahasiswa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit 
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
        $kelas = Kelas::all(); 
        return view('mahasiswa.edit', compact('Mahasiswa','kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $Nim)
    {
        //melakukan validasi data 
        $request->validate([ 
            'Nim' => 'required', 
            'Nama' => 'required',
            'email' => 'required', 
            'Kelas_Id' => 'required', 
            'Jurusan' => 'required', 
            'No_Handphone' => 'required', 
            'tanggal_lahir' => 'required',
            'foto'=>'required',
            ]); 
            

            //fungsi eloquent untuk mengupdate data inputan kita 
            $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first(); 
            $Mahasiswa->nim = $request->get('Nim');
            $Mahasiswa->nama = $request->get('Nama');
        
            $Mahasiswa->jurusan = $request->get('Jurusan');
            $Mahasiswa->no_handphone = $request->get('No_Handphone');
            $Mahasiswa->email = $request->get('Email');
            $Mahasiswa->tanggal_lahir = $request->get('tanggal_lahir');

            if($Mahasiswa->foto && file_exists(storage_path('app/public/'.$Mahasiswa->foto)))
        {
            Storage::delete('public/'.$mahasiswa->foto);
        }
        $image_name = $request->file('foto')->store('images','public');
        $Mahasiswa->foto = $image_name;



            $kelas = new Kelas;
            $kelas->id = $request->get('Kelas_Id');

            //fungsi eloquent untuk menambah data dengan relasi belongsTo
            $Mahasiswa->kelas()->associate($kelas);
            $Mahasiswa->save();

        
            //jika data berhasil diupdate, akan kembali ke halaman utama 
            return redirect()->route('mahasiswa.index')
             ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data 
        Mahasiswa::find($Nim)->delete(); 
        return redirect()->route('mahasiswa.index')
         -> with('success', 'Mahasiswa Berhasil Dihapus');
    }
    public function search(Request $request){
        $keyword = $request->keyword;
        $mahasiswa = Mahasiswa::where('nim', 'like', '%' .$keyword. '%')
        ->orWhere('nama', 'like', '%' .$keyword. '%')
        ->orWhere('kelas_id', 'like', '%' .$keyword. '%')
        ->orWhere('email', 'like', '%' .$keyword. '%')
        ->orWhere('jurusan', 'like', '%' .$keyword. '%')
        ->paginate(5);
        $mahasiswa->appends(['keyword' => $keyword]);
        return view('mahasiswa.index', compact('mahasiswa'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function nilai($nim){
        $nilai = Mahasiswa::with('kelas', 'matakuliah')->find($nim);
        return view('mahasiswa.nilai',compact('nilai'));
    }

    public function cetak_pdf($id)
    {
        $mhs = Mahasiswa::find($id);
        $pdf = PDF::loadview('mahasiswa.cetak_pdf',compact('mhs'));
        return $pdf->stream();
    }
}
