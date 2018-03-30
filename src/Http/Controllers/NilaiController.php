<?php

namespace Bantenprov\Nilai\Http\Controllers;

/* Require */
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Bantenprov\Nilai\Facades\NilaiFacade;

/* Models */
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai;
use Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa;
use Bantenprov\Prestasi\Models\Bantenprov\Prestasi\Prestasi;
use Bantenprov\Sktm\Models\Bantenprov\Sktm\Sktm;
use App\User;

/* Etc */
use Validator;

/**
 * The NilaiController class.
 *
 * @package Bantenprov\Nilai
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class NilaiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $siswa;
    protected $nilai;
    protected $sktm;
    protected $prestasi;
    protected $user;

    public function __construct(Nilai $nilai, Siswa $siswa, Sktm $sktm, Prestasi $prestasi, User $user)
    {
        $this->nilai = $nilai;
        $this->siswa = $siswa;
        $this->sktm = $sktm;
        $this->prestasi = $prestasi;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (request()->has('sort')) {
            list($sortCol, $sortDir) = explode('|', request()->sort);

            $query = $this->nilai->orderBy($sortCol, $sortDir);
        } else {
            $query = $this->nilai->orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where('user_id', 'like', $value)
                    ->orWhere('siswa_id', 'like', $value);
            });
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $response = $query->with('user')->with('siswa')->with('prestasi')->with('sktm')->paginate($perPage);
                
        return response()->json($response)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $siswas = $this->siswa->all();
        $users = $this->user->all();
        $sktms = $this->sktm->all();
        $prestasis = $this->prestasi->all();

        foreach($users as $user){
            array_set($user, 'label', $user->name);
        }

        foreach($siswas as $siswa){
            array_set($siswa, 'label', $siswa->nama_siswa);
        }

        foreach($sktms as $sktm){
            array_set($sktm, 'label', $sktm->no_sktm);
        }

        foreach($prestasis as $prestasi){
            array_set($prestasi, 'label', $prestasi->nama_lomba);
        }
        
        $response['siswa'] = $siswas;
        $response['user'] = $users;
        $response['sktm'] = $sktms;
        $response['prestasi'] = $prestasis;
        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nilai = $this->nilai;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:nilais,user_id',
            'siswa_id' => 'required|unique:nilais,siswa_id',
            'nomor_un' => 'required|unique:nilais,nomor_un',
            'akademik_id' => 'required|unique:nilais,akademik_id',
            'prestasi_id' => 'required|unique:nilais,prestasi_id',
            'zona_id' => 'required|unique:nilais,zona_id',
            'sktm_id' => 'required|unique:nilais,sktm_id'
        ]);

        if($validator->fails()){
            $check = $nilai->where('user_id',$request->user_id)->orWhere('nomor_un',$request->nomor_un)->orWhere('siswa_id',$request->siswa_id)->orWhere('akademik_id',$request->akademik_id)->orWhere('prestasi_id',$request->prestasi_id)->orWhere('sktm_id',$request->sktm_id)->orWhere('zona_id',$request->zona_id)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed ! Username, Nomor UN, Nama Siswa, Prestasi, Sktm, Zona, Akademik already exists';
            } else {
                $nilai->user_id = $request->input('user_id');
                $nilai->siswa_id = $request->input('siswa_id');
                $nilai->nomor_un = $request->input('nomor_un');
                $nilai->akademik_id = $request->input('akademik_id');
                $nilai->prestasi_id = $request->input('prestasi_id');
                $nilai->zona_id = $request->input('zona_id');
                $nilai->sktm_id = $request->input('sktm_id');
                $nilai->save();

                $response['message'] = 'success';
            }
        } else {
            $nilai->user_id = $request->input('user_id');
                $nilai->siswa_id = $request->input('siswa_id');
                $nilai->nomor_un = $request->input('nomor_un');
                $nilai->akademik_id = $request->input('akademik_id');
                $nilai->prestasi_id = $request->input('prestasi_id');
                $nilai->zona_id = $request->input('zona_id');
                $nilai->sktm_id = $request->input('sktm_id');
                $nilai->save();

            $response['message'] = 'success';
        }

        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nilai = $this->nilai->findOrFail($id);

        $response['user'] = $nilai->user;
        $response['siswa'] = $nilai->siswa;
        $response['prestasi'] = $nilai->prestasi;
        $response['sktm'] = $nilai->sktm;
        $response['nilai'] = $nilai;
        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nilai = $this->nilai->findOrFail($id);

        array_set($nilai->user, 'label', $nilai->user->name);
        array_set($nilai->siswa, 'label', $nilai->siswa->nama_siswa);
        array_set($nilai->prestasi, 'label', $nilai->prestasi->nama_lomba);
        array_set($nilai->sktm, 'label', $nilai->sktm->no_sktm);

        $response['nilai'] = $nilai;
        $response['siswa'] = $nilai->siswa;
        $response['user'] = $nilai->user;
        $response['prestasi'] = $nilai->prestasi;
        $response['sktm'] = $nilai->sktm;
        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $response = array();
        $message  = array();

        $nilai = $this->nilai->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|unique:nilais,user_id',
            'siswa_id' => 'required|unique:nilais,siswa_id',
            'nomor_un' => 'required|unique:nilais,nomor_un',
            'akademik_id' => 'required|unique:nilais,akademik_id',
            'prestasi_id' => 'required|unique:nilais,prestasi_id',
            'zona_id' => 'required|unique:nilais,zona_id',
            'sktm_id' => 'required|unique:nilais,sktm_id'

            ]);

        if ($validator->fails()) {

            foreach($validator->messages()->getMessages() as $key => $error){
                        foreach($error AS $error_get) {
                            array_push($message, $error_get);
                        }                
                    } 

             $check_user     = $this->nilai->where('id','!=', $id)->where('user_id', $request->user_id);
             $check_nomor_un = $this->nilai->where('id','!=', $id)->where('nomor_un', $request->nomor_un);
             $check_siswa = $this->nilai->where('id','!=', $id)->where('siswa_id', $request->siswa_id);
             $check_akademik_id = $this->nilai->where('id','!=', $id)->where('akademik_id', $request->akademik_id);
             $check_prestasi = $this->nilai->where('id','!=', $id)->where('prestasi_id', $request->prestasi_id);
             $check_zona_id = $this->nilai->where('id','!=', $id)->where('zona_id', $request->zona_id);
             $check_sktm = $this->nilai->where('id','!=', $id)->where('sktm_id', $request->sktm_id);

             if($check_user->count() > 0 || $check_nomor_un->count() > 0 || $check_siswa->count() > 0 || $check_akademik_id->count() > 0 || $check_prestasi->count() > 0 || $check_zona_id->count() > 0 || $check_sktm->count() > 0){
                  $response['message'] = implode("\n",$message);
            } else {
                $nilai->user_id    = $request->input('user_id');
                $nilai->siswa_id    = $request->input('siswa_id');
                $nilai->nomor_un    = $request->input('nomor_un');
                $nilai->akademik_id    = $request->input('akademik_id');
                $nilai->prestasi_id    = $request->input('prestasi_id');
                $nilai->zona_id    = $request->input('zona_id');
                $nilai->sktm_id    = $request->input('sktm_id');
                $nilai->save();

                $response['message'] = 'success';
            }
        } else {
            $nilai->user_id    = $request->input('user_id');
                $nilai->siswa_id    = $request->input('siswa_id');
                $nilai->nomor_un    = $request->input('nomor_un');
                $nilai->akademik_id    = $request->input('akademik_id');
                $nilai->prestasi_id    = $request->input('prestasi_id');
                $nilai->zona_id    = $request->input('zona_id');
                $nilai->sktm_id    = $request->input('sktm_id');
                $nilai->save();

            $response['message'] = 'success';
        }

        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nilai = $this->nilai->findOrFail($id);

        if ($nilai->delete()) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        return json_encode($response);
    }
}
