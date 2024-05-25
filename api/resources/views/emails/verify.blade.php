@extends('emails.email')

@section('title', 'Verificação de E-mail')

@section('header', 'Verificação de E-mail')

@section('content')
<p>Olá, {{ $user->first_name }} {{ $user->last_name }}!</p>
<p>Para concluir o processo de registro, por favor, verifique seu
    endereço de e-mail usando o código abaixo:</p>
<p><strong>{{ $verification }}</strong></p>
<p>Este código é válido por 60 minutos.</p>
<p>Se você não criou uma conta, por favor ignore este e-mail.</p>
@endsection