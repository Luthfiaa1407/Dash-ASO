<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            background: #F3F4F6;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .login-left {
            width: 50%;
            background: linear-gradient(160deg, #0F2A44, #091E33);
            color: #ffffff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* LOGO */
        .logo-box {
            display: flex;
            justify-content: center;
            margin-bottom: 60px;
        }

        .logo-box img {
            width: 280px;
            max-width: 100%;
            filter: drop-shadow(0 10px 24px rgba(0,0,0,0.45));
        }

        /* VISI MISI */
        .vm-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            max-width: 640px;
            margin: 0 auto;
            transform: translateY(-30px);
        }

        .vm-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 18px;
            padding: 28px;
        }

        .vm-card h4 {
            font-size: 16px;
            margin-bottom: 14px;
            font-weight: 600;
            color: #ffffff;
        }

        .vm-card p,
        .vm-card li {
            font-size: 13px;
            line-height: 1.7;
            color: #E5E7EB;
        }

        .vm-card ul {
            padding-left: 18px;
        }

        .vm-card ul li {
            margin-bottom: 8px;
        }

        .login-right {
            width: 50%;
            background: #ffffff;
            padding: 90px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h2 {
            font-size: 30px;
            color: #0F2A44;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 36px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid #D1D5DB;
            outline: none;
            font-size: 14px;
            background: #F9FAFB;
        }

        .form-group input:focus {
            border-color: #E31E24;
            background: #ffffff;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .eye {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            opacity: 0.6;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #E31E24;
            color: white;
            border: none;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 12px;
        }

        .btn-login:hover {
            background: #c81a1f;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-left,
            .login-right {
                width: 100%;
                padding: 40px;
            }

            .vm-wrapper {
                grid-template-columns: 1fr;
                transform: translateY(-10px);
            }

            .logo-box img {
                width: 200px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-left">
        <div class="logo-box">
            <img src="{{ asset('logo.png') }}" alt="Telkom Akses">
        </div>

        <div class="vm-wrapper">
            <div class="vm-card">
                <h4>Visi</h4>
                <p>
                    Menjadi Mitra Strategis Pilihan Telco di Indonesia
                    untuk Memajukan Masyarakat.
                </p>
            </div>

            <div class="vm-card">
                <h4>Misi</h4>
                <ul>
                    <li>Mempercepat pembangunan infrastruktur digital.</li>
                    <li>Mengorkestrasi ekosistem layanan digital.</li>
                    <li>Mengembangkan talenta dan kapabilitas digital.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="login-right">
        <h2>Login</h2>
        <p class="subtitle">Login to your account.</p>

        <form id="loginForm">
            <div class="form-group">
                <label>NIK</label>
                <input type="text" placeholder="Enter your NIK" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" placeholder="Enter your password" required>
                    <span class="eye"></span>
                </div>
            </div>

            <button class="btn-login" type="submit">LOGIN</button>
        </form>
    </div>
</div>

<script>
    // Show/hide password
    const eye = document.querySelector('.eye');
    const passwordInput = document.querySelector('.password-wrapper input');

    eye.addEventListener('click', () => {
        if(passwordInput.type === 'password'){
            passwordInput.type = 'text';
            eye.textContent = '👁';
        } else {
            passwordInput.type = 'password';
            eye.textContent = '👁';
        }
    });

    // Redirect ke dashboard setelah login
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function(e){
        e.preventDefault(); // cegah reload
        // Bisa ditambahkan validasi NIK/password di sini
        window.location.href = '/dashboard'; // redirect ke dashboard
    });
</script>

</body>
</html>