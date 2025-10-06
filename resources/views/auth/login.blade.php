<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - App Frutas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Tus estilos actuales... */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: url('/images/fondo1.webp') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(29, 89, 7, 0.9);
            padding: 1.5rem;
            border-radius: 15px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 1rem;
            margin-top: -45px;
        }

        .logo {
            width: 100%;
            max-width: 320px;
            height: auto;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: clamp(1.5rem, 4vw, 2rem);
        }
        h3 {
            font-size: clamp(1rem, 3vw, 1.2rem);
        }

        .input-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }
        .input-group input, .btn-login {
            width: 80%;
            padding: 12px;
            font-size: clamp(14px, 3vw, 16px);
        }

        .footer-links {
            margin-top: 1rem;
            font-size: clamp(12px, 3vw, 14px);
        }

        /* Estilos para loading */
        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
                width: 95%;
            }
            .input-group input, .btn-login {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 style="text-align: center; font-family: Arial Black; font-weight: bold; font-size: 30px; color: #fff; text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000; margin-bottom:-30px;">
            Aplicación <br> Compra Fruto
        </h1>
        
        <img style="margin-bottom:-40px;" src="/images/logo.webp" alt="Logo Empresa" class="logo">
        <h3 style="color: wheat">Ingrese al Sistema</h3>
        
        <!-- FORMULARIO SIN ACTION - se maneja con JavaScript -->
        <form id="loginForm" method="POST">
            @csrf 
            
            <!-- Mostrar errores de validación de Laravel -->
            @if($errors->any())
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 5px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="input-group">
                <label for="email" style="color: rgb(242, 231, 211)">Correo Institucional</label>
                <input style="border-radius: 15px;" id="email" type="email" name="email" required autofocus>
            </div>
            
            <div class="input-group">
                <label for="password" style="color: rgb(246, 236, 216)">Contraseña</label>
                <input style="border-radius: 15px;" id="password" type="password" name="password" required>
            </div>
            
            <button style="border-radius: 30px;" type="submit" class="btn-login" id="loginBtn">
                Ingresar
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            
            // Configurar CSRF token para todas las peticiones
            axios.defaults.withCredentials = true;
            
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Mostrar loading
                loginBtn.innerHTML = '<span class="spinner"></span> Ingresando...';
                loginBtn.classList.add('btn-loading');
                
                try {
                    // Obtener CSRF token primero
                    await axios.get('/sanctum/csrf-cookie');
                    
                    const formData = new FormData(this);
                    
                    // Enviar login
                    const response = await axios.post('{{ route('login') }}', formData, {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    // Si llegamos aquí, el login fue exitoso
                    console.log('Login exitoso, redirigiendo...');
                    
                    // Redirigir al dashboard
                    window.location.href = '/dashboard';
                    
                } catch (error) {
                    console.error('Error en login:', error);
                    
                    // Restaurar botón
                    loginBtn.innerHTML = 'Ingresar';
                    loginBtn.classList.remove('btn-loading');
                    
                    // Mostrar error
                    let errorMessage = 'Error de autenticación';
                    
                    if (error.response && error.response.data) {
                        // Errores de validación de Laravel
                        if (error.response.data.errors) {
                            const errors = error.response.data.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } 
                        // Error directo del servidor
                        else if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }
                    }
                    
                    // Mostrar mensaje de error
                    showError(errorMessage);
                }
            });
            
            function showError(message) {
                // Eliminar error anterior
                const oldError = document.querySelector('.alert-danger');
                if (oldError) oldError.remove();
                
                // Crear nuevo elemento de error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger';
                errorDiv.style.cssText = 'background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;';
                errorDiv.innerHTML = message;
                
                // Insertar después del h3
                const h3 = document.querySelector('h3');
                h3.parentNode.insertBefore(errorDiv, h3.nextSibling);
            }
        });
    </script>
</body>
</html>