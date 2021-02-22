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
@section('title', 'Edit Aplication')
 
@section('header')
<center class="mt-4">
    <h2>Edit Aplikasi</h2>
    <br><br>
</center>
@endsection
 
@section('main')
<div class="col-md-8 col-sm-12 bg-white p-4">
    <form method="post" action="/edit_app_process">
    @csrf

    @if($message = Session::get('failed')) <!--Ini adalah fungsi Session dari Laravel, sbiasanya utuk memunculkan pesan-->
                 <div class="alert alert-danger alert-block"> <!--Code Session ini sendiri akan memunculkan kotak pesan dengan kondisi bila menerima pesan Session sukses dari file lain seperti dari ArticleController@edit_process-->
                 <button type="button" class="close" data-dismiss="alert">x</button> 
                    <strong>{{ $message }}</strong>
                </div>
                 @endif

	<input type="hidden" value="{{ $appdata->app_id }}" name="name_id">
        <div class="form-group">
            <label>Application Name</label>
            <input type="text" class="form-control" value="{{ $appdata->app_name }}" name="name_app" placeholder="Judul artikel">
        </div>
        <div class="form-group">
            <label>Application Description</label>
            <textarea class="form-control" name="name_desc" rows="5">{{ $appdata->app_desc }}
            </textarea>
        </div>
</div>
@endsection
 
<!-- membuat komponen sidebar yang berisi tombol untuk upload artikel -->
@section('sidebar')
<div class="col-md-3 ml-md-5 col-sm-12 bg-white p-4" style="height:120px !important">
    <div class="form-group">
       <!-- <label>Edit</label> -->
        <input type="submit" class="form-control btn btn-primary" value="Edit">
    </div>
</div>
</form>
@endsection