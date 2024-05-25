@extends('emails.email')

@section('title', 'Bem-vindo!')

@section('header', 'Bem-vindo!')

@section('content')
<p>Olá, <strong>{{ $user->first_name }} {{ $user->last_name }}!</strong></p>
<p>Estamos felizes em tê-lo como parte da nossa família.</p>
<p>Se precisar de ajuda, não hesite em nos contatar.</p>
@endsection

<body>


</body>

</html>