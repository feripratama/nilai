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
     * Display the specified resource.
     *
     * @param  \App\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $akademik = $this->akademik;

        $total_nilai_bobot = $this->akademik->storeNilaiBobot($request);
        $total_nilai_akademik = $this->akademik->storeNilaiAkademik($request);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'nomor_un' => 'required|unique:akademiks,nomor_un',
            'bahasa_indonesia' => 'required',
            'bahasa_inggris' => 'required',
            'matematika' => 'required',
            'ipa' => 'required'
        ]);

        if($validator->fails()){
            $check = $akademik->where('nomor_un',$request->nomor_un)->whereNull('deleted_at')->count();

            if ($check > 0) {
                $response['message'] = 'Failed ! Username, Nama Siswa, already exists';
            } else {
                $akademik->user_id = $request->input('user_id');
                $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
                $akademik->nomor_un = $request->input('nomor_un');
                $akademik->bahasa_inggris = $request->input('bahasa_inggris');
                $akademik->matematika = $request->input('matematika');
                $akademik->ipa = $request->input('ipa');
                $akademik->save();


                $check_akademik = $this->nilai->where('nomor_un', $request->input('nomor_un'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('nomor_un', $request->input('nomor_un'))->update([
                        'user_id' => $request->input('user_id'),
                        'nomor_un' => $request->input('nomor_un'),
                        'bobot' => $total_nilai_bobot,
                        'akademik' => $total_nilai_akademik
                    ]);
                }else{
                    $this->nilai->create([
                        'user_id' => $request->input('user_id'),
                        'nomor_un' => $request->input('nomor_un'),
                        'bobot' => $total_nilai_bobot,
                        'akademik' => $total_nilai_akademik
                    ]);
                }

                $response['message'] = 'success';
            }
        } else {
                //$akademik->user_id = $request->input('user_id');
                $akademik->user_id = $request->input('user_id');
                $akademik->bahasa_indonesia = $request->input('bahasa_indonesia');
                $akademik->nomor_un = $request->input('nomor_un');
                $akademik->bahasa_inggris = $request->input('bahasa_inggris');
                $akademik->matematika = $request->input('matematika');
                $akademik->ipa = $request->input('ipa');
                $akademik->save();

                $check_akademik = $this->nilai->where('nomor_un', $request->input('nomor_un'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('nomor_un', $request->input('nomor_un'))->update([
                        'user_id' => $request->input('user_id'),
                        'nomor_un' => $request->input('nomor_un'),
                        'bobot' => $total_nilai_bobot,
                        'akademik' => $total_nilai_akademik
                    ]);
                }else{
                    $this->nilai->create([
                        'user_id' => $request->input('user_id'),
                        'nomor_un' => $request->input('nomor_un'),
                        'bobot' => $total_nilai_bobot,
                        'akademik' => $total_nilai_akademik
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

        $total_nilai_bobot = $this->akademik->storeNilaiBobot($request);

        $akademik = $this->akademik->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'nomor_un' => 'required|unique:akademiks,nomor_un,'.$id,
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

             $check_siswa    = $this->akademik->where('id','!=', $id)->where('nomor_un', $request->nomor_un);

             if($check_siswa->count() > 0){
                  $response['message'] = implode("\n",$message);
            } else {
                $akademik->user_id    = $request->input('user_id');
                $akademik->bahasa_indonesia    = $request->input('bahasa_indonesia');
                $akademik->nomor_un = $request->input('nomor_un');
                $akademik->bahasa_inggris    = $request->input('bahasa_inggris');
                $akademik->matematika    = $request->input('matematika');
                $akademik->ipa    = $request->input('ipa');
                $akademik->save();

                $check_akademik = $this->nilai->where('nomor_un', $request->input('nomor_un'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('nomor_un', $request->input('nomor_un'))->update([
                        'user_id' => $request->input('user_id'),
                        'nomor_un' => $request->input('nomor_un'),
                        'bobot' => $total_nilai_bobot
                    ]);
                }

                $response['message'] = 'success';
            }
        } else {
                $akademik->user_id    = $request->input('user_id');
                $akademik->bahasa_indonesia    = $request->input('bahasa_indonesia');
                $akademik->nomor_un = $request->input('nomor_un');
                $akademik->bahasa_inggris    = $request->input('bahasa_inggris');
                $akademik->matematika    = $request->input('matematika');
                $akademik->ipa    = $request->input('ipa');
                $akademik->save();

                $check_akademik = $this->nilai->where('nomor_un', $request->input('nomor_un'));
                if($check_akademik->count() > 0){
                    $this->nilai->where('nomor_un', $request->input('nomor_un'))->update([
                        'user_id' => $request->input('user_id'),
                        'nomor_un' => $request->input('nomor_un'),
                        'bobot' => $total_nilai_bobot
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
