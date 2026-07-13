<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;
            background-color: #f4f7fa;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: 800;
            color: #4f46e5;
            letter-spacing: -0.025em;
        }
        .content {
            line-height: 1.6;
            font-size: 15px;
        }
        .credential-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
        }
        .credential-item {
            margin-bottom: 12px;
        }
        .label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            display: block;
        }
        .value {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
        }
        .button {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #4f46e5;
            color: #ffffff;
            padding: 16px 0;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 800;
            font-size: 14px;
            margin-top: 30px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">SIMPATI</h1>
        </div>
        
        <div class="content">
            <p>Halo, <strong>{{ $user->name }}</strong>!</p>
            <p>Registrasi Anda berhasil! Berikut adalah detail akun yang dapat Anda gunakan untuk masuk ke aplikasi SIMPATI:</p>
            
            <div class="credential-box">
                <div class="credential-item">
                    <span class="label">Username / Email</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="label">Password</span>
                    <span class="value">{{ $password }}</span>
                </div>
            </div>
            
            <p>Silakan klik tombol di bawah ini untuk login. Demi keamanan, mohon segera ubah password Anda setelah masuk pertama kali.</p>
            
            <a href="{{ config('app.url') }}/login" class="button">Masuk ke Aplikasi</a>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 SIMPATI. Sistem Informasi Manajemen Persediaan dan Telekomunikasi.</p>
        </div>
    </div>
</body>
</html>
