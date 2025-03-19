<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carta de Experiencia Laboral</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        p {
            line-height: 1.6;
            margin: 10px 0;
        }

        strong {
            color: #333;
        }

        .signature {
            text-align: center;
            margin-top: 20px;
        }

        .signature img {
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Carta de Experiencia Laboral</h1>
        <p>A quien corresponda,</p>
        <p>Por medio de la presente, se certifica que el Sr./Sra. <strong>{{ $usuario->nombre }}</strong>, con correo
            electrónico <strong>{{ $usuario->correo_electronico }}</strong>, ha trabajado en nuestra empresa
            desempeñando el
            cargo de <strong>{{ $usuario->id_rol }}</strong> desde el <strong>{{ $usuario->fecha_ingreso }}</strong>.
        </p>
        <p>Durante su tiempo en nuestra empresa, el Sr./Sra. <strong>{{ $usuario->nombre }}</strong> ha demostrado ser
            un/a
            empleado/a dedicado/a y competente, contribuyendo significativamente a los objetivos de nuestra
            organización.
        </p>
        @if ($usuario->firma)
            <div class="signature">
                <p><strong>Firma: {{ $usuario->firma }}</strong></p>
            </div>
        @endif
        <div class="footer">
            <p>Atentamente,</p>
            <p><strong>Syscom</strong></p>
        </div>
    </div>
</body>

</html>
