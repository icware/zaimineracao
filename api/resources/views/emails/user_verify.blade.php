@extends('emails.email')

@section('title', 'Verificação de E-mail')

@section('header', 'Verificação de E-mail')

@section('content')
<p>Olá, {{ $user->first_name }} {{ $user->last_name }}!</p>
<p>Seu email foi verificado com sucesso!</p>
<p>Acesse nosso site para mais informações</p>
<p><strong><a href="https://zaimineracao.com.br/">Clique aqui</a></strong></p>
@endsection