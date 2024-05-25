@extends('emails.email')

@section('title', 'Alteração de Senha')

@section('header', 'Alteração de Senha')

@section('content')
<p>Olá, {{ $user->first_name }} {{ $user->last_name }}!</p>
<p>Aqui está o seu código de redefinição de senha:</p>
<p><strong>{{ $resetCode }}</strong></p>
<p>Por favor, use este código para redefinir sua senha.</p>
<p><em>Por favor, note que este código é confidencial e não deve ser compartilhado com ninguém. A Zai Mineração nunca
        solicitará este código por e-mail ou telefone.</em></p>
@endsection