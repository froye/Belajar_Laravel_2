<!-- menggunakan kerangka dari master.blade.php -->
@extends('master')
 
@section('navbar')
<?php $role = Auth::user()->role; ?>
@if ( $role== 'ADMIN' )
<li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
 
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/admin"> List Users</a> 
                                    <a class="dropdown-item" href="/list_app"> List Aplikasi</a> 
 
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
 
                                    <form id="logout-form" action="{{ route('logout') }}" style="display: none;">
                                        @csrf
                                    </form>
                                </div>               
                            </li>
@else
<li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
 
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/list_app"> List Aplikasi</a> 
 
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
 
                                    <form id="logout-form" action="{{ route('logout') }}" style="display: none;">
                                        @csrf
                                    </form>
                                </div>               
                            </li>                            
                            
@endif

@endsection

@section('header')
<h2><center>List Aplikasi</center></h2>
<br><br>
@if($message = Session::get('success')) <!--Ini adalah fungsi Session dari Laravel, sbiasanya utuk memunculkan pesan-->
    <div class="alert alert-success alert-block"> <!--Code Session ini sendiri akan memunculkan kotak pesan dengan kondisi bila menerima pesan Session sukses dari file lain seperti dari ArticleController@edit_process-->
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
    </div>
    @endif
@if($message = Session::get('fail')) <!--Ini adalah fungsi Session dari Laravel, sbiasanya utuk memunculkan pesan-->
    <div class="alert alert-warning alert-block"> <!--Code Session ini sendiri akan memunculkan kotak pesan dengan kondisi bila menerima pesan Session sukses dari file lain seperti dari ArticleController@edit_process-->
        <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
          
    </div>
    @endif
 
@endsection
 
@section('title', 'Halaman List Aplikasi')
 
@section('main')

    <div class="col-md-12 bg-white p-4">
       <a href="/add_app"><button class="btn btn-primary mb-3">Tambah Aplikasi</button></a>
        <table class="table table-bordered table-hover table-stripped ">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="15%">Nama User</th>
                    <th width="20%">Nama Aplikasi</th>
                    <th width="45%">Deskripsi Aplikasi</th>
                    <th width="30%">Aksi</th>
                </tr>
            </thead>
            <tbody>            
                
                @foreach ($listapp as $i => $user)
                    <tr>
                        <td>{{ $listapp->firstItem() + $i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->app_name }}</td>
                        <td>{{ $user->app_desc }}</td>
                        <td>
                           <a href="/edit_app/{{ $user->app_id }}"><button class="btn btn-success">Edit</button></a>
                          <a href="/delete_app/{{ $user->app_id }}"><button class="btn btn-danger">Delete</button></a>
                        
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $listapp->links() }} <!--ini untuk penomoran halaman-->
    
    </div>
@endsection