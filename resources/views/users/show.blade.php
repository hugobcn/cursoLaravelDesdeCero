@extends('layout')

@section('title', "Usuario {$user->id}")

@section('content')
   <br><br>
    <h1>Usuario #{{ $user->id }}</h1>

    <p>Nombre usuario:{{ $user->name }} </p>
    <p>email usuario:{{ $user->email }} </p>
    
    <p>
    {{--  <a href="{{url("/usuarios") }}">Regresa</a> --}}
    <a href="{{url()->previous() }}">Regresa</a>
    </p>
                
            
    
    //Mostrando detalle del usuario: {{ $user->id }}
@endsection
