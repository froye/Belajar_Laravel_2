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
<h2><center>List Users</center></h2>
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
 
@section('title', 'Halaman Khusus Admin')
 
@section('main')

    <div class="col-md-12 bg-white p-4">
      <!--  <a href="/add"><button class="btn btn-primary mb-3">Tambah Artikel</button></a> -->
        <table class="table table-bordered table-hover table-stripped ">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="25%">Nama</th>
                    <th width="45%">Email</th>
                    <th width="30%">Aksi</th>
                </tr>
            </thead>
            <tbody>            
                
                @foreach ($listuser as $i => $user)
                    <tr>
                        <td> {{ $listuser->firstItem() + $i }}</td> <!--Ini untuk penomoran agar saat saat pindah ke page berikutnya tidak memulai dari angka 1 lagi-->
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                          <a href="/edit_user/{{ $user->id }}"><button class="btn btn-success">Edit</button></a>
                          <a href="/delete_user/{{ $user->id }}"><button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button></a>
                          <a href="/verif/{{ $user->id }}"><button class="btn btn-primary">Verifikasi</button></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $listuser->links() }}
    
    </div>
@endsection