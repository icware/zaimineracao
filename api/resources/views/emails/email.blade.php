<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #444;
        }

        p {
            margin-bottom: 1em;
        }

        .highlight {
            font-weight: bold;
            color: #555;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>@yield('header')</h1>
        @yield('content')

        <div>
            <b>Contato</b>
            <ul>
                <li>Telefone: (99) 9 99999999</li>
                <li>Email: <a href="mailto:suporte@zaimineracao.com.br">suporte@zaimineracao.com.br</a></li>
                <li>Whatsapp: (99) 9 99999999</li>
            </ul>
        </div>

        <div class="footer">
            <p>Este é um e-mail automático, por favor, não responda.</p>
        </div>
    </div>
</body>

</html>