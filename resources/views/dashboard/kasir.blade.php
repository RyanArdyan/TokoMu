{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value section title ke parent nya yaitu layouts.app --}}
@section('title', 'Selamat Datang')

@section('konten')
<h1>Selamat Datang {{ auth()->user()->name }}</h1>
@endsection
