<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Viewport crítico para responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - App Frutas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Reset y estilos base */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: url('/images/fondo1.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Contenedor principal - Ahora responsive */
        .login-container {
            background: rgba(29, 89, 7, 0.9);
            padding: 1.5rem;
            border-radius: 15px;
            width: 90%; /* Ancho flexible */
            max-width: 400px; /* Máximo para pantallas grandes */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 1rem;
            margin-top: -45px; /* Espacio en móviles */
        }

        /* Logo adaptable */
        .logo {
            width: 100%; /* Ocupa el ancho del contenedor */
            max-width: 320px; /* Tamaño máximo */
            height: auto; /* Mantiene proporción */
            margin-bottom: 1rem;
        }

        /* Textos responsivos */
        h1 {
            font-size: clamp(1.5rem, 4vw, 2rem); /* Escala entre 1.5rem y 2rem */
        }
        h3 {
            font-size: clamp(1rem, 3vw, 1.2rem);
        }

        /* Inputs y botón */
        .input-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }
        .input-group input, .btn-login {
            width: 80%;
            padding: 12px;
            font-size: clamp(14px, 3vw, 16px); /* Texto adaptable */
        }

        /* Footer links */
        .footer-links {
            margin-top: 1rem;
            font-size: clamp(12px, 3vw, 14px);
        }

        /* Media Queries para ajustes específicos */
        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
                width: 95%;
                height: 80%; /* Más ancho en móviles pequeños */
            }
            .input-group input, .btn-login {
                padding: 10px; /* Más compacto */
            }
        }

        @media (min-width: 768px) {
            body {
                padding: 2rem;
                 /* Más espacio en tablets */
            }
        }
    </style>
</head>
<body>
    <!-- El HTML se mantiene igual -->
    <div class="login-container">
       
        <h1 style="text-align: center; 
                    
font-family: Arial Black; 
font-weight: bold; 
font-size: 30px; 
color: #fff; 
text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
                    margin-bottom:-30px;">Aplicación <br> Compra Fruto</h1>
        
        <img style=" margin-bottom:-40px;"  src="/images/logo.png" alt="Logo Empresa" class="logo">
         <h3 style="color: wheat">Ingrese al Sistema</h3>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group">
                <label for="email" style="color: rgb(242, 231, 211)">Correo Institucional</label>
                <input style="border-radius: 15px;" id="email" type="email" name="email" required autofocus>
            </div>
            <div class="input-group">
                <label for="password" style="color: rgb(246, 236, 216)">Contraseña</label>
                <input style="border-radius: 15px;" id="password" type="password" name="password" required>
            </div>
            <button style="border-radius: 30px;" type="submit" class="btn-login">Ingresar</button>
        </form>

        <div class="footer-links">
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>
</body>
</html>