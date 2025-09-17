<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: url('/images/fondo1.webp') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(20, 92, 20, 0.523);
            padding: 2.5rem;
            border-radius: 15px;
            width: 420px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            margin-top: 380px;
            animation: fadeIn 0.8s ease-in-out;
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
            color: #caf4ee;
            font-weight: 600;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #AFE1AF;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #097969;
            outline: none;
            box-shadow: 0 0 8px rgba(9, 121, 105, 0.3);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #097969;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #075e4d;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .btn-back {
            width: 100%;
            padding: 12px;
            background: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-back:hover {
            background: #d6d6d6;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="/images/logo.webp" alt="Logo Empresa" class="logo">
        
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

        <!-- Botón Atrás -->
        <button type="button" class="btn-back" onclick="history.back()">
            ← Atrás
        </button>
    </div>
</body>
</html>
