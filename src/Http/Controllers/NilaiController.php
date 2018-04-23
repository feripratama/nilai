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
use Auth;

/**
 * The NilaiController class.
 *
 * @package Bantenprov\Nilai
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class NilaiController extends Controller
{
    protected $siswa;
    protected $nilai;
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Nilai $nilai, Siswa $siswa, User $user)
    {
        $this->nilai    = new Nilai;
        $this->siswa    = new Siswa;
        $this->user     = new User;
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

                $q->where('nomor_un', 'like', $value);
            });
        }

        $perPage    = request()->has('per_page') ? (int) request()->per_page : null;

        $response   = $query->with(['siswa', 'user', 'nilaiakademik'])->paginate($perPage);

        return response()->json($response)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $nilais = $this->nilai->with(['siswa', 'user', 'nilaiakademik'])->get();

        foreach ($nilais as $nilai) {
            if ($nilai->siswa !== null) {
                array_set($nilai, 'label', $nilai->siswa->nomor_un.' - '.$nilai->siswa->nama_siswa);
            } else {
                array_set($nilai, 'label', $nilai->nomor_un.' - ');
            }
        }

        $response['nilais']     = $nilais;
        $response['error']      = false;
        $response['message']    = 'Success';
        $response['status']     = true;

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [];

        $siswas = $this->siswa->all();
        $users_special = $this->user->all();
        $users_standar = $this->user->find(\Auth::User()->id);
        $current_user = \Auth::User();

        $role_check = \Auth::User()->hasRole(['superadministrator','administrator']);

        if($role_check){
            $response['user_special'] = true;
            foreach($users_special as $user){
                array_set($user, 'label', $user->name);
            }
            $response['user'] = $users_special;
        }else{
            $response['user_special'] = false;
            array_set($users_standar, 'label', $users_standar->name);
            $response['user'] = $users_standar;
        }

        array_set($current_user, 'label', $current_user->name);

        foreach($siswas as $siswa){
            array_set($siswa, 'label', $siswa->nama_siswa);
        }

        $response['siswa'] = $siswas;
        $response['current_user'] = $current_user;
        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nilai = $this->nilai;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:nilais,user_id',
            'nomor_un' => 'required|unique:nilais,nomor_un',
            'nilai' => 'required',
            'prestasi' => 'required',
            'zona' => 'required',
            'sktm' => 'required'
        ]);

        if($validator->fails()){
            $check = $nilai->where('user_id',$request->user_id)->orWhere('nomor_un',$request->nomor_un)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed ! Username, Nama Siswa, already exists';
            } else {
                $nilai->user_id = $request->input('user_id');
                $nilai->nomor_un = $request->input('nomor_un');
                $nilai->nilai = $request->input('nilai');
                $nilai->prestasi = $request->input('prestasi');
                $nilai->zona = $request->input('zona');
                $nilai->sktm = $request->input('sktm');
                $nilai->save();

                $response['message'] = 'success';
            }
        } else {
                $nilai->user_id = $request->input('user_id');
                $nilai->nomor_un = $request->input('nomor_un');
                $nilai->nilai = $request->input('nilai');
                $nilai->prestasi = $request->input('prestasi');
                $nilai->zona = $request->input('zona');
                $nilai->sktm = $request->input('sktm');
                $nilai->save();

            $response['message'] = 'success';
        }

        $response['status'] = true;

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nilai = $this->nilai->with(['siswa', 'user', 'nilaiakademik'])->findOrFail($id);

        $response['nilai']      = $nilai;
        $response['error']      = false;
        $response['message']    = 'Success';
        $response['status']     = true;

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
                'user_id' => 'required|unique:nilais,user_id,'.$id,
                'nomor_un' => 'required|unique:nilais,nomor_un,'.$id,
                'nilai' => 'required',
                'prestasi' => 'required',
                'zona' => 'required',
                'sktm' => 'required'

            ]);

        if ($validator->fails()) {

            foreach($validator->messages()->getMessages() as $key => $error){
                        foreach($error AS $error_get) {
                            array_push($message, $error_get);
                        }
                    }

             $check_user     = $this->nilai->where('id','!=', $id)->where('user_id', $request->user_id);
             $check_siswa = $this->nilai->where('id','!=', $id)->where('nomor_un', $request->nomor_un);

             if($check_user->count() > 0 || $check_siswa->count() > 0 ){
                  $response['message'] = implode("\n",$message);
            } else {
                $nilai->user_id = $request->input('user_id');
                $nilai->nomor_un = $request->input('nomor_un');
                $nilai->nilai = $request->input('nilai');
                $nilai->prestasi = $request->input('prestasi');
                $nilai->zona = $request->input('zona');
                $nilai->sktm = $request->input('sktm');
                $nilai->save();

                $response['message'] = 'success';
            }
        } else {
                $nilai->user_id = $request->input('user_id');
                $nilai->nomor_un = $request->input('nomor_un');
                $nilai->nilai = $request->input('nilai');
                $nilai->prestasi = $request->input('prestasi');
                $nilai->zona = $request->input('zona');
                $nilai->sktm = $request->input('sktm');
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
            $response['message']    = 'Success';
            $response['success']    = true;
            $response['status']     = true;
        } else {
            $response['message']    = 'Failed';
            $response['success']    = false;
            $response['status']     = false;
        }

        return json_encode($response);
    }
}
