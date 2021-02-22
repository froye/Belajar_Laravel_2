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

<!-- membuat judul bernama 'Edit Aplication' pada tab bar -->
@section('title', 'Register Aplication')
 
@section('header')
<center class="mt-4">
    <h2>Tambah Aplikasi</h2>
    <br><br>
</center>
@endsection

@section('main')
<div class="col-md-8 col-sm-12 bg-white p-4">

    <form method="post" action="/add_app_process"> 
    @csrf  
            @if($message = Session::get('fail')) <!--Ini adalah fungsi Session dari Laravel, sbiasanya utuk memunculkan pesan-->
                 <div class="alert alert-danger alert-block"> <!--Code Session ini sendiri akan memunculkan kotak pesan dengan kondisi bila menerima pesan Session sukses dari file lain seperti dari ArticleController@edit_process-->
                 <button type="button" class="close" data-dismiss="alert">x</button> 
                    <strong>{{ $message }}</strong>
                </div>
                 @endif
        <div class="form-group">
            <label>Application Name</label>
            <input type="text" class="form-control" name="name_app">
        </div>
        <div class="form-group">
            <label>Application Description</label>
            <textarea class="form-control" name="desc_app" rows="5"></textarea>
        </div>
</div>
@endsection
 
<!-- membuat komponen sidebar yang berisi tombol untuk upload artikel -->
@section('sidebar')
<div class="col-md-3 ml-md-5 col-sm-12 bg-white p-4" style="height:120px !important">
    <div class="form-group">
        <label>Simpan</label>
        <input type="submit" class="form-control btn btn-primary" value="Upload">
    </div>
</div>
</form>
@endsection