@extends('emails.email')

@section('title', 'Atualização de perfil')

@section('header', 'Atualização de perfil')

@section('content')
<p>Olá, {{ $user->first_name }} {{ $user->last_name }}!</p>
<p>Seu perfil foi atualizado</p>
@endsection