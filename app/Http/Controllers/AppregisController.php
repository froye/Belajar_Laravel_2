<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AppregisController extends Controller
{
    private function is_login()
    {
        if(Auth::user()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function add_app()
    {
        if($this->is_login())
        {
            return view('add_app');
        }
 
        else
        {
           return redirect('/login');
        }
    }

public function add_app_process(Request $dataApp) 
    {
        
        $app_name = $dataApp->name_app;
        $app_desc = $dataApp->desc_app;
        $cek_app_name = DB::table('applications')->where('app_name', $dataApp->name_app)->exists();
        
        if (is_null($app_name)) //cek apakah input app_name kosong atau tidak
        {
            Session::flash('fail', 'Application Name Tidak Boleh Kosong');
            return redirect('/add_app');
        }
        if (is_null($app_desc)) //cek apakah input app_desc kosong atau tidak
        {
            Session::flash('fail', 'Description Name Tidak Boleh Kosong');
            return redirect('/add_app');
        }
        if ($cek_app_name) //cek apakah applikation name sudah ada atau belum
        {
            //sudah ada
            Session::flash('fail', 'Application Name Sudah ada');
            return redirect('/add_app');
        }


        $id_user = Auth::user()->id;
        DB::table('applications')->insert([
            'app_name'=>$dataApp->name_app,
            'app_desc'=>$dataApp->desc_app,
            'id' => $id_user
        ]);

        Session::flash('success', 'Menambahkan Applikasi berhasil');
        //return redirect()->action('AuthController@show_by_admin');
        return redirect('/list_app');

      /* $cek_insert = DB::table('application')->where('app_name', $dataApp->app_name)->exists();

        if( $cek_insert){
            Session::flash('success', 'Register berhasil!');
            return redirect()->route('admin');
        } else {
            Session::flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            return redirect()->route('add');
        }*/
    }

    public function list_app()
    {
        if($this->is_login())
        {
            $id_user = Auth::user()->id;
            $role = Auth::user()->role;

           
            if ($role == 'ADMIN'){
            // $applist= DB::table('applications')->where('id', $id_user)->orderby('app_id', 'desc')->paginate(10);
            $applist= DB::table('applications')->join ('users','applications.id','=','users.id')->orderby('app_id', 'desc')->paginate(10);
            return view('list_app', ['listapp'=>$applist]);
            }
            else{
            // $applist= DB::table('applications')->where('id', $id_user)->orderby('app_id', 'desc')->paginate(10);
            $applist= DB::table('applications')->join ('users','applications.id','=','users.id')->where('applications.id',$id_user)->orderby('app_id', 'desc')->paginate(10);
            return view('list_app', ['listapp'=>$applist]);
            }
           
        }
 
        else
        {
           return redirect('/login');
        }
    }

    public function edit_app($id)
    {
        if($this->is_login())
        {
            $editapp = DB::table('applications')->where('app_id', $id)->first();
            return view('edit_app', ['appdata'=>$editapp]);
        }
 
        else
        {
           return redirect('/login');
        }
    }

    public function edit_app_process(Request $request)
    {
        $app_id = $request->name_id;
        $app_name = $request->name_app;
        $app_desc = $request->name_desc;
        $cek_app_name = DB::table('applications')->where('app_name', $request->name_app)->exists();
        $editapp = DB::table('applications')->where('app_id', $app_id)->first();
        
        if (is_null($app_name)) //cek apakah input app_name kosong atau tidak
        {
            
            Session::flash('failed', 'Application Name Tidak Boleh Kosong');
            return view('edit_app', ['appdata'=>$editapp]);
        }
        if (is_null($app_desc)) //cek apakah input app_desc kosong atau tidak
        {
            Session::flash('failed', 'Description Name Tidak Boleh Kosong');
            return view('edit_app', ['appdata'=>$editapp]);
        }
        if ($cek_app_name) //cek apakah applikation name sudah ada atau belum
        {
            //sudah ada
           /* $cek_app_name2 = applications::select('app_name')->where('app_name', $request->name_app)->first();
            if ($cek_app_name2 === $app_name){
                Session::flash('failed', 'Application Name sudah terdaftar');
                return view('edit_app', ['appdata'=>$editapp]);
            }      */      
        }
    
        $id = $request->name_id;
        $aplikasi = $request->name_app;
        $deskripsi = $request->name_desc;
        DB::table('applications')->where('app_id', $id)
                            ->update(['app_name' => $aplikasi, 'app_desc' => $deskripsi]);
        Session::flash('success', 'Applikasi berhasil diedit');
        return redirect('/list_app');
    }

    public function delete($id){
        if($this->is_login())
        {
             //menghapus artikel dengan ID sesuai pada URL
            DB::table('applications')->where('app_id', $id)
                                ->delete();
 
            //membuat pesan yang akan ditampilkan ketika artikel berhasil dihapus
            Session::flash('success', 'Applikasi berhasil dihapus');
            return redirect('/list_app');
        }
 
        else
        {
           return redirect('/login');
        }
    }

    /*
    
    public function registrasi_app(Request $request)
    {    
   

        $rules = [
            'app_name'                  => 'required|min:3|max:35',
            'app_desc'                 => 'required|unique:users',
        ];
 
        $messages = [
            'app_name.required'         => 'Nama Lengkap wajib diisi',
            'app_name.min'              => 'Nama lengkap minimal 3 karakter',
            'app_desc.max'              => 'Nama lengkap maksimal 35 karakter',
            'app_desc.required'        => 'Deskripsi wajib diisi',
        ];
 
        $validator = Validator::make($request->all(), $rules, $messages);
 
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }
 
        $id_user = Auth::user()->id;
        // insert data ke table 
	    DB::table('applications')->insert([
		'app_name' => $request->app_name,
		'app_desc' => $request->app_desc,
		'id' => $id_user
	]);

    $cek_insert = DB::table('application')->where('app_name', $request->app_name)->exists();

        if( $cek_insert){
            Session::flash('success', 'Register berhasil!');
            return redirect()->route('login');
        } else {
            Session::flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            return redirect()->route('register_app');
        }
    }
*/
}
