<?php

namespace Bantenprov\Nilai\Http\Controllers;

/* Require */
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Bantenprov\Nilai\Facades\NilaiFacade;

/* Models */
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Akademik;
use Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa;
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai;
use App\User;

/* Etc */
use Validator;
use Auth;

/**
 * The AkademikController class.
 *
 * @package Bantenprov\Nilai
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class AkademikController extends Controller
{
    protected $akademik;
    protected $siswa;
    protected $user;
    protected $nilai;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->akademik = new Akademik;
        $this->siswa    = new Siswa;
        $this->user     = new User;
        $this->nilai    = new Nilai;
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

            $query = $this->akademik->orderBy($sortCol, $sortDir);
        } else {
            $query = $this->akademik->orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";

                $q->where('nomor_un', 'like', $value);
            });
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;

        $response = $query->with(['siswa', 'user'])->paginate($perPage);

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
        $user_id        = isset(Auth::User()->id) ? Auth::User()->id : null;
        $akademik       = $this->akademik->getAttributes();
        $siswas         = $this->siswa->getAttributes();
        $users          = $this->user->getAttributes();
        $users_special  = $this->user->all();
        $users_standar  = $this->user->findOrFail($user_id);
        $current_user   = Auth::User();

        foreach($siswas as $siswa){
            array_set($siswa, 'label', $siswa->nama_siswa);
        }

        $role_check = Auth::User()->hasRole(['superadministrator','administrator']);

        if($role_check){
            $user_special = true;

            foreach($users_special as $user){
                array_set($user, 'label', $user->name);
            }

            $users = $users_special;
        }else{
            $user_special = false;

            array_set($users_standar, 'label', $users_standar->name);

            $users = $users_standar;
        }

        array_set($current_user, 'label', $current_user->name);

        $response['akademik']       = $akademik;
        $response['siswas']         = $siswas;
        $response['users']          = $users;
        $response['user_special']   = $user_special;
        $response['current_user']   = $current_user;
        $response['error']          = false;
        $response['message']        = 'Success';
        $response['status']         = true;

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
        $akademik = $this->akademik;

        $validator = Validator::make($request->all(), [
            'nomor_un'          => "required|exists:{$this->siswa->getTable()},nomor_un|unique:{$this->akademik->getTable()},nomor_un,NULL,id,deleted_at,NULL",
            'bahasa_indonesia'  => 'required|numeric|min:0|max:100',
            'bahasa_inggris'    => 'required|numeric|min:0|max:100',
            'matematika'        => 'required|numeric|min:0|max:100',
            'ipa'               => 'required|numeric|min:0|max:100',
            'user_id'           => "required|exists:{$this->user->getTable()},id",
        ]);

        if ($validator->fails()) {
            $error      = true;
            $message    = $validator->errors()->first();
        } else {
            $akademik->nomor_un         = $request->input('nomor_un');
            $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
            $akademik->bahasa_inggris   = $request->input('bahasa_inggris');
            $akademik->matematika       = $request->input('matematika');
            $akademik->ipa              = $request->input('ipa');
            $akademik->user_id          = $request->input('user_id');
            $akademik->save();

            $error      = false;
            $message    = 'Success';
        }

        $response['akademik']   = $akademik;
        $response['error']      = $error;
        $response['message']    = $message;
        $response['status']     = true;

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
        $akademik   = $this->akademik->with(['siswa', 'user'])->findOrFail($id);

        $response['akademik']   = $akademik;
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
        $user_id        = isset(Auth::User()->id) ? Auth::User()->id : null;
        $akademik       = $this->akademik->with(['siswa', 'user'])->findOrFail($id);
        $siswas         = $this->siswa->getAttributes();
        $users          = $this->user->getAttributes();
        $users_special  = $this->user->all();
        $users_standar  = $this->user->findOrFail($user_id);
        $current_user   = Auth::User();

        if ($akademik->siswa !== null) {
            array_set($akademik->siswa, 'label', $akademik->siswa->nama_siswa);
        }

        $role_check = Auth::User()->hasRole(['superadministrator','administrator']);

        if ($akademik->user !== null) {
            array_set($akademik->user, 'label', $akademik->user->name);
        }

        if($role_check){
            $user_special = true;

            foreach($users_special as $user){
                array_set($user, 'label', $user->name);
            }

            $users = $users_special;
        }else{
            $user_special = false;

            array_set($users_standar, 'label', $users_standar->name);

            $users = $users_standar;
        }

        array_set($current_user, 'label', $current_user->name);

        $response['akademik']       = $akademik;
        $response['siswas']         = $siswas;
        $response['users']          = $users;
        $response['user_special']   = $user_special;
        $response['current_user']   = $current_user;
        $response['error']          = false;
        $response['message']        = 'Success';
        $response['status']         = true;

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
        $akademik   = $this->akademik->with(['siswa', 'user'])->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nomor_un'          => "required|exists:{$this->siswa->getTable()},nomor_un|unique:{$this->akademik->getTable()},nomor_un,{$id},id,deleted_at,NULL",
            'bahasa_indonesia'  => 'required|numeric|min:0|max:100',
            'bahasa_inggris'    => 'required|numeric|min:0|max:100',
            'matematika'        => 'required|numeric|min:0|max:100',
            'ipa'               => 'required|numeric|min:0|max:100',
            'user_id'           => "required|exists:{$this->user->getTable()},id",
        ]);

        if ($validator->fails()) {
            $error      = true;
            $message    = $validator->errors()->first();
        } else {
            $akademik->nomor_un         = $request->input('nomor_un');
            $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
            $akademik->bahasa_inggris   = $request->input('bahasa_inggris');
            $akademik->matematika       = $request->input('matematika');
            $akademik->ipa              = $request->input('ipa');
            $akademik->user_id          = $request->input('user_id');
            $akademik->save();

            $error      = false;
            $message    = 'Success';
        }

        $response['akademik']   = $akademik;
        $response['error']      = $error;
        $response['message']    = $message;
        $response['status']     = true;

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
        $akademik = $this->akademik->findOrFail($id);

        if ($akademik->delete()) {
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
