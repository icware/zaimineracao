@extends('emails.email')

@section('title', 'Alteração de Senha')

@section('header', 'Alteração de Senha')

@section('content')
<p>Olá, {{ $user->first_name }} {{ $user->last_name }}!</p>
<p>Sua senha foi alterada com sucesso.</p>
<p class="highlight">Data: {{ \Carbon\Carbon::parse($user->update_password_at)->format('d/m/Y H:i:s') }}</p>
<p>A Zai Mineração nunca solicitará sua senha por e-mail ou telefone.</p>
<p>Atenciosamente,<br>Zai Mineração</p>
@endsection