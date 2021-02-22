<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
 
 
class AuthController extends Controller
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

    public function showFormLogin()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            $email = Auth::user()->email;
            $cekadmin = DB::table('users')->select('role')->where('email',$email)->where('role','ADMIN')->first();

            if ($cekadmin){
                $userlist = DB::table('users')->orderby('id', 'desc')->paginate(10);
                return view('admin', ['listuser'=>$userlist]); 
            }
            else{
                return redirect('/list_app');
            }

            
        }
        return view('login');
    }
 
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required' 
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                        ->withErrors($validator);
        }

        $user = User::where('email',$request->email)->first(); // ngambil data email
        //$user_exist = User::where('email',$request->email)->exists(); // cek apakah email sudah ada

        if (User::where('email', $request->email)->first()=== null) {
            // doesn't exists
            Session::flash('fail', 'Email belum terdaftar');
             return redirect()->route('login')->withInput();     
        }

        if($user->email_verified_at == null){
            Session::flash('fail', 'Email belum terverifikasi');
             return redirect()->route('login')->withInput();        
         }

        if($user->email_verified_at == null){
           Session::flash('fail', 'Email belum terverifikasi');
            return redirect()->route('login')->withInput();        
        }
 
        $data = [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
        ];
 
        Auth::attempt($data);
 
        if (Auth::check()) { 
            //Login Success
          //  return redirect()->route('admin');
         // $userlist = DB::table('users')->orderby('id', 'desc')->get();
         $email = Auth::user()->email;
            $cekadmin = DB::table('users')->select('role')->where('email',$email)->where('role','ADMIN')->first();

            if ($cekadmin){
                $userlist = DB::table('users')->orderby('id', 'desc')->paginate(10);
                return view('admin', ['listuser'=>$userlist]); 
            }
            else{
                return redirect('/list_app');
            }
          //$userlist = DB::table('users')->orderby('id', 'desc')->paginate(10);
           //return view('admin', ['listuser'=>$userlist]); 
 
        } else { // false
 
            //Login Fail
            Session::flash('error', 'Email atau password salah');
            return redirect()->route('login')->withInput();
        }
 
    }
 
    public function show_by_admin()
    {
        if($this->is_login())
        {
            $email = Auth::user()->email;
            $cekadmin = DB::table('users')->select('role')->where('email',$email)->where('role','ADMIN')->first();
            if ($cekadmin){
                $userlist = DB::table('users')->orderby('id', 'desc')->paginate(10);
                return view('admin', ['listuser'=>$userlist]); 
            }
            else{
                return redirect('/list_app');
            }
          //  $email = Auth::user()->email; 
          //  $user = DB::table('users')->where('email', $email)->first();
           // $userlist = DB::table('users')->orderby('id', 'desc')->get();
          // $userlist = DB::table('users')->orderby('id', 'desc')->paginate(10);
           //return view('admin', ['listuser'=>$userlist]); 
        }
        else
        {
           return redirect('/login');
        }
    }

    public function verif($id)
    {
        $tanggal = Carbon::now()->timezone('Asia/Jakarta');
        $user = DB::table('users')->where('id', $id)->first();
        

        if($this->is_login())
        {
            if($user->email_verified_at== null){
                DB::table('users')->where('id', $id)
                            ->update(['email_verified_at' => $tanggal]);
            
            Session::flash('success', 'User berhasil diverifikasi');
            return redirect()->action('AuthController@show_by_admin');
            }
            else{
                $tanggal_verif= $user->email_verified_at;
                $pesan='User Sudah terverifikasi pada ';
                Session::flash('fail', $pesan.$tanggal_verif);
                return redirect()->action('AuthController@show_by_admin');
            }
            
        }
        else
        {
           return redirect('/login');
        }
    }

    public function edit_user($id)
    {
        if($this->is_login())
        {
            $edituser = DB::table('users')->where('id', $id)->first();
            return view('edit_user', ['userdata'=>$edituser]);
        }
 
        else
        {
           return redirect('/login');
        }
    }

    public function edit_user_process(Request $request)
    {
        $user_id = $request->id;
        $user_name = $request->name;
        $user_email = $request->email;
     //   $cek_user_name = DB::table('users')->where('app_name', $request->name_app)->exists();
        $edituser = DB::table('users')->where('id', $user_id)->first();
        
        if (is_null($user_name)) //cek apakah input app_name kosong atau tidak
        {
            
            Session::flash('failed', 'User Name Tidak Boleh Kosong');
            return view('edit_user', ['appdata'=>$editapp]);
        }
        if (is_null($user_email)) //cek apakah input app_desc kosong atau tidak
        {
            Session::flash('failed', 'User Email Tidak Boleh Kosong');
            return view('edit_user', ['appdata'=>$editapp]);
        }
    
    
        $user_id = $request->id;
        $user_name = $request->name;
        $user_email = $request->email;
        $user_role = $request->role;
        DB::table('users')->where('id', $user_id)
                            ->update(['name' => $user_name, 'email' => $user_email, 'role' => $user_role]);
        Session::flash('success', 'Data User berhasil diedit');
        return redirect('/admin');
    }

    public function delete($id){
        if($this->is_login())
        {
             //menghapus artikel dengan ID sesuai pada URL
            DB::table('users')->where('id', $id)
                                ->delete();
 
            //membuat pesan yang akan ditampilkan ketika artikel berhasil dihapus
            Session::flash('success', 'User berhasil dihapus');
            return redirect('/admin');
        }
 
        else
        {
           return redirect('/login');
        }
    }

    public function showFormRegister()
    {
        return view('register');
    }
 
    public function register(Request $request)
    {
        $rules = [
            'name'                  => 'required|min:3|max:35',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|confirmed'
        ];
 
        $messages = [
            'name.required'         => 'Nama Lengkap wajib diisi',
            'name.min'              => 'Nama lengkap minimal 3 karakter',
            'name.max'              => 'Nama lengkap maksimal 35 karakter',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.confirmed'    => 'Password tidak sama dengan konfirmasi password'
        ];
 
        $validator = Validator::make($request->all(), $rules, $messages);
 
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }
 
        $user = new User;
        $user->name = ucwords(strtolower($request->name));
        $user->email = strtolower($request->email);
        $user->password = Hash::make($request->password);
      //  $user->email_verified_at = \Carbon\Carbon::now();
        $simpan = $user->save();
 
        if($simpan){
            Session::flash('success', 'Register berhasil! Silahkan Verifikasi untuk mengakses data');
            return redirect()->route('login');
        } else {
            Session::flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            return redirect()->route('register');
        }
    }
 
    public function logout()
    {
        Auth::logout(); // menghapus session yang aktif
        return redirect()->route('login');
    }
 
 
}