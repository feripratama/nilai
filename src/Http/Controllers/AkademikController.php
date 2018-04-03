<?php

namespace Bantenprov\Nilai\Http\Controllers;

/* Require */
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Bantenprov\Nilai\Facades\NilaiFacade;

/* Models */
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Akademik;
use Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai;
use Bantenprov\Siswa\Models\Bantenprov\Siswa\Siswa;
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
    protected $siswa;
    protected $user;
    protected $nilai;

    public function __construct(Akademik $akademik, Siswa $siswa, User $user, Nilai $nilai)
    {
        $this->akademik = $akademik;
        $this->siswa = $siswa;
        $this->user = $user;
        $this->nilai = $nilai;
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
        $users = $this->user->all();
        $siswas = $this->siswa->all();

        foreach($users as $user){
            array_set($user, 'label', $user->name);
        }

        foreach($siswas as $siswa){
            array_set($siswa, 'label', $siswa->nama_siswa);
        }
        
        $response['user'] = $users;
        $response['siswa'] = $siswas;
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

        $bahasa_indonesia = $request->bahasa_indonesia;
        $bahasa_inggris = $request->bahasa_inggris;
        $matematika = $request->matematika;
        $ipa = $request->ipa;

        $total_nialai_akademik = $bahasa_indonesia + $bahasa_inggris + $matematika + $ipa;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:akademiks,user_id',
            'siswa_id' => 'required|unique:akademiks,siswa_id',
            'bahasa_indonesia' => 'required',
            'bahasa_inggris' => 'required',
            'matematika' => 'required',
            'ipa' => 'required'
        ]);

        if($validator->fails()){
            $check = $akademik->where('user_id',$request->user_id)->orWhere('siswa_id',$request->siswa_id)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed ! Username, Nama Siswa, already exists';
            } else {
                $akademik->user_id = $request->input('user_id');
                $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
                $akademik->siswa_id = $request->input('siswa_id');
                $akademik->bahasa_inggris = $request->input('bahasa_inggris');
                $akademik->matematika = $request->input('matematika');
                $akademik->ipa = $request->input('ipa');
                $akademik->save();

                
                $check_akademik = $this->nilai->where('siswa_id', $request->input('siswa_id'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('siswa_id', $request->input('siswa_id'))->update([
                        'user_id' => $request->input('user_id'),
                        'siswa_id' => $request->input('siswa_id'),
                        'akademik' => $total_nialai_akademik
                    ]);
                }else{
                    $this->nilai->create([
                        'user_id' => $request->input('user_id'),
                        'siswa_id' => $request->input('siswa_id'),
                        'akademik' => $total_nialai_akademik
                    ]);
                }


                $response['message'] = 'success';
            }
        } else {
                //$akademik->user_id = $request->input('user_id');
                $akademik->user_id = $request->input('user_id');
                $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
                $akademik->siswa_id = $request->input('siswa_id');
                $akademik->bahasa_inggris = $request->input('bahasa_inggris');
                $akademik->matematika = $request->input('matematika');
                $akademik->ipa = $request->input('ipa');
                $akademik->save();

                $check_akademik = $this->nilai->where('siswa_id', $request->input('siswa_id'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('siswa_id', $request->input('siswa_id'))->update([
                        'user_id' => $request->input('user_id'),
                        'siswa_id' => $request->input('siswa_id'),
                        'akademik' => $total_nialai_akademik
                    ]);
                }else{
                    $this->nilai->create([
                        'user_id' => $request->input('user_id'),
                        'siswa_id' => $request->input('siswa_id'),
                        'akademik' => $total_nialai_akademik
                    ]);
                }

                
                

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
        $response['siswa'] = $akademik->siswa;
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
        array_set($akademik->siswa, 'label', $akademik->siswa->nama_siswa);

        $response['akademik'] = $akademik;
        $response['siswa'] = $akademik->siswa;
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

        $bahasa_indonesia = $request->bahasa_indonesia;
        $bahasa_inggris = $request->bahasa_inggris;
        $matematika = $request->matematika;
        $ipa = $request->ipa;

        $total_nialai_akademik = $bahasa_indonesia + $bahasa_inggris + $matematika + $ipa;

        $akademik = $this->akademik->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|unique:akademiks,user_id,'.$id,
                'siswa_id' => 'required|unique:akademiks,siswa_id,'.$id,
                'bahasa_indonesia' => 'required',
                'bahasa_inggris' => 'required',
                'matematika' => 'required',
                'ipa' => 'required',

            ]);

        if ($validator->fails()) {

            foreach($validator->messages()->getMessages() as $key => $error){
                        foreach($error AS $error_get) {
                            array_push($message, $error_get);
                        }                
                    } 

             $check_user     = $this->akademik->where('id','!=', $id)->where('user_id', $request->user_id);
             $check_siswa    = $this->akademik->where('id','!=', $id)->where('siswa_id', $request->siswa_id);

             if($check_user->count() > 0 || $check_siswa->count() > 0){
                  $response['message'] = implode("\n",$message);
            } else {
                $akademik->user_id    = $request->input('user_id');
                $akademik->bahasa_indonesia    = $request->input('bahasa_indonesia');
                $akademik->siswa_id = $request->input('siswa_id');
                $akademik->bahasa_inggris    = $request->input('bahasa_inggris');
                $akademik->matematika    = $request->input('matematika');
                $akademik->ipa    = $request->input('ipa');
                $akademik->save();                

                $check_akademik = $this->nilai->where('siswa_id', $request->input('siswa_id'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('siswa_id', $request->input('siswa_id'))->update([
                        'user_id' => $request->input('user_id'),
                        'siswa_id' => $request->input('siswa_id'),
                        'akademik' => $total_nialai_akademik
                    ]);
                }

                $response['message'] = 'success';
            }
        } else {
                $akademik->user_id    = $request->input('user_id');
                $akademik->bahasa_indonesia    = $request->input('bahasa_indonesia');
                $akademik->siswa_id = $request->input('siswa_id');
                $akademik->bahasa_inggris    = $request->input('bahasa_inggris');
                $akademik->matematika    = $request->input('matematika');
                $akademik->ipa    = $request->input('ipa');
                $akademik->save();

                $check_akademik = $this->nilai->where('siswa_id', $request->input('siswa_id'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('siswa_id', $request->input('siswa_id'))->update([
                        'user_id' => $request->input('user_id'),
                        'siswa_id' => $request->input('siswa_id'),
                        'akademik' => $total_nialai_akademik
                    ]);
                }

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
        $akademik = $this->akademik->findOrFail($id);

        if ($akademik->delete()) {
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }

        return json_encode($response);
    }
}
