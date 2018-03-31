<?php

namespace Bantenprov\Nilai\Http\Controllers;

/* Require */
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Bantenprov\Nilai\Facades\NilaiFacade;

/* Models */
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Akademik;
use App\User;

/* Etc */
use Validator;

/**
 * The AkademikController class.
 *
 * @package Bantenprov\Akademik
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class AkademikController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $akademik;
    protected $user;

    public function __construct(Akademik $akademik, User $user)
    {
        $this->akademik = $akademik;
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

            $query = $this->akademik->orderBy($sortCol, $sortDir);
        } else {
            $query = $this->akademik->orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function($q) use($request) {
                $value = "%{$request->filter}%";
                $q->where('user_id', 'like', $value)
                    ->orWhere('nomor_un', 'like', $value);
            });
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        $response = $query->with('user')->paginate($perPage);
                
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
        $users = $this->user->all();

        foreach($users as $user){
            array_set($user, 'label', $user->name);
        }
        
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
        $akademik = $this->akademik;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:akademiks,user_id',
            'bahasa_indonesia' => 'required',
            'bahasa_inggris' => 'required',
            'matematika' => 'required',
            'nomor_un' => 'required|unique:akademiks,nomor_un'
        ]);

        if($validator->fails()){
            $check = $akademik->where('user_id',$request->user_id)->orWhere('nomor_un',$request->nomor_un)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed ! Username, Nomor UN, already exists';
            } else {
                $akademik->user_id = $request->input('user_id');
                $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
                $akademik->nomor_un = $request->input('nomor_un');
                $akademik->bahasa_inggris = $request->input('bahasa_inggris');
                $akademik->matematika = $request->input('matematika');
                $akademik->save();

                $response['message'] = 'success';
            }
        } else {
            $akademik->user_id = $request->input('user_id');
                $akademik->user_id = $request->input('user_id');
                $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
                $akademik->nomor_un = $request->input('nomor_un');
                $akademik->bahasa_inggris = $request->input('bahasa_inggris');
                $akademik->matematika = $request->input('matematika');
                $akademik->save();

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
        $akademik = $this->akademik->findOrFail($id);

        $response['user'] = $akademik->user;
        $response['akademik'] = $akademik;
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
        $akademik = $this->akademik->findOrFail($id);

        array_set($akademik->user, 'label', $akademik->user->name);

        $response['akademik'] = $akademik;
        $response['user'] = $akademik->user;
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

        $akademik = $this->akademik->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|unique:akademiks,user_id',
                'bahasa_indonesia' => 'required',
                'bahasa_inggris' => 'required',
                'matematika' => 'required',
                'nomor_un' => 'required|unique:akademiks,nomor_un'

            ]);

        if ($validator->fails()) {

            foreach($validator->messages()->getMessages() as $key => $error){
                        foreach($error AS $error_get) {
                            array_push($message, $error_get);
                        }                
                    } 

             $check_user     = $this->akademik->where('id','!=', $id)->where('user_id', $request->user_id);
             $check_nomor_un = $this->akademik->where('id','!=', $id)->where('nomor_un', $request->nomor_un);

             if($check_user->count() > 0 || $check_nomor_un->count() > 0){
                  $response['message'] = implode("\n",$message);
            } else {
                $akademik->user_id    = $request->input('user_id');
                $akademik->bahasa_indonesia    = $request->input('bahasa_indonesia');
                $akademik->nomor_un    = $request->input('nomor_un');
                $akademik->bahasa_inggris    = $request->input('bahasa_inggris');
                $akademik->matematika    = $request->input('matematika');
                $akademik->save();

                $response['message'] = 'success';
            }
        } else {
            $akademik->user_id    = $request->input('user_id');
                $akademik->user_id    = $request->input('user_id');
                $akademik->bahasa_indonesia    = $request->input('bahasa_indonesia');
                $akademik->nomor_un    = $request->input('nomor_un');
                $akademik->bahasa_inggris    = $request->input('bahasa_inggris');
                $akademik->matematika    = $request->input('matematika');
                $akademik->save();

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
