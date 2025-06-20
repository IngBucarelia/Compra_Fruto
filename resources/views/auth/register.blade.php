<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Mismo estilo que login (puedes reutilizar el CSS) -->
    <style>
        /* Copia todo el CSS del login aquí */
        .input-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #AFE1AF;
            border-radius: 8px;
            font-size: 16px;
        }
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
            margin-top: 140px;
        }
        .logo {
            width: 120px;
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
        <img src="/images/logo.png" alt="Logo Empresa" class="logo">
        
        <!-- Formulario de Registro -->
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="input-group">
                <label for="name">Nombre Completo</label>
                <input id="name" type="text" name="name" required>
            </div>
            <div class="input-group">
                <label for="num_documento">Número de Documento</label>
                <input id="num_documento" type="number" name="num_documento" required>
            </div>
            <div class="input-group">
                <label for="email">Correo Institucional</label>
                <input id="email" type="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="area_pertenece">Área</label>
                <input id="area_pertenece" type="text" name="area_pertenece" required>
            </div>
            <div class="input-group">
                <label for="ocupacion">Ocupación</label>
                <input id="ocupacion" type="text" name="ocupacion" required>
            </div>
            <div class="input-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required>
                    <option value="1">Administrador</option>
                    <option value="2">Encargado Visita</option>
                    <option value="3">Consultor</option>
                    <option value="4">Proveedor</option>
                </select>
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn-login">Registrarse</button>
        </form>

        <div class="footer-links">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia Sesión</a>
        </div>
    </div>
</body>
</html>