<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - App Frutas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: url('/images/fondo1.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            width: 320px;
            margin-bottom: 1.5rem;
        }
        .input-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #097969; /* Cadmio Verde */
            font-weight: 600;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #AFE1AF; /* Celadón */
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        .input-group input:focus {
            border-color: #097969; /* Cadmio Verde */
            outline: none;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #097969; /* Cadmio Verde */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background: #075e4d; /* Verde más oscuro */
        }
        .footer-links {
            margin-top: 1.5rem;
            font-size: 14px;
        }
        .footer-links a {
            color: #5F9EA0; /* Cadete Azul */
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Ingrese al Sistema</h1>
        <h3>Aplicación Compra de Fruto</h3>
        <img src="/images/logo.png" alt="Logo Empresa" class="logo">
        
        <!-- Formulario de Login -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group">
                <label for="email">Correo Institucional</label>
                <input id="email" type="email" name="email" required autofocus>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Ingresar</button>
        </form>

        <div class="footer-links">
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>
</body>
</html>