<!DOCTYPE html>
<html>

<head>
    <title>Verificação de E-mail</title>
</head>

<body>
    <p>Olá, {{ $user->first_name }} {{ $user->last_name }}!</p>
    <p>Obrigado por se registrar em nosso sistema. Para concluir o processo de registro, por favor, verifique seu
        endereço de e-mail clicando no link abaixo:</p>
    <p>
        <a href="{{ $verificationUrl }}">Verificar E-mail</a>
    </p>
    <p>Este link é válido por 60 minutos.</p>
    <p>Se você não criou uma conta, por favor ignore este e-mail.</p>
    <p>Atenciosamente,<br>Zai Mineração</p>
</body>

</html>