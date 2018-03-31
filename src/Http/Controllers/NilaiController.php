<?php

namespace Bantenprov\Nilai\Http\Controllers;

/* Require */
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Bantenprov\Nilai\Facades\NilaiFacade;

/* Models */
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai;
use Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa;
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
    protected $user;

    public function __construct(Nilai $nilai, Siswa $siswa, User $user)
    {
        $this->nilai = $nilai;
        $this->siswa = $siswa;
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
                    ->orWhere('id', 'like', $value);
            });
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $response = $query->with('user')->with('siswa')->paginate($perPage);
                
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

        foreach($users as $user){
            array_set($user, 'label', $user->name);
        }

        foreach($siswas as $siswa){
            array_set($siswa, 'label', $siswa->nama_siswa);
        }
        
        $response['siswa'] = $siswas;
        $response['user'] = $users;
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
            'akademik_id' => 'required|unique:nilais,akademik_id',
            'prestasi_id' => 'required|unique:nilais,prestasi_id',
            'zona_id' => 'required|unique:nilais,zona_id',
            'sktm_id' => 'required|unique:nilais,sktm_id'
        ]);

        if($validator->fails()){
            $check = $nilai->where('user_id',$request->user_id)->orWhere('siswa_id',$request->siswa_id)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed ! Username, Nama Siswa, already exists';
            } else {
                $nilai->user_id = $request->input('user_id');
                $nilai->siswa_id = $request->input('siswa_id');
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
        $response['nilai'] = $nilai;
        $response['siswa'] = $nilai->siswa;
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

        $response['nilai'] = $nilai;
        $response['siswa'] = $nilai->siswa;
        $response['user'] = $nilai->user;
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
             $check_siswa = $this->nilai->where('id','!=', $id)->where('siswa_id', $request->siswa_id);

             if($check_user->count() > 0 || $check_siswa->count() > 0 ){
                  $response['message'] = implode("\n",$message);
            } else {
                $nilai->user_id = $request->input('user_id');
                $nilai->siswa_id = $request->input('siswa_id');
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
